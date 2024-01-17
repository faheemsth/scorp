<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DealApplication;
use DB;
class VisaChartController extends Controller
{
    //////////////////////////////////////   Granted  ///////////////////////////////////////////////////
    public function TestChartGranted(){
        return view('chartOfAccount.TestChartGranted');
    }

    public function GrantedByCountry(){
        $results = DealApplication::join('universities', 'universities.id', '=', 'deal_applications.university_id')
        ->whereIn('deal_applications.stage_id', [7, 8, 9])
        ->groupBy('universities.country')
        ->select('universities.country', DB::raw('count(universities.country) as country_count'))
        ->get();

            $labels = [];
            $values = [];
            $backgroundColor = [];

            foreach ($results as $result) {
                $labels[] = $result['country'];
                $values[] = $result['country_count'];
                $backgroundColor[] = '#' . '1F2735';
            }
        return response()->json(['labels' => $labels, 'values' => $values, 'backgroundColor' => $backgroundColor]);
    }

    public function GrantedByUniversty(){
        $results = DealApplication::join('universities', 'universities.id', '=', 'deal_applications.university_id')
        ->whereIn('deal_applications.stage_id', [7, 8, 9])
        ->groupBy('deal_applications.university_id', 'universities.name')
        ->select('universities.name', DB::raw('count(deal_applications.university_id) as university_count'))
        ->get();

            $labels = [];
            $values = [];
            $backgroundColor = [];

            foreach ($results as $result) {
                $labels[] = $result['name'];
                $values[] = $result['university_count'];
                $backgroundColor[] = '#' . '1F2735';
            }
        return response()->json(['labels' => $labels, 'values' => $values, 'backgroundColor' => $backgroundColor]);
    }


    ////////////////////////////////////   Deposited  ///////////////////////////////////////////////////

    public function ChartDeposited(){
        return view('chartOfAccount.TestChartDeposted');
    }
    public function DepositedByCountry(){
        $results = DealApplication::join('universities', 'universities.id', '=', 'deal_applications.university_id')
        ->whereIn('deal_applications.stage_id', [4, 5, 6])
        ->groupBy('universities.country')
        ->select('universities.country', DB::raw('count(universities.country) as country_count'))
        ->get();

            $labels = [];
            $values = [];
            $backgroundColor = [];

            foreach ($results as $result) {
                $labels[] = $result['country'];
                $values[] = $result['country_count'];
                $backgroundColor[] = '#' . '1F2735';
            }
        return response()->json(['labels' => $labels, 'values' => $values, 'backgroundColor' => $backgroundColor]);
    }

    public function DepositedByUniversty(){
        $results = DealApplication::join('universities', 'universities.id', '=', 'deal_applications.university_id')
        ->whereIn('deal_applications.stage_id', [4, 5, 6])
        ->groupBy('deal_applications.university_id', 'universities.name')
        ->select('universities.name', DB::raw('count(deal_applications.university_id) as university_count'))
        ->get();

            $labels = [];
            $values = [];
            $backgroundColor = [];

            foreach ($results as $result) {
                $labels[] = $result['name'];
                $values[] = $result['university_count'];
                $backgroundColor[] = '#' . '1F2735';
            }
        return response()->json(['labels' => $labels, 'values' => $values, 'backgroundColor' => $backgroundColor]);
    }


    ////////////////////////////////////   All Application  ///////////////////////////////////////////////////

        public function ChartApplication(){
            return view('chartOfAccount.TestChartApplication');
        }
        public function ApplicationByCountry(){
            $results = DealApplication::join('universities', 'universities.id', '=', 'deal_applications.university_id')
            ->groupBy('universities.country')
            ->select('universities.country', DB::raw('count(universities.country) as country_count'))
            ->get();

                $labels = [];
                $values = [];
                $backgroundColor = [];

                foreach ($results as $result) {
                    $labels[] = $result['country'];
                    $values[] = $result['country_count'];
                    $backgroundColor[] = '#' . '1F2735';
                }
            return response()->json(['labels' => $labels, 'values' => $values, 'backgroundColor' => $backgroundColor]);
        }

        public function ApplicationByUniversty(){
            $results = DealApplication::join('universities', 'universities.id', '=', 'deal_applications.university_id')
            ->groupBy('deal_applications.university_id', 'universities.name')
            ->select('universities.name', DB::raw('count(deal_applications.university_id) as university_count'))
            ->get();

                $labels = [];
                $values = [];
                $backgroundColor = [];

                foreach ($results as $result) {
                    $labels[] = $result['name'];
                    $values[] = $result['university_count'];
                    $backgroundColor[] = '#' . '1F2735';
                }
            return response()->json(['labels' => $labels, 'values' => $values, 'backgroundColor' => $backgroundColor]);
        }
}
