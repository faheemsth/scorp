<?php

namespace App\Http\Controllers;

use App\Models\Appraisal;
use App\Models\Branch;
use App\Models\Competencies;
use App\Models\Employee;
use App\Models\Indicator;
use App\Models\Performance_Type;
use App\Models\PerformanceType;
use App\Models\SavedFilter;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage appraisal')) {

            $user = \Auth::user();
            $Appraisal_query = Appraisal::select(
                'appraisals.*', // Corrected this line
                'regions.name as region',
                'branches.name as branch',
                'users.name as brand',
                'assigned_to.name as created_user'
            )
            ->leftJoin('users', 'users.id', '=', 'appraisals.brand_id')
            ->leftJoin('branches', 'branches.id', '=', 'appraisals.branch')
            ->leftJoin('regions', 'regions.id', '=', 'appraisals.region_id')
            ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'appraisals.created_by');

            $userType = \Auth::user()->type;
            if (in_array($userType, ['super admin', 'Admin Team']) || \Auth::user()->can('level 1')) {
                // No additional filtering needed
            } elseif ($userType === 'company') {
                $Appraisal_query->where('appraisals.brand_id', \Auth::user()->id);
            } elseif (in_array($userType, ['Project Director', 'Project Manager']) || \Auth::user()->can('level 2')) {
                $Appraisal_query->whereIn('appraisals.brand_id', $brand_ids);
            } elseif (($userType === 'Region Manager' || \Auth::user()->can('level 3')) && !empty(\Auth::user()->region_id)) {
                $Appraisal_query->where('appraisals.region_id', \Auth::user()->region_id);
            } elseif (($userType === 'Branch Manager' || in_array($userType, ['Admissions Officer', 'Admissions Manager', 'Marketing Officer'])) || \Auth::user()->can('level 4') && !empty(\Auth::user())) {
                $Appraisal_query->where('appraisals.branch', \Auth::user()->branch_id);
            } else {
                $Appraisal_query->where('appraisals.created_by', \Auth::user()->id);
            }



            $filters = $this->AppraisalFilters();

            foreach ($filters as $column => $value) {
                if ($column == 'created_at') {
                    $Appraisal_query->whereDate('appraisals.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                }elseif ($column == 'brand') {
                    $Appraisal_query->where('appraisals.brand_id', $value);
                }elseif ($column == 'region_id') {
                    $Appraisal_query->where('appraisals.region_id', $value);
                }elseif ($column == 'branch_id') {
                    $Appraisal_query->where('appraisals.branch', $value);
                }

            }
            $appraisals = $Appraisal_query->get();
            if ($user->type == 'employee') {
                $employee   = Employee::where('user_id', $user->id)->first();
                $competencyCount = Competencies::where('created_by', '=', $user->creatorId())->count();
            } else {
                $competencyCount = Competencies::where('created_by', '=', $user->creatorId())->count();
            }
            $saved_filters = SavedFilter::where('created_by', \Auth::id())->where('module', 'Appraisal')->get();
            $filters = BrandsRegionsBranches();
            return view('appraisal.index', compact('filters','saved_filters','appraisals', 'competencyCount'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    private function AppraisalFilters()
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
        if (\Auth::user()->can('create appraisal')) {

            $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
            $filter = BrandsRegionsBranches();
            $companies = $filter['brands'];
            $regions = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];
            return view('appraisal.create', compact('branches', 'regions', 'employees', 'companies', 'performance'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {

        if (\Auth::user()->can('create appraisal')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'brand_id' => 'required|integer|min:1',
                    'region_id' => 'required|integer|min:1',
                    'lead_branch' => 'required|integer|min:1',
                    'lead_assigned_user' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }

            $appraisal                 = new Appraisal();

            $appraisal->brand_id         = $request->brand_id;
            $appraisal->region_id         = $request->region_id;
            $appraisal->branch         = $request->lead_branch;
            $appraisal->employee       = $request->lead_assigned_user;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->remark         = $request->remark;
            $appraisal->created_by     = \Auth::user()->creatorId();
            $appraisal->save();

            return json_encode([
                'status' => 'success',
                'message' => 'Appraisal successfully created.'
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission denied.'
            ]);
        }
    }

    public function appraisalShow(Request $request)
    {
        $appraisal = Appraisal::find($request->id);
        if(empty($appraisal)){
            return json_encode([
                'status' => 'error',
                'message' => 'Data Not Found'
            ]);
        }
        $rating = json_decode($appraisal->rating, true);
        $performance_types     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $employee = Employee::find($appraisal->employee);
        $indicator = Indicator::where('created_user', $appraisal->employee)->first();
        $ratings = json_decode($indicator->rating, true);

        $html = view('appraisal.show', compact('appraisal', 'performance_types', 'ratings', 'rating'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function edit(Appraisal $appraisal)
    {
        if (\Auth::user()->can('edit appraisal')) {

            $performance_types     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
            $brances = Branch::where('created_by', '=', \Auth::user()->creatorId())->get();
            $ratings = json_decode($appraisal->rating, true);


            return view('appraisal.edit', compact('brances', 'appraisal', 'performance_types', 'ratings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, Appraisal $appraisal)
    {
        if (\Auth::user()->can('edit appraisal')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'employee' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal->branch         = $request->branch;
            $appraisal->employee       = $request->employee;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->remark         = $request->remark;
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully updated.'));
        }
    }

    public function destroy(Appraisal $appraisal)
    {
        if (\Auth::user()->can('delete appraisal')) {
            if ($appraisal->created_by == \Auth::user()->creatorId()) {
                $appraisal->delete();

                return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function empByStar(Request $request)
    {
        $employee = Employee::find($request->employee);
        if (empty($employee)) {
            return response()->json(array('error' => true, 'message' => 'you have empty record'));
        }

        $indicator = Indicator::where('branch', $employee->branch_id)->where('department', $employee->department_id)->where('designation', $employee->designation_id)->first();

        $ratings = !empty($indicator) ? json_decode($indicator->rating, true) : [];

        $performance_types = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();

        $viewRender = view('appraisal.star', compact('ratings', 'performance_types'))->render();
        // dd($viewRender);
        return response()->json(array('success' => true, 'html' => $viewRender));
    }

    public function empByStar1(Request $request)
    {
        $employee = Employee::find($request->employee);

        $appraisal = Appraisal::find($request->appraisal);

        $indicator = Indicator::where('branch', $employee->branch_id)->where('department', $employee->department_id)->where('designation', $employee->designation_id)->first();

        $ratings = json_decode($indicator->rating, true);
        $rating = json_decode($appraisal->rating, true);
        $performance_types = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $viewRender = view('appraisal.staredit', compact('ratings', 'rating', 'performance_types'))->render();
        // dd($viewRender);
        return response()->json(array('success' => true, 'html' => $viewRender));
    }

    public function getemployee(Request $request)
    {
        $data['employee'] = Employee::where('branch_id', $request->branch_id)->get();
        return response()->json($data);
    }
}
