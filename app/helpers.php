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


        if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'HR'){

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

if (!function_exists('FiltersRegions')){
    function FiltersRegions($id){
        $regions = Region::whereRaw('FIND_IN_SET(?, brands)', [$id])->pluck('name', 'id')->toArray();
        $html = ' <select class="form form-control region_id select2" id="region_id" name="region_id"> <option value="">Select Region</option> ';
        foreach ($regions as $key => $region) {
            $html .= '<option value="' . $key . '">' . $region . '</option> ';
        }
        $html .= '</select>';

        return $html;
    }
}

if (!function_exists('FiltersBranches')){
    function FiltersBranches($id){
        $branches = Branch::where('region_id', $id)->pluck('name', 'id')->toArray();
        $html = ' <select class="form form-control branch_id select2" id="branch_id" name="lead_branch"> <option value="">Select Branch</option> ';
        foreach ($branches as $key => $branch) {
            $html .= '<option value="' . $key . '">' . $branch . '</option> ';
        }
        $html .= '</select>';

        return $html;
    }
}


if (!function_exists('FiltersBranchUsers')){
    function FiltersBranchUsers($id){
        $users = User::whereNotIn('type', ['super admin', 'company', 'accountant', 'client'])->where('branch_id', $id)->pluck('name', 'id')->toArray();
        $html = ' <select class="form form-control user_id select2" id="user_id" name="lead_assgigned_user"> <option value="">Select User</option> ';
        foreach ($users as $key => $user) {
            $html .= '<option value="' . $key . '">' . $user . '</option> ';
        }
        $html .= '</select>';

        return $html;
    }
}


//returning stages ranges like visa fall in 1,2,3 and deposit fall in 4,5,6
if (!function_exists('stagesRange')){
    function stagesRange($type){
         if($type == 'visas'){
            return [4, 5, 6];
         }else if($type == 'deposit'){
            return [7, 8, 9];
         }else{
            return [1, 2, 3];
         }
    }
}




if (!function_exists('BrandsRegionsBranches')){
    function BrandsRegionsBranches(){
        $brands = [];
        $regions = [];
        $branches = [];
        $employees = [];

        $user = \Auth::user();
        $type = $user->type;

        if($type == 'super admin' || $type == 'Admin Team' || $type == 'HR'){
              $brands = User::where('type', 'company')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }else if($type == 'company'){
            $brands = User::where('type', 'company')->where('id', $user->id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $regions = Region::where('brands', $user->id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }else if($type == 'Project Director' || $type == 'Project Manager') {
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $brands = User::where('type', 'company')->whereIn('id', $brand_ids)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }else if($type == 'Region Manager'){
            $brands = User::where('type', 'company')->where('id', $user->brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $regions = Region::where('id', $user->region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $branches = Branch::where('region_id', $user->region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }else if($type == 'Branch Manager' || $type == 'Admissions Officer' || $type == 'Admissions Manager' || $type == 'Marketing Officer'){
            $brands = User::where('type', 'company')->where('id', $user->brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $regions = Region::where('id', $user->region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $branches = Branch::where('id', $user->branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $employees = User::where('branch_id', $user->branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }



        return [
            'brands' => [0 => 'Select Brand']+$brands,
            'regions' => [0 => 'Select Region'] + $regions,
            'branches' => [0 => 'Select Branch'] + $branches,
            'employees' => [0 => 'Select Employee'] + $employees
        ];
    }




    if (!function_exists('BrandsRegionsBranchesForEdit')){
        function BrandsRegionsBranchesForEdit($brand_id = 0, $region_id = 0, $branch_id = 0){
            $brands = [];
            $regions = [];
            $branches = [];
            $employees = [];

            $user = \Auth::user();
            $type = $user->type;

            //dd($brand_id.' '.$region_id.' '.$branch_id);

            if($type == 'super admin' || $type == 'HR' || $type == 'Admin Team'){
                  $brands = User::where('type', 'company')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                  $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                  $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                  $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            }else if($type == 'company'){
                $brands = User::where('type', 'company')->where('id', $user->id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            }else if($type == 'Project Director' || $type == 'Project Manager') {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $brands = User::where('type', 'company')->whereIn('id', $brand_ids)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                  $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                  $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            }else if($type == 'Region Manager'){
                $brands = User::where('type', 'company')->where('id', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                  $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                  $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            }else if($type == 'Branch Manager' || $type == 'Admissions Officer' || $type == 'Admissions Manager' || $type == 'Marketing Officer'){
                $brands = User::where('type', 'company')->where('id', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            }


            return [
                'brands' => [0 => 'Select Brand']+$brands,
                'regions' => [0 => 'Select Region'] + $regions,
                'branches' => [0 => 'Select Branch'] + $branches,
                'employees' => [0 => 'Select Employee'] + $employees
            ];
        }
    }
}


function downloadCSV($headers, $data, $filename = 'data.csv') {
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write headers to CSV
    fputcsv($output, $headers);

    // Write data to CSV
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Close output stream
    fclose($output);

    // Stop further execution
    exit;
}

function accessLevel(){
    return [ 
        'first' => [
            'super admin',
            'Admin Team',
            'Project Director',
            'Project Manager'
        ],
        'second' => [
            'Region Manager'
        ],
        'third' => [
            'Branch Manager',
            'Admissions Manager',
            'Admissions Officer',
            'Marketing Officer'
        ]
    ];
}





?>
