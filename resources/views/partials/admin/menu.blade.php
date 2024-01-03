@php
use App\Models\Utility;
// $logo=asset(Storage::url('uploads/logo/'));
$logo = \App\Models\Utility::get_file('uploads/logo/');
$company_logo = Utility::getValByName('company_logo_dark');
$company_logos = Utility::getValByName('company_logo_light');
$company_small_logo = Utility::getValByName('company_small_logo');
$setting = \App\Models\Utility::colorset();
$mode_setting = \App\Models\Utility::mode_layout();
$emailTemplate = \App\Models\EmailTemplate::first();
$lang = Auth::user()->lang;

@endphp

<style>
    .emp:hover #icon1 {
        display: none;
    }

    .emp:hover #icon2 {
        display: inline;
    }

    .sidebar .collapse-inner ul .active a {
        color: #2E82D0 !important;
    }

    .sidebar .collapse-inner ul .active #icon1 {
        display: none !important;
    }

    .sidebar .collapse-inner ul .active #icon2 {
        display: inline !important;
    }

    .nav-item #icon2 {
        display: none;
    }
</style>

<div id="wrapper" style="position: relative">

    <!-- <div class="m-header main-logo">
<a href="#" class="b-brand">
{{-- <img src="{{ asset(Storage::url('uploads/logo/'.$logo)) }}" alt="{{ env('APP_NAME') }}" class="logo logo-lg" /> --}}
@if ($mode_setting['cust_darklayout'] && $mode_setting['cust_darklayout'] == 'on')
<img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') }}" alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">
@else
<img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}" alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">

</a>
</div> -->
    @endif
    <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #313949;">
        @if (\Auth::user()->type != 'client')
        <!-- Sidebar -->
        <ul style="list-style: none">
        
            @if (Auth::user()->type == 'company' ||
            Auth::user()->type == 'team' ||
            Gate::check('show hrm dashboard') ||
            Gate::check('show project dashboard') ||
            Gate::check('show crm dashboard') ||
            Gate::check('show account dashboard'))
            <li class="nav-item {{ Request::segment(1) == null ||
                        Request::segment(1) == 'crm-dashboard' ||
                        Request::segment(1) == 'account-dashboard' ||
                        Request::segment(1) == 'income report' ||
                        Request::segment(1) == 'report' ||
                        Request::segment(1) == 'reports-payroll' ||
                        Request::segment(1) == 'reports-leave' ||
                        Request::segment(1) == 'reports-monthly-attendance'
                            ? 'active dash-trigger'
                            : '' }}">

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseone" aria-expanded="true" aria-controls="collapseone">
                    <img src="{{ asset('assets/cs-theme/icons/Group 138.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">

                    <span>{{ __('Dashboard') }}</span>
                </a>
                <div id="collapseone" class="collapse {{ Request::segment(1) == 'crm-dashboard' || Request::segment(1) == 'hrm-dashboard' ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul>
                            @if (Gate::check('show crm dashboard') || Auth::user()->type == 'team' || Auth::user()->type == 'company')
                            <li class="{{ Request::route()->getName() == 'crm.dashboard' ? ' active' : '' }}">
                                <a class="collapse-item" href="{{ route('crm.dashboard') }}" style="color:white; font-size: 13px;">
                                    <i class="fa-solid fa-chart-line me-1" style="color: #ffffff;"></i>
                                    CRM Dashboard</a>
                            </li>
                            @endif
                            {{-- //// --}}
                            @if (\Auth::user()->show_account() == 1 && Gate::check('show account dashboard'))

                            <li class="nav-item  {{ Request::segment(1) == 'account-dashboard' || Request::segment(1) == 'report' ? ' active dash-trigger' : '' }}">

                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">


                                    <span>{{ __('Accounting ') }}</span>
                                </a>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            @can('show account dashboard')
                                            <li class="{{ Request::segment(1) == null || Request::segment(1) == 'account-dashboard' ? ' active' : '' }}">
                                                <a class="collapse-item" href="{{ route('dashboard') }}" style="color:white; font-size: 13px;">{{ __(' Overview') }}</a>
                                            </li>
                                            @endcan
                                            @if (Gate::check('income report') ||
                                            Gate::check('expense report') ||
                                            Gate::check('income vs expense report') ||
                                            Gate::check('tax report') ||
                                            Gate::check('loss & profit report') ||
                                            Gate::check('invoice report') ||
                                            Gate::check('bill report') ||
                                            Gate::check('stock report') ||
                                            Gate::check('invoice report') ||
                                            Gate::check('manage transaction') ||
                                            Gate::check('statement report'))
                                            <li class="nav-item {{ Request::segment(1) == 'report' ? 'active dash-trigger ' : '' }}">
                                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesys" aria-expanded="true" aria-controls="collapsesys">

                                                    <span>{{ __('Reports') }}</span>
                                                </a>
                                                <div id="collapsesys" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                                                    <div class="  collapse-inner rounded">
                                                        <ul>
                                                            @can('expense report')
                                                            <li class="{{ Request::route()->getName() == 'report.expense.summary' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.expense.summary') }}" style="color:white; font-size: 13px;">{{ __('Expense Summary') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('income vs expense report')
                                                            <li class="{{ Request::route()->getName() == 'report.income.vs.expense.summary' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.income.vs.expense.summary') }}" style="color: white; font-size: 13px;">{{ __('Income VS Expense') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('statement report')
                                                            <li class=" {{ Request::route()->getName() == 'report.account.statement' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.account.statement') }}" style="color: white; font-size: 13px;">{{ __('Account Statement') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('invoice report')
                                                            <li class=" {{ Request::route()->getName() == 'report.invoice.summary' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.invoice.summary') }}" style="color: white; font-size: 13px;">{{ __('Invoice Summary') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('bill report')
                                                            <li class=" {{ Request::route()->getName() == 'report.bill.summary' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.bill.summary') }}" style="color: white; font-size: 13px;">{{ __('Bill Summary') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('stock report')
                                                            <li class=" {{ Request::route()->getName() == 'report.product.stock.report' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.product.stock.report') }}" style="color: white; font-size: 13px;">{{ __('Product Stock') }}</a>
                                                            </li>
                                                            @endcan

                                                            @can('loss & profit report')
                                                            <li class=" {{ Request::route()->getName() == 'report.profit.loss.summary' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.profit.loss.summary') }}" style="color: white; font-size: 13px;">{{ __('Profite & Loss') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('manage transaction')
                                                            <li class=" {{ Request::route()->getName() == 'transaction.index' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('transaction.index') }}" style="color: white; font-size: 13px;">{{ __('Transaction') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('income report')
                                                            <li class=" {{ Request::route()->getName() == 'report.income.summary' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.income.summary') }}" style="color: white; font-size: 13px;">{{ __('Income Summary') }}</a>
                                                            </li>
                                                            @endcan
                                                            @can('tax report')
                                                            <li class=" {{ Request::route()->getName() == 'report.tax.summary' ? ' active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.tax.summary') }}" style="color: white; font-size: 13px;">{{ __('Tax Summary') }}</a>
                                                            </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                            {{-- //// --}}
                                            {{-- ///// --}}
                                        </ul>

                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (\Auth::user()->show_hrm() == 1)
                            <li class="d-none {{ \Request::route()->getName() == 'hrm.dashboard' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('hrm.dashboard') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/Layer_1.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">

                                    {{ __('HRM Dashboard') }}</a>
                            </li>

                            <li class="nav-item d-none {{ Request::segment(1) == 'hrm-dashboard' || Request::segment(1) == 'reports-payroll' ? ' active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesys" aria-expanded="true" aria-controls="collapsesys">

                                    <span>{{ __('HRM') }}</span>
                                </a>
                                <div id="collapsesys" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            @can('show hrm dashboard')
                                            <li class="{{ \Request::route()->getName() == 'hrm.dashboard' ? ' active' : '' }}">
                                                <a class="collapse-item" href="{{ route('hrm.dashboard') }}" style="color:white; font-size: 13px;">{{ __(' Overview') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage report')
                                            <li class="nav-item    {{ Request::segment(1) == 'reports-monthly-attendance' ||
                                                            Request::segment(1) == 'reports-leave' ||
                                                            Request::segment(1) == 'reports-payroll'
                                                                ? 'active dash-trigger'
                                                                : '' }}" href="#hr-report" aria-expanded="{{ Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll' ? 'true' : 'false' }}">
                                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesys" aria-expanded="true" aria-controls="collapsesys">

                                                    <span>{{ __('Reports') }}</span>
                                                </a>
                                                <div id="collapsesys" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                                                    <div class="  collapse-inner rounded">
                                                        <ul>

                                                            <li class="{{ request()->is('reports-payroll') ? 'active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.payroll') }}" style="color:white; font-size: 13px;">{{ __(' Payroll') }}</a>
                                                            </li>
                                                            <li class="{{ request()->is('reports-leave') ? 'active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.leave') }}" style="color:white; font-size: 13px;">{{ __(' Leave') }}</a>
                                                            </li>
                                                            <li class="{{ request()->is('reports-monthly-attendance') ? 'active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('report.monthly.attendance') }}" style="color:white; font-size: 13px;">{{ __(' Monthly Attendance') }}</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (\Auth::user()->show_project() == 1)
                            @can('show project dashboard')
                            <li class="{{ Request::route()->getName() == 'project.dashboard' ? ' active' : '' }}">
                                <a class="collapse-item" href="{{ route('project.dashboard') }}" style="color:white; font-size: 13px;">Project</a>
                            </li>
                            @endcan
                            @endif
                            {{-- ///// --}}
                        </ul>

                    </div>
                </div>
            </li>
            @endif
            <!-- Nav Item - Pages Collapse Menu -->
            @if (\Auth::user()->show_hrm() == 1 || Auth::user()->type == 'team')

            @if (Gate::check('manage employee') || Gate::check('manage setsalary'))
            <li class="nav-item d-none {{ Request::segment(1) == 'holiday-calender' ||
                            Request::segment(1) == 'leavetype' ||
                            Request::segment(1) == 'leave' ||
                            Request::segment(1) == 'attendanceemployee' ||
                            Request::segment(1) == 'document-upload' ||
                            Request::segment(1) == 'document' ||
                            Request::segment(1) == 'performanceType' ||
                            Request::segment(1) == 'branch' ||
                            Request::segment(1) == 'department' ||
                            Request::segment(1) == 'designation' ||
                            Request::segment(1) == 'employee' ||
                            Request::segment(1) == 'leave_requests' ||
                            Request::segment(1) == 'holidays' ||
                            Request::segment(1) == 'policies' ||
                            Request::segment(1) == 'leave_calender' ||
                            Request::segment(1) == 'award' ||
                            Request::segment(1) == 'transfer' ||
                            Request::segment(1) == 'resignation' ||
                            Request::segment(1) == 'training' ||
                            Request::segment(1) == 'travel' ||
                            Request::segment(1) == 'promotion' ||
                            Request::segment(1) == 'complaint' ||
                            Request::segment(1) == 'warning' ||
                            Request::segment(1) == 'termination' ||
                            Request::segment(1) == 'announcement' ||
                            Request::segment(1) == 'job' ||
                            Request::segment(1) == 'job-application' ||
                            Request::segment(1) == 'candidates-job-applications' ||
                            Request::segment(1) == 'job-onboard' ||
                            Request::segment(1) == 'custom-question' ||
                            Request::segment(1) == 'interview-schedule' ||
                            Request::segment(1) == 'career' ||
                            Request::segment(1) == 'holiday' ||
                            Request::segment(1) == 'setsalary' ||
                            Request::segment(1) == 'payslip' ||
                            Request::segment(1) == 'paysliptype' ||
                            Request::segment(1) == 'company-policy' ||
                            Request::segment(1) == 'job-stage' ||
                            Request::segment(1) == 'job-category' ||
                            Request::segment(1) == 'terminationtype' ||
                            Request::segment(1) == 'awardtype' ||
                            Request::segment(1) == 'trainingtype' ||
                            Request::segment(1) == 'goaltype' ||
                            Request::segment(1) == 'paysliptype' ||
                            Request::segment(1) == 'allowanceoption' ||
                            Request::segment(1) == 'competencies' ||
                            Request::segment(1) == 'loanoption' ||
                            Request::segment(1) == 'deductionoption'
                                ? 'active dash-trigger'
                                : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true" aria-controls="collapsetwo">

                    <span>{{ __('HRM System') }}</span>
                </a>
                <div id="collapsetwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul>
                            <li class="{{ Request::segment(1) == 'employee' ? 'active dash-trigger' : '' }} ">
                                @if (\Auth::user()->type == 'Employee')
                                @php
                                $employee = App\Models\Employee::where('user_id', \Auth::user()->id)->first();
                                @endphp
                                <a class="collapse-item" href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}" style="color:white; font-size: 13px;">{{ __('Employee') }}</a>
                                @else
                                <a class="collapse-item" href="{{ route('employee.index') }}" style="color:white; font-size: 13px;">
                                    {{ __('Employee Setup') }}</a>
                                @endif
                            </li>
                            @if (Gate::check('manage set salary') || Gate::check('manage pay slip'))
                            <li class="nav-item {{ Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Payroll Setup') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            @can('manage set salary')
                                            <li class="{{ request()->is('setsalary*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('setsalary.index') }}" style="color: white; font-size: 13px;">{{ __('Set salary') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage pay slip')
                                            <li class="{{ request()->is('payslip*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('payslip.index') }}" style="color: white; font-size: 13px;">{{ __('Payslip') }}</a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (Gate::check('manage leave') || Gate::check('manage attendance'))
                            <li class="nav-item {{ Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Leave Management Setup') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            @can('manage leave')
                                            <li class="{{ request()->is('leave.index*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('leave.index') }}" style="color: white; font-size: 13px;">{{ __('Manage Leave') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage attendance')
                                            <li class="nav-item {{ Request::segment(1) == 'attendanceemployee' ? 'active dash-trigger' : '' }}" href="#navbar-attendance" data-toggle="collapse" role="button" aria-expanded="{{ Request::segment(1) == 'attendanceemployee' ? 'true' : 'false' }}">
                                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                                    <span>{{ __('Attendance') }}</span>
                                                </a>
                                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                                    <div class="  collapse-inner rounded">
                                                        <ul>
                                                            <li class="{{ Request::route()->getName() == 'attendanceemployee.index' ? 'active' : '' }}">
                                                                <a class="collapse-item" href="{{ route('attendanceemployee.index') }}" style="color: white; font-size: 13px;">{{ __('Mark Attendance') }}</a>
                                                            </li>
                                                            @can('create attendance')
                                                            <li class="{{ Request::route()->getName() == 'attendanceemployee.bulkattendance' ? 'active' : '' }}">

                                                                <a class="collapse-item" href="{{ route('attendanceemployee.bulkattendance') }}" style="color: white; font-size: 13px;">{{ __('Bulk Attendance') }}</a>
                                                            </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (Gate::check('manage indicator') || Gate::check('manage appraisal') || Gate::check('manage goal tracking'))
                            <li class="nav-item {{ Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking' ? 'active dash-trigger' : '' }}" href="#navbar-performance" data-toggle="collapse" role="button" aria-expanded="{{ Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking' ? 'true' : 'false' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Performance Setup') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul class="{{ Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking' ? 'show' : 'collapse' }}">
                                            @can('manage indicator')
                                            <li class=" {{ request()->is('indicator*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('indicator.index') }}" style="color: white; font-size: 13px;">{{ 'Indicator' }}</a>
                                            </li>
                                            @endcan
                                            @can('manage appraisal')
                                            <li class="{{ request()->is('appraisal*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('appraisal.index') }}" style="color: white; font-size: 13px;">{{ 'Appraisal' }}</a>
                                            </li>
                                            @endcan
                                            @can('manage goal tracking')
                                            <li class="{{ request()->is('goaltracking*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('goaltracking.index') }}" style="color: white; font-size: 13px;">{{ 'Goal Tracking' }}</a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (Gate::check('manage training') || Gate::check('manage trainer') || Gate::check('show training'))
                            <li class="nav-item {{ Request::segment(1) == 'trainer' || Request::segment(1) == 'training' ? 'active dash-trigger' : '' }}" href="#navbar-training" data-toggle="collapse" role="button" aria-expanded="{{ Request::segment(1) == 'trainer' || Request::segment(1) == 'training' ? 'true' : 'false' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Training Setup') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            @can('manage training')
                                            <li class=" {{ request()->is('training*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('training.index') }}" style="color: white; font-size: 13px;">{{ 'Training List' }}</a>
                                            </li>
                                            @endcan
                                            @can('manage trainer')
                                            <li class="{{ request()->is('trainer*') ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('trainer.index') }}" style="color: white; font-size: 13px;">{{ 'Trainer' }}</a>
                                            </li>
                                            @endcan

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (Gate::check('manage job') ||
                            Gate::check('create job') ||
                            Gate::check('manage job application') ||
                            Gate::check('manage custom question') ||
                            Gate::check('show interview schedule') ||
                            Gate::check('show career'))
                            <li class="nav-item {{ Request::segment(1) == 'job' || Request::segment(1) == 'job-application' || Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question' || Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Recruitment Setup') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            @can('manage job')
                                            <li class=" {{ Request::route()->getName() == 'job.index' || Request::route()->getName() == 'job.create' || Request::route()->getName() == 'job.edit' || Request::route()->getName() == 'job.show' ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('job.index') }}">{{ __('Jobs') }}</a>
                                            </li>
                                            @endcan
                                            @can('create job')
                                            <li class=" {{ Request::route()->getName() == 'job.create' ? 'active' : '' }} ">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('job.create') }}">{{ __('Job Create') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage job application')
                                            <li class="{{ request()->is('job-application*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('job-application.index') }}">{{ __('Job Application') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage job application')
                                            <li class=" {{ request()->is('candidates-job-applications') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('job.application.candidate') }}">{{ __('Job Candidate') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage job application')
                                            <li class=" {{ request()->is('job-onboard*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('job.on.board') }}">{{ __('Job On-boarding') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage custom question')
                                            <li class="  {{ request()->is('custom-question*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('custom-question.index') }}">{{ __('Custom Question') }}</a>
                                            </li>
                                            @endcan
                                            @can('show interview schedule')
                                            <li class=" {{ request()->is('interview-schedule*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('interview-schedule.index') }}">{{ __('Interview Schedule') }}</a>
                                            </li>
                                            @endcan
                                            @can('show career')
                                            <li class=" {{ request()->is('career*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('career', [\Auth::user()->creatorId(), $lang]) }}">{{ __('Career') }}</a>
                                            </li>
                                            @endcan

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            {{-- ///  --}}
                            @if (Gate::check('manage award') ||
                            Gate::check('manage transfer') ||
                            Gate::check('manage resignation') ||
                            Gate::check('manage travel') ||
                            Gate::check('manage promotion') ||
                            Gate::check('manage complaint') ||
                            Gate::check('manage warning') ||
                            Gate::check('manage termination') ||
                            Gate::check('manage announcement') ||
                            Gate::check('manage holiday'))
                            <li class="nav-item {{ Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'holiday' || Request::segment(1) == 'policies' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'travel' || Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'announcement' || Request::segment(1) == 'competencies' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities" href="#">{{ __('HR Admin Setup') }}</a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            @can('manage award')
                                            <li class=" {{ request()->is('award*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('award.index') }}">{{ __('Award') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage transfer')
                                            <li class="  {{ request()->is('transfer*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('transfer.index') }}">{{ __('Transfer') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage resignation')
                                            <li class=" {{ request()->is('resignation*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('resignation.index') }}">{{ __('Resignation') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage travel')
                                            <li class=" {{ request()->is('travel*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('travel.index') }}">{{ __('Trip') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage promotion')
                                            <li class=" {{ request()->is('promotion*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('promotion.index') }}">{{ __('Promotion') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage complaint')
                                            <li class=" {{ request()->is('complaint*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('complaint.index') }}">{{ __('Complaints') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage warning')
                                            <li class=" {{ request()->is('warning*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('warning.index') }}">{{ __('Warning') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage termination')
                                            <li class=" {{ request()->is('termination*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('termination.index') }}">{{ __('Termination') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage announcement')
                                            <li class=" {{ request()->is('announcement*') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('announcement.index') }}">{{ __('Announcement') }}</a>
                                            </li>
                                            @endcan
                                            @can('manage holiday')
                                            <li class=" {{ request()->is('holiday*') || request()->is('holiday-calender') ? 'active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('holiday.index') }}">{{ __('Holidays') }}</a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>

                            </li>
                            @endif

                            @can('manage event')
                            <li class="{{ request()->is('event*') ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('event.index') }}">{{ __('Event Setup') }}</a>
                            </li>
                            @endcan
                            @can('manage meeting')
                            <li class="{{ request()->is('meeting*') ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('meeting.index') }}">{{ __('Meeting') }}</a>
                            </li>
                            @endcan
                            @can('manage assets')
                            <li class=" {{ request()->is('account-assets*') ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('account-assets.index') }}">{{ __('Employees Asset Setup ') }}</a>
                            </li>
                            @endcan
                            @can('manage document')
                            <li class=" {{ request()->is('document-upload*') ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('document-upload.index') }}">{{ __('Document Setup') }}</a>
                            </li>
                            @endcan
                            @can('manage company policy')
                            <li class=" {{ request()->is('company-policy*') ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('company-policy.index') }}">{{ __('Company policy') }}</a>
                            </li>
                            @endcan
                            <li class="{{ Request::segment(1) == 'leavetype' ||
                                            Request::segment(1) == 'document' ||
                                            Request::segment(1) == 'performanceType' ||
                                            Request::segment(1) == 'branch' ||
                                            Request::segment(1) == 'department' ||
                                            Request::segment(1) == 'designation' ||
                                            Request::segment(1) == 'job-stage' ||
                                            Request::segment(1) == 'performanceType' ||
                                            Request::segment(1) == 'job-category' ||
                                            Request::segment(1) == 'terminationtype' ||
                                            Request::segment(1) == 'awardtype' ||
                                            Request::segment(1) == 'trainingtype' ||
                                            Request::segment(1) == 'goaltype' ||
                                            Request::segment(1) == 'paysliptype' ||
                                            Request::segment(1) == 'allowanceoption' ||
                                            Request::segment(1) == 'loanoption' ||
                                            Request::segment(1) == 'deductionoption'
                                                ? 'active dash-trigger'
                                                : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('branch.index') }}">{{ __('HRM System Setup') }}</a>
                            </li>
                            {{-- ////  --}}
                        </ul>
                    </div>
                </div>
            </li>
            @endif
            @endif
            <!--------------------- End HRM ----------------------------------->
            <!--------------------- Start Account ----------------------------------->
            @if (\Auth::user()->show_account() == 1)
            @if (Gate::check('manage customer') ||
            Gate::check('manage vender') ||
            Gate::check('manage customer') ||
            Gate::check('manage vender') ||
            Gate::check('manage proposal') ||
            Gate::check('manage bank account') ||
            Gate::check('manage bank transfer') ||
            Gate::check('manage invoice') ||
            Gate::check('manage revenue') ||
            Gate::check('manage credit note') ||
            Gate::check('manage bill') ||
            Gate::check('manage payment') ||
            Gate::check('manage debit note') ||
            Gate::check('manage chart of account') ||
            Gate::check('manage journal entry') ||
            Gate::check('balance sheet report') ||
            Gate::check('ledger report') ||
            Gate::check('trial balance report'))
            <li class="nav-item
                                        {{ Request::route()->getName() == 'print-setting' ||
                                        Request::segment(1) == 'customer' ||
                                        Request::segment(1) == 'vender' ||
                                        Request::segment(1) == 'proposal' ||
                                        Request::segment(1) == 'bank-account' ||
                                        Request::segment(1) == 'bank-transfer' ||
                                        Request::segment(1) == 'invoice' ||
                                        Request::segment(1) == 'revenue' ||
                                        Request::segment(1) == 'credit-note' ||
                                        Request::segment(1) == 'taxes' ||
                                        Request::segment(1) == 'product-category' ||
                                        Request::segment(1) == 'product-unit' ||
                                        Request::segment(1) == 'payment-method' ||
                                        Request::segment(1) == 'custom-field' ||
                                        Request::segment(1) == 'chart-of-account-type' ||
                                        (Request::segment(1) == 'transaction' &&
                                            Request::segment(2) != 'ledger' &&
                                            Request::segment(2) != 'balance-sheet' &&
                                            Request::segment(2) != 'trial-balance') ||
                                        Request::segment(1) == 'goal' ||
                                        Request::segment(1) == 'budget' ||
                                        Request::segment(1) == 'chart-of-account' ||
                                        Request::segment(1) == 'journal-entry' ||
                                        Request::segment(2) == 'ledger' ||
                                        Request::segment(2) == 'balance-sheet' ||
                                        Request::segment(2) == 'trial-balance' ||
                                        Request::segment(1) == 'bill' ||
                                        Request::segment(1) == 'payment' ||
                                        Request::segment(1) == 'debit-note'
                                            ? ' active dash-trigger'
                                            : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapsethree">
                    <span>{{ __('Accounting System ') }}</span>
                </a>
                <div id="collapsethree" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul>

                            @if (Gate::check('manage customer'))
                            <li class=" {{ Request::segment(1) == 'customer' ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('customer.index') }}">{{ __('Customer') }}</a>
                            </li>
                            @endif
                            @if (Gate::check('manage vender'))
                            <li class=" {{ Request::segment(1) == 'vender' ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('vender.index') }}">{{ __('Vendor') }}</a>
                            </li>
                            @endif
                            @if (Gate::check('manage proposal'))
                            <li class=" {{ Request::segment(1) == 'proposal' ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('proposal.index') }}">{{ __('Proposal') }}</a>
                            </li>
                            @endif
                            @if (Gate::check('manage bank account') || Gate::check('manage bank transfer'))
                            <li class="nav-item {{ Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Banking') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            <li class="{{ Request::route()->getName() == 'bank-account.index' || Request::route()->getName() == 'bank-account.create' || Request::route()->getName() == 'bank-account.edit' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('bank-account.index') }}">{{ __('Account') }}</a>
                                            </li>
                                            <li class="{{ Request::route()->getName() == 'bank-transfer.index' || Request::route()->getName() == 'bank-transfer.create' || Request::route()->getName() == 'bank-transfer.edit' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('bank-transfer.index') }}">{{ __('Transfer') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </li>
                            @endif
                            @if (Gate::check('manage invoice') || Gate::check('manage revenue') || Gate::check('manage credit note'))
                            <li class="nav-item {{ Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Income') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            <li class="nav-item{{ Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a>
                                            </li>
                                            <li class="nav-item{{ Request::route()->getName() == 'revenue.index' || Request::route()->getName() == 'revenue.create' || Request::route()->getName() == 'revenue.edit' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('revenue.index') }}">{{ __('Revenue') }}</a>
                                            </li>
                                            <li class="nav-item{{ Request::route()->getName() == 'credit.note' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('credit.note') }}">{{ __('Credit Note') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (Gate::check('manage bill') || Gate::check('manage payment') || Gate::check('manage debit note'))
                            <li class="nav-item {{ Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Expense') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            <li class="nav-item{{ Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('bill.index') }}">{{ __('Bill') }}</a>
                                            </li>
                                            <li class="nav-item{{ Request::route()->getName() == 'payment.index' || Request::route()->getName() == 'payment.create' || Request::route()->getName() == 'payment.edit' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('payment.index') }}">{{ __('Payment') }}</a>
                                            </li>
                                            <li class="nav-item {{ Request::route()->getName() == 'debit.note' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('debit.note') }}">{{ __('Debit Note') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </li>
                            @endif
                            @if (Gate::check('manage chart of account') ||
                            Gate::check('manage journal entry') ||
                            Gate::check('balance sheet report') ||
                            Gate::check('ledger report') ||
                            Gate::check('trial balance report'))
                            <li class="nav-item {{ Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' || Request::segment(2) == 'balance-sheet' || Request::segment(2) == 'trial-balance' ? 'active dash-trigger' : '' }}">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                                    <span>{{ __('Double Entry') }}</span>
                                </a>
                                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>
                                            <li class="{{ Request::route()->getName() == 'chart-of-account.index' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('chart-of-account.index') }}">{{ __('Chart of Accounts') }}</a>
                                            </li>
                                            <li class="{{ Request::route()->getName() == 'journal-entry.edit' || Request::route()->getName() == 'journal-entry.create' || Request::route()->getName() == 'journal-entry.index' || Request::route()->getName() == 'journal-entry.show' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('journal-entry.index') }}">{{ __('Journal Account') }}</a>
                                            </li>
                                            <li class="{{ Request::route()->getName() == 'report.ledger' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('report.ledger') }}">{{ __('Ledger Summary') }}</a>
                                            </li>
                                            <li class="{{ Request::route()->getName() == 'report.balance.sheet' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('report.balance.sheet') }}">{{ __('Balance Sheet') }}</a>
                                            </li>
                                            <li class="{{ Request::route()->getName() == 'trial.balance' ? ' active' : '' }}">
                                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('trial.balance') }}">{{ __('Trial Balance') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if (\Auth::user()->type == 'company')
                            <li class=" {{ Request::segment(1) == 'budget' ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('budget.index') }}">{{ __('Budget Planner') }}</a>
                            </li>
                            @endif
                            @if (Gate::check('manage goal'))
                            <li class=" {{ Request::segment(1) == 'goal' ? 'active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('goal.index') }}">{{ __('Financial Goal') }}</a>
                            </li>
                            @endif
                            @if (Gate::check('manage constant tax') ||
                            Gate::check('manage constant category') ||
                            Gate::check('manage constant unit') ||
                            Gate::check('manage constant payment method') ||
                            Gate::check('manage constant custom field'))
                            <li class=" {{ Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' ? 'active dash-trigger' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('taxes.index') }}">{{ __('Accounting Setup') }}</a>
                            </li>
                            @endif

                            @if (Gate::check('manage print settings'))
                            <li class=" {{ Request::route()->getName() == 'print-setting' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('print.setting') }}">{{ __('Print Settings') }}</a>
                            </li>
                            @endif

                        </ul>
            </li>
            @endif
            @endif
            <!--------------------- End Account ----------------------------------->
            <!--------------------- Start CRM ----------------------------------->
            @if (\Auth::user()->show_crm() == 1 || Auth::user()->type == 'team')

            @if (Gate::check('manage lead') ||
            Gate::check('manage deal') ||
            Gate::check('manage form builder') ||
            Gate::check('manage contract'))

            <li class="nav-item
                                         {{ Request::segment(1) == 'stages' ||
                                         Request::segment(1) == 'labels' ||
                                         Request::segment(1) == 'sources' ||
                                         Request::segment(1) == 'lead_stages' ||
                                         Request::segment(1) == 'pipelines' ||
                                         Request::segment(1) == 'deals' ||
                                         Request::segment(1) == 'leads' ||
                                         Request::segment(1) == 'form_builder' ||
                                         Request::segment(1) == 'form_response' ||
                                         Request::segment(1) == 'contract'
                                             ? ' active dash-trigger'
                                             : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefour" aria-expanded="true" aria-controls="collapsefour">
                    <img src="{{ asset('assets/cs-theme/icons/quantity-2 1.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                    <span>{{ __('CRM System') }}</span>
                </a>
                <div id="collapsefour" class="collapse {{ Request::segment(1) == 'deals' || Request::segment(1) == 'leads' || Request::segment(1) == 'applications' || Request::segment(1) == 'clients' || Request::segment(1) == 'university' || Request::segment(1) == 'organization' || Request::segment(1) == 'company-permission' ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul class="
                                             {{ Request::segment(1) == 'stages' ||
                                             Request::segment(1) == 'labels' ||
                                             Request::segment(1) == 'sources' ||
                                             Request::segment(1) == 'university' ||
                                             Request::segment(1) == 'lead_stages' ||
                                             Request::segment(1) == 'leads' ||
                                             Request::segment(1) == 'form_builder' ||
                                             Request::segment(1) == 'course' ||
                                             Request::segment(1) == 'form_response' ||
                                             Request::segment(1) == 'deals' ||
                                             Request::segment(1) == 'pipelines'
                                                 ? 'show'
                                                 : '' }}">

                            @can('manage task')
                            <li class="emp nav-item {{ Request::route()->getName() == 'deals.get.user.tasks' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('deals.get.user.tasks') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/to-do-list-13177 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/taskblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                                    {{ __('Tasks') }}</a>
                            </li>
                            @endcan

                            @can('manage lead')
                            <li class="emp nav-item {{ Request::route()->getName() == 'leads.list' || Request::route()->getName() == 'leads.index' || Request::route()->getName() == 'leads.show' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('leads.list') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/Layer_1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/leadsblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                                    {{ __('Leads') }}</a>
                            </li>
                            @endcan



                            @can('manage deal')
                            <li class="emp nav-item {{ Request::route()->getName() == 'deals.list' || Request::route()->getName() == 'deals.index' || Request::route()->getName() == 'deals.show' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('deals.list') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/edit-icon 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/admiblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">
                                    Admissions</a>
                            </li>
                            @endcan


                            @can('manage application')
                            <li class="emp nav-item {{ Request::route()->getName() == 'applications.index' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('applications.index') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/result-pass-icon 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/appblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                                    Applications</a>
                            </li>
                            @endcan

                            @can('manage client')
                            <li class="emp nav-item {{ Request::route()->getName() == 'clients.index' || Request::segment(1) == 'clients' || Request::route()->getName() == 'clients.edit' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('clients.index') }}">
                                    {{-- <img src="{{ asset('assets/cs-theme/icons/Layer_1 (1).png') }}"
                                    id="icon1" width="15px" height="15px"
                                    style="margin-top:-10px" alt="" srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/callblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset=""> --}}
                                    <i class="fa-solid fa-address-card pe-1" id="icon1" style="color: #ffffff;font-size:15px;"></i>
                                    <i class="fa-solid fa-address-card pe-1" id="icon2" style="color: #2e82d0;font-size:15px;"></i>

                                    {{ __('Contacts') }}</a>
                            </li>
                            @endcan

                            {{-- Applicaitons missed --}}

                            @can('manage form builder')
                            {{-- <li class=" {{ (Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response')?'active open':''}}">
                            <a class="collapse-item" style="color: white; font-size: 13px;" href="{{route('form_builder.index')}}">{{__('Form Builder')}}</a>
            </li> --}}
            @endcan


            @can('manage university')
            <li class="emp nav-item {{ Request::route()->getName() == 'university.list' || Request::route()->getName() == 'university.index' || Request::route()->getName() == 'university.show' ? ' active' : '' }}">
                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('university.index') }}">
                    <img src="{{ asset('assets/cs-theme/icons/Layer_1 (7).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                    <img src="{{ asset('assets/cs-theme/icons/toolkitblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                    {{ __('Toolkit') }}</a>
            </li>
            @endcan

            @can('manage courses')
            {{-- <li class=" {{ (Request::route()->getName() == 'course.list' || Request::route()->getName() == 'course.index' || Request::route()->getName() == 'course.show') ? ' active' : '' }}">
            <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('course.index') }}">{{__('Courses')}}</a>
            </li> --}}
            @endcan




            @can('manage organization')
            <li class="d-none emp nav-item {{ Request::route()->getName() == 'organizaiton.list' || Request::route()->getName() == 'organization.index' || Request::route()->getName() == 'organization.show' ? ' active' : '' }}">
                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('organization.index') }}">
                    <img src="{{ asset('assets/cs-theme/icons/organization-01-1 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                    <img src="{{ asset('assets/cs-theme/icons/orgblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                    {{ __('Organizations') }}</a>
            </li>
            @endcan


            @if (\Auth::user()->type == 'company')
            <li class=" d-none" style="display: none;" {{ Request::route()->getName() == 'contract.index' || Request::route()->getName() == 'contract.show' ? 'active' : '' }}">
                <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('contract.index') }}">{{ __('Contract') }}</a>
            </li>
            @endif




        </ul>
</div>
</div>
</li>
@endif
@endif
<!--------------------- End CRM ----------------------------------->
<!--------------------- Start Project ----------------------------------->
@if (\Auth::user()->show_project() == 1)
@if (Gate::check('manage project'))
<li class="nav-item
                                             {{ Request::segment(1) == 'project' ||
                                             Request::segment(1) == 'bugs-report' ||
                                             Request::segment(1) == 'bugstatus' ||
                                             Request::segment(1) == 'project-task-stages' ||
                                             Request::segment(1) == 'calendar' ||
                                             Request::segment(1) == 'timesheet-list' ||
                                             Request::segment(1) == 'taskboard' ||
                                             Request::segment(1) == 'timesheet-list' ||
                                             Request::segment(1) == 'taskboard' ||
                                             Request::segment(1) == 'project' ||
                                             Request::segment(1) == 'projects' ||
                                             Request::segment(1) == 'project_report'
                                                 ? 'active dash-trigger'
                                                 : '' }}">

    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive">
        <span>{{ __('Project System') }}</span>
    </a>
    <div id="collapsefive" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">

            <ul>
                @can('manage project')
                <li class="  {{ Request::segment(1) == 'project' || Request::route()->getName() == 'projects.list' || Request::route()->getName() == 'projects.list' || Request::route()->getName() == 'projects.index' || Request::route()->getName() == 'projects.show' || request()->is('projects/*') ? 'active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                </li>
                @endcan
                @can('manage project task')
                <li class=" {{ request()->is('taskboard*') ? 'active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('taskBoard.view', 'list') }}">
                        <img src="{{ asset('assets/cs-theme/icons/to-do-list-13177 1.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                        {{ __('Tasks') }}</a>
                </li>
                @endcan
                @can('manage timesheet')
                <li class=" {{ request()->is('timesheet-list*') ? 'active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('timesheet.list') }}">{{ __('Timesheet') }}</a>
                </li>
                @endcan
                @can('manage bug report')
                <li class=" {{ request()->is('bugs-report*') ? 'active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('bugs.view', 'list') }}">{{ __('Bug') }}</a>
                </li>
                @endcan
                @can('manage project task')
                <li class=" {{ request()->is('calendar*') ? 'active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('task.calendar', ['all']) }}">{{ __('Task Calendar') }}</a>
                </li>
                @endcan
                @if (\Auth::user()->type != 'super admin')
                <li class="  {{ Request::segment(1) == 'time-tracker' ? 'active open' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('time.tracker') }}">{{ __('Tracker') }}</a>
                </li>
                @endif
                @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
                <li class="  {{ Request::route()->getName() == 'project_report.index' || Request::route()->getName() == 'project_report.show' ? 'active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('project_report.index') }}">{{ __('Project Report') }}</a>
                </li>
                @endif

                @if (Gate::check('manage project task stage') || Gate::check('manage bug status'))
                <li class=" nav-item {{ Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages' ? 'active dash-trigger' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesix" aria-expanded="true" aria-controls="collapsesix">
                        <span>Project System Setup</span>
                    </a>
                    <div id="collapsesix" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="  collapse-inner rounded">
                            <ul>
                                @can('manage project task stage')
                                <li class="  {{ Request::route()->getName() == 'project-task-stages.index' ? 'active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('project-task-stages.index') }}">{{ __('Project Task Stages') }}</a>
                                </li>
                                @endcan
                                @can('manage bug status')
                                <li class=" {{ Request::route()->getName() == 'bugstatus.index' ? 'active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('bugstatus.index') }}">{{ __('Bug Status') }}</a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</li>
@endif
@endif
<!--------------------- End Project ----------------------------------->
<!--------------------- Start User Managaement System ----------------------------------->
@if (
\Auth::user()->type != 'super admin' &&
(Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage client')))
<li class="nav-item
                        {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'clients' ? ' active dash-trigger' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseseven" aria-expanded="true" aria-controls="collapseseven">
        <span>
            <img src="{{ asset('assets/cs-theme/icons/Vector (2).png') }}" width="14px" height="14px" style="margin-top:-8px" alt="" srcset="">


            {{ __('Users') }}</span>
    </a>
    <div id="collapseseven" class="collapse {{Request::segment(1) == 'branch' || Request::segment(1) == 'user' && Request::segment(2) == 'employees' ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">
            <ul>
                @can('manage branch')
                <li class="emp nav-item {{ Request::route()->getName() == 'branch.index' || Request::route()->getName() == 'branch.edit' || Request::route()->getName() == 'branch.show' ? ' active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('branch.index') }}">
                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (3).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/branchesblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        {{ __('Branches') }}</a>
                </li>
                @endcan
                <li class="emp nav-item{{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('user.employees') }}">
                        <img src="{{ asset('assets/cs-theme/icons/Vector (1).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/employeeblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        Employees</a>
                </li>

            </ul>
        </div>
    </div>
</li>
@endif
<!--------------------- End User Managaement System----------------------------------->
<!--------------------- Start Products System ----------------------------------->
@if (Gate::check('manage product & service') || Gate::check('manage product & service'))
<li class="nav-item d-none">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseeight" aria-expanded="true" aria-controls="collapseeight">
        <span>{{ __('Products') }}</span>
    </a>
    <div id="collapseeight" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">
            <ul>
                @if (Gate::check('manage product & service'))
                <li class=" {{ Request::segment(1) == 'productservice' ? 'active' : '' }}">
                    <a href="{{ route('productservice.index') }}" class="collapse-item" style="color: white; font-size: 13px;">{{ __('Product & Services') }}
                    </a>
                </li>
                @endif
                @if (Gate::check('manage product & service'))
                <li class=" {{ Request::segment(1) == 'productstock' ? 'active' : '' }}">
                    <a href="{{ route('productstock.index') }}" class="collapse-item" style="color: white; font-size: 13px;">{{ __('Product Stock') }}
                    </a>
                </li>
                @endif

            </ul>
        </div>
    </div>
</li>
@endif
<!--------------------- End Products System ----------------------------------->
<!--------------------- Start POs System ----------------------------------->
@if (\Auth::user()->show_pos() == 1)
@if (Gate::check('manage warehouse') ||
Gate::check('manage purchase') ||
Gate::check('manage pos') ||
Gate::check('manage print settings'))
<li class="d-none nav-item {{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' || Request::route()->getName() == 'pos.show' ? ' active dash-trigger' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsenine" aria-expanded="true" aria-controls="collapsenine">
        <span>{{ __('POS System') }}</span>
    </a>
    <div id="collapsenine" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">

            <ul class="{{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' || Request::route()->getName() == 'pos.show' ? 'show' : '' }}">
                @can('manage warehouse')
                <li class=" {{ Request::route()->getName() == 'warehouse.index' || Request::route()->getName() == 'warehouse.show' ? ' active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('warehouse.index') }}">{{ __('Warehouse') }}</a>
                </li>
                @endcan
                @can('manage purchase')
                <li class=" {{ Request::route()->getName() == 'purchase.index' || Request::route()->getName() == 'purchase.create' || Request::route()->getName() == 'purchase.edit' || Request::route()->getName() == 'purchase.show' ? ' active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('purchase.index') }}">{{ __('Purchase') }}</a>
                </li>
                @endcan
                @can('manage pos')
                <li class=" {{ Request::route()->getName() == 'pos.index' ? ' active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('pos.index') }}">{{ __(' Add POS') }}</a>
                </li>

                <li class=" {{ Request::route()->getName() == 'pos.report' || Request::route()->getName() == 'pos.show' ? ' active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('pos.report') }}">{{ __('POS') }}</a>
                </li>
                @endcan
                @can('create barcode')
                <li class=" {{ Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' ? ' active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('pos.barcode') }}">{{ __('Print Barcode') }}</a>
                </li>
                @endcan
                @can('manage pos')
                <li class=" {{ Request::route()->getName() == 'pos-print-setting' ? ' active' : '' }}">
                    <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('pos.print.setting') }}">{{ __('Print Settings') }}</a>
                </li>
                @endcan

            </ul>
        </div>
    </div>
</li>
@endif
@endif
<!--------------------- End POs System ----------------------------------->
@if (\Auth::user()->type != 'super admin')
<li class="nav-item {{ Request::segment(1) == 'support' ? 'active' : '' }}">
    <a href="{{ route('support.index') }}" class="nav-link">
        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (4).png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">

        <span>{{ __('Support') }}</span>
    </a>

</li>
<li class="d-none nav-item {{ Request::segment(1) == 'zoom-meeting' || Request::segment(1) == 'zoom-meeting-calender' ? 'active' : '' }}">
    <a href="{{ route('zoom-meeting.index') }}" class="nav-link">
        <span>{{ __('Zoom Meeting') }}</span>
    </a>
</li>
<li class="d-none nav-item {{ Request::segment(1) == 'chats' ? 'active' : '' }}">
    <a href="{{ url('chats') }}" class="nav-link">
        <span>{{ __('Messenger') }}</span>
    </a>
</li>
@endif
<!--------------------- Start System Setup ----------------------------------->
@if (\Auth::user()->type != 'super admin')

@if (Gate::check('manage company plan') || Gate::check('manage order') || Gate::check('manage company settings'))
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseten" aria-expanded="true" aria-controls="collapseten">
        <img src="{{ asset('assets/cs-theme/icons/settings-3110 1.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">

        <span>{{ __('Settings') }}</span>
    </a>
    <div id="collapseten" class="collapse {{Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' || Request::segment(1) == 'settings' ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">
            <ul>
                @if (Gate::check('manage company settings'))
                <li class="emp {{ Request::segment(1) == 'settings' ? ' active' : '' }}">
                    <a href="{{ route('settings') }}" class="collapse-item" style="color: white; font-size: 13px;">
                        <i class="fa-solid fa-gears" id="icon1" style="color: #ffffff;font-size: 15px;"></i>

                        <i class="fa-solid fa-gears" id="icon2" style="color: #2e82d0;font-size: 15px;"></i>
                        {{ __('System Settings') }}</a>
                </li>
                @endif
                @if (Gate::check('manage company plan'))
                <li class="{{ Request::route()->getName() == 'plans.index' || Request::route()->getName() == 'stripe' ? ' active' : '' }}">
                    <a href="{{ route('plans.index') }}" class="collapse-item" style="color: white; font-size: 13px;">{{ __('Setup Subscription Plan') }}</a>
                </li>
                @endif

                @if (Gate::check('manage order') && Auth::user()->type == 'company')
                <li class=" {{ Request::segment(1) == 'order' ? 'active' : '' }}">
                    <a href="{{ route('order.index') }}" class="collapse-item" style="color: white; font-size: 13px;">{{ __('Order') }}</a>
                </li>
                @endif
                <li style="" class="emp nav-item {{ Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' ? 'active dash-trigger' : '' }}">

                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('pipelines.index') }}   ">
                        <img src="{{ asset('assets/cs-theme/icons/administrator-developer-icon 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/crmsysblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        {{ __('CRM System Setup') }}</a>
                </li>
            </ul>
        </div>
    </div>
</li>
@endif
@endif
</ul>
@endif

@if (\Auth::user()->type == 'client')
<ul style="background-color: #313949;">
    @if (Gate::check('manage client dashboard'))
    <li class="nav-item  {{ Request::segment(1) == 'dashboard' ? ' active' : '' }}">
        <a href="{{ route('client.dashboard.view') }}" class="nav-link">
            <i class="fa-solid fa-chart-line" style="color: #ffffff;"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>
    </li>
    @endif
    @if (Gate::check('manage deal'))
    <li class="nav-item {{ Request::segment(1) == 'deals' ? ' active' : '' }}">
        <a href="{{ route('deals.index') }}" class="nav-link">
            <span>{{ __('Deals') }}</span>
        </a>
    </li>
    @endif
    @if (Gate::check('manage contract'))
    <li class="nav-item {{ Request::route()->getName() == 'contract.index' || Request::route()->getName() == 'contract.show' ? 'active' : '' }}">
        <a href="{{ route('contract.index') }}" class="nav-link">
            <span>{{ __('Contract') }}</span>
        </a>
    </li>
    @endif
    @if (Gate::check('manage project'))
    <li class="nav-item  {{ Request::segment(1) == 'projects' ? ' active' : '' }}">
        <a href="{{ route('projects.index') }}" class="nav-link">
            <span>{{ __('Project') }}</span>
        </a>
    </li>
    @endif
    @if (Gate::check('manage project'))
    <li class="nav-item {{ Request::route()->getName() == 'project_report.index' || Request::route()->getName() == 'project_report.show' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('project_report.index') }}">
            <span>{{ __('Project Report') }}</span>
        </a>
    </li>
    @endif

    @if (Gate::check('manage project task'))
    <li class="nav-item  {{ Request::segment(1) == 'taskboard' ? ' active' : '' }}">
        <a href="{{ route('taskBoard.view', 'list') }}" class="nav-link">
            <img src="{{ asset('assets/cs-theme/icons/to-do-list-13177 1.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">

            <span>{{ __('Tasks') }}</span>
        </a>
    </li>
    @endif

    @if (Gate::check('manage bug report'))
    <li class="nav-item {{ Request::segment(1) == 'bugs-report' ? ' active' : '' }}">
        <a href="{{ route('bugs.view', 'list') }}" class="nav-link">
            <span>{{ __('Bugs') }}</span>
        </a>
    </li>
    @endif

    @if (Gate::check('manage timesheet'))
    <li class="nav-item {{ Request::segment(1) == 'timesheet-list' ? ' active' : '' }}">
        <a href="{{ route('timesheet.list') }}" class="nav-link">
            <span>{{ __('Timesheet') }}</span>
        </a>
    </li>
    @endif

    @if (Gate::check('manage project task'))
    <li class="nav-item {{ Request::segment(1) == 'calendar' ? ' active' : '' }}">
        <a href="{{ route('task.calendar', ['all']) }}" class="nav-link">
            <span>{{ __('Task Calender') }}</span>
        </a>
    </li>
    @endif

    <li class="nav-item">
        <a href="{{ route('support.index') }}" class="nav-link {{ Request::segment(1) == 'support' ? 'active' : '' }}">
            <img src="{{ asset('assets/cs-theme/icons/Layer_1 (4).png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">

            <span>{{ __('Support') }}</span>
        </a>
    </li>
</ul>
@endif

@if (\Auth::user()->type == 'super admin')
<ul style="list-style: none">
    <!-- <li class="nav-item {{ Request::segment(1) == 'dashboard' ? ' active' : '' }}">
                <a href="{{ route('crm.dashboard') }}" class="nav-link">
                    <span >{{ __('Dashboard') }}</span>
                </a>
            </li> -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesuper" aria-expanded="true" aria-controls="collapsesuper">
            <img src="{{ asset('assets/cs-theme/icons/quantity-2 1.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
            <span>{{ __('CRM System') }}</span>
        </a>
        <div id="collapsesuper" class="collapse {{ Request::segment(1) == 'deals' || Request::segment(1) == 'leads' || Request::segment(1) == 'applications' || Request::segment(1) == 'clients' || Request::segment(1) == 'university' || Request::segment(1) == 'organization' ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="  collapse-inner rounded">
                <ul>
                    <li class="emp nav-item{{ Request::route()->getName() == 'deals.get.user.tasks' ? ' active' : '' }}">
                        <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('deals.get.user.tasks') }}">
                            <img src="{{ asset('assets/cs-theme/icons/to-do-list-13177 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                            <img src="{{ asset('assets/cs-theme/icons/taskblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                            {{ __('Tasks') }}</a>
                    </li>
                    {{-- <li
                        class="nav-itemd-none {{ Request::route()->getName() == 'course.list' || Request::route()->getName() == 'course.index' || Request::route()->getName() == 'course.show' ? ' active' : '' }}">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('course.index') }}">{{ __('Courses') }}</a>
    </li> --}}
    <li class="emp nav-item {{ Request::route()->getName() == 'leads.list' || Request::route()->getName() == 'leads.index' || Request::route()->getName() == 'leads.show' ? ' active' : '' }}">
        <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('leads.list') }}">
            <img src="{{ asset('assets/cs-theme/icons/Layer_1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
            <img src="{{ asset('assets/cs-theme/icons/leadsblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

            {{ __('Leads') }}</a>
    </li>
    <li class="emp nav-item {{ Request::route()->getName() == 'deals.list' || Request::route()->getName() == 'deals.index' || Request::route()->getName() == 'deals.show' ? ' active' : '' }}">
        <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('deals.list') }}">
            <img src="{{ asset('assets/cs-theme/icons/edit-icon 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
            <img src="{{ asset('assets/cs-theme/icons/admiblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

            Admissions</a>
    </li>
    <li class="emp nav-item{{ Request::route()->getName() == 'applications.index' ? ' active' : '' }}">
        <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('applications.index') }}">
            <img src="{{ asset('assets/cs-theme/icons/result-pass-icon 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
            <img src="{{ asset('assets/cs-theme/icons/appblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

            Applications</a>
    </li>
    <li class="emp nav-item{{ Request::route()->getName() == 'clients.index' || Request::segment(1) == 'clients' || Request::route()->getName() == 'clients.edit' ? ' active' : '' }}">
        <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('clients.index') }}">
            {{-- <img src="{{ asset('assets/cs-theme/icons/Layer_1 (1).png') }}"
            id="icon1" width="15px" height="15px"
            style="margin-top:-10px" alt="" srcset="">
            <img src="{{ asset('assets/cs-theme/icons/callblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset=""> --}}
            <i class="fa-solid fa-address-card pe-1" id="icon1" style="color: #ffffff;font-size:15px;"></i>
            <i class="fa-solid fa-address-card pe-1" id="icon2" style="color: #2e82d0;font-size:15px;"></i>

            {{ __('Contacts') }}</a>
    </li>

    <li class="emp nav-item{{ Request::route()->getName() == 'university.list' || Request::route()->getName() == 'university.index' || Request::route()->getName() == 'university.show' ? ' active' : '' }}">
        <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('university.index') }}">
            <img src="{{ asset('assets/cs-theme/icons/Layer_1 (7).png') }}" width="15px" id="icon1" height="15px" style="margin-top:-10px" alt="" srcset="">
            <img src="{{ asset('assets/cs-theme/icons/toolkitblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

            Toolkit</a>
    </li>

    {{-- <li
                        class="nav-itemd-none {{ Request::route()->getName() == 'contract.index' || Request::route()->getName() == 'contract.show' ? 'active' : '' }}">
    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('contract.index') }}">{{ __('Contract') }}</a>
    </li> --}}








    <li class="d-none emp nav-item{{ Request::route()->getName() == 'organizaiton.list' || Request::route()->getName() == 'organization.index' || Request::route()->getName() == 'organization.show' ? ' active' : '' }}">
        <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('organization.index') }}">
            <img src="{{ asset('assets/cs-theme/icons/organization-01-1 1.png') }}" id="icon1" width="19px" height="19px" style="margin-top:-10px" alt="" srcset="">
            <img src="{{ asset('assets/cs-theme/icons/orgblue.png') }}" id="icon2" width="19px" height="19px" style="margin-top:-8px" alt="" srcset="">

            {{ __('Organizations') }}</a>
    </li>



</ul>
</div>
</div>
</li>

@if (\Auth::user()->type == 'super admin')
<li class="nav-item {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'clients' ? ' active dash-trigger' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesu" aria-expanded="true" aria-controls="collapsesu">
        <img src="{{ asset('assets/cs-theme/icons/Vector (2).png') }}" width="14px" height="14px" style="margin-top:-8px" alt="" srcset="">

        <span>{{ __('Users') }}</span>
    </a>
    <div id="collapsesu" class="collapse {{ Request::segment(1) == 'branch' || Request::segment(1) == 'users' || (Request::segment(1) == 'user' && Request::segment(2) == 'employees') || Request::segment(1) == 'roles' ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">
            <ul>
                @can('manage user')
                <li class="emp nav-item{{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('users.index') }}">
                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (5).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/brandblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        Brands</a>
                </li>
                @endcan

                <li class="emp nav-item{{ Request::segment(1) == 'region' ? ' active' : '' }}">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ url('/region/index') }}">
                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (3).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/branchesblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        {{ __('Region') }}</a>
                </li>

                <li class="emp nav-item{{ Request::route()->getName() == 'branch.index' || Request::route()->getName() == 'branch.edit' || Request::route()->getName() == 'branch.show' ? ' active' : '' }}">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('branch.index') }}">
                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (3).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/branchesblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        {{ __('Branches') }}</a>
                </li>

                <style>
                    .emp:hover #icon1 {
                        display: none;
                    }

                    .emp:hover #icon2 {
                        display: inline;
                    }

                    .nav-item #icon2 {
                        display: none;
                    }
                </style>

                @can('manage user')
                <li class="emp nav-item{{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('user.employees') }}">
                        <img src="{{ asset('assets/cs-theme/icons/Vector (1).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/employeeblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        Employees</a>
                </li>
                @endcan


                @can('manage role')
                <li class="emp nav-item{{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }} ">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('roles.index') }}">
                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (6).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/rolesblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        {{ __('Role') }}</a>
                </li>
                @endcan

            </ul>
        </div>
    </div>

</li>
@endif

@if (\Auth::user()->type == 'super admin')
@if (Gate::check('manage company plan') || Gate::check('manage order') || Gate::check('manage company settings')||Gate::check('super admin'))
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseten" aria-expanded="true" aria-controls="collapseten">
        <img src="{{ asset('assets/cs-theme/icons/settings-3110 1.png') }}" width="15px" height="15px" style="margin-top:-5px" alt="" srcset="">

        <span>{{ __('Settings') }}</span>
    </a>
    <div id="collapseten" class="collapse {{ Request::segment(1) == 'settings' || Request::segment(1) == 'plans' || Request::segment(1) == 'company-permission' || Request::segment(1) == 'pipelines' ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">
            <ul>
                @if (Gate::check('super admin'))


                <li style="" class="emp nav-item {{ Request::segment(1) == 'company-permission' || Request::segment(1) == 'finance-dashboard' ? 'active dash-trigger' : '' }}">
                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('company-permission') }}   ">
                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (2).png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/compblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        {{ __('Company Permission') }}</a>
                </li>


                <li style="" class="emp nav-item {{  Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' ? 'active dash-trigger' : '' }}">

                    <a class="collapse-item" style="color:white; font-size: 13px;" href="{{ route('pipelines.index') }}   ">
                        <img src="{{ asset('assets/cs-theme/icons/administrator-developer-icon 1.png') }}" id="icon1" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">
                        <img src="{{ asset('assets/cs-theme/icons/crmsysblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset="">

                        {{ __('CRM System Setup') }}</a>
                </li>
                @endif
                @if (Gate::check('manage company settings'))
                <li class="emp {{ Request::segment(1) == 'settings' ? ' active show' : '' }}">
                    <a href="{{ route('settings') }}" class="collapse-item" style="color: white; font-size: 13px;">
                        <i class="fa-solid fa-gears" id="icon1" style="color: #ffffff;font-size: 15px;"></i>

                        <i class="fa-solid fa-gears" id="icon2" style="color: #2e82d0;font-size: 15px;"></i>
                        {{ __('System Settings') }}</a>
                </li>
                @endif
                @if (Gate::check('manage company plan'))
                <li class="d-none {{ Request::route()->getName() == 'plans.index' || Request::route()->getName() == 'stripe' ? ' active' : '' }}">
                    <a href="{{ route('plans.index') }}" class="collapse-item" style="color: white; font-size: 13px;">{{ __('Setup Subscription Plan') }}</a>
                </li>
                @endif

                @if (Gate::check('manage order') && Auth::user()->type == 'company')
                <li class=" {{ Request::segment(1) == 'order' ? 'active' : '' }}">
                    <a href="{{ route('order.index') }}" class="collapse-item" style="color: white; font-size: 13px;">{{ __('Order') }}</a>
                </li>
                @endif

            </ul>
        </div>
    </div>
</li>
@endif
@endif
@if (\Auth::user()->type == 'super admin')
<li class="nav-item">
    <a href="{{ route('support.index') }}" class="nav-link {{ Request::segment(1) == 'support' ? 'active' : '' }}">
        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (4).png') }}" width="15px" height="15px" style="margin-top:-6px" alt="" srcset="">

        <span>{{ __('Support') }}</span>
    </a>
</li>
@endif


@if (Gate::check('manage system settings') ||
Gate::check('manage order') ||
Gate::check('manage plan') ||
\Auth::user()->type == 'super admin' ||
Gate::check('manage coupon'))
<li class="nav-item d-none {{ Request::route()->getName() == 'systems.index' ? ' active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesu" aria-expanded="true" aria-controls="collapsesu">
        <img src="{{ asset('assets/cs-theme/icons/settings-3110 1.png') }}" width="15px" height="15px" style="margin-top:-10px" alt="" srcset="">

        <span>{{ __('Settings') }}</span>
    </a>
    <div id="collapsesu" class="collapse {{ Request::segment(1) == 'settings' ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="  collapse-inner rounded">
            <ul>
                @if (Gate::check('manage system settings'))
                <li class="nav-item {{ Request::segment(1) == 'settings' ? 'active' : '' }}">
                    <a href="{{ route('systems.index') }}" class="collapse-item" style="color:white; font-size: 13px;">{{ __('General Settings') }}</a>
                </li>
                @endif
                @if (Gate::check('manage plan'))
                <li class="nav-item {{ Request::segment(1) == 'plans' ? 'active' : '' }}">
                    <a href="{{ route('plans.index') }}" class="collapse-item" style="color:white; font-size: 13px;">{{ __('Plan') }}</a>
                </li>
                @endif
                @if (\Auth::user()->type == 'super admin')
                <li class="nav-item {{ request()->is('plan_request*') ? 'active' : '' }}">
                    <a href="{{ route('plan_request.index') }}" class="collapse-item" style="color:white; font-size: 13px;">{{ __('Plan Request') }}</a>
                </li>
                @endif
                @if (Gate::check('manage coupon'))
                <li class="nav-item {{ Request::segment(1) == 'coupons' ? 'active' : '' }}">
                    <a href="{{ route('coupons.index') }}" class="collapse-item" style="color:white; font-size: 13px;">{{ __('Coupon') }}</a>
                </li>
                @endif
                @if (Gate::check('manage order'))
                <li class="nav-item {{ Request::segment(1) == 'orders' ? 'active' : '' }}">
                    <a href="{{ route('order.index') }}" class="collapse-item" style="color:white; font-size: 13px;">{{ __('Order') }}</a>
                </li>
                @endif
                <li class="nav-item {{ Request::segment(1) == 'email_template' ? 'active' : '' }}">
                    <a href="{{ route('manage.email.language', [$emailTemplate->id, \Auth::user()->lang]) }}" class="collapse-item" style="color:white; font-size: 13px;">{{ __('Email Template') }}</a>
                </li>
            </ul>
        </div>
    </div>

</li>
@endif
</ul>
@endif
</ul>

</div>