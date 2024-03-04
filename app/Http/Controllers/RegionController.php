<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Region;
use App\Models\University;
use App\Models\SavedFilter;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {

        $num_results_on_page = 25;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = 0;
        }

        $region_query = Region::select(['regions.*']);

        ///////////////////Filter Data
        if(isset($_GET['brand_id']) && !empty($_GET['brand_id'])){
            $region_query->where('brands', $_GET['brand_id']);
        }


        if(isset($_GET['region_id']) && !empty($_GET['region_id'])){
            $region_query->where('id', $_GET['region_id']);
        }

        if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
            $g_search = $_GET['search'];
            $region_query->leftjoin('users as brand', 'brand.id', '=', 'regions.brands')
                        ->leftjoin('users as manager', 'manager.id', '=', 'regions.region_manager_id')
                        ->where('regions.name', 'like', '%' . $g_search . '%')
                        ->orwhere('regions.email', 'like', '%'.$g_search.'%')
                        ->orwhere('regions.phone', 'like', '%'.$g_search.'%')
                        ->orwhere('regions.location', 'like', '%'.$g_search.'%')
                        ->orwhere('manager.name', 'like', '%'.$g_search.'%')
                        ->orwhere('brand.name', 'like', '%'.$g_search.'%');
        }

        if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'HR') {

        } else if (\Auth::user()->type == 'company') {
            $region_query->where('brands', \Auth::user()->id);
        } else {
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $region_query->whereIn('brands', $brand_ids);
        }


        $total_records = $region_query->count();
        $regions = $region_query->skip($start)->take($num_results_on_page)->orderBy('name', 'ASC')->paginate($num_results_on_page);
        $users = allUsers();
        $saved_filters = SavedFilter::where('created_by', \Auth::user()->id)->where('module', 'region')->get();

        //filter brand, region, employees
        $filter = BrandsRegionsBranches();


        $data = [
            'regions' => $regions,
            'users' => $users,
            'total_records' => $total_records,
            'filter' => $filter,
            'saved_filters' => $saved_filters
        ];



        if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
            $html = view('region.regionAjax', $data)->render();
            $pagination_html = view('layouts.pagination', [
                'total_pages' => $total_records,
                'num_results_on_page' => $num_results_on_page,
            ])->render();
            return json_encode([
                'status' => 'success',
                'html' => $html,
                'pagination_html' => $pagination_html
            ]);
        } else {
            return view('region.index', $data);
        }

        ////

    }

    public function create()
    {
        // $regions = Region::all();

        $brands = FiltersBrands();

        $regionmanager = User::where('type', 'branch manager')->get();

        return view('region.create', compact('regionmanager', 'brands'));
    }
    public function getRegionBrandsTask(Request $request)
    {
        $id = $_GET['id'];
        $type = $request->type;

        // dd($type);

        if ($type == 'brand') {
            $regions = Region::where('brands', $id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $html = ' <select class="form form-control select2" id="region_id" name="region_id"> <option value="">Select Region</option> ';
            foreach ($regions as $key => $region) {
                $html .= '<option value="' . $key . '">' . $region . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'regions' => $html,
            ]);
        } else if ($type == 'region') {

            $branches = Branch::where('region_id', $id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $html = '<select class="form form-control select2" id="branch_id" name="branch_id" onchange="Change(this)"> <option value="">Select Branch</option> ';
            foreach ($branches as $key => $branch) {
                $html .= '<option value="' . $key . '">' . $branch . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);
        } else if ($type == 'branch') {

            $employees = User::whereNotIn('type', ['super admin', 'company', 'accountant', 'client'])->where('branch_id', $id)
                ->where('type', '!=', 'company')
                ->pluck('name', 'id')
                ->toArray();

            $html = ' <select class="form form-control lead_assgigned_user select2" id="choices-multiple4" name="assigned_to" > <option value="">Select User</option> ';
            foreach ($employees as $key => $user) {
                $html .= '<option value="' . $key . '">' . $user . '</option> ';
            }
            $html .= '</select>';

            return json_encode([
                'status' => 'success',
                'employees' => $html,
            ]);
        } else {

            $region = Region::where('id', $id)->first();
            $brands = array();

            if ($region) {
                $ids = explode(',', $region->brands);
                $brands = User::whereIn('id', $ids)->where('type', 'company')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();

                $html = ' <label for="region_id">Brands</label><select class="form form-control brands select2" id="brands" name="brands" multiple required> <option value="">Select Brands</option> ';
                foreach ($brands as $key => $brand) {
                    $html .= '<option value="' . $key . '">' . $brand . '</option> ';
                }
                $html .= '</select>';


                return json_encode([
                    'status' => 'success',
                    'brands' => $html,
                ]);
            } else {
                return json_encode([
                    'status' => 'failure',
                ]);
            }
        }
    }

    public function getRegionBrands(Request $request)
    {
        $id = $_GET['id'];
        $type = $request->type;

        if ($type == 'brand') {
            //  $regions = Region::whereRaw('FIND_IN_SET(?, brands)', [$id])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();

            $regions = Region::where('brands', $id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $filter = $_GET['filter'] ?? '';

            $html = '<label for="region_id">Region';

            // Checking if $filter is not empty and is true
            if (empty($filter)) {
                $html .= '<span class="text-danger">*</span>';
            }

            $html .= '</label><select class="form form-control select2" id="region_id" name="region_id"><option value="">Select Region</option>';

            foreach ($regions as $key => $region) {
                $html .= '<option value="' . $key . '">' . $region . '</option> ';
            }
            $html .= '</select>';


            return json_encode([
                'status' => 'success',
                'regions' => $html,
            ]);
        } else if ($type == 'region') {
            $filter = $_GET['filter'] ?? '';

            $branches = Branch::where('region_id', $id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();

            $html = '<label for="branch_id">Branch';

            // Checking if $filter is not empty and is true
            if (empty($filter)) {
                $html .= '<span class="text-danger">*</span>';
            }

            $html .= '</label><select class="form form-control select2" id="branch_id" name="branch_id" > <option value="">Select Branch</option> ';


            foreach ($branches as $key => $branch) {
                $html .= '<option value="' . $key . '">' . $branch . '</option> ';
            }
            $html .= '</select>';



            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);
        } else if ($type == 'institute') {

            $institute = University::where('id', $id)->first();
            $intake_months = $institute->intake_months ?? '';
            $intake_months = explode(',', $intake_months);

            $html = '<label for="intake_month">Intake Month</label><select class="form form-control select2" id="intake_month" name="intake_month"> <option value="">Select Institute</option> ';
            foreach ($intake_months as $key => $month) {
                $html .= '<option value="' . $month . '">' . $month . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'insitute' => $html,
            ]);
        } else {

            $region = Region::where('id', $id)->first();
            $brands = array();

            if ($region) {
                $ids = explode(',', $region->brands);
                $brands = User::whereIn('id', $ids)->where('type', 'company')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();

                $html = ' <label for="region_id">Brands</label><select class="form form-control brands select2" id="brands" name="brands[]" multiple required> <option value="">Select Brands</option> ';
                foreach ($brands as $key => $brand) {
                    $html .= '<option value="' . $key . '">' . $brand . '</option> ';
                }
                $html .= '</select>';


                return json_encode([
                    'status' => 'success',
                    'brands' => $html,
                ]);
            } else {
                return json_encode([
                    'status' => 'failure',
                ]);
            }
        }
    }

    public function save(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'brands' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return json_encode([
                'status' => 'error',
                'msg' => $messages->first()
            ]);
            //return redirect()->back()->with('error', $messages->first());
        }

        if (!empty($request->id)) {

            // Region::find($request->id)->update($request->all());
            $region = Region::findOrFail($request->id);
            $region->name = $request->name;
            $region->region_manager_id = $request->region_manager_id;
            $region->location = $request->location;
            $region->phone = $request->phone;
            $region->email = $request->email;
            $region->brands = $request->brands[0];
            $region->update();

            return json_encode([
                'status' => 'success',
                'id' => $region->id,
                'msg' => 'Region updated successfully.'
            ]);
        } else {


            $brands = null;
            if ($request->brands != null && sizeof($request->brands) > 0) {
                $brands = $request->brands;
            }

            $data = $request->all();
            $data['brands'] = $brands[0];

            $region = Region::create($data);

            return json_encode([
                'status' => 'success',
                'id' => $region->id,
                'msg' => 'Region created successfully'
            ]);
        }



        //return back();
    }


    public function update(Request $request)
    {
        $brands = FiltersBrands();
        $regions = Region::find($request->id);
        $regionmanager = User::where('type', 'branch manager')->get();
        return view('region.edit', compact('regions', 'regionmanager', 'brands'));
    }

    public function delete($id)
    {
        Region::find($id)->delete();
        return back();
    }

    public function regions_show($id)
    {
        $employee = Region::findOrFail($id);
        $users = allUsers();
        $html = view('region.employeeDetail', compact('employee', 'users'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function getFilterRegions()
    {
        $html = FiltersRegions($_GET['id']);
        return json_encode([
            'html' => $html,
            'status' => 'success'
        ]);
    }

    public function getFilterBranches()
    {
        $html = FiltersBranches($_GET['id']);
        return json_encode([
            'html' => $html,
            'status' => 'success'
        ]);
    }


    public function getFilterBranchUsers()
    {
        $html = FiltersBranchUsers($_GET['id']);
        return json_encode([
            'html' => $html,
            'status' => 'success'
        ]);
    }

    ////////Delete bulk Regions
    public function deleteBulkRegions(Request $request){

        if (\Auth::user()->can('delete region') || \Auth::user()->type == 'super admin') {

                if($request->ids != null){
                    Region::whereIn('id', explode(',', $request->ids))->delete();
                    return redirect()->route('region.index')->with('success', 'Regions deleted successfully');
                }else{
                    return redirect()->route('region.index')->with('error', 'Atleast select 1 lead.');
                }

        }else{
            return redirect()->route('region.index')->with('error', __('Permission Denied.'));
        }

    }



    public function download(){
        $region_query = Region::select(['regions.*']);

        ///////////////////Filter Data
        if(isset($_GET['brand_id']) && !empty($_GET['brand_id'])){
            $region_query->where('brands', $_GET['brand_id']);
        }


        if(isset($_GET['region_id']) && !empty($_GET['region_id'])){
            $region_query->where('id', $_GET['region_id']);
        }

        if (\Auth::user()->type == 'super admin') {
            $regions = Region::orderBy('name', 'ASC')->get();
        } else if (\Auth::user()->type == 'company') {
           // $regions = Region::whereRaw('FIND_IN_SET(?, brands)', [\Auth::user()->id])->skip($start)->take($num_results_on_page)->orderBy('name', 'ASC')->paginate($num_results_on_page);;
            $region_query->where('brands', [\Auth::user()->id]);
        } else {


            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $region_query->whereIn('brands', $brand_ids);

           // $region_query = Region::query();

            // foreach ($brand_ids as $brandId) {
            //     $region_query->orWhereRaw('FIND_IN_SET(?, brands)', [$brandId]);
            // }
           // $total_records = $region_query->count();
            //$regions = $region_query->skip($start)->take($num_results_on_page)->orderBy('name', 'ASC')->paginate($num_results_on_page);
        }


        $regions = $region_query->orderBy('name', 'ASC')->get();
        $users = allUsers();


        $header = [
            'S.No.',
            'Name',
            'Email',
            'Phone',
            'Location',
            'Region Manager',
            'Brand'
        ];

        $data = [];
        foreach($regions as $key => $region){
            $data[] = [
                'sr' => $key+1,
                'name' => $region->name,
                'email' => $region->email,
                'phone' => $region->phone,
                'location' => $region->location,
                'manager' => $users[$region->region_manager_id] ?? '',
                'brand' => $users[$region->brands] ?? ''
            ];
        }

        downloadCSV($header, $data, 'regions.csv');
        return true;
    }
}
