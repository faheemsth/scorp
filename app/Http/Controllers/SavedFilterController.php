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

}
