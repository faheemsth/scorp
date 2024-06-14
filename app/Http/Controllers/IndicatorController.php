<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Competencies;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Indicator;
use App\Models\PerformanceType;
use App\Models\SavedFilter;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage indicator'))
        {
            $user = \Auth::user();
            $query = Indicator::select('indicators.id','regions.name as region','branches.name as branch','users.name as brand','indicators.id','indicators.created_by','indicators.department','indicators.designation','indicators.created_user')
            ->leftJoin('users', 'users.id', '=', 'indicators.brand_id')
            ->leftJoin('branches', 'branches.id', '=', 'indicators.branch')
            ->leftJoin('regions', 'regions.id', '=', 'indicators.region_id')
            ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'indicators.created_by');
            $indicator_query = RoleBaseTableGet($query,'indicators.brand_id','indicators.region_id','indicators.branch','indicators.created_by');
            $filters = $this->IndicatorFilters();

            foreach ($filters as $column => $value) {
                if ($column == 'created_at') {
                    $indicator_query->whereDate('indicators.created_at', 'LIKE', '%' . substr($value, 0, 10) . '%');
                }elseif ($column == 'brand') {
                    $indicator_query->where('indicators.brand_id', $value);
                }elseif ($column == 'region_id') {
                    $indicator_query->where('indicators.region_id', $value);
                }elseif ($column == 'branch_id') {
                    $indicator_query->where('indicators.branch', $value);
                }

            }
            $indicators=$indicator_query->get();
            $saved_filters = SavedFilter::where('created_by', \Auth::id())->where('module', 'indicator')->get();
            $filters = BrandsRegionsBranches();
            return view('indicator.index', compact('filters','saved_filters','indicators'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    private function IndicatorFilters()
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
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('Select Department', '');

        $filter = BrandsRegionsBranches();
        $companies = $filter['brands'];
        $regions = $filter['regions'];
        $branches = $filter['branches'];
        $employees = $filter['employees'];

        return view('indicator.create', compact('companies','regions','branches','departments','performance'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create indicator'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'brand_id' => 'required|integer|min:1',
                    'region_id' => 'required|integer|min:1',
                    'lead_branch' => 'required|integer|min:1',
                    'department' => 'required',
                    'designation' => 'required',
                               ]
            );


            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }


            $indicator              = new Indicator();
            $indicator->branch      = $request->lead_branch;

            $indicator->brand_id      = $request->brand_id;
            $indicator->region_id      = $request->region_id;

            $indicator->department  = $request->department;
            $indicator->designation = $request->designation;

            $indicator->rating      = json_encode($request->rating, true);

            if(\Auth::user()->type == 'company')
            {
                $indicator->created_user = \Auth::user()->creatorId();
            }
            else
            {
                $indicator->created_user = \Auth::user()->id;
            }

            $indicator->created_by = \Auth::user()->creatorId();
            $indicator->save();

            return json_encode([
                'status' => 'success',
                'message' => 'Indicator successfully created.',
                'id' => $indicator->id
            ]);
        }
        else
        {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission denied.'
            ]);
        }
    }


    public function IndicatorShowing(Request $request)
    {
        $indicator = Indicator::select('indicators.id','regions.name as region','branches.name as branch','users.name as brand','indicators.id','indicators.created_by','indicators.department','indicators.designation','indicators.created_user','indicators.rating','indicators.created_at','indicators.updated_at')
        ->leftJoin('users', 'users.id', '=', 'indicators.brand_id')
        ->leftJoin('branches', 'branches.id', '=', 'indicators.branch')
        ->leftJoin('regions', 'regions.id', '=', 'indicators.region_id')
        ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'indicators.created_by')->first();

        $ratings = json_decode($indicator->rating,true);
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();

        $html = view('indicator.show', compact('indicator','ratings','performance'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }


    public function edit(Indicator $indicator)
    {
        if(\Auth::user()->can('edit indicator'))
        {

            $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
            $brances        = Branch::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments    = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('Select Department', '');

            $ratings = json_decode($indicator->rating,true);
            $filter = BrandsRegionsBranchesForEdit($indicator->brand_id, $indicator->region_id, $indicator->branch);
            $companies = $filter['brands'];
            $regions = $filter['regions'];
            $branches = $filter['branches'];
            $employees = $filter['employees'];

            return view('indicator.edit', compact('companies','regions','branches','brances', 'departments','performance','indicator','ratings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Indicator $indicator)
    {

        if(\Auth::user()->can('edit indicator'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'brand_id' => 'required|integer|min:1',
                    'region_id' => 'required|integer|min:1',
                    'lead_branch' => 'required|integer|min:1',
                    'department' => 'required',
                    'designation' => 'required',
                               ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return json_encode([
                    'status' => 'error',
                    'message' => $messages->first()
                ]);
            }


            $indicator->branch      = $request->lead_branch;

            $indicator->brand_id      = $request->brand_id;
            $indicator->region_id      = $request->region_id;

            $indicator->department  = $request->department;
            $indicator->designation = $request->designation;

            $indicator->rating = json_encode($request->rating, true);
            $indicator->save();

            return json_encode([
                'status' => 'success',
                'message' => 'Indicator successfully updated.'
            ]);
        }
        else
        {
            return json_encode([
                'status' => 'error',
                'message' => 'Permission denied.'
            ]);
        }
    }


    public function destroy(Indicator $indicator)
    {
        if(\Auth::user()->can('delete indicator'))
        {
            if($indicator->created_by == \Auth::user()->creatorId())
            {
                $indicator->delete();

                return redirect()->route('indicator.index')->with('success', __('Indicator successfully deleted.'));
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

    public function HrmIndicator()
    {
        $indicator = Indicator::select('indicators.id','regions.name as region','branches.name as branch','users.name as brand','indicators.created_by','indicators.department','indicators.designation','indicators.created_user','indicators.rating','indicators.created_at','indicators.updated_at')
        ->leftJoin('users', 'users.id', '=', 'indicators.brand_id')
        ->leftJoin('branches', 'branches.id', '=', 'indicators.branch')
        ->leftJoin('regions', 'regions.id', '=', 'indicators.region_id')
        ->leftJoin('users as assigned_to', 'assigned_to.id', '=', 'indicators.created_by')
        ->where('indicators.created_user', \Auth::id())
        ->first();
        $ratings = json_decode($indicator->rating,true);
        $performance     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();

        return view('hrmhome.indicator', compact('indicator','ratings','performance'));

       
    }
}
