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

            $Source_query = LeadTag::where('tag', '!=', '');
            $total_records = $Source_query->count();

            $Source_query->orderBy('created_at', 'desc')->skip($start)->take($num_results_on_page);
            $sources = $Source_query->get();

            return view('tages.index', compact('sources', 'total_records'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
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

    public function destroy(LeadTag $source,$id)
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
}
