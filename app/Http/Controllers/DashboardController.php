<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\Job;
use App\Models\Tax;
use App\Models\Bill;
use App\Models\Deal;
use App\Models\Goal;
use App\Models\Lead;
use App\Models\Plan;
use App\Models\User;
use App\Models\Event;
use App\Models\Label;
use App\Models\Order;
use App\Models\Payer;
use App\Models\Stage;
use App\Models\Payees;
use App\Models\Ticket;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Meeting;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Revenue;
use App\Models\Trainer;
use App\Models\Utility;
use App\Models\DealTask;
use App\Models\Employee;
use App\Models\Pipeline;
use App\Models\Training;
use App\Models\BugStatus;
use App\Models\LeadStage;
use App\Models\Timesheet;
use App\Models\University;
use App\Models\AccountList;
use App\Models\BankAccount;
use App\Models\ProjectTask;
use App\Models\TimeTracker;
use App\Models\Announcement;
use App\Models\BalanceSheet;
use Illuminate\Http\Request;
use App\Models\AttendanceEmployee;
use App\Models\CompanyPermission;
use App\Models\DealApplication;
use App\Models\LandingPageSection;
use App\Models\ProductServiceUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductServiceCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Providers\RouteServiceProvider;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function account_dashboard_index()
    {

        if (Auth::check()) {
            if (Auth::user()->type == 'super admin') {
                return redirect()->route('client.dashboard.view');
            } elseif (Auth::user()->type == 'client') {
                return redirect()->route('client.dashboard.view');
            } else {
                if (\Auth::user()->can('show account dashboard')) {
                    $data['latestIncome']  = Revenue::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                    $data['latestExpense'] = Payment::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                    $currentYer           = date('Y');


                    $incomeCategory = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 1)->get();
                    $inColor        = array();
                    $inCategory     = array();
                    $inAmount       = array();
                    for ($i = 0; $i < count($incomeCategory); $i++) {
                        $inColor[]    = '#' . $incomeCategory[$i]->color;
                        $inCategory[] = $incomeCategory[$i]->name;
                        $inAmount[]   = $incomeCategory[$i]->incomeCategoryRevenueAmount();
                    }



                    $data['incomeCategoryColor'] = $inColor;
                    $data['incomeCategory']      = $inCategory;
                    $data['incomeCatAmount']     = $inAmount;


                    $expenseCategory = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 2)->get();
                    $exColor         = array();
                    $exCategory      = array();
                    $exAmount        = array();
                    for ($i = 0; $i < count($expenseCategory); $i++) {
                        $exColor[]    = '#' . $expenseCategory[$i]->color;
                        $exCategory[] = $expenseCategory[$i]->name;
                        $exAmount[]   = $expenseCategory[$i]->expenseCategoryAmount();
                    }


                    $data['expenseCategoryColor'] = $exColor;
                    $data['expenseCategory']      = $exCategory;
                    $data['expenseCatAmount']     = $exAmount;

                    $data['incExpBarChartData']  = \Auth::user()->getincExpBarChartData();
                    $data['incExpLineChartData'] = \Auth::user()->getIncExpLineChartDate();
                    //dd($data['incExpBarChartData']);
                    $data['currentYear']  = date('Y');
                    $data['currentMonth'] = date('M');

                    $constant['taxes']         = Tax::where('created_by', \Auth::user()->creatorId())->count();
                    $constant['category']      = ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->count();
                    $constant['units']         = ProductServiceUnit::where('created_by', \Auth::user()->creatorId())->count();
                    $constant['bankAccount']   = BankAccount::where('created_by', \Auth::user()->creatorId())->count();
                    $data['constant']          = $constant;
                    $data['bankAccountDetail'] = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $data['recentInvoice']     = Invoice::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                    $data['weeklyInvoice']     = \Auth::user()->weeklyInvoice();
                    $data['monthlyInvoice']    = \Auth::user()->monthlyInvoice();
                    $data['recentBill']        = Bill::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                    $data['weeklyBill']        = \Auth::user()->weeklyBill();
                    $data['monthlyBill']       = \Auth::user()->monthlyBill();
                    $data['goals']             = Goal::where('created_by', '=', \Auth::user()->creatorId())->where('is_display', 1)->get();

                    return view('dashboard.account-dashboard', $data);
                } else {

                    return $this->hrm_dashboard_index();
                }
            }
        } else {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            } else {
                $settings = Utility::settings();
                if ($settings['display_landing_page'] == 'on') {


                    return view('layouts.landing', compact('settings'));
                } else {
                    return redirect('login');
                }
            }
        }
    }

    private function superAdminCrmDashboarData()
    {
        
        //$labels   = Label::get();
        $labels   = Label::select('labels.*')->join('pipelines', 'pipelines.id', '=', 'labels.pipeline_id')->orderBy('labels.pipeline_id')->get();
        $lead_label_color = array();
        $lead_label = array();
        $lead_label_count = array();
        foreach ($labels as $label) {

            $lead_label_count[] = Lead::where('labels', 'like', '%' . $label->id . '%')
                ->count();

            $lead_label[] = $label->name;
            if ($label->color == 'primary') {
                $lead_label_color[] = '#0d6efd';
            } else if ($label->color == 'secondary') {
                $lead_label_color[] = '#6c757d';
            } else if ($label->color == 'danger') {
                $lead_label_color[] = '#dc3545';
            } else if ($label->color == 'warning') {
                $lead_label_color[] = '#ffc107';
            } else if ($label->color == 'success') {
                $lead_label_color[] = '#198754';
            } else {
                $lead_label_color[] = '#f8f9fa';
            }
        }
        $data['lead_label'] = $lead_label;
        $data['lead_label_color']      = $lead_label_color;
        $data['lead_label_count']     = $lead_label_count;

        $stages = Stage::select('stages.*')->join('pipelines', 'pipelines.id', '=', 'stages.pipeline_id')->orderBy('stages.order')->get();

        $companiesData = [];
        $company_names = [];

        $currentUserDealStagesCount = [];
        $currentUserDealStage = [];


        if (!isset($_GET['company']) || isset($_GET['company']) && $_GET['company'] == 'all') {

            //fetching all permitted companies
           // $allowedCompanies = CompanyPermission::where(['active' => 'true'])->get()->pluck('name', 'id')->toArray();

            $companies = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();

            // Process the pipelines for the allowed companies
            foreach ($companies as $key => $company) {
                $company_names[$key] = $company;

                $deal_stages = [];
                $stage_deal = [];

                foreach ($stages as $stage) {
                    $deal_stages[] = $stage->name;
                    $deals_count = Deal::where(['stage_id' => $stage->id, 'created_by' => $key])->count();
                    $stage_deal[] = !empty($deals_count) ? $deals_count : 0;
                }

                $companiesData[] = [
                    'company' => $company,
                    'deal_stages' =>  $deal_stages,
                    'deal_stage_count' =>  $stage_deal
                ];
            }
        } else {

            $company_id = $_GET['company'];
            $currentUserCompany = User::where('type', 'company')->find($company_id);
            foreach ($stages as $stage) {
                $currentUserDealStage[] = $stage->name;
                $currentUserDealStagesCount[] = Deal::where(['stage_id' => $stage->id, 'created_by' => $company_id])->count();
            }
            $companiesData[] = [
                'company' => $currentUserCompany->name,
                'deal_stages' =>  $currentUserDealStage,
                'deal_stage_count' =>  $currentUserDealStagesCount
            ];

            // Get the current user's company
            $company_names = User::where('type', 'company')->get()->pluck('name', 'id')->toArray();
        }


        $data['companies_deals_data'] = $companiesData;
        $data['company_names'] = $company_names;


        ////////////////////////////////////////////////////////////Companies and approved Deals
        $all_companies = User::where(['type' => 'company'])->get()->pluck('name', 'id');
        $fixed_deal_stage = DB::table('settings')->where('name', '=', 'deal_stage')->first();


        $all_company_approved = [];
        $company_name_for_approved = [];
        foreach ($all_companies as $id => $comp) {
            $company_name_for_approved[] = $comp;
            $all_company_approved[] = Deal::where(['stage_id' => isset($fixed_deal_stage->value) ? $fixed_deal_stage->value : 0, 'created_by' => $id])->count();
        }

        $data['all_companies_by_deal_stage'] = $all_company_approved;
        $data['company_name_for_approved'] = $company_name_for_approved;


        ////////////////////////////////////////////////////////Universities data
        $universities = University::paginate(10, ['id', 'name'])->pluck('name', 'id');
        $university_deals = [];
        $university_name = [];
        foreach ($universities as $id => $university) {
            $university_name[] = $university;
            $university_deals[] = DB::table('deals as l')
                ->whereExists(function ($query) use ($id) {
                    $query->select(DB::raw(1))
                        ->from('courses as c')
                        ->whereRaw("FIND_IN_SET(c.id, l.courses)")
                        ->where('c.university_id', $id);
                })
                ->count();
        }
        $data['university_deals'] = $university_deals;
        $data['university_names'] = $university_name;
        return $data;
    }

    public function crm_dashboard_index1()
    {

        if (Auth::check()) {

            if (Auth::user()->default_pipeline) {
                $pipeline = Pipeline::where('id', '=', Auth::user()->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::first();
                }
            } else {
                $pipeline = Pipeline::first();
            }
            $pipelines = Pipeline::get()->pluck('name', 'id');


            if (Auth::user()->type == 'client') {
                return redirect()->route('client.dashboard.view');
            } elseif (Auth::user()->type == 'super admin') {
                ///////////////////////////////////////////////////Chart of Lead Stages
                $data = $this->superAdminCrmDashboarData();
                return view('dashboard.crm-dashboard', $data);
            } elseif (Auth::user()->type == 'team') {

                $data = $this->superAdminCrmDashboarData();
                return view('dashboard.crm-dashboard', $data);
            } elseif (Auth::user()->type == 'company') {

                ///////////////////////////////////////////////////Chart of Lead Stages
                $leadsQuery = Lead::where('created_by', \Auth()->user()->id);


                if (isset($_GET['time'])) {
                    $startDate = now()->subMonth(); // Get the start date as one month ago
                    $endDate = now(); // Get the current date

                    if ($_GET['time'] == 'monthly') {
                        // Filter leads for the monthly time period
                        $leadsQuery->whereBetween('created_at', [$startDate, $endDate]);
                    } elseif ($_GET['time'] == 'weekly') {
                        // Filter leads for the weekly time period
                        $startDate = now()->subWeek(); // Get the start date as one week ago
                        $leadsQuery->whereBetween('created_at', [$startDate, $endDate]);
                    } elseif ($_GET['time'] == 'yearly') {
                        // Filter leads for the yearly time period
                        $startDate = now()->subYear(); // Get the start date as one year ago
                        $leadsQuery->whereBetween('created_at', [$startDate, $endDate]);
                    }
                }
                $leads = $leadsQuery->get();

                //$labels   = Label::get();
                $labels   = Label::select('labels.*')->join('pipelines', 'pipelines.id', '=', 'labels.pipeline_id')->orderBy('labels.pipeline_id')->get();
                $lead_label_color = array();
                $lead_label = array();
                $lead_label_count = array();
                foreach ($labels as $label) {

                    $lead_label_count[] = Lead::where('labels', 'like', '%' . $label->id . '%')
                        ->where('created_by', Auth::user()->id)
                        ->count();

                    $lead_label[] = $label->name;
                    if ($label->color == 'primary') {
                        $lead_label_color[] = '#0d6efd';
                    } else if ($label->color == 'secondary') {
                        $lead_label_color[] = '#6c757d';
                    } else if ($label->color == 'danger') {
                        $lead_label_color[] = '#dc3545';
                    } else if ($label->color == 'warning') {
                        $lead_label_color[] = '#ffc107';
                    } else if ($label->color == 'success') {
                        $lead_label_color[] = '#198754';
                    } else {
                        $lead_label_color[] = '#f8f9fa';
                    }
                }
                $data['lead_label'] = $lead_label;
                $data['lead_label_color']      = $lead_label_color;
                $data['lead_label_count']     = $lead_label_count;



                ///////////////////////////////////////////////////////// Chart of Deal Stages
                // $stages = Stage::orderBy('order', 'asc')->get();
                $stages    = Stage::select('stages.*')->join('pipelines', 'pipelines.id', '=', 'stages.pipeline_id')->orderBy('stages.order')->get();
                //$stages    = LeadStage::select('lead_stages.*')->join('pipelines', 'pipelines.id', '=', 'lead_stages.pipeline_id')->orderBy('lead_stages.order')->get();
                $companiesData = [];
                $company_names = [];

                $currentUserDealStagesCount = [];
                $currentUserDealStage = [];

                if (!isset($_GET['company']) || isset($_GET['company']) && $_GET['company'] == 'all') {

                    //current company
                    $company_id = auth()->user()->id;
                    $currentUserCompany = User::where('type', 'company')->find($company_id);
                    $company_names[$company_id] = $currentUserCompany->name;

                    foreach ($stages as $stage) {
                        $currentUserDealStage[] = $stage->name;
                        $currentUserDealStagesCount[] = Deal::where(['stage_id' => $stage->id, 'created_by' => $company_id])->count();
                    }

                    $companiesData[] = [
                        'company' => $currentUserCompany->name,
                        'deal_stages' =>  $currentUserDealStage,
                        'deal_stage_count' =>  $currentUserDealStagesCount
                    ];



                    //fetching all permitted companies
                    $allowedCompanies = CompanyPermission::where(['company_id' => $company_id, 'active' => 'true'])->get();

                    // Process the pipelines for the allowed companies
                    foreach ($allowedCompanies as $allowedCompany) {
                        $company = User::find($allowedCompany->permitted_company_id);
                        $company_names[$company->id] = $company->name;

                        $deal_stages = [];
                        $stage_deal = [];

                        foreach ($stages as $stage) {
                            $deal_stages[] = $stage->name;
                            $deals_count = Deal::where(['stage_id' => $stage->id, 'created_by' => $company->id])->count();
                            $stage_deal[] = !empty($deals_count) ? $deals_count : 0;
                        }

                        $companiesData[] = [
                            'company' => $company->name,
                            'deal_stages' =>  $deal_stages,
                            'deal_stage_count' =>  $stage_deal
                        ];
                    }
                } else {

                    $company_id = isset($_GET['company']) && !empty($_GET['company']) ? $_GET['company'] : auth()->user()->id;
                    $currentUserCompany = User::where('type', 'company')->find($company_id);

                    foreach ($stages as $stage) {
                        $currentUserDealStage[] = $stage->name;
                        $currentUserDealStagesCount[] = Deal::where(['stage_id' => $stage->id, 'created_by' => $company_id])->count();
                    }

                    $companiesData[] = [
                        'company' => $currentUserCompany->name,
                        'deal_stages' =>  $currentUserDealStage,
                        'deal_stage_count' =>  $currentUserDealStagesCount
                    ];

                    // Get the current user's company
                    $currentUserCompany = User::where('type', 'company')->find(\Auth()->user()->id);
                    $allowedCompanies = $currentUserCompany->companyPermissions;

                    $company_names[$currentUserCompany->id] = $currentUserCompany->name;

                    // Process the pipelines for the allowed companies
                    foreach ($allowedCompanies as $allowedCompany) {
                        $company = User::find($allowedCompany->permitted_company_id);
                        $company_names[$company->id] = $company->name;
                    }
                }

                $data['companies_deals_data'] = $companiesData;
                $data['company_names'] = $company_names;


                ////////////////////////////////////////////////////////////Companies and approved Deals
                $all_companies = User::where(['type' => 'company'])->get()->pluck('name', 'id');
                $fixed_deal_stage = DB::table('settings')->where('name', '=', 'deal_stage')->first();


                $all_company_approved = [];
                $company_name_for_approved = [];
                foreach ($all_companies as $id => $comp) {
                    $company_name_for_approved[] = $comp;
                    $all_company_approved[] = Deal::where(['stage_id' => isset($fixed_deal_stage->value) ? $fixed_deal_stage->value : 0, 'created_by' => $id])->count();
                }

                $data['all_companies_by_deal_stage'] = $all_company_approved;
                $data['company_name_for_approved'] = $company_name_for_approved;

                ////////////////////////////////////////////////////////Universities data
                $universities = University::paginate(10, ['id', 'name'])->pluck('name', 'id');
                $university_deals = [];
                $university_name = [];
                foreach ($universities as $id => $university) {
                    $university_name[] = $university;
                    $university_deals[] = DB::table('deals as l')
                        ->whereExists(function ($query) use ($id) {
                            $query->select(DB::raw(1))
                                ->from('courses as c')
                                ->whereRaw("FIND_IN_SET(c.id, l.courses)")
                                ->where('c.university_id', $id);
                        })
                        ->count();
                }
                $data['university_deals'] = $university_deals;
                $data['university_names'] = $university_name;


                return view('dashboard.crm-dashboard', $data);
            } else {
                return redirect()->route('dashboard');
            }
        } else {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            } else {
                $settings = Utility::settings();
                if ($settings['display_landing_page'] == 'on') {


                    return view('layouts.landing', compact('settings'));
                } else {
                    return redirect('login');
                }
            }
        }
    }

    public function crm_dashboard_index()
    {
         if (!Auth::check()) {
           return redirect('login');
         }
        $total_admissions = 0;
        $total_deposits = 0;
        $total_visas = 0;
        $total_app = 0;
        if(Auth::user()->type == 'super admin'){

            $total_admissions = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
            ->whereIn('deals.stage_id', [1, 2, 3])
            ->where('s.id', '<', 4)
            ->count();
            $total_deposits = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [4, 5, 6])
                ->where('s.id', '<', 7)
                ->count();
            $total_visas = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [7, 8, 9])
                ->where('s.id', '<', 10)
                ->count();

            $total_app = DealApplication::count();

        }else if(Auth::user()->type == 'company'){

            $id = Auth::user()->id;
          
            $total_admissions = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
            ->whereIn('deals.stage_id', [1, 2, 3])
            ->where('s.id', '<', 4)
            ->where('deals.created_at', $id)

            ->count();
            $total_deposits = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [4, 5, 6])
                ->where('s.id', '<', 7)
            ->where('deals.created_at', $id)

                ->count();
            $total_visas = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [7, 8, 9])
                ->where('s.id', '<', 10)
            ->where('deals.created_at', $id)

                ->count();

            $total_app = DealApplication::join('deals as d', 'd.id', '=', 'deal_applications.deal_id')
            ->where('d.created_by', $id)
            ->count();

        }
        

        $totalValues3 = ['total_applications' => $total_app];

        $top_brands = DB::table('deals as d')
            ->select(DB::raw('COUNT(d.id) AS total'), 'd.created_by', 'u.name')
            ->join('stages as s', 's.id', '=', 'd.stage_id')
            ->join('users as u', 'u.id', '=', 'd.created_by')
            ->where('s.id', '<=', 4)
            ->groupBy('d.created_by')
            ->orderByDesc('total')
            ->limit(3)
            ->get();


        $totalValues = [];

        foreach ($top_brands as $top_brand) {
            $totalValues[] = $top_brand->total;
        }

        $jsonData = json_encode($totalValues);

        if(isset($_GET['status']) && $_GET['status'] == 'Admission-Application'){
            $spline_chart = $this->AdmissionApplications();
        }else if(isset($_GET['status']) && $_GET['status'] == 'Application-Deposit'){
            $spline_chart = $this->ApplicationDeposit();
        }else if(isset($_GET['status']) && $_GET['status'] == 'Admission-Deposit'){
            $spline_chart = $this->AdmissionDeposit();
        }else if(isset($_GET['status']) && $_GET['status'] == 'Deposit-visas'){
            $spline_chart = $this->DepositVisas();
        }else{
            $spline_chart = $this->AdmissionApplications();
        }


        if(isset($_GET['top_brand_filter']) && $_GET['top_brand_filter'] == 'admissions'){
            $area_chart = $this->GetTop3Brands();
        }else if(isset($_GET['top_brand_filter']) && $_GET['top_brand_filter'] == 'deposits'){
            $area_chart = $this->GetTop3Brands([4, 5, 6]);
        }else if(isset($_GET['top_brand_filter']) && $_GET['top_brand_filter'] == 'visas'){
            $area_chart = $this->GetTop3Brands([7, 8, 9]);
        }else{
            $area_chart = $this->GetTop3Brands();
        }

        return view('dashboard.crm-dashboard', compact('total_admissions', 'total_deposits', 'total_visas', 'total_app', 'top_brands',  'spline_chart', 'area_chart'));
    }


    private function AdmissionApplications()
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $admission_counts = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m'); // Get the month number

            $admission_query = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereYear('deals.created_at', 2023)
                ->whereMonth('deals.created_at', $monthNumber);

            $admission_counts[$month] = $admission_query->count();
        }




        $application_counts = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("1 $month")->format('m'); // Get the month number

            $applications_query = DealApplication::whereYear('created_at', 2023)
                ->whereMonth('created_at', $monthNumber);

            $application_counts[$month] = $applications_query->count();
        }

        // Fill in 0 for months with no records
        $application_counts = array_merge(array_fill_keys($months, 0), $application_counts);


        $spline_chart = [
            [
                'name' => 'Applications',
                'data' => $application_counts
            ],
            [
                'name' => 'Admissions',
                'data' => $admission_counts
            ]
        ];
        return $spline_chart;
    }

    private function AdmissionDeposit(){
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $admission_counts = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m'); // Get the month number

            $admission_query = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereYear('deals.created_at', 2023)
                ->whereMonth('deals.created_at', $monthNumber);

            $admission_counts[$month] = $admission_query->count();
        }



        $monthlyDepositApplications = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m'); // Get the month number

            $application_query = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [4, 5, 6])
                ->where('s.id', '<', 7)
                ->whereYear('deals.created_at', 2023)
                ->whereMonth('deals.created_at', $monthNumber);

            $monthlyDepositApplications[$month] = $application_query->count();
        }


        $spline_chart = [
            [
                'name' => 'Admissions',
                'data' => $admission_counts
            ],
            [
                'name' => 'Deposits',
                'data' => $monthlyDepositApplications
            ]
        ];
        return $spline_chart;
    }

    private function ApplicationDeposit(){
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $application_counts = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("1 $month")->format('m'); // Get the month number

            $applications_query = DealApplication::whereYear('created_at', 2023)
                ->whereMonth('created_at', $monthNumber);

            $application_counts[$month] = $applications_query->count();
        }

        // Fill in 0 for months with no records
        $application_counts = array_merge(array_fill_keys($months, 0), $application_counts);



        $monthlyDepositApplications = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m'); // Get the month number

            $application_query = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [4, 5, 6])
                ->where('s.id', '<', 7)
                ->whereYear('deals.created_at', 2023)
                ->whereMonth('deals.created_at', $monthNumber);

            $monthlyDepositApplications[$month] = $application_query->count();
        }


        $spline_chart = [
            [
                'name' => 'Applications',
                'data' => $application_counts
            ],
            [
                'name' => 'Deposits',
                'data' => $monthlyDepositApplications
            ]
        ];
        return $spline_chart;
    }

    private function DepositVisas(){

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];


        $monthlyDepositApplications = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m'); // Get the month number

            $application_query = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [4, 5, 6])
                ->where('s.id', '<', 7)
                ->whereYear('deals.created_at', 2023)
                ->whereMonth('deals.created_at', $monthNumber);

            $monthlyDepositApplications[$month] = $application_query->count();
        }

        $monthlyVisas = [];
        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m'); // Get the month number

            $deal_query = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereIn('deals.stage_id', [7, 8, 9])
                ->where('s.id', '<', 10)
                ->whereYear('deals.created_at', 2023)
                ->whereMonth('deals.created_at', $monthNumber);

            $monthlyVisas[$month] = $deal_query->count();
        }


        $spline_chart = [
            [
                'name' => 'Deposits',
                'data' => $monthlyDepositApplications
            ],
            [
                'name' => 'Visas',
                'data' => $monthlyVisas
            ],
        ];
        return $spline_chart;
    }

    private function GetTop3Brands($filter = [1, 2, 3]){
        $topUsers = DB::table('deals as d')
                    ->join('users as u', 'd.created_by', '=', 'u.id')
                    ->join('stages as s', 'd.stage_id', '=', 's.id')
                    ->whereIn('s.id', $filter)
                    ->groupBy('u.id', 'u.name')
                    ->select('u.id as user_id', 'u.name', DB::raw('COUNT(d.id) as total'))
                    ->orderByDesc('total')
                    ->limit(3)
                    ->get()->toArray();

        return $topUsers;
    }




    public function project_dashboard_index()
    {
        $user = Auth::user();
        if (\Auth::user()->can('show project dashboard')) {
            if ($user->type == 'admin') {
                return view('admin.dashboard');
            } else {
                $home_data = [];

                $user_projects   = $user->projects()->pluck('project_id')->toArray();
                $project_tasks   = ProjectTask::whereIn('project_id', $user_projects)->get();
                $project_expense = Expense::whereIn('project_id', $user_projects)->get();
                $seven_days      = Utility::getLastSevenDays();

                // Total Projects
                $complete_project           = $user->projects()->where('status', 'LIKE', 'complete')->count();
                $home_data['total_project'] = [
                    'total' => count($user_projects),
                    'percentage' => Utility::getPercentage($complete_project, count($user_projects)),
                ];

                // Total Tasks
                $complete_task           = ProjectTask::where('is_complete', '=', 1)->whereRaw("find_in_set('" . $user->id . "',assign_to)")->whereIn('project_id', $user_projects)->count();
                $home_data['total_task'] = [
                    'total' => $project_tasks->count(),
                    'percentage' => Utility::getPercentage($complete_task, $project_tasks->count()),
                ];

                // Total Expense
                $total_expense        = 0;
                $total_project_amount = 0;
                foreach ($user->projects as $pr) {
                    $total_project_amount += $pr->budget;
                }
                foreach ($project_expense as $expense) {
                    $total_expense += $expense->amount;
                }
                $home_data['total_expense'] = [
                    'total' => $project_expense->count(),
                    'percentage' => Utility::getPercentage($total_expense, $total_project_amount),
                ];

                // Total Users
                $home_data['total_user'] = Auth::user()->contacts->count();

                // Tasks Overview Chart & Timesheet Log Chart
                $task_overview    = [];
                $timesheet_logged = [];
                foreach ($seven_days as $date => $day) {
                    // Task
                    $task_overview[$day] = ProjectTask::where('is_complete', '=', 1)->where('marked_at', 'LIKE', $date)->whereIn('project_id', $user_projects)->count();

                    // Timesheet
                    $time                   = Timesheet::whereIn('project_id', $user_projects)->where('date', 'LIKE', $date)->pluck('time')->toArray();
                    $timesheet_logged[$day] = str_replace(':', '.', Utility::calculateTimesheetHours($time));
                }

                $home_data['task_overview']    = $task_overview;
                $home_data['timesheet_logged'] = $timesheet_logged;

                // Project Status
                $total_project  = count($user_projects);
                $project_status = [];
                foreach (Project::$project_status as $k => $v) {
                    $project_status[$k]['total']      = $user->projects->where('status', 'LIKE', $k)->count();
                    $project_status[$k]['percentage'] = Utility::getPercentage($project_status[$k]['total'], $total_project);
                }
                $home_data['project_status'] = $project_status;

                // Top Due Project
                $home_data['due_project'] = $user->projects()->orderBy('end_date', 'DESC')->limit(5)->get();

                // Top Due Tasks
                $home_data['due_tasks'] = ProjectTask::where('is_complete', '=', 0)->whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();

                $home_data['last_tasks'] = ProjectTask::whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();

                return view('dashboard.project-dashboard', compact('home_data'));
            }
        } else {
            return $this->account_dashboard_index();
        }
    }

    public function hrm_dashboard_index()
    {


        if (Auth::check()) {

            if (\Auth::user()->can('show hrm dashboard')) {
                $user = Auth::user();

                if ($user->type != 'client' && $user->type != 'company') {
                    $emp = Employee::where('user_id', '=', $user->id)->first();

                    $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $emp->id)->orWhere(function ($q) {
                        $q->where('announcements.department_id', '["0"]')->where('announcements.employee_id', '["0"]');
                    })->get();

                    $employees = Employee::get();
                    $meetings  = Meeting::orderBy('meetings.id', 'desc')->take(5)->leftjoin('meeting_employees', 'meetings.id', '=', 'meeting_employees.meeting_id')->where('meeting_employees.employee_id', '=', $emp->id)->orWhere(function ($q) {
                        $q->where('meetings.department_id', '["0"]')->where('meetings.employee_id', '["0"]');
                    })->get();
                    $events    = Event::leftjoin('event_employees', 'events.id', '=', 'event_employees.event_id')->where('event_employees.employee_id', '=', $emp->id)->orWhere(function ($q) {
                        $q->where('events.department_id', '["0"]')->where('events.employee_id', '["0"]');
                    })->get();

                    $arrEvents = [];
                    foreach ($events as $event) {

                        $arr['id']              = $event['id'];
                        $arr['title']           = $event['title'];
                        $arr['start']           = $event['start_date'];
                        $arr['end']             = $event['end_date'];
                        $arr['backgroundColor'] = $event['color'];
                        $arr['borderColor']     = "#fff";
                        $arr['textColor']       = "white";
                        $arrEvents[]            = $arr;
                    }

                    $date               = date("Y-m-d");
                    $time               = date("H:i:s");
                    $employeeAttendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0)->where('date', '=', $date)->first();

                    $officeTime['startTime'] = Utility::getValByName('company_start_time');
                    $officeTime['endTime']   = Utility::getValByName('company_end_time');

                    return view('dashboard.dashboard', compact('arrEvents', 'announcements', 'employees', 'meetings', 'employeeAttendance', 'officeTime'));
                } else if ($user->type == 'super admin') {
                    $user                       = \Auth::user();
                    $user['total_user']         = $user->countCompany();
                    $user['total_paid_user']    = $user->countPaidCompany();
                    $user['total_orders']       = Order::total_orders();
                    $user['total_orders_price'] = Order::total_orders_price();
                    $user['total_plan']         = Plan::total_plan();
                    $user['most_purchese_plan'] = (!empty(Plan::most_purchese_plan()) ? Plan::most_purchese_plan()->name : '');

                    $chartData = $this->getOrderChart(['duration' => 'week']);

                    return view('dashboard.super_admin', compact('user', 'chartData'));
                } else {
                    $events    = Event::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $arrEvents = [];

                    foreach ($events as $event) {
                        $arr['id']    = $event['id'];
                        $arr['title'] = $event['title'];
                        $arr['start'] = $event['start_date'];
                        $arr['end']   = $event['end_date'];

                        $arr['backgroundColor'] = $event['color'];
                        $arr['borderColor']     = "#fff";
                        $arr['textColor']       = "white";
                        $arr['url']             = route('event.edit', $event['id']);

                        $arrEvents[] = $arr;
                    }


                    $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->where('created_by', '=', \Auth::user()->creatorId())->get();


                    $emp           = User::where('type', '!=', 'client')->where('type', '!=', 'company')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countEmployee = count($emp);

                    $user      = User::where('type', '!=', 'client')->where('type', '!=', 'company')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countUser = count($user);


                    $countTrainer    = Trainer::where('created_by', '=', \Auth::user()->creatorId())->count();
                    $onGoingTraining = Training::where('status', '=', 1)->where('created_by', '=', \Auth::user()->creatorId())->count();
                    $doneTraining    = Training::where('status', '=', 2)->where('created_by', '=', \Auth::user()->creatorId())->count();

                    $currentDate = date('Y-m-d');

                    $employees   = User::where('type', '=', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countClient = count($employees);
                    $notClockIn  = AttendanceEmployee::where('date', '=', $currentDate)->get()->pluck('employee_id');

                    $notClockIns = Employee::where('created_by', '=', \Auth::user()->creatorId())->whereNotIn('id', $notClockIn)->get();
                    $activeJob   = Job::where('status', 'active')->where('created_by', '=', \Auth::user()->creatorId())->count();
                    $inActiveJOb = Job::where('status', 'in_active')->where('created_by', '=', \Auth::user()->creatorId())->count();


                    $meetings = Meeting::where('created_by', '=', \Auth::user()->creatorId())->limit(5)->get();

                    return view('dashboard.dashboard', compact('arrEvents', 'onGoingTraining', 'activeJob', 'inActiveJOb', 'doneTraining', 'announcements', 'employees', 'meetings', 'countTrainer', 'countClient', 'countUser', 'notClockIns', 'countEmployee'));
                }
            } else {
                return redirect()->route('crm.dashboard');
               // return $this->project_dashboard_index();
            }
        } else {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            } else {
                $settings = Utility::settings();
                if ($settings['display_landing_page'] == 'on') {
                    $plans = Plan::get();

                    return view('layouts.landing', compact('plans'));
                } else {
                    return redirect('login');
                }
            }
        }
    }

    // Load Dashboard user's using ajax
    public function filterView(Request $request)
    {
        $usr   = Auth::user();
        $users = User::where('id', '!=', $usr->id);

        if ($request->ajax()) {
            if (!empty($request->keyword)) {
                $users->where('name', 'LIKE', $request->keyword . '%')->orWhereRaw('FIND_IN_SET("' . $request->keyword . '",skills)');
            }

            $users      = $users->get();
            $returnHTML = view('dashboard.view', compact('users'))->render();

            return response()->json([
                'success' => true,
                'html' => $returnHTML,
            ]);
        }
    }

    public function clientView()
    {
        return redirect('/crm-dashboard');
        if (Auth::check()) {
            if (Auth::user()->type == 'super admin') {
                $user                       = \Auth::user();
                $user['total_user']         = $user->countCompany();
                $user['total_paid_user']    = $user->countPaidCompany();
                $user['total_orders']       = Order::total_orders();
                $user['total_orders_price'] = Order::total_orders_price();
                $user['total_plan']         = Plan::total_plan();
                $user['most_purchese_plan'] = (!empty(Plan::most_purchese_plan()) ? Plan::most_purchese_plan()->total : 0);
                $chartData                  = $this->getOrderChart(['duration' => 'week']);

                return view('dashboard.super_admin', compact('user', 'chartData'));
            } elseif (Auth::user()->type == 'client') {
                $transdate   = date('Y-m-d', time());
                $currentYear = date('Y');

                $calenderTasks = [];
                $chartData     = [];
                $arrCount      = [];
                $arrErr        = [];
                $m             = date("m");
                $de            = date("d");
                $y             = date("Y");
                $format        = 'Y-m-d';
                $user          = \Auth::user();
                if (\Auth::user()->can('View Task')) {
                    $company_setting = Utility::settings();
                }
                $arrTemp = [];
                for ($i = 0; $i <= 7 - 1; $i++) {
                    $date                 = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
                    $arrTemp['date'][]    = __(date('D', strtotime($date)));
                    $arrTemp['invoice'][] = 10;
                    $arrTemp['payment'][] = 20;
                }

                $chartData = $arrTemp;

                foreach ($user->clientDeals as $deal) {
                    foreach ($deal->tasks as $task) {
                        $calenderTasks[] = [
                            'title' => $task->name,
                            'start' => $task->date,
                            'url' => route('deals.tasks.show', [
                                $deal->id,
                                $task->id,
                            ]),
                            'className' => ($task->status) ? 'bg-success border-success' : 'bg-warning border-warning',
                        ];
                    }

                    $calenderTasks[] = [
                        'title' => $deal->name,
                        'start' => $deal->created_at->format('Y-m-d'),
                        'url' => route('deals.show', [$deal->id]),
                        'className' => 'deal bg-primary border-primary',
                    ];
                }
                $client_deal = $user->clientDeals->pluck('id');

                $arrCount['deal'] = $user->clientDeals->count();
                if (!empty($client_deal->first())) {
                    $arrCount['task'] = DealTask::whereIn('deal_id', [$client_deal])->count();
                } else {
                    $arrCount['task'] = 0;
                }


                $project['projects']             = Project::where('client_id', '=', Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->where('end_date', '>', date('Y-m-d'))->limit(5)->orderBy('end_date')->get();
                $project['projects_count']       = count($project['projects']);
                $user_projects                   = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
                $tasks                           = ProjectTask::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->get();
                $project['projects_tasks_count'] = count($tasks);
                $project['project_budget']       = Project::where('client_id', Auth::user()->id)->sum('budget');

                $project_last_stages      = Auth::user()->last_projectstage();
                $project_last_stage       = (!empty($project_last_stages) ? $project_last_stages->id : 0);
                $project['total_project'] = Auth::user()->user_project();
                $total_project_task       = Auth::user()->created_total_project_task();
                $allProject               = Project::where('client_id', \Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->get();
                $allProjectCount          = count($allProject);

                $bugs                               = Bug::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->get();
                $project['projects_bugs_count']     = count($bugs);
                $bug_last_stage                     = BugStatus::orderBy('order', 'DESC')->first();
                $completed_bugs                     = Bug::whereIn('project_id', $user_projects)->where('status', $bug_last_stage->id)->where('created_by', \Auth::user()->creatorId())->get();
                $allBugCount                        = count($bugs);
                $completedBugCount                  = count($completed_bugs);
                $project['project_bug_percentage']  = ($allBugCount != 0) ? intval(($completedBugCount / $allBugCount) * 100) : 0;
                $complete_task                      = Auth::user()->project_complete_task($project_last_stage);
                $completed_project                  = Project::where('client_id', \Auth::user()->id)->where('status', 'complete')->where('created_by', \Auth::user()->creatorId())->get();
                $completed_project_count            = count($completed_project);
                $project['project_percentage']      = ($allProjectCount != 0) ? intval(($completed_project_count / $allProjectCount) * 100) : 0;
                $project['project_task_percentage'] = ($total_project_task != 0) ? intval(($complete_task / $total_project_task) * 100) : 0;
                $invoice                            = [];
                $top_due_invoice                    = [];
                $invoice['total_invoice']           = 5;
                $complete_invoice                   = 0;
                $total_due_amount                   = 0;
                $top_due_invoice                    = array();
                $pay_amount                         = 0;

                if (Auth::user()->type == 'client') {
                    if (!empty($project['project_budget'])) {
                        $project['client_project_budget_due_per'] = intval(($pay_amount / $project['project_budget']) * 100);
                    } else {
                        $project['client_project_budget_due_per'] = 0;
                    }
                }

                $top_tasks       = Auth::user()->created_top_due_task();
                $users['staff']  = User::where('created_by', '=', Auth::user()->creatorId())->count();
                $users['user']   = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '!=', 'client')->count();
                $users['client'] = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', 'client')->count();
                $project_status  = array_values(Project::$project_status);
                $projectData     = \App\Models\Project::getProjectStatus();

                $taskData = \App\Models\TaskStage::getChartData();

                return view('dashboard.clientView', compact('calenderTasks', 'arrErr', 'arrCount', 'chartData', 'project', 'invoice', 'top_tasks', 'top_due_invoice', 'users', 'project_status', 'projectData', 'taskData', 'transdate', 'currentYear'));
            }
        }
    }

    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-2 week +1 day");
                for ($i = 0; $i < 14; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach ($arrDuration as $date => $label) {

            $data               = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }

    public function stopTracker(Request $request)
    {
        if (Auth::user()->isClient()) {
            return Utility::error_res(__('Permission denied.'));
        }
        $validatorArray = [
            'name' => 'required|max:120',
            'project_id' => 'required|integer',
        ];
        $validator      = Validator::make(
            $request->all(),
            $validatorArray
        );
        if ($validator->fails()) {
            return Utility::error_res($validator->errors()->first());
        }
        $tracker = TimeTracker::where('created_by', '=', Auth::user()->id)->where('is_active', '=', 1)->first();
        if ($tracker) {
            $tracker->end_time   = $request->has('end_time') ? $request->input('end_time') : date("Y-m-d H:i:s");
            $tracker->is_active  = 0;
            $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
            $tracker->save();

            return Utility::success_res(__('Add Time successfully.'));
        }

        return Utility::error_res('Tracker not found.');
    }

    public function loggedInAsCustomer($id){
        try{
            $sess_check = Session::get('auth_type_id');
            $auth_id = auth()->user()->id;

            if($sess_check != null && $sess_check != ''){
                $auth_id = $sess_check;
            }else{
                Session::put('auth_type_id', $auth_id);
                Session::put('auth_type_created_by', auth()->user()->created_by);
                Session::put('auth_type', auth()->user()->type);
                Session::put('is_company_login', true);
                if(auth()->user()->type == 'super admin'){
                    Session::put('onlyadmin', auth()->user()->type);
                }
            }

            
            // if(auth()->user()->type == 'Project Manager' || auth()->user()->type == 'Project Director'){
            //     Session::put('ProjectController', auth()->user()->type);
            // }
            $user = User::where('id',$id)->first();
            if($user){
                // session()->put('action_clicked_admin',$user->email);
                \Auth::loginUsingId($user->id);
                return redirect()->intended(RouteServiceProvider::HOME);
            }else{
                return redirect()->back()->with('error','User Not Found');
            }
        }catch(Exception $e){

            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function loggedInAsUser($id){

        try{
            $auth_id = auth()->user()->id;

            if(Session::get('auth_type_id') == $id){
                $user = User::where('id',$id)->first();

                Session::flush('auth_type');
                Session::flush('is_company_login');
                Session::flush('auth_type_id');
                Session::flush('auth_type_created_by');

            }
            if($user){
                // session()->put('action_clicked_admin',$user->email);
                \Auth::loginUsingId($user->id);
                return redirect()->intended(RouteServiceProvider::HOME);
            }else{
                return redirect()->back()->with('error','User Not Found');
            }
        }catch(Exception $e){

            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
