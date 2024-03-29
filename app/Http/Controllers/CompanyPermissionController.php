<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CompanyPermission;
use Illuminate\Support\Facades\DB;

class CompanyPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = array();
        $user = \Auth::user();
       // if (\Auth::user()->type == 'super admin') {
            if (isset($_GET['role']) && !empty($_GET['role'])) {
                $employees = User::where('type', '=', $_GET['role'])->orderBy('name', 'ASC')->get();
            }else{
                $employees = User::where('type', '=', 'Project Director')->orderBy('name', 'ASC')->get();
            }


            $companies = User::where('type', 'company')->orderBy('name', 'ASC')->get();
            $permission_arr = [];

            foreach ($employees as $com) {
                $permitted_companies = $com->companyPermissions;

                foreach ($permitted_companies as $per_com) {
                    $permission_arr[$com->id][$per_com->permitted_company_id] = $per_com->active;
                }
            }
            return view('company_permission.index')->with(['employees' => $employees, 'permission_arr' => $permission_arr, 'companies' => $companies]);
        // } else {
        //     return redirect()->back();
        // }
    }

    public function company_permission_updated(Request $request)
    {
        //dd($request->input());

        //dd(\Auth::user()->id);
        //Project Director or Manager
        $user_id = $request->company_for;

        //Brand
        $permitted_company = $request->company_permission;
        $is_active = $_POST['active'];

        $company_per = CompanyPermission::where(['user_id' =>  $user_id, 'permitted_company_id' => $permitted_company])->first();

        if (!$company_per) {
            $new_permission = new CompanyPermission();
            $new_permission->user_id = $user_id;
            $new_permission->permitted_company_id = $permitted_company;
            $new_permission->active = $is_active;
            $new_permission->created_by = \Auth::user()->id;
            $new_permission->save();
        } else {
            $company_per->user_id = $user_id;
            $company_per->permitted_company_id = $permitted_company;
            $company_per->active = $is_active;
            $company_per->created_by = \Auth::user()->id;
            $company_per->save();   
        }


        
        
        // Step 1: Check whether it is adding or removing
        if ($is_active == 'true') {
            $user = User::findOrFail($user_id);

            if ($user->type == 'Project Director') {
                User::where('id', $permitted_company)->update(['project_director_id' => $user_id]);
            } else {
                User::where('id', $permitted_company)->update(['project_manager_id' => $user_id]);
            }
        } else {
            $user = User::findOrFail($user_id);

            if ($user->type == 'Project Director') {
                User::where('id', $permitted_company)->update(['project_director_id' => null]);
            } else {
                User::where('id', $permitted_company)->update(['project_manager_id' => null]);
            }
        }



        return json_encode([
            'status' => 'success'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
