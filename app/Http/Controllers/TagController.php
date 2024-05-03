<?php

namespace App\Http\Controllers;

use App\Models\LeadTag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
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
        if(\Auth::user()->can('manage source'))
        {

            $start = 0;
            $num_results_on_page = env("RESULTS_ON_PAGE");
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
                $start = ($page - 1) * $num_results_on_page;
            } else {
                $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            }

            $Source_query = LeadTag::query();
            $total_records = $Source_query->count();

            $Source_query->orderBy('tag')->skip($start)->take($num_results_on_page);
            $sources = $Source_query->get();


            return view('sources.index', compact('sources','total_records'));
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
        if(\Auth::user()->can('create source'))
        {
            return view('sources.create');
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('create source'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('sources.index')->with('error', $messages->first());
            }

            $source             = new LeadTag();
            $source->tag       = $request->name;
            $source->created_by = \Auth::user()->ownerId();
            $source->save();

            return redirect()->route('sources.index')->with('success', __('Source successfully created!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Source $source
     *
     * @return \Illuminate\Http\Response
     */
    public function show(LeadTag $source)
    {
        return redirect()->route('sources.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Source $source
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(LeadTag $source)
    {
        if(\Auth::user()->can('edit source'))
        {
            // if($source->created_by == \Auth::user()->ownerId())
            // {
                return view('sources.edit', compact('source'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission Denied.')], 401);
            // }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Source $source
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadTag $source)
    {
        if(\Auth::user()->can('edit source'))
        {
            // if($source->created_by == \Auth::user()->ownerId())
            // {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('sources.index')->with('error', $messages->first());
                }

                $source->tag = $request->name;
                $source->save();

                return redirect()->route('sources.index')->with('success', __('Source successfully updated!'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Source $source
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadTag $source)
    {
        if(\Auth::user()->can('delete source'))
        {
            // if($source->created_by == \Auth::user()->ownerId())
            // {
                $source->delete();

                return redirect()->route('sources.index')->with('success', __('Source successfully deleted!'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
