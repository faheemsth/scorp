<?php

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


if (!function_exists('allUniversities')) {
    function allUniversities()
    {
       return University::pluck('name', 'id')->toArray();
    }
}

?>