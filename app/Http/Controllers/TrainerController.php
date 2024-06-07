<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SavedFilter;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage trainer')) {
            $trainers = Trainer::where('created_by', '=', \Auth::user()->creatorId())->get();





            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);

            // Build the leads query
            $Trainer_query = Trainer::select('regions.name as region','branches.name as branch','users.name as brand','trainers.id','trainers.firstname','trainers.lastname','trainers.brand_id','trainers.email','trainers.branch_id','trainers.contact','trainers.created_by')
                ->join('users', 'users.id', '=', 'trainers.brand_id')
                ->join('branches', 'branches.id', '=', 'trainers.branch_id')
                ->join('regions', 'regions.id', '=', 'trainers.region_id')
                ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'trainers.created_by')
                ->leftJoin('lead_tags as tag', 'tag.lead_id', '=', 'trainers.id');

            if (!empty($_GET['Assigned'])) {
                $Trainer_query->whereNotNull('trainers.created_by');
            }
            if (!empty($_GET['Unassigned'])) {
                $Trainer_query->whereNull('trainers.created_by');
            }
            // Apply user type-based filtering
            $userType = \Auth::user()->type;
            if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                // No additional filtering needed
            } elseif ($userType === 'company') {
                $Trainer_query->where('trainers.brand_id', \Auth::user()->id);
            } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                $Trainer_query->whereIn('trainers.brand_id', $brand_ids);
            } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                $Trainer_query->where('trainers.region_id', \Auth::user()->region_id);
            } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || \Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id)) {
                $Trainer_query->where('trainers.branch_id', \Auth::user()->branch_id);
            } else {
                $Trainer_query->where('trainers.created_by', \Auth::user()->id);
            }

            $filters = $this->dealFilters();

            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $Trainer_query->whereIn('trainers.firstname', $value);
                } elseif ($column === 'stage_id') {
                    $Trainer_query->whereIn('trainers.stage_id', $value);
                } elseif ($column == 'users') {
                    $Trainer_query->whereIn('trainers.created_by', $value);
                } elseif ($column == 'created_at') {
                    $Trainer_query->whereDate('trainers.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                }elseif ($column == 'brand') {
                    $Trainer_query->where('trainers.brand_id', $value);
                }elseif ($column == 'region_id') {
                    $Trainer_query->where('trainers.region_id', $value);
                }elseif ($column == 'branch_id') {
                    $Trainer_query->where('trainers.branch_id', $value);
                }

            }

            $trainers=$Trainer_query->get();
            $saved_filters = SavedFilter::where('created_by', \Auth::id())->where('module', 'trainer')->get();
            $filters = BrandsRegionsBranches();
            return view('trainer.index', compact('filters','trainers', 'saved_filters'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    private function dealFilters()
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

    public function ShowTrainer(Request $request)
    {
        $trainers = Trainer::select('regions.name as region','branches.name as branch','users.name as brand','trainers.id','trainers.firstname','trainers.lastname','trainers.brand_id','trainers.email','trainers.branch_id','trainers.contact','trainers.created_by','trainers.created_at','trainers.updated_at')
        ->join('users', 'users.id', '=', 'trainers.brand_id')
        ->join('branches', 'branches.id', '=', 'trainers.branch_id')
        ->join('regions', 'regions.id', '=', 'trainers.region_id')
        ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'trainers.created_by')
        ->leftJoin('lead_tags as tag', 'tag.lead_id', '=', 'trainers.id')->where('trainers.id',$request->id)->first();

        $html = view('trainer.show', compact('trainers'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html
            ]);

    }
    public function create()
    {
        if (\Auth::user()->can('create trainer')) {

            // for all Brands Regions Branches
            $filter = BrandsRegionsBranches();
            $companies = $filter['brands'];
            $regions = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];
            return view('trainer.create', compact('employees', 'regions', 'companies', 'branches'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create trainer')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'brand_id' => 'required|numeric|min:1',
                    'region_id' => 'required|numeric|min:1',
                    'lead_branch' => 'required|numeric|min:1',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'contact' => 'required',
                    'email' => 'required|email',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ]);
            }

            $trainer = new Trainer();
            $trainer->branch_id = $request->lead_branch;
            $trainer->brand_id = $request->brand_id;
            $trainer->region_id = $request->region_id;
            $trainer->firstname = $request->firstname;
            $trainer->lastname = $request->lastname;
            $trainer->contact = $request->contact;
            $trainer->email = $request->email;
            $trainer->address = $request->address;
            $trainer->expertise = $request->expertise;
            $trainer->created_by = \Auth::user()->creatorId();
            $trainer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Trainer created successfully.',
                'id' => $trainer->id
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => __('Permission denied.')
            ]);
        }
    }


    public function show(Trainer $trainer)
    {
        return view('trainer.show', compact('trainer'));
    }


    public function edit(Trainer $trainer)
    {
        if (\Auth::user()->can('edit trainer')) {
            $filter = BrandsRegionsBranchesForEdit($trainer->brand_id, $trainer->region_id, $trainer->branch_id);
            $companies = $filter['brands'];
            $regions = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];

            return view('trainer.edit', compact('branches', 'trainer','companies','regions','branches','employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Trainer $trainer)
    {
        if (\Auth::user()->can('edit trainer')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'brand_id' => 'required|numeric|min:1',
                    'region_id' => 'required|numeric|min:1',
                    'lead_branch' => 'required|numeric|min:1',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'contact' => 'required',
                    'email' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ]);
            }

            $trainer->branch_id = $request->lead_branch;
            $trainer->brand_id = $request->brand_id;
            $trainer->region_id = $request->region_id;
            $trainer->firstname = $request->firstname;
            $trainer->lastname  = $request->lastname;
            $trainer->contact   = $request->contact;
            $trainer->email     = $request->email;
            $trainer->address   = $request->address;
            $trainer->expertise = $request->expertise;
            $trainer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Trainer  successfully updated.',
                'id' => $trainer->id
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => __('Permission denied.')
            ]);
        }
    }


    public function destroy(Trainer $trainer)
    {
        if (\Auth::user()->can('delete trainer')) {
            if ($trainer->created_by == \Auth::user()->creatorId()) {
                $trainer->delete();

                return redirect()->route('trainer.index')->with('success', __('Trainer successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
