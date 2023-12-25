<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
            $html = view('region.region_list', compact('organizations', 'org_types', 'countries', 'user_type'))->render();
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
      return view('region.create', compact('regions'));
    }
}
