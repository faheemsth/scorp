<?php

use App\Models\User;
use App\Models\Branch;
use App\Models\Region;
use App\Models\University;
use App\Models\ActivityLog;
use App\Models\LogActivity;
use App\Models\Notification;
use App\Models\StageHistory;
use App\Events\NewNotification;
use App\Models\CompanyPermission;

if (!function_exists('countries')) {
    function countries()
    {
        $all_countries = [];
        $contries = \App\Models\Country::get();


        foreach ($contries as $country) {
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



        ///////////////////Creating Notification
        $msg = '';
        if(strtolower($data['notification_type']) == 'application stage update'){
            $msg = 'Application stage updated.';
        }else if(strtolower($data['notification_type']) == 'lead updated'){
            $msg = 'Lead updated.';
        }else if(strtolower($data['module_type']) == 'application'){
            $msg = 'New application created.';
        }else if(strtolower($data['notification_type']) == 'University Created'){
            $msg = 'New University Created.';
        }else if(strtolower($data['notification_type']) == 'University Updated'){
            $msg = 'University Updated.';
        }else if(strtolower($data['notification_type']) == 'University Deleted'){
            $msg = 'University Deleted.';
        }else if(strtolower($data['notification_type']) == 'Deal Created'){
            $msg = 'Deal Created.';
        }else if(strtolower($data['notification_type']) == 'Deal Updated'){
            $msg = 'Deal Updated.';
        }else if(strtolower($data['notification_type']) == 'Lead Updated'){
            $msg = 'Lead Updated.';
        }else if(strtolower($data['notification_type']) == 'Deal Notes Created'){
            $msg = 'Deal Notes Created.';
        }else if(strtolower($data['notification_type']) == 'Task Created'){
            $msg = 'Task Created.';
        }else if(strtolower($data['notification_type']) == 'Task Updated'){
            $msg = 'Task Updated.';
        }else if(strtolower($data['notification_type']) == 'Stage Updated'){
            $msg = 'Stage Updated.';
        }else if(strtolower($data['notification_type']) == 'Deal Stage Updated'){
            $msg = 'Deal Stage Updated.';
        }else if(strtolower($data['notification_type']) == 'Organization Created'){
            $msg = 'Organization Created.';
        }else if(strtolower($data['notification_type']) == 'Organization Updated'){
            $msg = 'Organization Updated.';
        }else if(strtolower($data['notification_type']) == 'Lead Notes Updated'){
            $msg = 'Lead Notes Updated.';
        }else if(strtolower($data['notification_type']) == 'Notes created'){
            $msg = 'Notes created.';
        }else if(strtolower($data['notification_type']) == 'Task Deleted'){
            $msg = 'Task Deleted.';
        }else if(strtolower($data['notification_type']) == 'Lead Created'){
            $msg = 'Lead Created.';
        }else if(strtolower($data['notification_type']) == 'Lead Updated'){
            $msg = 'Lead Updated.';
        }else if(strtolower($data['notification_type']) == 'Lead Deleted'){
            $msg = 'Lead Deleted.';
        }else if(strtolower($data['notification_type']) == 'Discussion created'){
            $msg = 'Discussion created.';
        }else if(strtolower($data['notification_type']) == 'Drive link added'){
            $msg = 'Drive link added.';
        }else if(strtolower($data['notification_type']) == 'Lead Notes Updated'){
            $msg = 'Lead Notes Updated.';
        }else if(strtolower($data['notification_type']) == 'Lead Notes Deleted'){
            $msg = 'Lead Notes Deleted.';
        }else if(strtolower($data['notification_type']) == 'Lead Converted'){
            $msg = 'Lead Converted.';
        }else if(strtolower($data['notification_type']) == 'Application Notes Created'){
            $msg = 'Application Notes Created.';
        }else if(strtolower($data['notification_type']) == 'Application Notes Updated'){
            $msg = 'Application Notes Updated.';
        }else if(strtolower($data['notification_type']) == 'Applicaiton Notes Deleted'){
            $msg = 'Applicaiton Notes Deleted.';
        }else{
            $msg = 'New record created';
        }

        

        $notification = new Notification;
        $notification->user_id = \Auth::user()->id;
        $notification->type = 'push notificationn';
        $notification->data = $msg;
        $notification->is_read = 0;

        $notification->save();
       // event(new NewNotification($notification));
    }
}

if (!function_exists('addLeadHistory')) {
    function addLeadHistory($data = [])
    {
        if (isset($data['stage_id'])) {
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
        if (\Auth::user()->type == 'company') {
            $user_brand = !empty(\Auth::user()->id) ? \Auth::user()->id : 0;
        } else {
            $user_brand = !empty(\Auth::user()->brand_id) ? \Auth::user()->brand_id : 0;
        }

        if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'HR' || \Auth::user()->can('level 1')) {
           
        } else if (\Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager' || \Auth::user()->can('level 2')) {
            $permittedCompanies = allPermittedCompanies();
            $brands->whereIn('id', $permittedCompanies);
            $brands->orWhere('id', $user_brand);
        } else if (\Auth::user()->type == 'Region Manager' || \Auth::user()->can('level 3')) {
            $regions = Region::where('region_manager_id', \Auth::user()->id)->pluck('id')->toArray();
            $branches = Branch::whereIn('region_id', $regions)->pluck('id')->toArray();
            $brands->whereIn('branch_id', $branches);
            $brands->orWhere('id', $user_brand);
        } else if (\Auth::user()->type == 'Branch Manager' || \Auth::user()->can('level 4')) {
            $branches = Branch::where('branch_manager_id', \Auth::user()->id)->pluck('id')->toArray();
            $brands->whereIn('branch_id', $branches);
            $brands->orWhere('id', $user_brand);
        } else if(\Auth::user()->can('level 5')){
            $brands->where('id', $user_brand);
        } else {
            $brands->where('id', $user_brand);
        }

        return $brands->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('FiltersRegions')) {
    function FiltersRegions($id)
    {
        $regions = Region::whereRaw('FIND_IN_SET(?, brands)', [$id])->pluck('name', 'id')->toArray();
        $html = ' <select class="form form-control region_id select2" id="region_id" name="region_id"> <option value="">Select Region</option> ';
        foreach ($regions as $key => $region) {
            $html .= '<option value="' . $key . '">' . $region . '</option> ';
        }
        $html .= '</select>';

        return $html;
    }
}

if (!function_exists('FiltersBranches')) {
    function FiltersBranches($id)
    {
        $branches = Branch::where('region_id', $id)->pluck('name', 'id')->toArray();
        $html = ' <select class="form form-control branch_id select2" id="branch_id" name="lead_branch"> <option value="">Select Branch</option> ';
        foreach ($branches as $key => $branch) {
            $html .= '<option value="' . $key . '">' . $branch . '</option> ';
        }
        $html .= '</select>';

        return $html;
    }
}


if (!function_exists('FiltersBranchUsers')) {
    function FiltersBranchUsers($id)
    {
        $users = User::whereNotIn('type', ['super admin', 'company', 'accountant', 'client'])->where('branch_id', $id)->pluck('name', 'id')->toArray();
        $html = ' <select class="form form-control user_id select2" id="user_id" name="lead_assgigned_user"> <option value="">Select User</option> ';
            $html .= '<option value="null">Not Assign</option> ';
        foreach ($users as $key => $user) {
            $html .= '<option value="' . $key . '">' . $user . '</option> ';
        }
        $html .= '</select>';

        return $html;
    }
}


//returning stages ranges like visa fall in 1,2,3 and deposit fall in 4,5,6
if (!function_exists('stagesRange')) {
    function stagesRange($type)
    {
        if ($type == 'visas') {
            return [4, 5, 6];
        } else if ($type == 'deposit') {
            return [7, 8, 9];
        } else {
            return [1, 2, 3];
        }
    }
}




if (!function_exists('BrandsRegionsBranches')) {
    function BrandsRegionsBranches()
    {
        $brands = [];
        $regions = [];
        $branches = [];
        $employees = [];

        $user = \Auth::user();
        $type = $user->type;

        if(isset($_GET['brand']) && !empty($_GET['brand'])){
            $regions = Region::where('brands', $_GET['brand'])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }

        if(isset($_GET['region_id']) && !empty($_GET['region_id'])){
            $branches = Branch::where('region_id', $_GET['region_id'])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            // $regions = Region::where('id', $_GET['region_id'])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }

        if(isset($_GET['branch_id']) && !empty($_GET['branch_id'])){
           // $branches = Branch::where('id', $_GET['branch_id'])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
           $employees = User::where('branch_id', $_GET['branch_id'])->whereNotIn('type', ['client', 'company', 'super admin'])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }

        if ($type == 'super admin' || $type == 'Admin Team' || $type == 'HR' || \Auth::user()->can('level 1')) {
            $brands = User::where('type', 'company')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        } else if ($type == 'company') {
            $brands = User::where('type', 'company')->where('id', $user->id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $regions = Region::where('brands', $user->id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        } else if ($type == 'Project Director' || $type == 'Project Manager' || \Auth::user()->can('level 2')) {
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $brands = User::where('type', 'company')->whereIn('id', $brand_ids)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        } else if ($type == 'Region Manager' || \Auth::user()->can('level 3')) {
            $brands = User::where('type', 'company')->where('id', $user->brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $regions = Region::where('id', $user->region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $branches = Branch::where('region_id', $user->region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        } else if ($type == 'Branch Manager' || $type == 'Admissions Officer' || $type == 'Admissions Manager' || $type == 'Marketing Officer' || \Auth::user()->can('level 4')) {
            $brands = User::where('type', 'company')->where('id', $user->brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $regions = Region::where('id', $user->region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $branches = Branch::where('id', $user->branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $employees = User::where('branch_id', $user->branch_id)->whereNotIn('type', ['client', 'company', 'super admin'])->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        }



        return [
            'brands' => [0 => 'Select Brand'] + $brands,
            'regions' => [0 => 'Select Region'] + $regions,
            'branches' => [0 => 'Select Branch'] + $branches,
            'employees' => [0 => 'Select Employee'] + $employees
        ];
    }




    if (!function_exists('BrandsRegionsBranchesForEdit')) {
        function BrandsRegionsBranchesForEdit($brand_id = 0, $region_id = 0, $branch_id = 0)
        {
            $brands = [];
            $regions = [];
            $branches = [];
            $employees = [];

            $user = \Auth::user();
            $type = $user->type;

            //dd($brand_id.' '.$region_id.' '.$branch_id);

            if ($type == 'super admin' || $type == 'HR' || $type == 'Admin Team' || \Auth::user()->can('level 1')) {
                $brands = User::where('type', 'company')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();           
            } else if ($type == 'company') {
                $brands = User::where('type', 'company')->where('id', $user->id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            } else if ($type == 'Project Director' || $type == 'Project Manager' || \Auth::user()->can('level 2')) {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $brands = User::where('type', 'company')->whereIn('id', $brand_ids)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            } else if ($type == 'Region Manager' || \Auth::user()->can('level 3')) {
                $brands = User::where('type', 'company')->where('id', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('brands', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            } else if ($type == 'Branch Manager' || $type == 'Admissions Officer' || $type == 'Admissions Manager' || $type == 'Marketing Officer' || \Auth::user()->can('level 4')) {
                $brands = User::where('type', 'company')->where('id', $brand_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $regions = Region::where('id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $branches = Branch::where('region_id', $region_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
                $employees = User::where('branch_id', $branch_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            }


            return [
                'brands' => [0 => 'Select Brand'] + $brands,
                'regions' => [0 => 'Select Region'] + $regions,
                'branches' => [0 => 'Select Branch'] + $branches,
                'employees' => [0 => 'Select Employee'] + $employees
            ];
        }
    }
}


function downloadCSV($headers, $data, $filename = 'data.csv')
{
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

function accessLevel()
{
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

/**
 * Calculates pagination details based on the current page and number of results per page.
 * If 'page' and 'num_results_on_page' parameters are provided in the GET request,
 * calculates the start index for fetching results accordingly.
 * 
 * @return array An array containing pagination details:
 *               - 'start': The start index for fetching results.
 *               - 'num_results_on_page': The number of results to display on each page.
 *               - 'page': The current page number.
 */
function getPaginationDetail(){
    // Pagination calculation
    $start = 0; // Default start index
    $num_results_on_page = 25; // Default number of results per page
    
    if (isset($_GET['page'])) {
        $page = $_GET['page']; // Current page number
        
        // If 'num_results_on_page' parameter is provided, update $num_results_on_page
        $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
        
        // Calculate the start index based on the current page and number of results per page
        $start = ($page - 1) * $num_results_on_page;
    } else {
        $page = 1;
        // If 'page' parameter is not provided, only update $num_results_on_page if 'num_results_on_page' parameter is provided
        $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
    }

    // Return an array containing pagination details
    return [
        'start' => $start, // Start index for fetching results
        'num_results_on_page' => $num_results_on_page, // Number of results to display on each page
        'page' => $page // Current page number
    ];
}


/**
 * Retrieves lists of users, regions, and branches for use in dropdowns or select inputs.
 * Assumes 'name' and 'id' fields exist in the respective database tables.
 *
 * @return array Associative array containing lists of users, regions, and branches.
 */
function UserRegionBranch(){
    // Retrieve users and format them as 'name' => 'id'
    $users = User::pluck('name', 'id')->toArray();
    
    // Retrieve regions and format them as 'name' => 'id'
    $regions = Region::pluck('name', 'id')->toArray();
    
    // Retrieve branches and format them as 'name' => 'id'
    $branches = Branch::pluck('name', 'id')->toArray();

    // Return the formatted data
    return [
        'users' => $users,
        'regions' => $regions,
        'branches' => $branches
    ];
}

