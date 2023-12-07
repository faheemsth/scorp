<?php

namespace App\Http\Controllers;

use App\Models\CourseLevel;
use Illuminate\Http\Request;

class CourselevelController extends Controller
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
      
        
        if(\Auth::user()->can('manage course level'))
        {
           
            $courselevel = CourseLevel::get();
        
            return view('courselevel.index')->with('courselevel', $courselevel);
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
        if(\Auth::user()->can('create course level'))
        {
            return view('courselevel.create');
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
        if(\Auth::user()->can('create course level'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20'
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('courselevel.index')->with('error', $messages->first());
            }

            $courselevel              = new CourseLevel();
            $courselevel->name        = $request->name;
            $courselevel->save();

            return redirect()->route('courselevel.index')->with('success', __('Course level successfully created!'));
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
        return redirect()->route('courselevel.index');
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
        if(\Auth::user()->can('edit course level'))
        {
            $courselevel = CourseLevel::find($id);

            return view('courselevel.edit', compact('courselevel'));
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
    public function update(Request $request, CourseLevel $courselevel)
    {
        //
        if(\Auth::user()->can('edit course level'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20'
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('users')->with('error', $messages->first());
            }

            $courselevel->name = $request->name;
            $courselevel->save();

            return redirect()->route('courselevel.index')->with('success', __('Coure level successfully updated!'));
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
        if(\Auth::user()->can('delete course level'))
        {
            CourseLevel::find($id)->delete();

            return redirect()->route('courselevel.index')->with('success', __('Course level successfully deleted!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
