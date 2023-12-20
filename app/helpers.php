<?php

use App\Models\ActivityLog;
use App\Models\LogActivity;
use App\Models\University;
use App\Models\User;

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

if (!function_exists('getLogActivity')) {
    function getLogActivity($id)
    {
        return LogActivity::where('module_id', $id)->get();
    }
}

?>