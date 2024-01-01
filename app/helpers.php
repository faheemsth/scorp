<?php

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\StageHistory;
use App\Models\LogActivity;
use App\Models\Region;
use App\Models\University;
use App\Models\User;
use App\Models\CompanyPermission;

if (!function_exists('countries')) {
    function countries()
    {
        $all_countries = [];
        $contries = \App\Models\Country::get();


        foreach($contries as $country){
            $all_countries[$country->name] = $country->name;
        }

        return $all_countries;
    }
}

if (!function_exists('months')) {
    function months()
    {
        $months = [
            'JAN' => 'January',
            'FEB' => 'February',
            'MAR' => 'March',
            'APR' => 'April',
            'MAY' => 'May',
            'JUN' => 'June',
            'JUL' => 'July',
            'AUG' => 'August',
            'SEP' => 'September',
            'OCT' => 'October',
            'NOV' => 'November',
            'DEC' => 'December',
        ];
        return $months;
    }
}


if (!function_exists('companies')) {
    function companies()
    {
       return User::where('type', 'company')->pluck('name', 'id')->toArray();
    }
}


if (!function_exists('allUsers')) {
    function allUsers()
    {
       return User::pluck('name', 'id')->toArray();
    }
}

if (!function_exists('allRegions')) {
    function allRegions()
    {
       return Region::pluck('name', 'id')->toArray();
    }
}

if (!function_exists('companiesEmployees')) {
    function companiesEmployees($company_id)
    {
       return User::where('created_by', $company_id)->pluck('name', 'id')->toArray();
    }
}


if (!function_exists('allUniversities')) {
    function allUniversities()
    {
       return University::pluck('name', 'id')->toArray();
    }
}


if (!function_exists('allPermittedCompanies')) {
    function allPermittedCompanies()
    {
       return CompanyPermission::where('user_id', \Auth::user()->id)->where('active', 'true')->pluck('permitted_company_id')->toArray();
    }
}


if (!function_exists('addLogActivity')) {
    function addLogActivity($data = [])
    {
       $new_log = new LogActivity();
       $new_log->type = $data['type'];
       $new_log->start_date = date('Y-m-d');
       $new_log->time = date('H:i:s');
       $new_log->note = $data['note'];
       $new_log->module_type = isset($data['module_type']) ? $data['module_type'] : '';
       $new_log->module_id = isset($data['module_id']) ? $data['module_id'] : 0;
       $new_log->created_by = \Auth::user()->id;
       $new_log->save();
    }
}

if (!function_exists('addLeadHistory')) {
    function addLeadHistory($data = [])
    {
        if(isset($data['stage_id'])){
            StageHistory::where('type_id', $data['type_id'])
                        ->where('type', $data['type'])
                        ->where('stage_id', '>=', $data['stage_id'])
                        ->delete();
        }


       $new_log = new StageHistory();
       $new_log->type = $data['type'];
       $new_log->type_id = $data['type_id'];
       $new_log->stage_id = $data['stage_id'];
       $new_log->created_by = \Auth::user()->id;
       $new_log->save();
    }
}

if (!function_exists('getLogActivity')) {
    function getLogActivity($id, $type)
    {
        return LogActivity::where('module_id', $id)->where('module_type', $type)->orderBy('created_at', 'desc')->get();
    }
}
if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phoneNumber)
    {
        // Remove non-numeric characters from the phone number
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the phone number starts with '92' (country code for Pakistan)
        if (strpos($phoneNumber, '92') === 0) {
            // Remove the leading '92' if present
            $phoneNumber = substr($phoneNumber, 2);
        }

        // Add the country code '92' to the phone number
        $formattedPhoneNumber = '92' . $phoneNumber;

        return $formattedPhoneNumber;
    }
}


if (!function_exists('FiltersBrands')) {
    function FiltersBrands()
    {
        $brands = User::where('type', 'company');
        if(\Auth::user()->type == 'company'){
            $user_brand = !empty(\Auth::user()->id) ? \Auth::user()->id : 0;
        }else{
            $user_brand = !empty(\Auth::user()->brand_id) ? \Auth::user()->brand_id : 0;
        }

        if(\Auth::user()->type == 'super admin'){

        }else if(\Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager'){
            $permittedCompanies = allPermittedCompanies();
            $brands->whereIn('id', $permittedCompanies);
            $brands->orWhere('id', $user_brand);
        }else if(\Auth::user()->type == 'Regional Manager'){
            $regions = Region::where('region_manager_id', \Auth::user()->id)->pluck('id')->toArray();
            $branches = Branch::whereIn('region_id', $regions)->pluck('id')->toArray();
            $brands->whereIn('branch_id', $branches);
            $brands->orWhere('id', $user_brand);
        }else if(\Auth::user()->type == 'Branch Manager') {
            $branches = Branch::where('branch_manager_id', \Auth::user()->id)->pluck('id')->toArray();
            $brands->whereIn('branch_id', $branches);
            $brands->orWhere('id', $user_brand);
        }else{
            $brands->where('id', $user_brand);
        }

       return $brands->pluck('name', 'id')->toArray();
    }
}



?>
