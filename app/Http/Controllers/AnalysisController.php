<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Deal;
use App\Models\Region;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{

    //
    public function index_old()
    {

        //by defaul we will fetch visa stages deals
        $type = $_GET['type'] ?? 'visas';

        //fetch stages range
        $range = stagesRange($type);

        //fetch brands
        $companies = FiltersBrands();
        asort($companies);
        $brand_ids = array_keys($companies);

        //filters dropdown
        $filter_companies = FiltersBrands();
        $filter_companies = [0 => 'Select Brand'] + $filter_companies;






        //fitlers
        $filters = [];

        if (isset($_GET['institute_id']) && !empty($_GET['institute_id'])) {
            $filters['institute_id'] = $_GET['institute_id'];
        }

        if (isset($_GET['intake_month']) && !empty($_GET['intake_month'])) {
            $filters['intake_month'] = $_GET['intake_month'];
        }




        $admissionsQuery = User::selectRaw('COALESCE(count(deals.id), 0) as total_admissions, users.id as brand_id')
            ->leftJoin('deals', function ($join) use ($range) {
                $join->on('users.id', '=', 'deals.brand_id')
                    ->whereIn('deals.stage_id', $range);
            })->whereIn('users.id', $brand_ids);

        // Apply filters
        foreach ($filters as $field => $value) {
            $admissionsQuery->where("users.$field", $value);
        }

        if (isset($_GET['brand_id']) && !empty($_GET['brand_id'])) {
            $admissionsQuery->where('users.id',  $_GET['brand_id']);
            $brands[$_GET['brand_id']] = $companies[$_GET['brand_id']];
        } else {
            $brands = $companies;
        }

        if (isset($_GET['region_id']) && !empty($_GET['region_id'])) {
            $admissionsQuery->where('deals.region_id',  $_GET['region_id']);
        }

        if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
            $admissionsQuery->where('deals.branch_id',  $_GET['branch_id']);
        }

        $admissions = $admissionsQuery
            ->groupBy('users.id')
            ->get()
            ->pluck('total_admissions', 'brand_id')
            ->toArray();


        //sort data according to brands alphabatical orders
        $total_admissions = [];
        foreach ($companies as $key => $comp) {
            $total_admissions[$key] = $admissions[$key] ?? 0;
        }

        //fetch universities
        $filter_institutes = University::orderBy('name', 'ASC')->pluck('name', 'id')->prepend('Select Institutes', '');

        //university data
        $institute_result = University::select('universities.name', DB::raw('COALESCE(COUNT(deal_applications.id), 0) as application_count'))
            ->leftJoin('deal_applications', 'universities.id', '=', 'deal_applications.university_id')
            ->leftJoin('deals', 'deals.id', '=', 'deal_applications.deal_id')
            ->whereIn('deals.brand_id', $brand_ids)
            ->groupBy('universities.id', 'universities.name')
            ->orderByDesc('application_count')
            ->take(5) // Limit to the top 5 universities
            ->get();

        $institutes = [];
        $total_app = [];

        foreach ($institute_result as $result) {
            $institutes[] = $result->name;
            $total_app[] = $result->application_count;
        }



        $data = [
            'filter_companies' => $filter_companies,
            'brands' => $brands,
            'total_admissions' => $total_admissions,
            'filter_institutes' => $filter_institutes,
            'institutes' => $institutes,
            'total_app' => $total_app
        ];

        return view('analysis.index', $data);
    }

    public function index()
    {
        //by defaul we will fetch visa stages deals
        $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : 'visas';

        //fetch stages range
        $range = stagesRange($type);


        //fetch brands
        $companies = FiltersBrands();
        asort($companies);
        $brand_ids = array_keys($companies);


        //filters dropdown
        $filter_companies = FiltersBrands();
        $filter_companies = [0 => 'Select Brand'] + $filter_companies;

        if (isset($_GET['brand_id']) && !empty($_GET['brand_id'])) {
            $brands[$_GET['brand_id']] = $companies[$_GET['brand_id']];
        } else {
            $brands = $companies;
        }








        //fitlers
        $filters = [];

        if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2')){
            $filter_companies = FiltersBrands();
            $filter_companies = [0 => 'Select Brand'] + $filter_companies;
            $filters['brands'] = $filter_companies;

            $filters['regions'] = [];
            if (isset($_GET['brand_id']) && !empty($_GET['brand_id'])) {
                $filters['regions'] = Region::where('brands', $_GET['brand_id'])->pluck('name', 'id')->toArray();
            }

            $filters['branches'] = [];
            if (isset($_GET['region_id']) && !empty($_GET['region_id'])) {
                $filters['branches'] = Branch::where('region_id', $_GET['region_id'])->pluck('name', 'id')->toArray();
            }

            if (isset($_GET['type']) && $_GET['type'] == 'applications') {

                if (isset($_GET['brand_id']) && !empty($_GET['brand_id']) && empty($_GET['region_id'])) {
                    $total_admissions = $this->GetTop10AppRegions($_GET['brand_id']);
                } else if (isset($_GET['region_id']) && !empty($_GET['region_id']) && empty($_GET['branch_id'])) {
                    $total_admissions = $this->GetTop10AppBranches($_GET['region_id']);
                } else if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
                    $total_admissions = $this->GetTop10AppEmployees($_GET['branch_id']);
                } else {
                    $total_admissions = $this->GetTop10AppBrands($brand_ids);
                }
            } else {
                if (isset($_GET['brand_id']) && !empty($_GET['brand_id']) && empty($_GET['region_id'])) {
                    $total_admissions = $this->GetTop10Regions($range, $_GET['brand_id']);
                } else if (isset($_GET['region_id']) && !empty($_GET['region_id']) && empty($_GET['branch_id'])) {
                    $total_admissions = $this->GetTop10Branches($range, $_GET['region_id']);
                } else if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
                    $total_admissions = $this->GetTop10Employees($range, $_GET['branch_id']);
                } else {
                    $total_admissions = $this->GetTop10Brands($range, $brand_ids);
                }
            }
        }else if(\Auth::user()->can('level 3')){
            $filters['brands'] = User::where('id', \Auth::user()->brand_id)->pluck('name', 'id')->toArray();
            $filters['regions'] = Region::where('id', \Auth::user()->region_id)->pluck('name', 'id')->toArray();
            $filters['branches'] = Branch::where('region_id', \Auth::user()->region_id)->pluck('name', 'id')->toArray();
            if (isset($_GET['type']) && $_GET['type'] == 'applications') {
               if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
                    $total_admissions = $this->GetTop10AppEmployees($_GET['branch_id']);
                } else {
                    $total_admissions = $this->GetTop10AppBranches(\Auth::user()->region_id);
                }
            } else {
                if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
                    $total_admissions = $this->GetTop10Employees($range, $_GET['branch_id']);
                } else {
                    $total_admissions = $this->GetTop10Branches($range, \Auth::user()->region_id);
                }
            }
        }else if(\Auth::user()->can('level 4')){
            $filters['brands'] = User::where('id', \Auth::user()->brand_id)->pluck('name', 'id')->toArray();
            $filters['regions'] = Region::where('id', \Auth::user()->region_id)->pluck('name', 'id')->toArray();
            $filters['branches'] = Branch::where('id', \Auth::user()->branch_id)->pluck('name', 'id')->toArray();

            if (isset($_GET['type']) && $_GET['type'] == 'applications') {
                $total_admissions = $this->GetTop10AppEmployees(\Auth::user()->branch_id);
            } else {
                $total_admissions = $this->GetTop10Employees($range, \Auth::user()->branch_id);
            }
        }
        


        $top_sum = array_sum(array_column($total_admissions['top'], 'total_deals'));
        $top_sum += isset($total_admissions['other']) ? $total_admissions['other'] : 0;

        $data = [
            'filters' => $filters,
            'filter_companies' => $filter_companies,
            'brands' => $brands,
            'total_admissions' => json_encode($total_admissions),
            'top_sum' => $top_sum
        ];
        return view('analysis.index', $data);
    }

    private function GetTop10AppBrands($brand_ids)
    {
        $results = DB::table(DB::raw('(
        SELECT 
            CASE 
                WHEN @prev_brand = brand_id THEN @rank
                ELSE @rank := @rank + 1
            END AS deal_rank,
            @prev_brand := brand_id AS brand_id,
            name,
            total_deals
        FROM (
            SELECT 
                d.brand_id,
                COALESCE(u.name, "others") AS name,
                COUNT(*) AS total_deals
            FROM deal_applications d
            LEFT JOIN users u ON d.brand_id = u.id
            WHERE d.brand_id IN (' . implode(',', $brand_ids) . ')  -- Properly interpolate stage_ids
            GROUP BY d.brand_id
            ORDER BY total_deals DESC
        ) AS ranked_brands,
        (SELECT @rank := 0, @prev_brand := NULL) AS vars
    ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 brands
        $top10Brands = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining brands
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 brands and the total count of deals for the remaining brands under "other"
        return [
            'top' => $top10Brands,
            'other' => $totalOtherDeals
        ];
    }

    private function GetTop10Brands($stage_ids = [], $brand_ids)
    {
        $results = DB::table(DB::raw('(
        SELECT 
            CASE 
                WHEN @prev_brand = brand_id THEN @rank
                ELSE @rank := @rank + 1
            END AS deal_rank,
            @prev_brand := brand_id AS brand_id,
            name,
            total_deals
        FROM (
            SELECT 
                d.brand_id,
                COALESCE(u.name, "others") AS name,
                COUNT(*) AS total_deals
            FROM deals d
            LEFT JOIN users u ON d.brand_id = u.id
            WHERE d.stage_id IN (' . implode(',', $stage_ids) . ')  -- Properly interpolate stage_ids
            AND d.brand_id IN (' . implode(',', $brand_ids) . ')  -- Properly interpolate stage_ids
            GROUP BY d.brand_id
            ORDER BY total_deals DESC
        ) AS ranked_brands,
        (SELECT @rank := 0, @prev_brand := NULL) AS vars
    ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 brands
        $top10Brands = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining brands
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 brands and the total count of deals for the remaining brands under "other"
        return [
            'top' => $top10Brands,
            'other' => $totalOtherDeals
        ];
    }

    private function GetTop10AppRegions($brand_id)
    {
        $results = DB::table(DB::raw('(
        SELECT 
            CASE 
                WHEN @prev_region = region_id THEN @rank
                ELSE @rank := @rank + 1
            END AS deal_rank,
            @prev_region := region_id AS region_id,
            name,
            total_deals
        FROM (
            SELECT 
                deals.region_id,
                COALESCE(u.name, "others") AS name,
                COUNT(*) AS total_deals
            FROM deals 
            INNER JOIN deal_applications d ON deals.id = d.deal_id
            LEFT JOIN regions u ON deals.region_id = u.id
            WHERE d.brand_id = "' . $brand_id . '"
            GROUP BY deals.region_id
            ORDER BY total_deals DESC
        ) AS ranked_regions,
        (SELECT @rank := 0, @prev_region := NULL) AS vars
    ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 regions
        $top10Regions = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining regions
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 regions and the total count of deals for the remaining regions under "other"
        return [
            'top' => $top10Regions,
            'other' => $totalOtherDeals
        ];
    }

    private function GetTop10Regions($stage_ids = [], $brand_id)
    {
        $results = DB::table(DB::raw('(
        SELECT 
            CASE 
                WHEN @prev_region = region_id THEN @rank
                ELSE @rank := @rank + 1
            END AS deal_rank,
            @prev_region := region_id AS region_id,
            name,
            total_deals
        FROM (
            SELECT 
                d.region_id,
                COALESCE(u.name, "others") AS name,
                COUNT(*) AS total_deals
            FROM deals d
            LEFT JOIN regions u ON d.region_id = u.id
            WHERE d.stage_id IN (' . implode(',', $stage_ids) . ')
            AND d.brand_id = "' . $brand_id . '"
            GROUP BY d.region_id
            ORDER BY total_deals DESC
        ) AS ranked_regions,
        (SELECT @rank := 0, @prev_region := NULL) AS vars
    ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 regions
        $top10Regions = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining regions
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 regions and the total count of deals for the remaining regions under "other"
        return [
            'top' => $top10Regions,
            'other' => $totalOtherDeals
        ];
    }

    private function GetTop10AppBranches($region_id)
    {

        $results = DB::table(DB::raw('(
        SELECT 
            CASE 
                WHEN @prev_branch = branch_id THEN @rank
                ELSE @rank := @rank + 1
            END AS deal_rank,
            @prev_branch := branch_id AS branch_id,
            name,
            total_deals
        FROM (
            SELECT 
                deals.branch_id,
                COALESCE(u.name, "others") AS name,
                COUNT(*) AS total_deals
            FROM deals 
            INNER JOIN deal_applications d ON d.deal_id = deals.id
            LEFT JOIN branches u ON deals.branch_id = u.id
            WHERE deals.region_id = "' . $region_id . '"
            GROUP BY deals.branch_id
            ORDER BY total_deals DESC
        ) AS ranked_branches,
        (SELECT @rank := 0, @prev_branch := NULL) AS vars
    ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 branches
        $top10Branches = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining branches
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 branches and the total count of deals for the remaining branches under "other"
        return [
            'top' => $top10Branches,
            'other' => $totalOtherDeals
        ];
    }

    private function GetTop10Branches($stage_ids = [], $region_id)
    {
        $results = DB::table(DB::raw('(
        SELECT 
            CASE 
                WHEN @prev_branch = branch_id THEN @rank
                ELSE @rank := @rank + 1
            END AS deal_rank,
            @prev_branch := branch_id AS branch_id,
            name,
            total_deals
        FROM (
            SELECT 
                d.branch_id,
                COALESCE(u.name, "others") AS name,
                COUNT(*) AS total_deals
            FROM deals d
            LEFT JOIN branches u ON d.branch_id = u.id
            WHERE d.stage_id IN (' . implode(',', $stage_ids) . ')
            AND d.region_id = "' . $region_id . '"
            GROUP BY d.branch_id
            ORDER BY total_deals DESC
        ) AS ranked_branches,
        (SELECT @rank := 0, @prev_branch := NULL) AS vars
    ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 branches
        $top10Branches = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining branches
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 branches and the total count of deals for the remaining branches under "other"
        return [
            'top' => $top10Branches,
            'other' => $totalOtherDeals
        ];
    }

    private function GetTop10AppEmployees($branch_id)
    {
        $results = DB::table(DB::raw('(
            SELECT 
                CASE 
                    WHEN @prev_user = assigned_to THEN @rank
                    ELSE @rank := @rank + 1
                END AS deal_rank,
                @prev_user := assigned_to AS assigned_to,
                name,
                total_deals
            FROM (
                SELECT 
                    deals.assigned_to,
                    COALESCE(u.name, "others") AS name,
                    COUNT(*) AS total_deals
                FROM deals
                INNER JOIN deal_applications d ON d.deal_id = deals.id
                LEFT JOIN users u ON deals.assigned_to = u.id
                WHERE deals.branch_id = "' . $branch_id . '"
                GROUP BY deals.assigned_to
                ORDER BY total_deals DESC
            ) AS ranked_employees,
            (SELECT @rank := 0, @prev_user := NULL) AS vars
        ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 employees
        $top10Employees = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining employees
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 employees and the total count of deals for the remaining employees under "other"
        return [
            'top' => $top10Employees,
            'other' => $totalOtherDeals
        ];
    }

    private function GetTop10Employees($stage_ids = [], $branch_id)
    {
        $results = DB::table(DB::raw('(
            SELECT 
                CASE 
                    WHEN @prev_user = assigned_to THEN @rank
                    ELSE @rank := @rank + 1
                END AS deal_rank,
                @prev_user := assigned_to AS assigned_to,
                name,
                total_deals
            FROM (
                SELECT 
                    d.assigned_to,
                    COALESCE(u.name, "others") AS name,
                    COUNT(*) AS total_deals
                FROM deals d
                LEFT JOIN users u ON d.assigned_to = u.id
                WHERE d.stage_id IN (' . implode(',', $stage_ids) . ')
                AND d.branch_id = "' . $branch_id . '"
                GROUP BY d.assigned_to
                ORDER BY total_deals DESC
            ) AS ranked_employees,
            (SELECT @rank := 0, @prev_user := NULL) AS vars
        ) AS ranked_with_ranks'))
            ->select('name', DB::raw('SUM(total_deals) AS total_deals'))
            ->groupBy('name')
            ->orderByDesc('total_deals')
            ->get();

        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 10 employees
        $top10Employees = array_slice($resultsArray, 0, 9);

        // Calculate the total deals for the remaining employees
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 9), 'total_deals'));

        // Return the top 10 employees and the total count of deals for the remaining employees under "other"
        return [
            'top' => $top10Employees,
            'other' => $totalOtherDeals
        ];
    }
}
