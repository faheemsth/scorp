<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Department;
use App\Models\SavedFilter;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage branch'))
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

        $branch_query = Branch::select(['branches.*']);
        if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
            $g_search = $_GET['search'];
            $branch_query->leftjoin('regions', 'regions.id', '=', 'branches.region_id')
                        ->leftjoin('users as brand', 'brand.id', '=', 'branches.brands')
                        ->leftjoin('users as manager', 'manager.id', '=', 'branches.branch_manager_id')
                        ->where('branches.name', 'like', '%' . $g_search . '%')
                        ->orwhere('branches.email', 'like', '%'.$g_search.'%')
                        ->orwhere('branches.google_link', 'like', '%'.$g_search.'%')
                        ->orwhere('branches.social_media_link', 'like', '%'.$g_search.'%')
                        ->orwhere('branches.phone', 'like', '%'.$g_search.'%')
                        ->orwhere('regions.name', 'like', '%'.$g_search.'%')
                        ->orwhere('manager.name', 'like', '%'.$g_search.'%')
                        ->orwhere('brand.name', 'like', '%'.$g_search.'%');
        }

            if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'HR'){
             }else if(\Auth::user()->type == 'company'){
              $branch_query->whereRaw('brands', [\Auth::user()->id]);
            }else{
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $branch_query->whereIn('brands', $brand_ids);
            }


            if(\Auth::user()->type == 'Region Manager'){
                $branch_query->where('region_id', \Auth::user()->region_id);
            }

            ///Filter Data
            if(isset($_GET['brand_id']) && !empty($_GET['brand_id'])){
                $branch_query->where('brands', $_GET['brand_id']);
            }   

            if(isset($_GET['region_id']) && !empty($_GET['region_id'])){
                $branch_query->where('region_id', $_GET['region_id']);
            }

            if(isset($_GET['id']) && !empty($_GET['branch_id'])){
                
            }


            $total_records = $branch_query->count();
            $branches = $branch_query->skip($start)->take($num_results_on_page)->orderBy('name', 'ASC')->paginate($num_results_on_page);




            $users = allUsers();
            $regions = allRegions();
            $filters = BrandsRegionsBranches();
            $saved_filters = SavedFilter::where('created_by', \Auth::user()->id)->where('module', 'branch')->get();

            // echo "<pre>";
            // print_r($filters);
            // die();
            $data = [
                'branches' => $branches,
                'users' => $users,
                'regions' => $regions,
                'total_records' => $total_records,
                'filters' => $filters,
                'saved_filters' => $saved_filters
            ];

            if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
                $html = view('branch.branchAjax', $data)->render();
                $pagination_html = view('layouts.pagination', [
                    'total_pages' => $total_records,
                    'num_results_on_page' => 25,
                ])->render();
                return json_encode([
                    'status' => 'success',
                    'html' => $html,
                    'pagination_html' => $pagination_html
                ]);
            } else {
                return view('branch.index', $data);
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $companies = FiltersBrands();
        $brand_ids = array_keys($companies);
        $brands = User::whereIn('id', $brand_ids)->pluck('name', 'id')->toArray();
        $branchmanager=User::where('type','Branch Manager')->get();
        $regions=Region::all();






        $filter = BrandsRegionsBranches();
        $brands = $filter['brands'];
        $regions = $filter['regions'];
        $branches = $filter['branches'];
        
        if(\Auth::user()->can('create branch'))
        {
            return view('branch.create',compact('branchmanager','regions', 'brands'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create branch'))
        {

            $validator = \Validator::make(
                $request->all(),  [
                    'name' => 'required',
                    'brands' => 'required',
                    'region_id' => 'required'
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return json_encode([
                    'status' => 'error',
                    'msg' => $messages->first()
                ]);
                //return redirect()->back()->with('error', $messages->first());
            }

            $branch             = new Branch();
            $branch->name       = $request->name;
            $branch->brands       = $request->brands;
            $branch->region_id       = $request->region_id;
            $branch->branch_manager_id       = $request->branch_manager_id;
            $branch->google_link       = $request->google_link;
            $branch->social_media_link       = $request->social_media_link;
            $branch->phone       = $request->phone;
            $branch->email       = $request->email;


            $branch->created_by = \Auth::user()->creatorId();
            $branch->save();

            return json_encode([
                'status' => 'success',
                'id' => $branch->id,
                'msg' =>  __('Branch  successfully created.')
            ]);

          //  return redirect()->route('branch.index')->with('success', __('Branch  successfully created.'));
        }
        else
        {
            return json_encode([
                'status' => 'error',
                'msg' => __('Permission denied.')
            ]);
           // return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Branch $branch)
    {
        return redirect()->route('branch.index');
    }

    public function edit(Branch $branch)
    {
        $branchmanager=User::where('type','')->get();
        $regions=Region::all();

      //  $region = Region::where('id', $branch->region_id)->first();

    //    $brands = User::where('id',$branch->brands)->where('type', 'company')->pluck('name', 'id')->toArray();
        
        $filter = BrandsRegionsBranchesForEdit($branch->brands, $branch->region_id, 0);
        $brands = $filter['brands'];
        $regions = $filter['regions'];
        
        if(\Auth::user()->can('edit branch'))
        {
            // if($branch->created_by == \Auth::user()->creatorId())
            // {

                return view('branch.edit', compact('branch','brands','branchmanager','regions'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission denied.')], 401);
            // }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Branch $branch)
    {
        if(\Auth::user()->can('edit branch'))
        {
            // if($branch->created_by == \Auth::user()->creatorId())
            // {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
                    return json_encode([
                        'status' => 'error',
                        'msg' => $messages->first()
                    ]);
                   // return redirect()->back()->with('error', $messages->first());
                }

                // $brands = null;
                // if($request->brands != null && sizeof($request->brands) > 0){
                //     $brands = implode(',',$request->brands);
                // }

                $branch->name = $request->name;
                $branch->region_id       = $request->region_id;
                $branch->brands       = $request->brands;
                if(isset($request->branch_manager_id)){
                    $branch->branch_manager_id       = $request->branch_manager_id;
                }
                $branch->google_link       = $request->google_link;
                $branch->social_media_link       = $request->social_media_link;
                $branch->phone       = $request->phone;
                $branch->email       = $request->email;
                $branch->save();
                return json_encode([
                    'status' => 'success',
                    'id' => $branch->id,
                    'msg' => 'Branch successfully updated.'
                ]);

               // return redirect()->route('branch.index')->with('success', __('Branch successfully updated.'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission denied.'));
            // }
        }
        else
        {
            return json_encode([
                'status' => 'error',
                'msg' => 'Permission denied'
            ]);
           // return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Branch $branch)
    {
        if(\Auth::user()->can('delete branch'))
        {
            if($branch->created_by == \Auth::user()->creatorId())
            {
                $branch->delete();

                return redirect()->route('branch.index')->with('success', __('Branch successfully deleted.'));
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

    public function getdepartment(Request $request)
    {

        if($request->branch_id == 0)
        {
            $departments = Department::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $departments = Department::where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    public function getemployee(Request $request)
    {
        if(in_array('0', $request->department_id))
        {
            $employees = Employee::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $employees = Employee::whereIn('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($employees);
    }
    public function branchDetail($id)
    {
        $Branch = Branch::findOrFail($id);
        $Regions = Region::get()->pluck('name', 'id')->toArray();
        $Manager = User::get()->pluck('name', 'id')->toArray();

        $html = view('branch.BranchDetail', compact('Branch','Regions','Manager'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }

    ////////Delete bulk Regions
    public function deleteBulkBranches(Request $request){

        if (\Auth::user()->can('delete region') || \Auth::user()->type == 'super admin') {

                if($request->ids != null){
                    Branch::whereIn('id', explode(',', $request->ids))->delete();
                    return redirect()->route('branch.index')->with('success', 'Branches deleted successfully');
                }else{
                    return redirect()->route('branch.index')->with('error', 'Atleast select 1 branch.');
                }

        }else{
            return redirect()->route('branch.index')->with('error', __('Permission Denied.'));
        }

    }

    public function download(){
        $branch_query = Branch::select(['branches.*']);
       

            if(\Auth::user()->type == 'super admin'){
           }else if(\Auth::user()->type == 'company'){
               $branch_query->where('brands', [\Auth::user()->id]);
            }else{
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $branch_query->whereIn('brands', $brand_ids);
            }

            $branches = $branch_query->orderBy('name', 'ASC')->get();




            $users = allUsers();
            $regions = allRegions();

            $header = [
                'Sr.No.',
                'Branch',
                'Brand',
                'Region',
                'Branch Manager',
                'Phone',
                'Email',
                'Google Link',
                'Social Media Link'
            ];

            $data = [];

            foreach($branches as $key => $branch){
                $data[] = [
                    'sr' => $key + 1,
                    'Branch' => $branch->name,
                    'Brand' => $users[$branch->brands] ?? '',
                    'Region' => $regions[$branch->region_id] ?? '',
                    'Branch Manager' => $users[$branch->branch_manager_id] ?? '',
                    'Phone' => $branch->phone,
                    'Email' => $branch->email,
                    'Google Link' => $branch->google_link,
                    'Social media link' => $branch->social_media_link
                ];
            }

        downloadCSV($header, $data, 'branches.csv');
        return true;

    }
}
