<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstituteCategory;

class InstituteCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('manage institute category')) {
        $start = 0;
        $num_results_on_page = env("RESULTS_ON_PAGE");
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $num_of_result_per_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $start = ($page - 1) * $num_results_on_page;
        } else {
            $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        }

        $institute_categories_query = InstituteCategory::query();
        $total_records = $institute_categories_query->count();

        $institute_categories_query->skip($start)->take($num_results_on_page);
        $institute_categories = $institute_categories_query->get();

        return view('institute_category.index', compact('institute_categories','total_records'));
        }else{
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
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('create institute category')) {
        return view('institute_category.create');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
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
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('create institute category')) {
        $new = new InstituteCategory();
        $new->name = $request->institute_category;
        $new->created_by = \Auth::user()->id;
        $new->save();

        return redirect()->route('institute-category.index')->with('success', 'Insitute Category created successfully');;
       }else{
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
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit institute category')) {
        $category = InstituteCategory::findOrFail($id);
        return view('institute_category.edit',['category' => $category]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
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
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit institute category')) {
        $category = InstituteCategory::findOrFail($id);
        $category->name = $request->institute_category;;
        $category->update();

        return redirect()->route('institute-category.index')->with('success', 'Insitute Category updated successfully');

        }else{
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
        if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete institute category')) {
        $category = InstituteCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('institute-category.index')->with('success', 'Insitute Category deleted successfully');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
