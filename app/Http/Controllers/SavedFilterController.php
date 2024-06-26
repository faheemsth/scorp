<?php

namespace App\Http\Controllers;

use App\Models\SavedFilter;
use Illuminate\Http\Request;

class SavedFilterController extends Controller
{
    public function save(Request $request)
    {

        $filter = new SavedFilter;
        $filter->filter_name = $request->filter_name;
        $filter->url = $request->url;
        $filter->module = $request->module;
        $filter->count = $request->count;
        $filter->created_by = \Auth::user()->id;
        $filter->save();

        return redirect()->back()->with('success', __('Filter Saved'));

    }

    public function edit(Request $request){
        $filter = SavedFilter::findOrFail($request->id);
        $filter->filter_name = $request->filter_name;
        $filter->save();
        return redirect()->back()->with('success', __('Filter Updated'));
    }

    public function deleteFilter(Request $request){
        $filter = SavedFilter::where('id',$request->id)->delete();
        return response()->json(['status' => 'success','msg' =>__('Filter Deleted')]);
    }

}
