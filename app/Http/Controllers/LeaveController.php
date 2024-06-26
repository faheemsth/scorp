<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Utility;
use App\Models\Trainer;
use App\Models\SavedFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeaveController extends Controller
{
    public function index()
    {



          //  dd($filters);

        if(\Auth::user()->can('manage leave'))
        {


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
            $saved_filters = SavedFilter::where('created_by', \Auth::id())->where('module', 'leads')->get();
            $filters = BrandsRegionsBranches();


            $pagination = getPaginationDetail();
            $start = $pagination['start'];
            $limit = $pagination['num_results_on_page'];

            $query = Leave::query();

             // Apply user type-based filtering
            $userType = \Auth::user()->type;
            if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                // No additional filtering needed
            } elseif ($userType === 'company') {
                $query->where('leaves.brand_id', \Auth::user()->id);
            } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                $query->whereIn('leaves.brand_id', $brand_ids);
            } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                $query->where('leaves.region_id', \Auth::user()->region_id);
            } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || \Auth::user()->can('level 4') && !empty(\Auth::user()->branch_id)) {
                $query->where('leaves.branch_id', \Auth::user()->branch_id);
            } else {
                $query->where('leaves.created_by', \Auth::user()->id);
            }

            $filters = $this->dealFilters();

            foreach ($filters as $column => $value) {
                if ($column === 'name') {
                    $query->whereIn('leaves.firstname', $value);
                } elseif ($column === 'stage_id') {
                    $query->whereIn('leaves.stage_id', $value);
                } elseif ($column == 'users') {
                    $query->whereIn('leaves.created_by', $value);
                } elseif ($column == 'created_at') {
                    $query->whereDate('leaves.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                }elseif ($column == 'brand') {
                    $query->where('leaves.brand_id', $value);
                }elseif ($column == 'region_id') {
                    $query->where('leaves.region_id', $value);
                }elseif ($column == 'branch_id') {
                    $query->where('leaves.branch_id', $value);
                }

            }



            if(\Auth::user()->can('level 1')){
                $total_records = $query->count();
                $leaves = $query->skip($start)->take($limit)->get();


            }else{
                $user     = \Auth::user();
                $employee = Employee::where('user_id', '=', $user->id)->first();
                $total_records = $query->count();
                $leaves = $query->where('employee_id',  $employee->id)->skip($start)->take($limit)->get();
            }

             $filters = BrandsRegionsBranches();
              $allPluckUser = allUsers();
              $allPluckUser[0] = '';
              $allPluckregion = allRegions();
              $allPluckregion[0] = '';
              $allPluckbranch = allBranches();
              $allPluckbranch[0] = '';
            return view('leave.index', compact('leaves','allPluckUser','allPluckregion','allPluckbranch', 'total_records','filters','trainers', 'saved_filters'));
        }
        else
        {
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


    public function create()
    {
        if(\Auth::user()->can('create leave'))
        {
            if(Auth::user()->type == 'employee')
            {
                $employees = Employee::where('user_id', '=', \Auth::user()->id)->get()->pluck('name', 'id');
            }
            else
            {
                $employees = Employee::where('created_by', '=', \Auth::id())->get()->pluck('name', 'id');
            }
            $leavetypes      = LeaveType::where('created_by', '=', \Auth::id())->get();
            $leavetypes_days = LeaveType::where('created_by', '=', \Auth::id())->get();


            $filter = BrandsRegionsBranches();
            $companies = $filter['brands'];
            $regions = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];
            return view('leave.create', compact('employees', 'leavetypes', 'leavetypes_days', 'companies', 'regions', 'branches'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create leave'))
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'brand_id' => 'required',
                                   'region_id' => 'required',
                                   'lead_branch' => 'required',
                                   'lead_assigned_user' => 'required',
                                   'leave_type_id' => 'required',
                                   'start_date' => 'required',
                                   'end_date' => 'required',
                                   'leave_reason' => 'required',
                                   'remark' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

           // dd($request);


            $leave    = new Leave();

            $leave->employee_id = $request->lead_assigned_user;
            $leave->brand_id = $request->brand_id;
            $leave->region_id = $request->region_id;
            $leave->branch_id = $request->lead_branch;

            $leave->leave_type_id    = $request->leave_type_id;
            $leave->applied_on       = date('Y-m-d');
            $leave->start_date       = $request->start_date;
            $leave->end_date         = $request->end_date;
            $leave->total_leave_days = 0;
            $leave->leave_reason     = $request->leave_reason;
            $leave->remark           = $request->remark;
            $leave->status           = 'Pending';
            $leave->created_by       = \Auth::id();

            $leave->save();

            return redirect()->route('leave.index')->with('success', __('Leave  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Leave $leave)
    {
        return redirect()->route('leave.index');
    }

    public function edit(Leave $leave)
    {
        if(\Auth::user()->can('edit leave'))
        {
            if($leave->created_by == \Auth::id())
            {
                $employees  = Employee::where('created_by', '=', \Auth::id())->get()->pluck('name', 'id');
                $leavetypes = LeaveType::where('created_by', '=', \Auth::id())->get()->pluck('title', 'id');

                return view('leave.edit', compact('leave', 'employees', 'leavetypes'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $leave)
    {

        $leave = Leave::find($leave);
        if(\Auth::user()->can('edit leave'))
        {
            if($leave->created_by == Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'leave_type_id' => 'required',
                                       'start_date' => 'required',
                                       'end_date' => 'required',
                                       'leave_reason' => 'required',
                                       'remark' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $leave->employee_id      = $request->employee_id;
                $leave->leave_type_id    = $request->leave_type_id;
                $leave->start_date       = $request->start_date;
                $leave->end_date         = $request->end_date;
                $leave->total_leave_days = 0;
                $leave->leave_reason     = $request->leave_reason;
                $leave->remark           = $request->remark;

                $leave->save();

                return redirect()->route('leave.index')->with('success', __('Leave successfully updated.'));
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

    public function destroy(Leave $leave)
    {
        if(\Auth::user()->can('delete leave'))
        {
            if($leave->created_by == \Auth::id())
            {
                $leave->delete();

                return redirect()->route('leave.index')->with('success', __('Leave successfully deleted.'));
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

    public function action($id)
    {
        $leave     = Leave::find($id);
        $employee  = Employee::find($leave->employee_id);
        $leavetype = LeaveType::find($leave->leave_type_id);

        return view('leave.action', compact('employee', 'leavetype', 'leave'));
    }

    public function changeaction(Request $request)
    {

        $leave = Leave::find($request->leave_id);

        $leave->status = $request->status;
        if($leave->status == 'Approval')
        {
            $startDate               = new \DateTime($leave->start_date);
            $endDate                 = new \DateTime($leave->end_date);
            $total_leave_days        = $startDate->diff($endDate)->days;
            $leave->total_leave_days = $total_leave_days;
            $leave->status           = 'Approved';
        }

        $leave->save();


       //Send Email
        $setings = Utility::settings();
        if(!empty($employee->id))
        {
            if($setings['leave_status'] == 1)
            {

                $employee     = Employee::where('id', $leave->employee_id)->where('created_by', '=', \Auth::id())->first();
                $leave->name  = !empty($employee->name) ? $employee->name : '';
                $leave->email = !empty($employee->email) ? $employee->email : '';
//            dd($leave);

                $actionArr = [

                    'leave_name'=> !empty($employee->name) ? $employee->name : '',
                    'leave_status' => $leave->status,
                    'leave_reason' =>  $leave->leave_reason,
                    'leave_start_date' => $leave->start_date,
                    'leave_end_date' => $leave->end_date,
                    'total_leave_days' => $leave->total_leave_days,

                ];
//            dd($actionArr);
                $resp = Utility::sendEmailTemplate('leave_action_sent', [$employee->id => $employee->email], $actionArr);


                return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.') .(($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

            }

        }

        return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.'));
    }


    public function jsoncount(Request $request)
    {

        // $leave_counts = LeaveType::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave, leave_types.title, leave_types.days,leave_types.id'))
        //                          ->leftjoin('leaves', function ($join) use ($request){
        //     $join->on('leaves.leave_type_id', '=', 'leave_types.id');
        //     $join->where('leaves.employee_id', '=', $request->employee_id);
        // }
        // )->groupBy('leaves.leave_type_id')->get();

        $leave_counts=[];
        $leave_types = LeaveType::where('created_by',\Auth::id())->get();
        foreach ($leave_types as  $type) {
            $counts=Leave::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave'))->where('leave_type_id',$type->id)->groupBy('leaves.leave_type_id')->where('employee_id',$request->employee_id)->first();

            $leave_count['total_leave']=!empty($counts)?$counts['total_leave']:0;
            $leave_count['title']=$type->title;
            $leave_count['days']=$type->days;
            $leave_count['id']=$type->id;
            $leave_counts[]=$leave_count;
        }


        return $leave_counts;

    }

    public function Hrmleave()
    {

        
        $user = \Auth::user();

        if ($user->type!='HR' && $user->type!='super admin' && $user->type!='Project Manager') {
            echo 'access Denied';
                exit();
                die();
    }
    
        // Build the leads query
        if(isset($_GET['emp_id'])){
            $userId = $_GET['emp_id'];
         }else{
             $userId = \Auth::id();
         }
        $Leave_query = Leave::select(
                'regions.name as region',
                'branches.name as branch',
                'users.name as brand',
                'leaves.id',
                'leaves.brand_id',
                'leaves.branch_id',
                'leaves.created_by',
                'leaves.start_date',
                'leaves.created_at',
                'leaves.end_date',
                'leaves.leave_type_id',
                'leaves.status',
                )
                ->join('users', 'users.id', '=', 'leaves.brand_id')
                ->leftJoin('users as leavedPerson', 'users.id', '=', 'leaves.employee_id')
                ->join('branches', 'branches.id', '=', 'leaves.branch_id')
                ->join('regions', 'regions.id', '=', 'leaves.region_id')
                ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'leaves.created_by')
                ->leftJoin('lead_tags as tag', 'tag.lead_id', '=', 'leaves.id');

        $Leave_query->where('leaves.created_by',$userId);
        $leaves=$Leave_query->get();
           
        return view('hrmhome.leave', compact('leaves'));
       
    }
}
