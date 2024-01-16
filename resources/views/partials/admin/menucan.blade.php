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

    .active a {
        color: #2E82D0 !important;
    }

    .active .nav-link span {
        color: #2E82D0 !important;
    }

    .active a #icon1 {
        display: none;
    }

    .active a #icon2 {
        display: inline;
    }

    #icon2 {
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
        {{-- dashboard li --}}
        <li class="nav-item">


            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseone"
                aria-expanded="true" aria-controls="collapseone">
                <img src="{{ asset('assets/cs-theme/icons/Group 138.png') }}" width="15px" height="15px"
                    style="margin-top:-10px" alt="" srcset="">

                <span>{{ __('Dashboard') }}</span>
            </a>
            <div id="collapseone"
                class="collapse {{ Request::segment(1) == 'crm-dashboard' || Request::segment(1) == 'hrm-dashboard' ? 'show' : '' }}"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="  collapse-inner rounded">
                    <ul>
                        @can('show crm dashboard')
                            <li class="{{ Request::route()->getName() == 'crm.dashboard' ? ' active' : '' }}">
                                <a class="collapse-item" href="{{ route('crm.dashboard') }}"
                                    style="color:white; font-size: 13px;">
                                    <i class="fa-solid fa-chart-line me-1" id="icon1" style="color: #ffffff;font-size: 15px;"></i>
                                    <i class="fa-solid fa-chart-line me-1" id="icon2" style="color: #2e82d0;font-size: 15px;"></i>

                                    CRM Dashboard</a>
                            </li>
                        @endcan

                        @can('show hrm dashboard')
                            <li class=" {{ \Request::route()->getName() == 'hrm.dashboard' ? ' active' : '' }}">
                                <a class="collapse-item" style="color: white; font-size: 13px;"
                                    href="{{ route('hrm.dashboard') }}">
                                    <i class="fa-solid fa-gauge-high me-1" id="icon1" style="color: #ffffff;font-size: 15px;"></i>
                                    <i class="fa-solid fa-gauge-high me-1" id="icon2" style="color: #2e82d0;font-size: 15px;"></i>

                                    {{ __('HRM Dashboard') }}</a>
                            </li>
                        @endcan



                    </ul>
                </div>
            </div>
        </li>
        {{-- dashboard end li  --}}
        {{-- /// --}}


            <li class="nav-item">
                <a class="nav-link {{ Request::segment(1) == 'hrm-dashboard' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave'  ? '' : 'collapsed' }} "
                    href="#" data-toggle="collapse"  data-target="#collapsehrmsys" aria-expanded="true"
                    aria-controls="collapsehrmsys ">
                    <img src="{{ asset('assets/cs-theme/icons/quantity-2 1.png') }}" width="15px" height="15px"
                        style="margin-top:-10px" alt="" srcset="">
                    <span>{{ __('HRM System') }}</span>
                </a>

            <div id="collapsehrmsys"
                    class="collapse {{ Request::segment(1) == 'hrm-dashboard' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' ? 'show' : '' }}"
                    aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="  collapse-inner rounded">
                    <ul>
                        @can('show hrm dashboard')
                            <li class="emp {{  Request::segment(1) == 'hrm-dashboard' ? ' active' : '' }}">
                                <a class="collapse-item" href="{{ route('hrm.dashboard') }}"
                                    style="color:white; font-size: 13px;">{{ __(' Overview') }}</a>
                            </li>
                        @endcan
                        @can('manage report')
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll'  ? '' : 'collapsed' }} "
                                        href="#" data-toggle="collapse" data-target="#collapsehrmsys" aria-expanded="true"
                                        aria-controls="collapsehrmsys" style="padding-left: 0px !important;
                                        padding-right: 35px !important;">
                                        <img src="{{ asset('assets/cs-theme/icons/quantity-2 1.png') }}" width="15px" height="15px"
                                            style="margin-top:-10px" alt="" srcset="">
                                        <span>{{ __('Reports') }}</span>
                                    </a>
                                <div id="collapsehrmsys"
                                        class="collapse {{ Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll'  ? 'show' : '' }}"
                                        aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                                    <div class="  collapse-inner rounded">
                                        <ul>

                                            <li class="emp {{ Request::segment(1) =='reports-payroll' ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('report.payroll') }}"
                                                    style="color:white; font-size: 13px;">{{ __(' Payroll') }}</a>
                                            </li>
                                            <li class="emp {{ Request::segment(1) =='reports-leave' ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('report.leave') }}"
                                                    style="color:white; font-size: 13px;">{{ __(' Leave') }}</a>
                                            </li>
                                            <li class="emp {{ Request::segment(1) == 'reports-monthly-attendance' ? 'active' : '' }}">
                                                <a class="collapse-item" href="{{ route('report.monthly.attendance') }}"
                                                    style="color:white; font-size: 13px;">{{ __(' Monthly Attendance') }}</a>
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
        {{-- //// --}}
        {{-- //// --}}
        @can('show account dashboard')
            <li class="d-none nav-item ">
                <a class="nav-link {{ Request::segment(1) == 'account-dashboard' ? ' ' : 'collapsed' }}" href="#"
                    data-toggle="collapse" data-target="#collapsesaccount" aria-expanded="true"
                    aria-controls="collapsesaccount">
                    <span>{{ __('Accounting ') }}</span>
                </a>
                <div id="collapsesaccount" class="collapse {{ Request::segment(1) == 'account-dashboard' ? 'show' : '' }}"
                    aria-labelledby="headingacc" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul>
                            <li class="emp nav-item {{ Request::segment(1) == 'account-dashboard' ? ' active' : '' }}">
                                <a class="collapse-item" href="{{ route('dashboard') }}"
                                    style="color:white; font-size: 13px;">{{ __(' Overview') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        @endcan
        {{-- //// --}}
               <!--------------------- Start CRM ----------------------------------->
        @can('show crm dashboard')
        @if (Gate::check('manage lead') ||
                Gate::check('manage task') ||
                Gate::check('manage deal') ||
                Gate::check('manage application') ||
                Gate::check('manage client') ||
                Gate::check('manage courses') ||
                Gate::check('manage university') ||
                Gate::check('manage organization') ||
                Gate::check('manage form builder') ||
                Gate::check('manage contract'))
            <li class="nav-item">
                <a class="nav-link {{ Request::segment(1) == 'deals' || Request::segment(1) == 'leads' || Request::segment(1) == 'applications' || Request::segment(1) == 'clients' || Request::segment(1) == 'university' || Request::segment(1) == 'organization' ? '' : 'collapsed' }} "
                    href="#" data-toggle="collapse" data-target="#collapsefour" aria-expanded="true"
                    aria-controls="collapsefour">
                    <img src="{{ asset('assets/cs-theme/icons/quantity-2 1.png') }}" width="15px" height="15px"
                        style="margin-top:-10px" alt="" srcset="">
                    <span>{{ __('CRM System') }}</span>
                </a>
                <div id="collapsefour"
                    class="collapse {{ Request::segment(1) == 'deals' || Request::segment(1) == 'leads' || Request::segment(1) == 'applications' || Request::segment(1) == 'clients' || Request::segment(1) == 'university' || Request::segment(1) == 'organization' ? 'show' : '' }}"
                    aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul
                            class="
                              {{ Request::segment(1) == 'stages' ||
                              Request::segment(1) == 'labels' ||
                              Request::segment(1) == 'sources' ||
                              Request::segment(1) == 'university' ||
                              Request::segment(1) == 'lead_stages' ||
                              Request::segment(1) == 'leads' ||
                              Request::segment(1) == 'form_builder' ||
                              Request::segment(1) == 'course' ||
                              Request::segment(1) == 'form_response' ||
                              Request::segment(1) == 'deals'
                                  ? 'show'
                                  : '' }}">

                            @can('manage task')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'deals.get.user.tasks' ? ' active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('deals.get.user.tasks') }}">
                                        <img src="{{ asset('assets/cs-theme/icons/to-do-list-13177 1.png') }}"
                                            id="icon1" width="15px" height="15px" style="margin-top:-10px"
                                            alt="" srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/taskblue.png') }}" id="icon2"
                                            width="15px" height="15px" style="margin-top:-8px" alt=""
                                            srcset="">

                                        {{ __('Tasks') }}</a>
                                </li>
                            @endcan
                            @can('manage lead')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'leads.list' || Request::route()->getName() == 'leads.index' || Request::route()->getName() == 'leads.show' ? ' active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('leads.list') }}">
                                        <img src="{{ asset('assets/cs-theme/icons/Layer_1.png') }}" id="icon1"
                                            width="15px" height="15px" style="margin-top:-10px" alt=""
                                            srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/leadsblue.png') }}" id="icon2"
                                            width="15px" height="15px" style="margin-top:-8px" alt=""
                                            srcset="">

                                        {{ __('Leads') }}</a>
                                </li>
                            @endcan
                            @can('manage deal')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'deals.list' || Request::route()->getName() == 'deals.index' || Request::route()->getName() == 'deals.show' ? ' active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('deals.list') }}">
                                        <img src="{{ asset('assets/cs-theme/icons/edit-icon 1.png') }}" id="icon1"
                                            width="15px" height="15px" style="margin-top:-10px" alt=""
                                            srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/admiblue.png') }}" id="icon2"
                                            width="15px" height="15px" style="margin-top:-8px" alt=""
                                            srcset="">
                                        Admissions</a>
                                </li>
                            @endcan
                            @can('manage application')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'applications.index' ? ' active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('applications.index') }}">
                                        <img src="{{ asset('assets/cs-theme/icons/result-pass-icon 1.png') }}"
                                            id="icon1" width="15px" height="15px" style="margin-top:-10px"
                                            alt="" srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/appblue.png') }}" id="icon2"
                                            width="15px" height="15px" style="margin-top:-8px" alt=""
                                            srcset="">

                                        Applications</a>
                                </li>
                            @endcan
                            @can('manage client')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'clients.index' || Request::segment(1) == 'clients' || Request::route()->getName() == 'clients.edit' ? ' active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('clients.index') }}">
                                        {{-- <img src="{{ asset('assets/cs-theme/icons/Layer_1 (1).png') }}"
                                     id="icon1" width="15px" height="15px"
                                     style="margin-top:-10px" alt="" srcset="">
                                     <img src="{{ asset('assets/cs-theme/icons/callblue.png') }}" id="icon2" width="15px" height="15px" style="margin-top:-8px" alt="" srcset=""> --}}
                                        <i class="fa-solid fa-address-card pe-1" id="icon1"
                                            style="color: #ffffff;font-size:15px;"></i>
                                        <i class="fa-solid fa-address-card pe-1" id="icon2"
                                            style="color: #2e82d0;font-size:15px;"></i>

                                        {{ __('Contacts') }}</a>
                                </li>
                            @endcan
                            @can('manage form builder')
                                {{-- <li class=" {{ (Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response')?'active open':''}}">
                             <a class="collapse-item" style="color: white; font-size: 13px;" href="{{route('form_builder.index')}}">{{__('Form Builder')}}</a>
                             </li> --}}
                            @endcan
                            @can('manage university')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'university.list' || Request::route()->getName() == 'university.index' || Request::route()->getName() == 'university.show' ? ' active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('university.index') }}">
                                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (7).png') }}" id="icon1"
                                            width="15px" height="15px" style="margin-top:-10px" alt=""
                                            srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/toolkitblue.png') }}" id="icon2"
                                            width="15px" height="15px" style="margin-top:-8px" alt=""
                                            srcset="">

                                        {{ __('Toolkit') }}</a>
                                </li>
                            @endcan
                            @can('manage courses')
                                {{-- <li class=" {{ (Request::route()->getName() == 'course.list' || Request::route()->getName() == 'course.index' || Request::route()->getName() == 'course.show') ? ' active' : '' }}">
                              <a class="collapse-item" style="color: white; font-size: 13px;" href="{{ route('course.index') }}">{{__('Courses')}}</a>
                              </li> --}}
                            @endcan
                            @can('manage organization')
                                <li
                                    class="d-none emp nav-item {{ Request::route()->getName() == 'organizaiton.list' || Request::route()->getName() == 'organization.index' || Request::route()->getName() == 'organization.show' ? ' active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('organization.index') }}">
                                        <img src="{{ asset('assets/cs-theme/icons/organization-01-1 1.png') }}"
                                            id="icon1" width="15px" height="15px" style="margin-top:-10px"
                                            alt="" srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/orgblue.png') }}" id="icon2"
                                            width="15px" height="15px" style="margin-top:-8px" alt=""
                                            srcset="">

                                        {{ __('Organizations') }}</a>
                                </li>
                            @endcan
                            @can('company')
                                <li class=" d-none" style="display: none;"
                                    {{ Request::route()->getName() == 'contract.index' || Request::route()->getName() == 'contract.show' ? 'active' : '' }}">
                                    <a class="collapse-item" style="color: white; font-size: 13px;"
                                        href="{{ route('contract.index') }}">{{ __('Contract') }}</a>
                                </li>
                            @endcan




                        </ul>
                    </div>
                </div>
            </li>
        @endif
    @endcan
    <!--------------------- End CRM ----------------------------------->
        {{--  --}}
        @can('manage report')
            <li class="nav-item ">
                <a class="nav-link {{ Request::segment(1) == 'report' || Request::segment(1) == 'ChartGranted' || Request::segment(1) == 'ChartDeposited' || Request::segment(1) == 'ChartApplication' ? '' : 'collapsed' }}" href="#"
                    data-toggle="collapse" data-target="#collapsesreport" aria-expanded="true"
                    aria-controls="collapsesreport">
                    <i class="fa-solid fa-chart-bar me-1" style="color: #ffffff;font-size: 15px;"></i>
                    <span>{{ __('Reports') }}</span>
                </a>
                <div id="collapsesreport" class="collapse {{ Request::segment(1) == 'report' || Request::segment(1) == 'ChartGranted' || Request::segment(1) == 'ChartDeposited' || Request::segment(1) == 'ChartApplication' ? 'show' : '' }}"
                    aria-labelledby="headingrepost" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul>
                            <li
                                class="emp nav-item {{ Request::segment(1) == 'ChartGranted' || Request::segment(1) == 'ChartDeposited' || Request::segment(1) == 'ChartApplication' ? ' active' : '' }}">
                                <a class="collapse-item" href="{{ url('/ChartGranted') }}"
                                    style="color:white; font-size: 13px;">

                                    <i class="fa-solid fa-chart-pie me-1" id="icon1" style="color: #ffffff;font-size: 15px;"></i>
                                    <i class="fa-solid fa-chart-pie me-1" id="icon2" style="color: #2e82d0;font-size: 15px;"></i>

                                    {{ __('Analysis Report') }}</a>
                            </li>
                        </ul>
                        <ul class="d-none">
                            @can('expense report')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'report.expense.summary' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.expense.summary') }}"
                                        style="color:white; font-size: 13px;">{{ __('Expense Summary') }}</a>
                                </li>
                            @endcan
                            @can('income vs expense report')
                                <li
                                    class=" emp nav-item {{ Request::route()->getName() == 'report.income.vs.expense.summary' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.income.vs.expense.summary') }}"
                                        style="color: white; font-size: 13px;">{{ __('Income VS Expense') }}</a>
                                </li>
                            @endcan
                            @can('statement report')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'report.account.statement' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.account.statement') }}"
                                        style="color: white; font-size: 13px;">{{ __('Account Statement') }}</a>
                                </li>
                            @endcan
                            @can('invoice report')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'report.invoice.summary' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.invoice.summary') }}"
                                        style="color: white; font-size: 13px;">{{ __('Invoice Summary') }}</a>
                                </li>
                            @endcan
                            @can('bill report')
                                <li class=" {{ Request::route()->getName() == 'report.bill.summary' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.bill.summary') }}"
                                        style="color: white; font-size: 13px;">{{ __('Bill Summary') }}</a>
                                </li>
                            @endcan
                            @can('stock report')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'report.product.stock.report' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.product.stock.report') }}"
                                        style="color: white; font-size: 13px;">{{ __('Product Stock') }}</a>
                                </li>
                            @endcan

                            @can('loss & profit report')
                                <li
                                    class=" {{ Request::route()->getName() == 'report.profit.loss.summary' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.profit.loss.summary') }}"
                                        style="color: white; font-size: 13px;">{{ __('Profite & Loss') }}</a>
                                </li>
                            @endcan
                            @can('manage transaction')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'transaction.index' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('transaction.index') }}"
                                        style="color: white; font-size: 13px;">{{ __('Transaction') }}</a>
                                </li>
                            @endcan
                            @can('income report')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'report.income.summary' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.income.summary') }}"
                                        style="color: white; font-size: 13px;">{{ __('Income Summary') }}</a>
                                </li>
                            @endcan
                            @can('tax report')
                                <li
                                    class="emp nav-item {{ Request::route()->getName() == 'report.tax.summary' ? ' active' : '' }}">
                                    <a class="collapse-item" href="{{ route('report.tax.summary') }}"
                                        style="color: white; font-size: 13px;">{{ __('Tax Summary') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </li>
        @endcan
        {{--  --}}

        <!--------------------- Start Project ----------------------------------->

        @can('show project dashboard')
            @if (Gate::check('manage project'))
                <li class="d-none nav-item">

                    <a class="nav-link  {{ Request::segment(1) == 'project' ||
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
                        ? ''
                        : 'collapsed' }}"
                        href="#" data-toggle="collapse" data-target="#collapseprosys" aria-expanded="true"
                        aria-controls="collapseprosys">
                        <span>{{ __('Project System') }}</span>
                    </a>
                    <div id="collapseprosys"
                        class="collapse {{ Request::segment(1) == 'project' ||
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
                            ? 'show'
                            : '' }}"
                        aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="  collapse-inner rounded">

                            <ul>
                                @can('manage project')
                                    <li
                                        class="emp nav-item  {{ Request::segment(1) == 'project' || Request::route()->getName() == 'projects.list' || Request::route()->getName() == 'projects.list' || Request::route()->getName() == 'projects.index' || Request::route()->getName() == 'projects.show' || request()->is('projects/*') ? 'active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                                    </li>
                                @endcan
                                @can('manage project task')
                                    <li class="emp nav-item {{ request()->is('taskboard*') ? 'active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('taskBoard.view', 'list') }}">
                                            {{ __('Tasks') }}</a>
                                    </li>
                                @endcan
                                @can('manage timesheet')
                                    <li class="emp nav-item {{ request()->is('timesheet-list*') ? 'active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('timesheet.list') }}">{{ __('Timesheet') }}</a>
                                    </li>
                                @endcan
                                @can('manage bug report')
                                    <li class="emp nav-item {{ request()->is('bugs-report*') ? 'active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('bugs.view', 'list') }}">{{ __('Bug') }}</a>
                                    </li>
                                @endcan
                                @can('manage project task')
                                    <li class="emp nav-item {{ request()->is('calendar*') ? 'active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('task.calendar', ['all']) }}">{{ __('Task Calendar') }}</a>
                                    </li>
                                @endcan
                                @if (\Auth::user()->type != 'super admin')
                                    <li
                                        class="emp nav-item  {{ Request::segment(1) == 'time-tracker' ? 'active open' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('time.tracker') }}">{{ __('Tracker') }}</a>
                                    </li>
                                @endif
                                @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
                                    <li
                                        class="emp nav-item  {{ Request::route()->getName() == 'project_report.index' || Request::route()->getName() == 'project_report.show' ? 'active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('project_report.index') }}">{{ __('Project Report') }}</a>
                                    </li>
                                @endif

                                @if (Gate::check('manage project task stage') || Gate::check('manage bug status'))
                                    <li
                                        class="emp nav-item d-none {{ Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages' ? 'active dash-trigger' : '' }}">
                                        <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                            data-target="#collapsesix" aria-expanded="true" aria-controls="collapsesix">
                                            <span>Project System Setup</span>
                                        </a>
                                        <div id="collapsesix" class="collapse" aria-labelledby="headingUtilities"
                                            data-parent="#accordionSidebar">
                                            <div class="  collapse-inner rounded">
                                                <ul>
                                                    @can('manage project task stage')
                                                        <li
                                                            class="  {{ Request::route()->getName() == 'project-task-stages.index' ? 'active' : '' }}">
                                                            <a class="collapse-item" style="color: white; font-size: 13px;"
                                                                href="{{ route('project-task-stages.index') }}">{{ __('Project Task Stages') }}</a>
                                                        </li>
                                                    @endcan
                                                    @can('manage bug status')
                                                        <li
                                                            class=" {{ Request::route()->getName() == 'bugstatus.index' ? 'active' : '' }}">
                                                            <a class="collapse-item" style="color: white; font-size: 13px;"
                                                                href="{{ route('bugstatus.index') }}">{{ __('Bug Status') }}</a>
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
        @endcan
        <!--------------------- End Project ----------------------------------->
        <!--------------------- Start Products System ----------------------------------->
        @if (Gate::check('manage product & service') || Gate::check('manage product & service'))
            <li class="nav-item d-none">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseeight"
                    aria-expanded="true" aria-controls="collapseeight">
                    <span>{{ __('Products') }}</span>
                </a>
                <div id="collapseeight" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul>
                            @if (Gate::check('manage product & service'))
                                <li class=" {{ Request::segment(1) == 'productservice' ? 'active' : '' }}">
                                    <a href="{{ route('productservice.index') }}" class="collapse-item"
                                        style="color: white; font-size: 13px;">{{ __('Product & Services') }}
                                    </a>
                                </li>
                            @endif
                            @if (Gate::check('manage product & service'))
                                <li class=" {{ Request::segment(1) == 'productstock' ? 'active' : '' }}">
                                    <a href="{{ route('productstock.index') }}" class="collapse-item"
                                        style="color: white; font-size: 13px;">{{ __('Product Stock') }}
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
                {{-- //// --}}
                <li class="d-none nav-item ">
                    <a class="nav-link {{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::segment(2) == 'pos.barcode' || Request::segment(1) == 'pos.print' || Request::segment(1) == 'pos.show' ? ' ' : 'collapsed' }}"
                        href="#" data-toggle="collapse" data-target="#collapsepos" aria-expanded="true"
                        aria-controls="collapsepos">


                        <span>{{ __('POS System') }}</span>
                    </a>
                    <div id="collapsepos"
                        class="collapse {{ Request::segment(1) == 'warehouse' || Request::segment(1) == 'purchase' || Request::segment(2) == 'pos.barcode' || Request::segment(1) == 'pos.print' || Request::segment(1) == 'pos.show' ? 'show' : '' }}"
                        aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="  collapse-inner rounded">
                            <ul>
                                @can('manage warehouse')
                                    <li
                                        class="emp nav-item {{ Request::route()->getName() == 'warehouse.index' || Request::route()->getName() == 'warehouse.show' ? ' active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('warehouse.index') }}">{{ __('Warehouse') }}</a>
                                    </li>
                                @endcan
                                @can('manage purchase')
                                    <li
                                        class="emp nav-item {{ Request::route()->getName() == 'purchase.index' || Request::route()->getName() == 'purchase.create' || Request::route()->getName() == 'purchase.edit' || Request::route()->getName() == 'purchase.show' ? ' active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('purchase.index') }}">{{ __('Purchase') }}</a>
                                    </li>
                                @endcan
                                @can('manage pos')
                                    <li
                                        class="emp nav-item {{ Request::route()->getName() == 'pos.index' ? ' active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('pos.index') }}">{{ __(' Add POS') }}</a>
                                    </li>

                                    <li
                                        class="emp nav-item {{ Request::route()->getName() == 'pos.report' || Request::route()->getName() == 'pos.show' ? ' active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('pos.report') }}">{{ __('POS') }}</a>
                                    </li>
                                @endcan
                                @can('create barcode')
                                    <li
                                        class="emp nav-item {{ Request::route()->getName() == 'pos.barcode' || Request::route()->getName() == 'pos.print' ? ' active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('pos.barcode') }}">{{ __('Print Barcode') }}</a>
                                    </li>
                                @endcan
                                @can('manage pos')
                                    <li
                                        class="emp nav-item {{ Request::route()->getName() == 'pos-print-setting' ? ' active' : '' }}">
                                        <a class="collapse-item" style="color: white; font-size: 13px;"
                                            href="{{ route('pos.print.setting') }}">{{ __('Print Settings') }}</a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </div>
                </li>
                {{-- ///// --}}
            @endif
        @endif
        <!--------------------- End POs System ----------------------------------->


        {{-- user management system  --}}
        <li class="nav-item ">
            <a class="nav-link {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(2) == 'employees' || Request::segment(1) == 'clients' ? ' ' : 'collapsed' }}"
                href="#" data-toggle="collapse" data-target="#collapsesu" aria-expanded="true"
                aria-controls="collapsesu">
                <img src="{{ asset('assets/cs-theme/icons/Vector (2).png') }}" width="14px" height="14px"
                    style="margin-top:-8px" alt="" srcset="">

                <span>{{ __('Users') }}</span>
            </a>
            <div id="collapsesu"
                class="collapse {{ Request::segment(1) == 'branch' || Request::segment(1) == 'users' || (Request::segment(1) == 'user' && Request::segment(2) == 'employees') || Request::segment(1) == 'roles' || Request::segment(1) == 'region' ? 'show' : '' }}"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="  collapse-inner rounded">
                    <ul>
                        @can('manage user')
                            <li class="emp nav-item {{ Request::segment(1) == 'users' ? 'active' : '' }}">
                                <a class="collapse-item" style="color:white; font-size: 13px;"
                                    href="{{ route('users.index') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/Layer_1 (5).png') }}" id="icon1"
                                        width="15px" height="15px" style="margin-top:-8px" alt=""
                                        srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/brandblue.png') }}" id="icon2"
                                        width="15px" height="15px" style="margin-top:-8px" alt=""
                                        srcset="">

                                    Brands</a>
                            </li>
                        @endcan
                        {{-- @can('manage region') --}}
                        <li class="emp nav-item {{ Request::segment(1) == 'region' ? ' active' : '' }}">
                            <a class="collapse-item" style="color:white; font-size: 13px;"
                                href="{{ url('/region/index') }}">
                                <img src="{{ asset('assets/cs-theme/icons/Layer_1 (3).png') }}" id="icon1"
                                    width="15px" height="15px" style="margin-top:-10px" alt=""
                                    srcset="">
                                <img src="{{ asset('assets/cs-theme/icons/branchesblue.png') }}" id="icon2"
                                    width="15px" height="15px" style="margin-top:-8px" alt=""
                                    srcset="">

                                {{ __('Region') }}</a>
                        </li>
                        {{-- @endcan --}}
                        @can('manage branch')
                            <li class="emp nav-item {{ Request::segment(1) == 'branch' ? ' active' : '' }}">
                                <a class="collapse-item" style="color:white; font-size: 13px;"
                                    href="{{ route('branch.index') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/Layer_1 (3).png') }}" id="icon1"
                                        width="15px" height="15px" style="margin-top:-10px" alt=""
                                        srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/branchesblue.png') }}" id="icon2"
                                        width="15px" height="15px" style="margin-top:-8px" alt=""
                                        srcset="">

                                    {{ __('Branches') }}</a>
                            </li>
                        @endcan



                        @can('manage user')
                            <li class="emp nav-item {{ Request::segment(2) == 'employees' ? ' active' : '' }}">
                                <a class="collapse-item " style="color:white; font-size: 13px;"
                                    href="{{ route('user.employees') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/Vector (1).png') }}" id="icon1"
                                        width="15px" height="15px" style="margin-top:-8px" alt=""
                                        srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/employeeblue.png') }}" id="icon2"
                                        width="15px" height="15px" style="margin-top:-8px" alt=""
                                        srcset="">

                                    Employees</a>
                            </li>
                        @endcan


                        @can('manage role')
                            <li
                                class="emp nav-item{{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }} ">
                                <a class="collapse-item" style="color:white; font-size: 13px;"
                                    href="{{ route('roles.index') }}">
                                    <img src="{{ asset('assets/cs-theme/icons/Layer_1 (6).png') }}" id="icon1"
                                        width="15px" height="15px" style="margin-top:-8px" alt=""
                                        srcset="">
                                    <img src="{{ asset('assets/cs-theme/icons/rolesblue.png') }}" id="icon2"
                                        width="15px" height="15px" style="margin-top:-8px" alt=""
                                        srcset="">

                                    {{ __('Role') }}</a>
                            </li>
                        @endcan

                    </ul>
                </div>
            </div>
        </li>
        {{-- End user System  --}}

        {{-- setting  --}}

        @if (Gate::check('manage company plan') ||
                Gate::check('manage order') ||
                Gate::check('manage company settings') ||
                Gate::check('manage system settings') ||
                Gate::check('manage coupon') ||
                Gate::check('manage permission') ||
                Gate::check('manage plan') ||
                Gate::check('super admin'))
            <li class="nav-item">
                <a class="nav-link {{ Request::segment(1) == 'settings' || Request::segment(1) == 'coupons' || Request::segment(1) == 'email_template_lang' || Request::segment(1) == 'plans' || Request::segment(1) == 'company-permission' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'systems' || Request::segment(1) == 'plan_request' ? '' : 'collapsed' }}"
                    href="#" data-toggle="collapse" data-target="#collapsesetting" aria-expanded="true"
                    aria-controls="collapsesetting">
                    <img src="{{ asset('assets/cs-theme/icons/settings-3110 1.png') }}" width="15px"
                        height="15px" style="margin-top:-5px" alt="" srcset="">

                    <span>{{ __('Settings') }}</span>
                </a>
                <div id="collapsesetting"
                    class="collapse {{ Request::segment(1) == 'settings' || Request::segment(1) == 'coupons' || Request::segment(1) == 'email_template_lang' || Request::segment(1) == 'plans' || Request::segment(1) == 'company-permission' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'systems' || Request::segment(1) == 'plan_request' ? 'show' : '' }}"
                    aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="  collapse-inner rounded">
                        <ul>
                            @can('manage permission')
                                <li style=""
                                    class="emp nav-item {{ Request::segment(1) == 'company-permission' || Request::segment(1) == 'finance-dashboard' ? 'active dash-trigger' : '' }}">
                                    <a class="collapse-item" style="color:white; font-size: 13px;"
                                        href="{{ route('company-permission') }}   ">
                                        <img src="{{ asset('assets/cs-theme/icons/Layer_1 (2).png') }}" id="icon1"
                                            width="15px" height="15px" style="margin-top:-10px" alt=""
                                            srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/compblue.png') }}" id="icon2"
                                            width="15px" height="15px" style="margin-top:-8px" alt=""
                                            srcset="">

                                        {{ __('Company Permission') }}</a>
                                </li>
                            @endcan
                            @if (Gate::check('super admin'))
                                <li style=""
                                    class="emp nav-item {{ Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' ? 'active dash-trigger' : '' }}">

                                    <a class="collapse-item" style="color:white; font-size: 13px;"
                                        href="{{ route('stages.index') }}   ">
                                        <img src="{{ asset('assets/cs-theme/icons/administrator-developer-icon 1.png') }}"
                                            id="icon1" width="15px" height="15px" style="margin-top:-10px"
                                            alt="" srcset="">
                                        <img src="{{ asset('assets/cs-theme/icons/crmsysblue.png') }}"
                                            id="icon2" width="15px" height="15px" style="margin-top:-8px"
                                            alt="" srcset="">

                                        {{ __('CRM System Setup') }}
                                    </a>
                                </li>
                            @endif
                            @can('manage company settings')
                                <li class="emp {{ Request::segment(1) == 'settings' ? ' active show' : '' }}">
                                    <a href="{{ route('settings') }}" class="collapse-item"
                                        style="color: white; font-size: 13px;">
                                        <i class="fa-solid fa-gears" id="icon1"
                                            style="color: #ffffff;font-size: 15px;"></i>

                                        <i class="fa-solid fa-gears" id="icon2"
                                            style="color: #2e82d0;font-size: 15px;"></i>
                                        {{ __('System Settings') }}</a>
                                </li>
                            @endcan
                            @can('manage company plan')
                                <li
                                    class="d-none {{ Request::route()->getName() == 'plans.index' || Request::route()->getName() == 'stripe' ? ' active' : '' }}">
                                    <a href="{{ route('plans.index') }}" class="collapse-item"
                                        style="color: white; font-size: 13px;">{{ __('Setup Subscription Plan') }}</a>
                                </li>
                            @endcan

                            @can('manage order')
                                <li class="d-none emp nav-item  {{ Request::segment(1) == 'order' ? 'active' : '' }}">
                                    <a href="{{ route('order.index') }}" class="collapse-item"
                                        style="color: white; font-size: 13px;">{{ __('Order') }}</a>
                                </li>
                            @endcan
                            {{-- ///// --}}
                            @can('manage system settings')
                                <li class="d-none emp nav-item {{ Request::segment(1) == 'systems' ? 'active' : '' }}">
                                    <a href="{{ route('systems.index') }}" class="collapse-item"
                                        style="color:white; font-size: 13px;">{{ __('General Settings') }}</a>
                                </li>
                            @endcan
                            @can('manage plan')
                                <li class="d-none  emp nav-item {{ Request::segment(1) == 'plans' ? 'active' : '' }}">
                                    <a href="{{ route('plans.index') }}" class="collapse-item"
                                        style="color:white; font-size: 13px;">{{ __('Plan') }}</a>
                                </li>
                                <li
                                    class="d-none emp nav-item {{ Request::segment(1) == 'plan_request' ? 'active' : '' }}">
                                    <a href="{{ route('plan_request.index') }}" class="collapse-item"
                                        style="color:white; font-size: 13px;">{{ __('Plan Request') }}</a>
                                </li>
                            @endcan
                            @can('manage coupon')
                                <li class="d-none emp nav-item {{ Request::segment(1) == 'coupons' ? 'active' : '' }}">
                                    <a href="{{ route('coupons.index') }}" class="collapse-item"
                                        style="color:white; font-size: 13px;">{{ __('Coupon') }}</a>
                                </li>
                            @endcan

                            <li
                                class="d-none emp nav-item {{ Request::segment(1) == 'email_template_lang' ? 'active' : '' }}">
                                <a href="{{ route('manage.email.language', [$emailTemplate->id, \Auth::user()->lang]) }}"
                                    class="collapse-item"
                                    style="color:white; font-size: 13px;">{{ __('Email Template') }}</a>
                            </li>
                            {{-- ///// --}}

                        </ul>
                    </div>
                </div>
            </li>
        @endif
        {{-- end setting  --}}

        {{-- support  --}}
        <li class=" nav-item {{ Request::segment(1) == 'support' ? 'active' : '' }}">
            <a href="{{ route('support.index') }}" class="nav-link">
                <img src="{{ asset('assets/cs-theme/icons/Layer_1 (4).png') }}" id="icon1" width="15px"
                    height="15px" style="margin-top:-6px" alt="" srcset="">
                <img src="{{ asset('assets/cs-theme/icons/Layer_1(4.1).svg') }}" id="icon2" width="15px"
                    height="15px" style="margin-top:-6px" alt="" srcset="">

                <span>{{ __('Support') }}</span>
            </a>
        </li>
        {{-- end support  --}}



    </ul>

</div>
