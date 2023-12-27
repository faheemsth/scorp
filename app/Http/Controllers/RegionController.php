<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
            $html = view('region.region_ajax_list', compact('regions'))->render();
            return json_encode([
                'status' => 'success',
                'html' => $html
            ]);
        } else {
            return view('region.index', compact('regions'));
        }
    }

    public function create()
    {
        $regions = Region::all();
        $regionmanager=User::where('type','Region Manager')->get();
        return view('region.create', compact('regions','regionmanager'));
    }

    public function save(Request $request)
    {
        if (!empty($request->id)) {
            Region::find($request->id)->update($request->all());
        } else {
            Region::create($request->all());
        }

        return back();
    }


    public function update(Request $request)
    {

        $regions = Region::find($request->id);
        $regionmanager=User::where('type','branch manager')->get();

        return view('region.create', compact('regions','regionmanager'));
    }

    public function delete(Request $request)
    {
        Region::find($request->id)->delete();
        return back();
    }
}
