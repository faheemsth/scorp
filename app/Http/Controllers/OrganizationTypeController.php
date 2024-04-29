<?php

namespace App\Http\Controllers;

use App\Models\OrganizationType;
use Illuminate\Http\Request;

class OrganizationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        $types_query = OrganizationType::query();
        $total_records = $types_query->count();

        $types_query->skip($start)->take($num_results_on_page);
        $types = $types_query->get();

        return view('organizationTypes.index', compact('types','total_records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         //
         if(\Auth::user()->type == 'super admin' || \Auth::user()->can('create course duration'))
         {
             return view('organizationTypes.create');
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
         //
         if(\Auth::user()->type == 'super admin' || \Auth::user()->can('create organization_type'))
         {

             $validator = \Validator::make(
                 $request->all(), [
                                    'name' => 'required|max:20'
                                ]
             );

             if($validator->fails())
             {
                 $messages = $validator->getMessageBag();

                 return redirect()->route('organization-type.index')->with('error', $messages->first());
             }

             $org_type              = new OrganizationType();
             $org_type->name        = $request->name;
             $org_type->created_by = \Auth::user()->id;
             $org_type->save();

             return redirect()->route('organization-type.index')->with('success', __('Organization type successfully created!'));
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
         //
         if(\Auth::user()->type == 'super admin' || \Auth::user()->can('edit course duration'))
         {
             $type = OrganizationType::find($id);

             return view('organizationTypes.edit', compact('type'));
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
    public function update(Request $request, $id)
    {
        //
        if(\Auth::user()->type == 'super admin' || \Auth::user()->can('edit organization_type'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20'
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $type = OrganizationType::find($id);
            $type->name = $request->name;
            $type->created_by = \Auth::user()->id;
            $type->save();

            return redirect()->route('organization-type.index')->with('success', __('Organization Type successfully updated!'));
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
         //
         if(\Auth::user()->type == 'super admin' || \Auth::user()->can('delete organization_type'))
         {
             OrganizationType::find($id)->delete();

             return redirect()->route('organization-type.index')->with('success', __('Organization Type successfully deleted!'));
         }
         else
         {
             return redirect()->back()->with('error', __('Permission Denied.'));
         }
    }
}
