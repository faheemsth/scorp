<?php

namespace App\Http\Controllers;

use App\Models\CourseDuration;
use App\Models\CourseLevel;
use App\Models\Deal;
use App\Models\DealApplication;
use App\Models\Stage;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //dd(\Auth::user()->can('manage university'));
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('manage university')) { 

             $universities = University::get();

            $users = User::get()->pluck('name', 'id');

            return view('university.index')->with(['universities'=> $universities, 'users' => $users]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (\Auth::user()->can('create university')) {
            return view('university.create');
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
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
        if (\Auth::user()->can('create university')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'country' => 'required|max:20',
                    'city' => 'required|max:20',
                    'phone' => 'required|max:20',
                    'note' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('university.index')->with('error', $messages->first());
            }

            $university              = new University();
            $university->name        = $request->name;
            $university->country        = $request->country;
            $university->city        = $request->city;
            $university->phone        = $request->phone;
            $university->note        = $request->note;
            $university->created_by = \Auth::user()->id;
            $university->save();

            return redirect()->route('university.index')->with('success', __('University successfully created!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function show(University $university)
    {
        //
        return redirect()->route('university.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        //
        if (\Auth::user()->can('edit university')) {
            $university = University::find($id);

            return view('university.edit', compact('university'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, University $university)
    {
        //
        if (\Auth::user()->can('edit university')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'country' => 'required|max:20',
                    'city' => 'required|max:20',
                    'phone' => 'required|max:20',
                    'note' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('users')->with('error', $messages->first());
            }

            $university->name        = $request->name;
            $university->country        = $request->country;
            $university->city        = $request->city;
            $university->phone        = $request->phone;
            $university->note        = $request->note;
            $university->save();

            return redirect()->route('university.index')->with('success', __('University successfully updated!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(\Auth::user()->can('delete university'))
        {
            University::find($id)->delete();

            return redirect()->route('university.index')->with('success', __('University successfully deleted!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function universityDetail($id){
        $university = University::findOrFail($id);

        //related applications
        $applications = DealApplication::where('university_id', $id)->get();
        
        //related admissions
        $deals = Deal::where('university_id', $id)->get();

        $dealArr = Deal::get()->pluck('name', 'id')->toArray();
        $stages = Stage::get()->pluck('name', 'id')->toArray();
        $organizations = User::where('type', 'organization')->pluck('name', 'id')->toArray();

        $users = User::get()->pluck('name', 'id')->toArray();

        $html = view('university.universityDetail', compact('university', 'applications', 'deals', 'users', 'dealArr', 'stages', 'organizations'))->render();

        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }
}
