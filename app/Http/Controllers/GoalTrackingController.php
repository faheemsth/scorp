<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\GoalTracking;
use App\Models\GoalType;
use App\Models\SavedFilter;
use Illuminate\Http\Request;

class GoalTrackingController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage goal tracking'))
        {
            $user = \Auth::user();

                $goalTracking_query = GoalTracking::select(
                    'goal_trackings.*', // Corrected this line
                    'regions.name as region',
                    'branches.name as branch',
                    'users.name as brand',
                )
                ->leftJoin('users', 'users.id', '=', 'goal_trackings.brand_id')
                ->leftJoin('branches', 'branches.id', '=', 'goal_trackings.branch')
                ->leftJoin('regions', 'regions.id', '=', 'goal_trackings.region_id');

                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $userType = \Auth::user()->type;
                if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                    // No additional filtering needed
                } elseif ($userType === 'company') {
                    $goalTracking_query->where('goal_trackings.brand_id', \Auth::user()->id);
                } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                    $goalTracking_query->whereIn('goal_trackings.brand_id', $brand_ids);
                } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                    $goalTracking_query->where('goal_trackings.region_id', \Auth::user()->region_id);
                } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || \Auth::user()->can('level 4') && !empty(\Auth::user())) {
                    $goalTracking_query->where('goal_trackings.branch', \Auth::user()->branch_id);
                } else {
                    $goalTracking_query->where('goal_trackings.created_by', \Auth::user()->id);
                }

                $filters = $this->GoalTrackingFilters();

                foreach ($filters as $column => $value) {
                    if ($column == 'created_at') {
                        $goalTracking_query->whereDate('goal_trackings.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                    }elseif ($column == 'brand') {
                        $goalTracking_query->where('goal_trackings.brand_id', $value);
                    }elseif ($column == 'region_id') {
                        $goalTracking_query->where('goal_trackings.region_id', $value);
                    }elseif ($column == 'branch_id') {
                        $goalTracking_query->where('goal_trackings.branch', $value);
                    }

                }
                $goalTrackings = $goalTracking_query->get();

            $saved_filters = SavedFilter::where('created_by', \Auth::id())->where('module', 'goaltracking')->get();
            $filters = BrandsRegionsBranches();

            return view('goaltracking.index', compact('saved_filters','filters','goalTrackings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    private function GoalTrackingFilters()
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

    public function create()
    {
        if(\Auth::user()->can('create goal tracking'))
        {

            $brances = Branch::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $brances->prepend('Select Branch', '');
            $goalTypes = GoalType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $goalTypes->prepend('Select Goal Type', '');
            $status = GoalTracking::$status;
            $filter = BrandsRegionsBranches();
            $companies = $filter['brands'];
            $regions = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];
            return view('goaltracking.create', compact('branches', 'regions', 'employees', 'companies',  'goalTypes','status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create goal tracking'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'brand_id' => 'required|integer|min:1',
                    'region_id' => 'required|integer|min:1',
                    'lead_branch' => 'required|integer|min:1',
                    'goal_type' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'subject' => 'required',
                               ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }

            $goalTracking                     = new GoalTracking();
            $goalTracking->brand_id         = $request->brand_id;
            $goalTracking->region_id         = $request->region_id;
            $goalTracking->branch         = $request->lead_branch;

            $goalTracking->goal_type          = $request->goal_type;
            $goalTracking->start_date         = $request->start_date;
            $goalTracking->end_date           = $request->end_date;
            $goalTracking->subject            = $request->subject;
            $goalTracking->target_achievement = $request->target_achievement;
            $goalTracking->description        = $request->description;
            $goalTracking->created_by         = \Auth::user()->creatorId();
            $goalTracking->save();

            return json_encode([
                'status' => 'success',
                'message' => 'Goal tracking successfully created.',
                'id'=> $goalTracking->id,
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission denied.'
            ]);
        }
    }


    public function GoalTrackingShow(Request $request)
    {
        $goalTracking = GoalTracking::select(
            'goal_trackings.*', // Corrected this line
            'regions.name as region',
            'branches.name as branch',
            'users.name as brand',
        )
        ->leftJoin('users', 'users.id', '=', 'goal_trackings.brand_id')
        ->leftJoin('branches', 'branches.id', '=', 'goal_trackings.branch')
        ->leftJoin('regions', 'regions.id', '=', 'goal_trackings.region_id')->where('goal_trackings.id',$request->id)->first();
        if(!empty($goalTracking)){
            $html = view('goaltracking.show', compact('goalTracking'))->render();

            return json_encode([
                'status' => 'success',
                'html' => $html
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Sorry Empty data.'
            ]);
        }
    }


    public function edit($id)
    {

        if(\Auth::user()->can('edit goal tracking'))
        {
            $goalTracking = GoalTracking::find($id);
            $brances      = Branch::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $brances->prepend('Select Branch', '');
            $goalTypes = GoalType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $goalTypes->prepend('Select Goal Type', '');
            $status = GoalTracking::$status;

            $ratings = json_decode($goalTracking->rating,true);
            $filter = BrandsRegionsBranchesForEdit($goalTracking->brand_id, $goalTracking->region_id, $goalTracking->branch);
            $companies = $filter['brands'];
            $regions = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];

            return view('goaltracking.edit', compact('companies','regions','branches','employees','goalTypes', 'goalTracking', 'ratings','status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit goal tracking'))
        {
            $goalTracking = GoalTracking::find($id);
            $validator    = \Validator::make(
                $request->all(), [
                    'brand_id' => 'required|integer|min:1',
                    'region_id' => 'required|integer|min:1',
                    'lead_branch' => 'required|integer|min:1',
                    'goal_type' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'subject' => 'required',
                               ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }

            $goalTracking->brand_id         = $request->brand_id;
            $goalTracking->region_id         = $request->region_id;
            $goalTracking->branch         = $request->lead_branch;


            $goalTracking->goal_type          = $request->goal_type;
            $goalTracking->start_date         = $request->start_date;
            $goalTracking->end_date           = $request->end_date;
            $goalTracking->subject            = $request->subject;
            $goalTracking->target_achievement = $request->target_achievement;
            $goalTracking->status             = $request->status;
            $goalTracking->progress           = $request->progress;
            $goalTracking->description        = $request->description;
            $goalTracking->rating         = json_encode($request->rating, true);
            $goalTracking->rating        = $request->rating;
            $goalTracking->save();

            return json_encode([
                'status' => 'success',
                'message' => 'Goal tracking successfully updated.',
                'id'=> $goalTracking->id,
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission denied.'
            ]);
        }
    }



    public function destroy($id)
    {

        if(\Auth::user()->can('delete goal tracking'))
        {
            $goalTracking = GoalTracking::find($id);
            if($goalTracking->created_by == \Auth::user()->creatorId())
            {
                $goalTracking->delete();

                return redirect()->route('goaltracking.index')->with('success', __('GoalTracking successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
