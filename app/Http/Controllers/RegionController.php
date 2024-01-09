<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {

        if(\Auth::user()->type == 'super admin'){
            $regions = Region::get();
       }else if(\Auth::user()->type == 'company'){
            $regions = Region::whereRaw('FIND_IN_SET(?, brands)', [\Auth::user()->id])->get();
       }else{
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $regions = Region::whereRaw('FIND_IN_SET(?, brands)', [$brand_ids])->get();
       } 

       $users = allUsers();

       $data = [
        'regions' => $regions,
        'users' => $users,
       ];



        if (isset($_GET['ajaxCall']) && $_GET['ajaxCall'] == 'true') {
            $html = view('region.region_ajax_list', $data)->render();
            return json_encode([
                'status' => 'success',
                'html' => $html
            ]);
        } else {
            return view('region.index', $data);
        }
    }

    public function create()
    {
       // $regions = Region::all();

        $brands = FiltersBrands();
       
        $regionmanager=User::where('type','branch manager')->get();

        return view('region.create', compact('regionmanager','brands'));
    }

    public function getRegionBrands(Request $request){
        $id = $_GET['id'];
        $type = $request->type; 

        if($type == 'brand'){
            $regions = Region::whereRaw('FIND_IN_SET(?, brands)', [$id])->pluck('name', 'id')->toArray();
            $html = ' <label for="region_id">Regions</label><select class="form form-control select2" id="region_id" name="region_id" required> <option value="">Select Region</option> ';
            foreach ($regions as $key => $region) {
                $html .= '<option value="' . $key . '">' . $region . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'regions' => $html,
            ]);

        }else if($type == 'region'){

            $branches = Branch::where('region_id', $id)->pluck('name', 'id')->toArray();
            $html = ' <label for="branch_id">Branch</label><select class="form form-control select2" id="branch_id" name="branch_id" required> <option value="">Select Branch</option> ';
            foreach ($branches as $key => $branch) {
                $html .= '<option value="' . $key . '">' . $branch . '</option> ';
            }
            $html .= '</select>';
            return json_encode([
                'status' => 'success',
                'branches' => $html,
            ]);

        }else{

            $region = Region::where('id', $id)->first();
            $brands = array();

            if($region){
                $ids = explode(',',$region->brands);
                $brands = User::whereIn('id',$ids)->where('type', 'company')->pluck('name', 'id')->toArray();

                $html = ' <label for="region_id">Brands</label><select class="form form-control brands select2" id="brands" name="brands[]" multiple required> <option value="">Select Brands</option> ';
                foreach ($brands as $key => $brand) {
                    $html .= '<option value="' . $key . '">' . $brand . '</option> ';
                }
                $html .= '</select>';


                return json_encode([
                    'status' => 'success',
                    'brands' => $html,
                ]);

            }else{
                return json_encode([
                    'status' => 'failure',
                ]);
            }

        }

    }

    public function save(Request $request)
    {

        if (!empty($request->id)) {
            
           // Region::find($request->id)->update($request->all());
           $region = Region::findOrFail($request->id);
           $region->name = $request->name;
           $region->region_manager_id = $request->region_manager_id;
           $region->location = $request->location;
           $region->phone = $request->phone;
           $region->email = $request->email;
           $region->brands =implode(',',$request->brands);
           $region->update();


        } else {
          
            
            $brands = null;
            if($request->brands != null && sizeof($request->brands) > 0){
                $brands = implode(',',$request->brands);
            }

            $data = $request->all();
            $data['brands'] = $brands;

            Region::create($data);
        }

        return back();
    }


    public function update(Request $request)
    {
        $brands = FiltersBrands();
        $regions = Region::find($request->id);
        $regionmanager=User::where('type','branch manager')->get();
        return view('region.edit', compact('regions','regionmanager','brands'));
    }

    public function delete($id)
    {
        Region::find($id)->delete();
        return back();
    }

    public function regions_show($id)
    {
        $employee = Region::findOrFail($id);
        $users = allUsers();
        $html = view('region.employeeDetail', compact('employee', 'users'))->render();
        return json_encode([
            'status' => 'success',
            'html' => $html
        ]);
    }


}
