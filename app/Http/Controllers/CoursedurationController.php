<?php

namespace App\Http\Controllers;

use App\Models\CourseDuration;
use App\Models\CourseLevel;
use Illuminate\Http\Request;

class CoursedurationController extends Controller
{

    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'XSS',
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(\Auth::user()->can('manage course duration'))
        {
           
            $courseduration = CourseDuration::get();
            
            return view('courseduration.index')->with('courseduration', $courseduration);
        }
        else
        {
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
        if(\Auth::user()->can('create course duration'))
        {
            return view('courseduration.create');
        }
        else
        {
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
        if(\Auth::user()->can('create course duration'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20'
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('courseduration.index')->with('error', $messages->first());
            }

            $courseduration              = new CourseDuration();
            $courseduration->duration        = $request->name;
            $courseduration->save();

            return redirect()->route('courseduration.index')->with('success', __('Course duration successfully created!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
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
        return redirect()->route('courseduration.index');
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
        if(\Auth::user()->can('edit course duration'))
        {
            $courseduration = CourseDuration::find($id);

            return view('courseduration.edit', compact('courseduration'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseDuration $courseduration)
    {
        //
        if(\Auth::user()->can('edit course level'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'duration' => 'required|max:20'
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('users')->with('error', $messages->first());
            }

            $courseduration->duration = $request->duration;
            $courseduration->save();

            return redirect()->route('courseduration.index')->with('success', __('Course duration successfully updated!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
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
        if(\Auth::user()->can('delete course duration'))
        {
            CourseDuration::find($id)->delete();

            return redirect()->route('courseduration.index')->with('success', __('Course duration successfully deleted!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
