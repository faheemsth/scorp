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
        $institute_categories = InstituteCategory::all();
        $data = [
            'institute_categories' => $institute_categories
        ];
        return view('institute_category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('institute_category.create');
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
        $new = new InstituteCategory();
        $new->name = $request->institute_category;
        $new->created_by = \Auth::user()->id;
        $new->save();

        return redirect()->route('institute-category.index')->with('success', 'Insitute Category created successfully');;
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
        $category = InstituteCategory::findOrFail($id);
        return view('institute_category.edit',['category' => $category]);
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
        $category = InstituteCategory::findOrFail($id);
        $category->name = $request->institute_category;;
        $category->update();

        return redirect()->route('institute-category.index')->with('success', 'Insitute Category updated successfully');
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
        $category = InstituteCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('institute-category.index')->with('success', 'Insitute Category deleted successfully');
    }
}
