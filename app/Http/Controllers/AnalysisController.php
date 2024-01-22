<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{

    //
    public function index(){

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

        if(isset($_GET['institute_id']) && !empty($_GET['institute_id'])){
            $filters['institute_id'] = $_GET['institute_id'];
        }

        if(isset($_GET['intake_month']) && !empty($_GET['intake_month'])){
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
                            
        if(isset($_GET['brand_id']) && !empty($_GET['brand_id'])){
            $admissionsQuery->where('users.id',  $_GET['brand_id']);
            $brands[$_GET['brand_id']] = $companies[$_GET['brand_id']];
        }else{
            $brands = $companies;
        }

        if(isset($_GET['region_id']) && !empty($_GET['region_id'])){
            $admissionsQuery->where('deals.region_id',  $_GET['region_id']);
        }

        if(isset($_GET['branch_id']) && !empty($_GET['branch_id'])){
            $admissionsQuery->where('deals.branch_id',  $_GET['branch_id']);
        }

        $admissions = $admissionsQuery
                                ->groupBy('users.id')
                                ->get()
                                ->pluck('total_admissions', 'brand_id')
                                ->toArray();     

       
       //sort data according to brands alphabatical orders
        $total_admissions = [];
        foreach($companies as $key => $comp){
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

        foreach($institute_result as $result){
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
}
