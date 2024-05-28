<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\LeadTag;
use App\Models\Pipeline;
use App\Models\Source;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'XSS',
            ]
        );
    }
    public function index()
    {
        if (\Auth::user()->can('level 2') || \Auth::user()->type == 'Project Director' || \Auth::user()->type ==  'Project Manager') {

            $start = 0;
            $num_results_on_page = env("RESULTS_ON_PAGE");
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
                $start = ($page - 1) * $num_results_on_page;
            } else {
                $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            }
            $targer_query = [];

            if (\Auth::check()) {
                $user = \Auth::user();

                if (in_array($user->type, ['super admin', 'Admin Team'])) {
                    $targer_query = LeadTag::pluck('id', 'tag')->toArray();
                } elseif (in_array($user->type, ['Project Director', 'Project Manager', 'Admissions Officer'])) {
                    $targer_query = LeadTag::whereIn('brand_id', array_keys(FiltersBrands()))->pluck('id', 'tag')->toArray();
                } elseif (in_array($user->type, ['Region Manager'])) {
                    $targer_query = LeadTag::where('region_id', $user->region_id)->pluck('id', 'tag')->toArray();
                } else {
                    $targer_query = LeadTag::where('branch_id', $user->branch_id)->pluck('id', 'tag')->toArray();
                }
            }
            // Define the base query
            $query = LeadTag::select(
                'lead_tags.id',
                'lead_tags.tag',
                'users.name as brand',
                'branches.name as branch',
                'regions.name as region'
                )
                ->leftJoin('users', 'users.id', '=', 'lead_tags.brand_id')
                ->leftJoin('branches', 'branches.id', '=', 'lead_tags.branch_id')
                ->leftJoin('regions', 'regions.id', '=', 'branches.region_id') // Ensure correct join condition
                ->where('lead_tags.tag', '!=', '')
                ->whereIn('lead_tags.id', array_values($targer_query));


                $filters = $this->tagFilters();
                foreach ($filters as $column => $value) {
                    if ($column === 'name') {
                        $query->whereIn('lead_tags.tag', $value);
                    } elseif ($column == 'created_at') {
                        $query->whereDate('lead_tags.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                    }elseif ($column == 'brand') {
                        $query->where('lead_tags.brand_id', $value);
                    }elseif ($column == 'region_id') {
                        $query->where('lead_tags.region_id', $value);
                    }elseif ($column == 'branch_id') {
                        $query->where('lead_tags.branch_id', $value);
                    }

                }

            // Get the total record count
            $total_records = $query->count();

            // Apply sorting and pagination
            $tags = $query->orderBy('lead_tags.tag', 'desc')
                ->skip($start)
                ->take($num_results_on_page)
                ->get()
                ->toArray();
                $filters = BrandsRegionsBranches();
            return view('tages.index', compact('filters','tags', 'total_records'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    private function tagFilters()
    {
        $filters = [];
        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $filters['name'] = $_GET['name'];
        }

        if (isset($_GET['brand']) && !empty($_GET['brand'])) {
            $filters['brand'] = $_GET['brand'];
        }

        if (isset($_GET['region_id']) && !empty($_GET['region_id'])) {
            $filters['region_id'] = $_GET['region_id'];
        }

        if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
            $filters['branch_id'] = $_GET['branch_id'];
        }

        if (isset($_GET['lead_assigned_user']) && !empty($_GET['lead_assigned_user'])) {
            $filters['deal_assigned_user'] = $_GET['lead_assigned_user'];
        }


        if (isset($_GET['stages']) && !empty($_GET['stages'])) {
            $filters['stage_id'] = $_GET['stages'];
        }

        if (isset($_GET['users']) && !empty($_GET['users'])) {
            $filters['users'] = $_GET['users'];
        }

        if (isset($_GET['created_at_from']) && !empty($_GET['created_at_from'])) {
            $filters['created_at_from'] = $_GET['created_at_from'];
        }

        if (isset($_GET['created_at_to']) && !empty($_GET['created_at_to'])) {
            $filters['created_at_to'] = $_GET['created_at_to'];
        }
        if (isset($_GET['tag']) && !empty($_GET['tag'])) {
            $filters['tag'] = $_GET['tag'];
        }

        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $price = $_GET['price'];

            if (preg_match('/^(<=|>=|<|>)/', $price, $matches)) {
                $comparePrice = $matches[1]; // Get the comparison operator
                $filters['price'] = (float) substr($price, strlen($comparePrice)); // Get the price value
            } else {
                $comparePrice = '=';
                $filters['price'] = '=' . $price; // Default to '=' if no comparison operator is provided
            }
        }

        return $filters;
    }
    private function executeLeadQuery()
    {
        $usr = \Auth::user();

        // Pagination calculation
        $start = 0;
        if (!empty($_GET['perPage'])) {
            $num_results_on_page = $_GET['perPage'];
        } else {
            $num_results_on_page = env("RESULTS_ON_PAGE");
        }
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }



        if ($usr->can('view lead') || $usr->can('manage lead') || \Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team') {


            $pipeline = Pipeline::first();

            // Initialize variables
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);

            // Build the leads query
            $leads_query = Lead::select('leads.*')
                ->join('lead_stages', 'leads.stage_id', '=', 'lead_stages.id')
                ->join('users', 'users.id', '=', 'leads.brand_id')
                ->join('branches', 'branches.id', '=', 'leads.branch_id')
                ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'leads.user_id')
                ->leftJoin('lead_tags as tag', 'tag.lead_id', '=', 'leads.id');

            if (!empty($_GET['Assigned'])) {
                $leads_query->whereNotNull('leads.user_id');
            }
            if (!empty($_GET['Unassigned'])) {
                $leads_query->whereNull('leads.user_id');
            }
            // Apply user type-based filtering
            $userType = \Auth::user()->type;
            if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                // No additional filtering needed
            } elseif ($userType === 'company') {
                $leads_query->where('leads.brand_id', \Auth::user()->id);
            } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                $leads_query->whereIn('leads.brand_id', $brand_ids);
            } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                $leads_query->where('leads.region_id', \Auth::user()->region_id);
            } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || \Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id)) {
                $leads_query->where('leads.branch_id', \Auth::user()->branch_id);
            } else {
                $leads_query->where('user_id', \Auth::user()->id);
            }





            return [
                'companies' => $companies,
                'pipeline' => $pipeline,
                'num_results_on_page' => $num_results_on_page
            ];
        }
    }

    public function create()
    {
        if (\Auth::user()->can('level 2') || \Auth::user()->type == 'Project Director' || \Auth::user()->type ==  'Project Manager') {
            $usr = \Auth::user();

            $executed_data = $this->executeLeadQuery();


            $brands = $executed_data['companies'];
            $companies = $executed_data['companies'];
            $pipeline = $executed_data['pipeline'];
            $num_results_on_page = $executed_data['num_results_on_page'];

            $users = allUsers();
            $stages = LeadStage::get();
            $organizations = User::where('type', 'organization')->pluck('name', 'id');

            $sourcess = Source::get()->pluck('name', 'id');
            $branches = Branch::get()->pluck('name', 'id')->ToArray();





            $assign_to = [];
            if (\Auth::user()->type == 'super admin') {
                $assign_to = User::whereNotIn('type', ['client', 'company', 'super admin', 'organization', 'team'])
                    ->pluck('name', 'id')->toArray();
            } else {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);

                $assign_to = User::whereIn('brand_id', $brand_ids)->pluck('name', 'id')->toArray();
            }

            $filters = BrandsRegionsBranches();
            if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager') {
                $tags = LeadTag::pluck('tag', 'tag')->toArray();
            } else {
                $tags = LeadTag::where('created_by', \Auth::id())->pluck('tag', 'tag')->toArray();
            }

            return view('tages.create', compact('filters'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }
    public function store(Request $request)
    {

        if (\Auth::user()->can('level 2') || \Auth::user()->type == 'Project Director' || \Auth::user()->type ==  'Project Manager') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'brand' => 'required|min:1|numeric',
                    'region_id' => 'required|min:1|numeric',
                    'branch_id' => 'required|min:1|numeric',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }

            $source             = new LeadTag();
            $source->tag       = $request->name;
            $source->brand_id       = $request->brand;
            $source->region_id       = $request->region_id;
            $source->branch_id       = $request->branch_id;
            $source->created_by = \Auth::user()->ownerId();
            $source->save();

            return json_encode([
                'status' => 'success',
                'message' => 'Tag successfully created!'
            ]);
        } else {

            return json_encode([
                'status' => 'error',
                'message' => 'Permission Denied.'
            ]);
        }
    }

    public function show(LeadTag $source)
    {
        return redirect()->route('tages.index');
    }

    public function edit($id)
    {
        $LeadTag = LeadTag::find($id);
        if (\Auth::user()->can('level 2') || \Auth::user()->type == 'Project Director' || \Auth::user()->type ==  'Project Manager') {
            $usr = \Auth::user();
            $executed_data = $this->executeLeadQuery();
            $brands = $executed_data['companies'];
            $companies = $executed_data['companies'];
            $pipeline = $executed_data['pipeline'];
            $num_results_on_page = $executed_data['num_results_on_page'];
            $users = allUsers();
            $stages = LeadStage::get();
            $organizations = User::where('type', 'organization')->pluck('name', 'id');
            $sourcess = Source::get()->pluck('name', 'id');
            $branches = Branch::get()->pluck('name', 'id')->ToArray();
            $assign_to = [];
            if (\Auth::user()->type == 'super admin') {
                $assign_to = User::whereNotIn('type', ['client', 'company', 'super admin', 'organization', 'team'])
                    ->pluck('name', 'id')->toArray();
            } else {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $assign_to = User::whereIn('brand_id', $brand_ids)->pluck('name', 'id')->toArray();
            }
            $filters = BrandsRegionsBranches();
            if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager') {
                $tags = LeadTag::pluck('tag', 'tag')->toArray();
            } else {
                $tags = LeadTag::where('created_by', \Auth::id())->pluck('tag', 'tag')->toArray();
            }
            return view('tages.edit', compact('LeadTag', 'filters'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }
    public function update(Request $request)
    {
        if (\Auth::user()->can('level 2') || \Auth::user()->type == 'Project Director' || \Auth::user()->type ==  'Project Manager') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'brand' => 'required|min:1|numeric',
                    'region_id' => 'required|min:1|numeric',
                    'branch_id' => 'required|min:1|numeric',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }
            $lead_tag = LeadTag::findOrFail($request->id);
            if (empty($lead_tag)) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Sorry Your Tag Not Found!'
                ]);
            }
            $source             = $lead_tag;
            $source->tag       = $request->name;
            $source->brand_id       = $request->brand;
            $source->region_id       = $request->region_id;
            $source->branch_id       = $request->branch_id;
            $source->created_by = \Auth::user()->ownerId();
            $source->save();

            return json_encode([
                'status' => 'success',
                'message' => 'Tag successfully Updated!'
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission Denied.'
            ]);
        }
    }

    public function destroy(LeadTag $source, $id)
    {
        $lead_tag = LeadTag::findOrFail($id);
        if (empty($lead_tag)) {
            return redirect()->route('tages.index')->with('success', __('Sorry Your Tag Not Found!'));
        }
        if (\Auth::user()->can('level 2') || \Auth::user()->type == 'Project Director' || \Auth::user()->type ==  'Project Manager') {
            $lead_tag->delete();

            return redirect()->route('tages.index')->with('success', __('Tag successfully deleted!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



    public function TagesBulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('id'));
        $users = LeadTag::whereIn('id', $ids)->get();
        if (count($users) > 0) {
            foreach($users as $user){
                $user->delete();
            }
            return redirect()->route('tages.index')->with('success', __('Tag successfully deleted!'));
        } else {
            return redirect()->route('tages.index')->with('success', __('Some Ids Not Found!'));
        }
    }

}
