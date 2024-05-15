<?php

namespace App\Http\Controllers;

use App\Models\EmailMarketing;
use Illuminate\Http\Request;
use App\Models\Pipeline;
use App\Models\User;

class EmailMarketingController extends Controller
{


    public function list(Request $request)
    {
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

        $pipeline = Pipeline::first();
        $leads_query = EmailMarketing::query();
        $total_records = $leads_query->count();
        $users = allUsers();
        $users_with_roles = \DB::table('roles')->pluck('name', 'id')->toArray();
        $EmailMarketings = $leads_query->skip($start)->take($num_results_on_page)->get();

        return view('markiting.list', compact('pipeline', 'EmailMarketings', 'total_records', 'users', 'users_with_roles'));
    }
    public function inset(Request $request)
    {
        $filter = BrandsRegionsBranches();
        $users = allUsers();
        $companies = $filter['brands'];
        $regions = $filter['regions'];
        $branches = $filter['branches'];
        $employees = $filter['employees'];
        $users_with_roles = \DB::table('roles')->get();
        return view('markiting.create', compact('users', 'companies', 'branches', 'regions', 'employees', 'users_with_roles'));
    }
    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), ['name' => 'required', 'tag' => 'required', 'email_content' => 'required']);
        if ($validator->fails()) return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);

        $emailMarketing = new EmailMarketing;
        $emailMarketing->name = $request->name;
        $emailMarketing->type = $request->type;
        $emailMarketing->email_content = $request->email_content;
        $emailMarketing->created_by = \Auth::id();
        $emailMarketing->tag = $request->tag;

        if ($emailMarketing->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email Marketing successfully created!',
                'id' => $emailMarketing->id,
            ]);
        }
    }

    public function show(Request $request)
    {
        $emailMarketing = EmailMarketing::find($request->id);
        $users = allUsers();
        $users_with_roles = \DB::table('roles')->pluck('name', 'id')->toArray();
        $html =  view('markiting.EmailDetail', compact('emailMarketing', 'users', 'users_with_roles'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }
    public function update(Request $request)
    {

        $filter = BrandsRegionsBranches();
        $users = allUsers();
        $companies = $filter['brands'];
        $regions = $filter['regions'];
        $branches = $filter['branches'];
        $employees = $filter['employees'];
        $users_with_roles = \DB::table('roles')->get();
        $emailMarketing = EmailMarketing::find($request->id);
        return view('markiting.edit', compact('emailMarketing', 'users', 'companies', 'branches', 'regions', 'employees', 'users_with_roles'));
    }
    public function updateSave(Request $request)
    {
        $validator = \Validator::make($request->all(), ['name' => 'required', 'tag' => 'required', 'email_content' => 'required']);
        if ($validator->fails()) return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);

        $emailMarketing = EmailMarketing::find($request->id);
        $emailMarketing->name = $request->name;
        $emailMarketing->type = $request->type;
        $emailMarketing->email_content = $request->email_content;
        $emailMarketing->created_by = \Auth::id();
        $emailMarketing->tag = $request->tag;

        if ($emailMarketing->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email Marketing successfully created!',
                'id' => $request->id,
            ]);
        }
    }
    public function delete(Request $request)
    {
        $emailMarketing = EmailMarketing::find($request->id);
        if ($emailMarketing->delete()) {
            return back()->with('success', __('User successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }
}
