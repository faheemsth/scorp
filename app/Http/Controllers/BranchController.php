<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
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



            if(\Auth::user()->type == 'super admin'){
                $total_records = Branch::count();
                $branches = Branch::skip($start)->take($num_results_on_page)->orderBy('name', 'ASC')->paginate($num_results_on_page);
            }else if(\Auth::user()->type == 'company'){
                $total_records = Branch::whereRaw('FIND_IN_SET(?, brands)', [\Auth::user()->id])->count();
                $branches = Branch::whereRaw('FIND_IN_SET(?, brands)', [\Auth::user()->id])->skip($start)->take($num_results_on_page)->orderBy('name', 'ASC')->paginate($num_results_on_page);
            }else{
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
               // $branches = Branch::whereRaw('FIND_IN_SET(?, brands)', [$brand_ids])->get();
                
                
                $branch_query = Branch::query();

               foreach ($brand_ids as $brandId) {
                   $branch_query->orWhereRaw('FIND_IN_SET(?, brands)', [$brandId]);
               }
               $total_records = $branch_query->count();
   
               $branches = $branch_query->skip($start)->take($num_results_on_page)->orderBy('name', 'ASC')->paginate($num_results_on_page);
            }




            $users = allUsers();
            $regions = allRegions();
            $data = [
                'branches' => $branches,
                'users' => $users,
                'regions' => $regions,
                'total_records' => $total_records
            ];
            return view('branch.index', $data);
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
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $brands = null;
            if($request->brands != null && sizeof($request->brands) > 0){
                $brands = implode(',',$request->brands);
            }

            $branch             = new Branch();
            $branch->name       = $request->name;
            $branch->brands       = $brands;

            $branch->region_id       = $request->region_id;
            $branch->branch_manager_id       = $request->branch_manager_id;
            $branch->google_link       = $request->google_link;
            $branch->social_media_link       = $request->social_media_link;
            $branch->phone       = $request->phone;
            $branch->email       = $request->email;


            $branch->created_by = \Auth::user()->creatorId();
            $branch->save();

            return redirect()->route('branch.index')->with('success', __('Branch  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
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

        $region = Region::where('id', $branch->region_id)->first();


       $ids = explode(',', $region->brands ?? '');

        $brands = User::whereIn('id',$ids)->where('type', 'company')->pluck('name', 'id')->toArray();

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

                    return redirect()->back()->with('error', $messages->first());
                }

                $brands = null;
                if($request->brands != null && sizeof($request->brands) > 0){
                    $brands = implode(',',$request->brands);
                }

                $branch->name = $request->name;
                $branch->region_id       = $request->region_id;
                $branch->brands       = $brands;
                if(isset($request->branch_manager_id)){
                    $branch->branch_manager_id       = $request->branch_manager_id;
                }
                $branch->google_link       = $request->google_link;
                $branch->social_media_link       = $request->social_media_link;
                $branch->phone       = $request->phone;
                $branch->email       = $request->email;
                $branch->save();

                return redirect()->route('branch.index')->with('success', __('Branch successfully updated.'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission denied.'));
            // }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
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

}
