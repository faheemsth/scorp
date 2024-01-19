<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Utility;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\AnnouncementEmployee;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage announcement'))
        {
            $current_employee = Auth::user();

            if(Auth::user()->type == 'super admin'){
                $announcements = Announcement::orderByDesc('announcements.announcement_counter')
                                ->get();
            }else{
                $announcements = Announcement::join('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')
                ->where('announcement_employees.employee_id', Auth::user()->id)
                 ->orderByDesc('announcements.announcement_counter')
                 ->get();
            }

            return view('announcement.index', compact('announcements', 'current_employee'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create announcement'))
        {


            $companies = FiltersBrands();


            return view('announcement.create', compact('companies'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        // echo '<pre>';
        // print_r($request->input());
        // die();
        if(\Auth::user()->can('create announcement'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'start_date' => 'required',
                                   'end_date' => 'required',
                                   //'lead_branch' => 'required',
                                   //'region_id' => 'required',
                                   //'brand_id' => 'required',
                                   //'employee_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            //getting previous announcement count
            $counter = 0;
            $last_announcemnt = Announcement::orderBy('announcement_counter', 'DESC')->first();
            if($last_announcemnt){
                $counter = $last_announcemnt->announcement_counter;
                $counter += 1;
            }

            $announcement                = new Announcement();
            $announcement->title         = $request->title;
            $announcement->start_date    = $request->start_date;
            $announcement->end_date      = $request->end_date;
            $announcement->branch_id     = $request->lead_branch;
            $announcement->brand_id     = $request->brand_id;
            $announcement->region_id    =  $request->region_id;
            $announcement->employee_id   = json_encode($request->employee_id);
            $announcement->description   = $request->description;
            $announcement->announcement_counter = $counter;
            $announcement->created_by    = \Auth::user()->creatorId();
            $announcement->save();

            $data = [];

            //if brand empty
            if(empty($request->brand_id)){
                $companies = FiltersBrands();
                foreach($companies as $key => $comp){
                    $brand_employees = User::where('brand_id', $key)->pluck('id')->toArray();
                    foreach($brand_employees as $emp_id){
                        $data[] = [
                            'announcement_id' => $announcement->id,
                            'employee_id' => $emp_id,
                            'created_by' =>\Auth::user()->creatorId()
                        ];
                    }
                }
            }else{
                $brand_id = $request->brand_id;
                
                //now check regions, if empty get all the region employee else go to check branch
                if(empty($request->region_id)){

                    $regions = Region::where('brand_id', $brand_id)->get()->pluck('id')->toArray();
                    foreach($regions as $reg){
                        $employees = User::where('brand_id', $brand_id)->where('region_id', $reg)->pluck('id')->toArray();
                        foreach($employees as $emp_id){
                            $data[] = [
                                'announcement_id' => $announcement->id,
                                'employee_id' => $emp_id,
                                'created_by' =>\Auth::user()->creatorId()
                            ];
                        }
                    }

                }else{

                    //now check if branches is empty then fetch all the branches related to region and brand and get all branch employes
                    if(empty($request->lead_branch)) {
                        $branches = Branch::where('brand_id', $request->brand_id)->where('region_id', $request->region_id)->get()->pluck('id');
                        foreach($branches as $branch){
                            $employees = User::where('brand_id', $request->brand_id)->where('region_id', $request->region_id)->where('branch_id', $branch)->pluck('id')->toArray();
                            foreach($employees as $emp_id){
                                $data[] = [
                                    'announcement_id' => $announcement->id,
                                    'employee_id' => $emp_id,
                                    'created_by' =>\Auth::user()->creatorId()
                                ];
                            }
                        }
                    }else{

                        //now check employees is empty, if yes then fetch all the related branch employees
                        if(empty($request->employee_id)){
                            $employees = User::where('brand_id', $request->brand_id)->where('region_id', $request->region_id)->where('branch_id', $request->lead_branch)->pluck('id')->toArray();
                            foreach($employees as $emp_id){
                                $data[] = [
                                    'announcement_id' => $announcement->id,
                                    'employee_id' => $emp_id,
                                    'created_by' =>\Auth::user()->creatorId()
                                ];
                            }
                        }else{
                            foreach($request->employee_id as $emp_id){
                                $data[] = [
                                    'announcement_id' => $announcement->id,
                                    'employee_id' => $emp_id,
                                    'created_by' =>\Auth::user()->creatorId()
                                ];
                            }
                        }
                    }
                }
            }

            //add created at and updated at timestamp
            $timestamp = Carbon::now();
            foreach ($data as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }
            AnnouncementEmployee::insert($data);


            //Slack Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if(isset($setting['announcement_notification']) && $setting['announcement_notification'] ==1){
                $msg = $request->title .' '.__("announcement created for branch").' '. $branch->name.' '. __("from").' '. $request->start_date. ' '.__("to").' '.$request->end_date.'.';
                Utility::send_slack_msg($msg);
            }

            //Telegram Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if(isset($setting['telegram_announcement_notification']) && $setting['telegram_announcement_notification'] ==1){
                $msg = $request->title .' '.__("announcement created for branch").' '. $branch->name.' '. __("from").' '. $request->start_date. ' '.__("to").' '.$request->end_date.'.';
                Utility::send_telegram_msg($msg);
            }


            return redirect()->route('announcement.index')->with('success', __('Announcement  successfully created.'));
        }

        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Announcement $announcement)
    {
        return redirect()->route('announcement.index');
    }

    public function edit($announcement)
    {
        if(\Auth::user()->can('edit announcement'))
        {
            $announcement = Announcement::find($announcement);
            if($announcement->created_by == Auth::user()->creatorId())
            {
                $companies = FiltersBrands();
                $regions = Region::where('brands', $announcement->brand_id )->get();
                $branchs = Branch::where('region_id', $announcement->region_id )->get();
                $employees = User::where('branch_id',$announcement->branch_id)->get();
                return view('announcement.edit', compact('announcement','companies','regions','branchs','employees' ));
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

    public function update(Request $request, Announcement $announcement)
    {
        if(\Auth::user()->can('edit announcement'))
        {
            if($announcement->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'title' => 'required',
                                       'start_date' => 'required',
                                       'end_date' => 'required',
                                       //'branch_id' => 'required',
                                       //'region_id' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $announcement->title         = $request->title;
                $announcement->start_date    = $request->start_date;
                $announcement->end_date      = $request->end_date;
                $announcement->branch_id     = $request->branch_id;
                $announcement->region_id = $request->region_id;
                $announcement->description   = $request->description;
                $announcement->save();

                return redirect()->route('announcement.index')->with('success', __('Announcement successfully updated.'));
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

    public function destroy(Announcement $announcement)
    {
        if(\Auth::user()->can('delete announcement'))
        {
            if($announcement->created_by == \Auth::user()->creatorId())
            {
                $announcement->delete();

                return redirect()->route('announcement.index')->with('success', __('Announcement successfully deleted.'));
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
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

//    public function getemployee(Request $request)
//    {
//        if(in_array('0', $request->department_id))
//        {
//            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
//        }
//        else
//        {
//            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->whereIn('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
//        }
//
//        return response()->json($employees);
//    }


    public function getemployee(Request $request)
    {
        // dd(department_id);
        if(!$request->region_id )
        {
            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->where('region_id', $request->region_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($employees);
    }


}
