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
            return view('chartdashboard.chart');


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
                return view('crm.dashboard', $data);
            } elseif (Auth::user()->type == 'team') {

                $data = $this->superAdminCrmDashboarData();
                return view('chartdashboard.chart');
                // return view('dashboard.crm-dashboard', $data);
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

        $filter_data = BrandsRegionsBranches();

        $chart_data = $this->getChartData();
        $stage_share_data = $this->getStageShareLeads();
        $top_brands = $this->AdmissionTopper();
        $top_countries = $this->GetTop3Countries();
        $deals_stage_share_data = $this->getStageShareDeals();

        
        //getting subcharts
        $sub_chart_visas = $this->getSubChartVisasData();
        $sub_chart_deposit = $this->getSubChartDepositData();
        $sub_chart_applications = $this->getSubChartApplicationsData();
        $sub_chart_admissions = $this->getSubChartAdmissionsData();
        $sub_chart_assignedleads = $this->getSubChartAssignedLeadsData();
        $sub_chart_unassignedleads = $this->getSubChartUnassignedLeadsData();
        $sub_chart_Qualifiedleads = $this->getSubChartQualifiedLeadsData();
        $sub_chart_Unqualifiedleads = $this->getSubChartUnqualifiedLeadsData();





        $data = [
            'filter_data' => $filter_data,
            'chart_data1_json' => json_encode($chart_data),
            'stage_share_data' => json_encode($stage_share_data),
            'top_brands' => json_encode($top_brands),
            'top_countries' => $top_countries,
            'deals_stage_share_data' => json_encode($deals_stage_share_data),

            'sub_chart_deposit' => json_encode($sub_chart_deposit),
            'sub_chart_visas' => json_encode($sub_chart_visas),
            'sub_chart_applications' =>  json_encode($sub_chart_applications),
            'sub_chart_admissions' => json_encode($sub_chart_admissions),
            'sub_chart_assignedleads' => json_encode($sub_chart_assignedleads),
            'sub_chart_unassignedleads' => json_encode($sub_chart_unassignedleads),
            'sub_chart_qualifiedleads' => json_encode($sub_chart_Qualifiedleads),
            'sub_chart_unqualifiedleads' => json_encode($sub_chart_Unqualifiedleads)
        ];

        return view('chartdashboard.chart', $data);
    }

    private function getChartData()
    {
        $datatype = isset($_GET['datatype']) ? $_GET['datatype'] : 'Admission-Applications';

        switch ($datatype) {
            case 'Application-Deposit':
                return $this->getApplicationsDepositData();
            case 'Admission-Deposit':
                return $this->getAdmissionsDepositData();
            case 'Deposit-Visa':
                return $this->getDepositVisasData();
            default:
                return $this->getAdmissionApplicationsData();
        }
    }

    private function getApplicationsDepositData()
    {
        return [
            $this->getApplicationsData(),
            $this->getAdmissionsData('Admissions', [4, 5, 6])
        ];
    }

    private function getAdmissionsDepositData()
    {
        return [
            $this->getAdmissionsData('Admissions'),
            $this->getAdmissionsData('Deposits', [4, 5, 6])
        ];
    }

    private function getDepositVisasData()
    {
        return [
            $this->getAdmissionsData('Deposits', [4, 5, 6]),
            $this->getAdmissionsData('Visas', [7, 8, 9])
        ];
    }

    private function getAdmissionApplicationsData()
    {
        return [
            $this->getAdmissionsData('Admissions'),
            $this->getApplicationsData()
        ];
    }

    private function getApplicationsData()
    {
        // Array of months
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Year to query
        $year = 2024;

        // Initialize empty arrays
        $application_counts = [];

        // Retrieve query parameters
        $brand_id = isset($_GET['brand_id']) ? $_GET['brand_id'] : 0;
        $region_id = isset($_GET['region_id']) ? $_GET['region_id'] : 0;
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 0;


        foreach ($months as $month) {
            // Get the month number
            $monthNumber = Carbon::parse("first day of $month")->format('m');

            // Query for applications
            $applications_query = Deal::join('deal_applications as da', 'da.deal_id', '=', 'deals.id')
                ->whereYear('da.created_at', $year)
                ->whereMonth('da.created_at', $monthNumber);

            if (\Auth::user()->can('level 1') || \Auth::user()->can('level 2')) {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $applications_query->whereIn('deals.brand_id', $brand_ids);
            } else if (\Auth::user()->can('level 3')) {
                $applications_query->where('deals.region_id', \Auth::user()->region_id);
            } else if (\Auth::user()->can('level 4')) {
                $applications_query->where('deals.branch_id', \Auth::user()->branch_id);
            } else {
                $applications_query->where('deals.branch_id', \Auth::user()->branch_id);
            }

            $applications_query->when($brand_id, function ($query, $brand_id) {
                return $query->where('deals.brand_id', $brand_id);
            })
                ->when($region_id, function ($query, $region_id) {
                    return $query->where('deals.region_id', $region_id);
                })
                ->when($branch_id, function ($query, $branch_id) {
                    return $query->where('deals.branch_id', $branch_id);
                });

            // Count applications for the month
            $application_counts[$month] = $applications_query->count();
        }

        return [
            'name' => 'Applications',
            'data' => $application_counts
        ];
    }

    private function getAdmissionsData($name, $stageIds = [])
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $year = 2024;
        $data = [];

        $brand_id = isset($_GET['brand_id']) ? $_GET['brand_id'] : 0;
        $region_id = isset($_GET['region_id']) ? $_GET['region_id'] : 0;
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 0;

        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m');

            $query = Deal::join('stages as s', 'deals.stage_id', '=', 's.id')
                ->whereYear('deals.created_at', $year)
                ->whereMonth('deals.created_at', $monthNumber)
                ->when(!empty($stageIds), function ($query) use ($stageIds) {
                    return $query->whereIn('deals.stage_id', $stageIds);
                });

            if (\Auth::user()->can('level 1') || \Auth::user()->can('level 2')) {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $query->whereIn('deals.brand_id', $brand_ids);
            } else if (\Auth::user()->can('level 3')) {
                $query->where('deals.region_id', \Auth::user()->region_id);
            } else if (\Auth::user()->can('level 4')) {
                $query->where('deals.branch_id', \Auth::user()->branch_id);
            } else {
                $query->where('deals.branch_id', \Auth::user()->branch_id);
            }

            $query->when($brand_id, function ($query, $brand_id) {
                return $query->where('deals.brand_id', $brand_id);
            })
                ->when($region_id, function ($query, $region_id) {
                    return $query->where('deals.region_id', $region_id);
                })
                ->when($branch_id, function ($query, $branch_id) {
                    return $query->where('deals.branch_id', $branch_id);
                });

            // Count applications for the month
            $data[$month] = $query->count();
        }

        return ['name' => $name, 'data' => $data];
    }

    private function getLeadsData($name, $filter)
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $year = 2024;
        $data = [];

        // Get filter values safely
        $brand_id = $this->getSafeFilterValue('brand_id');
        $region_id = $this->getSafeFilterValue('region_id');
        $branch_id = $this->getSafeFilterValue('branch_id');

        foreach ($months as $month) {
            $monthNumber = Carbon::parse("first day of $month")->format('m');

            $query = Lead::whereYear('leads.created_at', $year)
                ->whereMonth('leads.created_at', $monthNumber);

            if (\Auth::user()->can('level 1') || \Auth::user()->can('level 2')) {
                $companies = FiltersBrands();
                $brand_ids = array_keys($companies);
                $query->whereIn('leads.brand_id', $brand_ids);
            } else if (\Auth::user()->can('level 3')) {
                $query->where('leads.region_id', \Auth::user()->region_id);
            } else if (\Auth::user()->can('level 4')) {
                $query->where('leads.branch_id', \Auth::user()->branch_id);
            } else {
                $query->where('leads.branch_id', \Auth::user()->branch_id);
            }

            // Apply filter based on the provided filter type
            if ($filter == 'Unassigned Lead') {
                $query->whereNull('user_id');
            } else if ($filter == 'Qualified Lead') {
                $query->whereIn('stage_id', [5]);
            } else if ($filter == 'Unqualified Lead') {
                $query->whereIn('stage_id', [6]);
            } else { // Unassigned leads
                $query->whereNotNull('user_id');
            }

            // Apply optional filters
            if ($brand_id) {
                $query->where('leads.brand_id', $brand_id);
            }
            if ($region_id) {
                $query->where('leads.region_id', $region_id);
            }
            if ($branch_id) {
                $query->where('leads.branch_id', $branch_id);
            }

            // Count applications for the month
            $data[$month] = $query->count();
        }

        return ['name' => $name, 'data' => $data];
    }

    // Helper function to get safe filter values from $_GET
    private function getSafeFilterValue($key)
    {
        return isset($_GET[$key]) ? intval($_GET[$key]) : null;
    }


    ////////////////////////////////////////Getting subchart details
    private function getSubChartDepositData()
    {
        //Getting Deposit data
        $deposit = $this->getAdmissionsData('Deposits', [4, 5, 6]);

        return [
            'name' => $deposit['name'],
            'data' => $deposit['data'],
            'total' => array_sum($deposit['data'])
        ];
    }

    private function getSubChartVisasData()
    {
        //Getting Deposit data
        $deposit = $this->getAdmissionsData('Visas', [7, 8, 9]);
        return [
            'name' => $deposit['name'],
            'data' => $deposit['data'],
            'total' => array_sum($deposit['data'])
        ];
    }

    private function getSubChartApplicationsData()
    {
        //Getting Deposit data
        $applications = $this->getApplicationsData();
        return [
            'name' => $applications['name'],
            'data' => $applications['data'],
            'total' => array_sum($applications['data'])
        ];
    }

    private function getSubChartAdmissionsData()
    {
        //Getting Deposit data
        $admissions = $this->getAdmissionsData('Admissions');

        return [
            'name' => $admissions['name'],
            'data' => $admissions['data'],
            'total' => array_sum($admissions['data'])
        ];
    }

    private function getSubChartAssignedLeadsData()
    {
        //Getting Deposit data
        $leads = $this->getLeadsData('Assigned Leads', 'Assigned Lead');
        return [
            'name' => $leads['name'],
            'data' => $leads['data'],
            'total' => array_sum($leads['data'])
        ];
    }

    private function getSubChartUnassignedLeadsData()
    {
        //Getting Deposit data
        $leads = $this->getLeadsData('Unassigned Leads', 'Unassigned Lead');
        return [
            'name' => $leads['name'],
            'data' => $leads['data'],
            'total' => array_sum($leads['data'])
        ];
    }


    private function getSubChartQualifiedLeadsData()
    {
        //Getting Deposit data
        $leads = $this->getLeadsData('Qualified Leads', 'Qualified Lead');
        return [
            'name' => $leads['name'],
            'data' => $leads['data'],
            'total' => array_sum($leads['data'])
        ];
    }

    private function getSubChartUnqualifiedLeadsData()
    {
        //Getting Deposit data
        $leads = $this->getLeadsData('Unqualified Leads', 'Unqualified Lead');
        return [
            'name' => $leads['name'],
            'data' => $leads['data'],
            'total' => array_sum($leads['data'])
        ];
    }


    private function getStageShareLeads()
    {
        $year = 2024;

        // Get filter values safely
        $brand_id = $this->getSafeFilterValue('brand_id');
        $region_id = $this->getSafeFilterValue('region_id');
        $branch_id = $this->getSafeFilterValue('branch_id');

        $query = Lead::select(['s.name', DB::raw('count(leads.id) as total')])
            ->join('lead_stages as s', 's.id', '=', 'leads.stage_id')
            ->whereYear('leads.created_at', $year);
        if (\Auth::user()->can('level 1') || \Auth::user()->can('level 2')) {
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $query->whereIn('leads.brand_id', $brand_ids);
        } else if (\Auth::user()->can('level 3')) {
            $query->where('leads.region_id', \Auth::user()->region_id);
        } else if (\Auth::user()->can('level 4')) {
            $query->where('leads.branch_id', \Auth::user()->branch_id);
        } else {
            $query->where('leads.branch_id', \Auth::user()->branch_id);
        }

        if ($brand_id) {
            $query->where('leads.brand_id', $brand_id);
        }
        if ($region_id) {
            $query->where('leads.region_id', $region_id);
        }
        if ($branch_id) {
            $query->where('leads.branch_id', $branch_id);
        }

        $leads = $query->groupBy('leads.stage_id')
            ->orderBy('s.id')->pluck('total', 's.name')->toArray();
        return $leads;
    }

    private function getStageShareDeals()
    {
        $year = 2024;

        // Get filter values safely
        $brand_id = $this->getSafeFilterValue('brand_id');
        $region_id = $this->getSafeFilterValue('region_id');
        $branch_id = $this->getSafeFilterValue('branch_id');

        $query = Deal::select(['s.name', DB::raw('count(deals.id) as total')])
            ->join('stages as s', 's.id', '=', 'deals.stage_id')
            ->whereYear('deals.created_at', $year);

        if (\Auth::user()->can('level 1') || \Auth::user()->can('level 2')) {
            $companies = FiltersBrands();
            $brand_ids = array_keys($companies);
            $query->whereIn('deals.brand_id', $brand_ids);
        } else if (\Auth::user()->can('level 3')) {
            $query->where('deals.region_id', \Auth::user()->region_id);
        } else if (\Auth::user()->can('level 4')) {
            $query->where('deals.branch_id', \Auth::user()->branch_id);
        } else {
            $query->where('deals.branch_id', \Auth::user()->branch_id);
        }

        if ($brand_id) {
            $query->where('deals.brand_id', $brand_id);
        }
        if ($region_id) {
            $query->where('deals.region_id', $region_id);
        }
        if ($branch_id) {
            $query->where('deals.branch_id', $branch_id);
        }

        $deals = $query->groupBy('deals.stage_id')
            ->orderBy('s.id')->pluck('total', 's.name')->toArray();
        return $deals;
    }

    private function AdmissionTopper()
    {
        // if (\Auth::user()->can('level 1') || \Auth::user()->can('level 2')) {
        //    $top = $this->GetTop3Brands();
        // } else if (\Auth::user()->can('level 3')) {

        // } else if (\Auth::user()->can('level 4')) {

        // } else {

        // }
        // echo "<pre>";
        // print_r($this->GetTop3Regions());
        // die();
        return  $this->GetTop3Brands();
    }



    private function GetTop3Regions()
    {
        $results = DB::table(DB::raw('(
                                        SELECT 
                                            CASE 
                                                WHEN @prev_region = region_id THEN @rank
                                                ELSE @rank := @rank + 1
                                            END AS rank,
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

        // Get the top 3 regions
        $top3Regions = array_slice($resultsArray, 0, 3);

        // Calculate the total deals for the remaining regions
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 3), 'total_deals'));

        // Return the top 3 regions and the total count of deals for the remaining regions under "other"
        return [
            'top' => $top3Regions,
            'other' => $totalOtherDeals
        ];
    }


    private function GetTop3Brands()
    {
        $results = DB::table(DB::raw('(
    SELECT 
        CASE 
            WHEN @prev_brand = brand_id THEN @rank
            ELSE @rank := @rank + 1
        END AS `rank`,
        @prev_brand := brand_id AS brand_id,
        brand_name,
        total_deals
    FROM (
        SELECT 
            d.brand_id,
            COALESCE(u.name, "other_brand") AS brand_name,
            COUNT(*) AS total_deals
        FROM deals d
        LEFT JOIN users u ON d.brand_id = u.id
        GROUP BY d.brand_id
        ORDER BY total_deals DESC
    ) AS ranked_brands,
    (SELECT @rank := 0, @prev_brand := NULL) AS vars
) AS ranked_with_ranks'))
->select('brand_name', DB::raw('SUM(total_deals) AS total_deals'))
->groupBy('brand_name')
->orderByDesc('total_deals')
->get();



        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 3 brands
        $top3Brands = array_slice($resultsArray, 0, 3);

        // Calculate the total deals for the remaining brands
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 3), 'total_deals'));

        return [
            'top_brands' => $top3Brands,
            'totalOtherDeal' => $totalOtherDeals
        ];
    }


    private function GetTop3Countries()
    {
        $results = DB::table(DB::raw('(
    SELECT 
        CASE 
            WHEN @prev_country = country THEN @rank
            ELSE @rank := @rank + 1
        END AS `rank`,
        @prev_country := country AS country,
        COALESCE(country, "others") AS name,
        total_deals
    FROM (
        SELECT 
            l.country,
            COUNT(*) AS total_deals
        FROM deals d
        LEFT JOIN leads l ON d.id = l.is_converted
        GROUP BY l.country
        ORDER BY total_deals DESC
    ) AS ranked_countries,
    (SELECT @rank := 0, @prev_country := NULL) AS vars
) AS ranked_with_ranks'))
->select(DB::raw('COALESCE(country, "others") AS country'), DB::raw('SUM(total_deals) AS total_deals'))
->groupBy('country')
->orderByDesc('total_deals')
->get();


        // Convert the stdClass objects to associative arrays for easier manipulation
        $resultsArray = json_decode(json_encode($results), true);

        // Get the top 3 countries
        $top3Countries = array_slice($resultsArray, 0, 3);

        // Calculate the total deals for the remaining countries
        $totalOtherDeals = array_sum(array_column(array_slice($resultsArray, 3), 'total_deals'));

        return [
            'top_countries' => $top3Countries,
            'other' => $totalOtherDeals
        ];
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

    public function loggedInAsCustomer($id)
    {
        try {
            $sess_check = Session::get('auth_type_id');
            $auth_id = auth()->user()->id;

            if ($sess_check != null && $sess_check != '') {
                $auth_id = $sess_check;
            } else {
                Session::put('auth_type_id', $auth_id);
                Session::put('auth_type_created_by', auth()->user()->created_by);
                Session::put('auth_type', auth()->user()->type);
                Session::put('is_company_login', true);
                if (auth()->user()->type == 'super admin') {
                    Session::put('onlyadmin', auth()->user()->type);
                }
            }


            // if(auth()->user()->type == 'Project Manager' || auth()->user()->type == 'Project Director'){
            //     Session::put('ProjectController', auth()->user()->type);
            // }
            $user = User::where('id', $id)->first();
            if ($user) {
                // session()->put('action_clicked_admin',$user->email);
                \Auth::loginUsingId($user->id);
                return redirect('crm-dashboard');
            } else {
                return redirect()->back()->with('error', 'User Not Found');
            }
        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function loggedInAsUser($id)
    {

        try {
            $auth_id = auth()->user()->id;

            if (Session::get('auth_type_id') == $id) {
                $user = User::where('id', $id)->first();

                Session::flush('auth_type');
                Session::flush('is_company_login');
                Session::flush('auth_type_id');
                Session::flush('auth_type_created_by');
            }
            if ($user) {
                // session()->put('action_clicked_admin',$user->email);
                \Auth::loginUsingId($user->id);
                return redirect('crm-dashboard');
            } else {
                return redirect()->back()->with('error', 'User Not Found');
            }
        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
