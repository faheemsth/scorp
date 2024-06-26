@extends('layouts.admin')
@section('page-title')
{{ $emailTemplate->name ?? '' }}
    @php
        $emailTemplateName=$emailTemplate->name ?? '';
        $emailTemplateId=$emailTemplate->id ?? '0';
    @endphp
@endsection
@push('css-page')
@endpush

@push('script-page')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $('#summernote').summernote();
        });
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Email Template') }}</li>
@endsection
@section('action-btn')
    <div class="row">
        <div class="col-lg-6">

        </div>
        <div class="col-lg-6">
            <div class="text-end">
                <div class="d-flex justify-content-end drp-languages">
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language">
                            <a class="email-color dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                id="dropdownLanguage">
                                {{-- <i class="ti ti-world nocolor"></i> --}}
                                <span
                                    class="email-color drp-text hide-mob text-dark">{{ Str::upper($currEmailTempLang->lang) }}</span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                                @foreach ($languages as $lang)
                                    <a href="{{ route('manage.email.language', [$emailTemplateId, $lang]) }}"
                                        class="dropdown-item {{ $currEmailTempLang->lang == $lang ? 'text-dark' : '' }}">{{ Str::upper($lang) }}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language">
                            <a class="email-color dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false"
                                id="dropdownLanguage">
                                <span
                                    class="drp-text hide-mob text-dark">{{ __('Template: ') }}{{ $emailTemplateName }}</span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end email_temp"
                                aria-labelledby="dropdownLanguage">
                                <style>
                                    .dropdown-menu.email_temp {
                                        max-height: 200px;
                                        overflow-y: auto;
                                    }
                                </style>
                                @foreach ($EmailTemplates as $EmailTemplate)
                                    @php
                                        $templateName = strlen($EmailTemplate->name) > 20 ? substr($EmailTemplate->name, 0, 17) . '...' : $EmailTemplate->name;
                                    @endphp
                                    <a href="{{ route('manage.email.language', [$EmailTemplate->id, Request::segment(3) ? Request::segment(3) : \Auth::user()->lang]) }}"
                                        class="dropdown-item {{ $EmailTemplate->name == $emailTemplateName ? 'text-dark' : '' }}" title="{{ $EmailTemplate->name }}">
                                        {{ $templateName }}
                                    </a>
                                @endforeach

                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('content')
@if (!empty($emailTemplateName))
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body ">
                    {{-- <div class="card"> --}}
                    {{ Form::model($currEmailTempLang, ['route' => ['email_template.update', $currEmailTempLang->parent_id], 'method' => 'PUT']) }}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h6 class="font-weight-bold pb-1">{{ __('Place Holder') }}</h6>

                            <div class="card">
                                <div class="card-body">
                                    <div class="row ">

                                        @if ($emailTemplate->slug == 'new_user')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Email') }} : <span
                                                        class="pull-right text-dark">{email}</span></p>
                                                <p class="col-4">{{ __('Password') }} : <span
                                                        class="pull-right text-dark">{password}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'new_client')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Client Name') }} : <span
                                                        class="pull-right text-dark">{client_name}</span></p>
                                                <p class="col-4">{{ __('Email') }} : <span
                                                        class="pull-right text-dark">{client_email}</span></p>
                                                <p class="col-4">{{ __('Password') }} : <span
                                                        class="pull-right text-dark">{client_password}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'new_support_ticket')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('User Name') }} : <span
                                                        class="pull-right text-dark">{support_name}</span></p>
                                                <p class="col-4">{{ __('Support Title') }} : <span
                                                        class="pull-right text-dark">{support_title}</span></p>
                                                <p class="col-4">{{ __('Support Priority') }} : <span
                                                        class="pull-right text-dark">{support_priority}</span></p>
                                                <p class="col-4">{{ __('Support End Date') }} : <span
                                                        class="pull-right text-dark">{support_end_date}</span></p>
                                                <p class="col-4">{{ __('Support Description') }} : <span
                                                        class="pull-right text-dark">{support_description}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'new_contract')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Contract Subject') }} : <span
                                                        class="pull-right text-dark">{contract_subject}</span></p>
                                                <p class="col-4">{{ __('Client Name') }} : <span
                                                        class="pull-right text-dark">{contract_client}</span></p>
                                                <p class="col-4">{{ __('Contract Title') }} : <span
                                                        class="pull-right text-dark">{contract_value}</span></p>
                                                <p class="col-4">{{ __('Contract Priority') }} : <span
                                                        class="pull-right text-dark">{contract_start_date}</span></p>
                                                <p class="col-4">{{ __('Contract End Date') }} : <span
                                                        class="pull-right text-dark">{contract_end_date}</span></p>
                                                <p class="col-4">{{ __('Contract Description') }} : <span
                                                        class="pull-right text-dark">{contract_description}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'lead_assigned')
                                            <div class="row">
                                                <p class="col-4">{{ __('Lead Name') }} : <span
                                                        class="pull-right text-dark">{lead_name}</span></p>
                                                <p class="col-4">{{ __('Lead Email') }} : <span
                                                        class="pull-right text-dark">{lead_email}</span></p>
                                                <p class="col-4">{{ __('Lead Subject') }} : <span
                                                        class="pull-right text-dark">{lead_subject}</span></p>
                                                <p class="col-4">{{ __('Lead Pipeline') }} : <span
                                                        class="pull-right text-dark">{lead_pipeline}</span></p>
                                                <p class="col-4">{{ __('Lead Stage') }} : <span
                                                        class="pull-right text-dark">{lead_stage}</span></p>
                                                <p class="col-4">{{ __('Lead Brand') }} : <span
                                                        class="pull-right text-dark">{lead_brand}</span></p>
                                                <p class="col-4">{{ __('Lead Region') }} : <span
                                                        class="pull-right text-dark">{lead_region}</span></p>
                                                <p class="col-4">{{ __('Lead Branch') }} : <span
                                                        class="pull-right text-dark">{lead_branch}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'deal_assigned')
                                            <div class="row">
                                                <p class="col-4">{{ __('Deal Name') }} : <span
                                                        class="pull-right text-dark">{deal_name}</span></p>
                                                <p class="col-4">{{ __('Deal Pipeline') }} : <span
                                                        class="pull-right text-dark">{deal_pipeline}</span></p>
                                                <p class="col-4">{{ __('Deal Stage') }} : <span
                                                        class="pull-right text-dark">{deal_stage}</span></p>
                                                <p class="col-4">{{ __('Deal Status') }} : <span
                                                        class="pull-right text-dark">{deal_status}</span></p>
                                                <p class="col-4">{{ __('Deal Price') }} : <span
                                                        class="pull-right text-dark">{deal_price}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'award_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Award Name') }} : <span
                                                        class="pull-right text-dark">{award_name}</span></p>
                                                <p class="col-4">{{ __('Award Email') }} : <span
                                                        class="pull-right text-dark">{award_email}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'customer_invoice_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Customer Name') }} : <span
                                                        class="pull-right text-dark">{customer_name}</span></p>
                                                <p class="col-4">{{ __('Customer Email') }} : <span
                                                        class="pull-right text-dark">{customer_email}</span></p>
                                                <p class="col-4">{{ __('Invoice Name') }} : <span
                                                        class="pull-right text-dark">{invoice_name}</span></p>
                                                <p class="col-4">{{ __('Invoice Number') }} : <span
                                                        class="pull-right text-dark">{invoice_number}</span></p>
                                                <p class="col-4">{{ __('Invoice Url') }} : <span
                                                        class="pull-right text-dark">{invoice_url}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'new_invoice_payment')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Customer Name') }} : <span
                                                        class="pull-right text-dark">{invoice_payment_name}</span></p>
                                                <p class="col-4">{{ __('Invoice Payment') }} : <span
                                                        class="pull-right text-dark">{invoice_payment}</span></p>
                                                <p class="col-4">{{ __('Invoice Payment Amount') }} : <span
                                                        class="pull-right text-dark">{invoice_payment_amount}</span></p>
                                                <p class="col-4">{{ __('Invoice Payment Date') }} : <span
                                                        class="pull-right text-dark">{invoice_payment_date}</span></p>
                                                <p class="col-4">{{ __('Invoice Payment Method') }} : <span
                                                        class="pull-right text-dark">{invoice_payment_method}</span></p>

                                            </div>
                                        @elseif($emailTemplate->slug == 'new_payment_reminder')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Customer Name') }} : <span
                                                        class="pull-right text-dark">{customer_name}</span></p>
                                                <p class="col-4">{{ __('Customer Email') }} : <span
                                                        class="pull-right text-dark">{customer_email}</span></p>
                                                <p class="col-4">{{ __('Payment Reminder Name') }} : <span
                                                        class="pull-right text-dark">{payment_reminder_name}</span></p>
                                                <p class="col-4">{{ __('Invoice Payment Number') }} : <span
                                                        class="pull-right text-dark">{invoice_payment_number}</span></p>
                                                <p class="col-4">{{ __('Payment Due Amount') }} : <span
                                                        class="pull-right text-dark">{invoice_payment_dueAmount}</span></p>
                                                <p class="col-4">{{ __('Payment Reminder Date') }} : <span
                                                        class="pull-right text-dark">{payment_reminder_date}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'new_bill_payment')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Payment Name') }} : <span
                                                        class="pull-right text-dark">{payment_name}</span></p>
                                                <p class="col-4">{{ __('Payment Bill') }} : <span
                                                        class="pull-right text-dark">{payment_bill}</span></p>
                                                <p class="col-4">{{ __('Payment Amount') }} : <span
                                                        class="pull-right text-dark">{payment_amount}</span></p>
                                                <p class="col-4">{{ __('Payment Date') }} : <span
                                                        class="pull-right text-dark">{payment_date}</span></p>
                                                <p class="col-4">{{ __('Payment Method') }} : <span
                                                        class="pull-right text-dark">{payment_method}</span></p>

                                            </div>
                                        @elseif($emailTemplate->slug == 'bill_resent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Vendor Name') }} : <span
                                                        class="pull-right text-dark">{vender_name}</span></p>
                                                <p class="col-4">{{ __('Vendor Email') }} : <span
                                                        class="pull-right text-dark">{vender_email}</span></p>
                                                <p class="col-4">{{ __('Bill Name') }} : <span
                                                        class="pull-right text-dark">{bill_name}</span></p>
                                                <p class="col-4">{{ __('Bill Number') }} : <span
                                                        class="pull-right text-dark">{bill_number}</span></p>
                                                <p class="col-4">{{ __('Bill Url') }} : <span
                                                        class="pull-right text-dark">{bill_url}</span></p>

                                            </div>
                                        @elseif($emailTemplate->slug == 'proposal_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Proposal Name') }} : <span
                                                        class="pull-right text-dark">{proposal_name}</span></p>
                                                <p class="col-4">{{ __('Proposal Email') }} : <span
                                                        class="pull-right text-dark">{proposal_number}</span></p>
                                                <p class="col-4">{{ __('Proposal Url') }} : <span
                                                        class="pull-right text-dark">{proposal_url}</span></p>


                                            </div>
                                        @elseif($emailTemplate->slug == 'complaint_resent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Complaint Name') }} : <span
                                                        class="pull-right text-dark">{complaint_name}</span></p>
                                                <p class="col-4">{{ __('Complaint Title') }} : <span
                                                        class="pull-right text-dark">{complaint_title}</span></p>
                                                <p class="col-4">{{ __('Complaint Against') }} : <span
                                                        class="pull-right text-dark">{complaint_against}</span></p>
                                                <p class="col-4">{{ __('Complaint Date') }} : <span
                                                        class="pull-right text-dark">{complaint_date}</span></p>
                                                <p class="col-4">{{ __('Complaint Date') }} : <span
                                                        class="pull-right text-dark">{complaint_description}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'leave_action_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Leave Name') }} : <span
                                                        class="pull-right text-dark">{leave_name}</span></p>
                                                <p class="col-4">{{ __('Leave Status') }} : <span
                                                        class="pull-right text-dark">{leave_status}</span></p>
                                                <p class="col-4">{{ __('Leave Reason') }} : <span
                                                        class="pull-right text-dark">{leave_reason}</span></p>
                                                <p class="col-4">{{ __('Leave Start Date') }} : <span
                                                        class="pull-right text-dark">{leave_start_date}</span></p>
                                                <p class="col-4">{{ __('Leave End Date') }} : <span
                                                        class="pull-right text-dark">{leave_end_date}</span></p>
                                                <p class="col-4">{{ __('Leave Days') }} : <span
                                                        class="pull-right text-dark">{total_leave_days}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'payslip_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Employee Name') }} : <span
                                                        class="pull-right text-dark">{employee_name}</span></p>
                                                <p class="col-4">{{ __('Employee Email') }} : <span
                                                        class="pull-right text-dark">{employee_email}</span></p>
                                                <p class="col-4">{{ __('Payslip Name') }} : <span
                                                        class="pull-right text-dark">{payslip_name}</span></p>
                                                <p class="col-4">{{ __('Payslip Salary Month ') }} : <span
                                                        class="pull-right text-dark">{payslip_salary_month}</span></p>
                                                <p class="col-4">{{ __('Payslip Url') }} : <span
                                                        class="pull-right text-dark">{payslip_url}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'promotion_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p clss="col-4">{{ __('Employee Name') }} : <span
                                                        class="pull-right text-dark">{employee_name}</span></p>
                                                <p class="col-4">{{ __('Designation') }} : <span
                                                        class="pull-right text-dark">{promotion_designation}</span></p>
                                                <p class="col-4">{{ __('Promotion Title') }} : <span
                                                        class="pull-right text-dark">{promotion_title}</span></p>
                                                <p class="col-4">{{ __('Promotion Date') }} : <span
                                                        class="pull-right text-dark">{promotion_date}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'resignation_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                {{--                                        <p class="col-4">{{__('Employee Name')}} : <span class="pull-right text-dark">{employee_name}</span></p> --}}
                                                <p class="col-4">{{ __('Employee Email') }} : <span
                                                        class="pull-right text-dark">{resignation_email}</span></p>
                                                <p class="col-4">{{ __('Employee Name') }} : <span
                                                        class="pull-right text-dark">{assign_user}</span></p>
                                                <p class="col-4">{{ __('Last Working Date') }} : <span
                                                        class="pull-right text-dark">{resignation_date}</span></p>
                                                <p class="col-4">{{ __('Resignation Date') }} : <span
                                                        class="pull-right text-dark">{notice_date}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'termination_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Employee Name') }} : <span
                                                        class="pull-right text-dark">{termination_name}</span></p>
                                                <p class="col-4">{{ __('Employee Email') }} : <span
                                                        class="pull-right text-dark">{termination_email}</span></p>
                                                <p class="col-4">{{ __('Notice Date') }} : <span
                                                        class="pull-right text-dark">{notice_date}</span></p>
                                                <p class="col-4">{{ __('Termination Date') }} : <span
                                                        class="pull-right text-dark">{termination_date}</span></p>
                                                <p class="col-4">{{ __('Termination Type') }} : <span
                                                        class="pull-right text-dark">{termination_type}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'transfer_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Employee Name') }} : <span
                                                        class="pull-right text-dark">{transfer_name}</span></p>
                                                <p class="col-4">{{ __('Employee Email') }} : <span
                                                        class="pull-right text-dark">{transfer_email}</span></p>
                                                <p class="col-4">{{ __('Transfer Date') }} : <span
                                                        class="pull-right text-dark">{transfer_date}</span></p>
                                                <p class="col-4">{{ __('Transfer Department') }} : <span
                                                        class="pull-right text-dark">{transfer_department}</span></p>
                                                <p class="col-4">{{ __('Transfer Branch') }} : <span
                                                        class="pull-right text-dark">{transfer_branch}</span></p>
                                                <p class="col-4">{{ __('Transfer Desciption') }} : <span
                                                        class="pull-right text-dark">{transfer_description}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'trip_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Employee ') }} : <span
                                                        class="pull-right text-dark">{trip_name}</span></p>
                                                <p class="col-4">{{ __('Purpose of Trip') }} : <span
                                                        class="pull-right text-dark">{purpose_of_visit}</span></p>
                                                <p class="col-4">{{ __('Start Date') }} : <span
                                                        class="pull-right text-dark">{start_date}</span></p>
                                                <p class="col-4">{{ __('End Date') }} : <span
                                                        class="pull-right text-dark">{end_date}</span></p>
                                                <p class="col-4">{{ __('Country') }} : <span
                                                        class="pull-right text-dark">{place_of_visit}</span></p>
                                                <p class="col-4">{{ __('Description') }} : <span
                                                        class="pull-right text-dark">{trip_description}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'vender_bill_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Vendor Name') }} : <span
                                                        class="pull-right text-dark">{vender_bill_name}</span></p>
                                                <p class="col-4">{{ __('Bill Number') }} : <span
                                                        class="pull-right text-dark">{vender_bill_number}</span></p>
                                                <p class="col-4">{{ __('Bill Url') }} : <span
                                                        class="pull-right text-dark">{vender_bill_url}</span></p>
                                            </div>
                                        @elseif($emailTemplate->slug == 'warning_sent')
                                            <div class="row">
                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-end text-dark">{app_name}</span></p>
                                                <p class="col-4">{{ __('Company Name') }} : <span
                                                        class="pull-right text-dark">{company_name}</span></p>
                                                <p class="col-4">{{ __('App Url') }} : <span
                                                        class="pull-right text-dark">{app_url}</span></p>
                                                <p class="col-4">{{ __('Employee Name') }} : <span
                                                        class="pull-right text-dark">{employee_warning_name}</span></p>
                                                <p class="col-4">{{ __('Subject') }} : <span
                                                        class="pull-right text-dark">{warning_subject}</span></p>
                                                <p class="col-4">{{ __('Description') }} : <span
                                                        class="pull-right text-dark">{warning_description}</span></p>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('from', __('From'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('from', $emailTemplate->from, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>


                        <div class="form-group col-12">
                            {{ Form::label('content', __('Email Message'), ['class' => 'col-form-label text-dark']) }}
                            <textarea name="content" id="summernote" style="height: 120px;" class="form-control font-style summernote">{{ $currEmailTempLang->content }}</textarea>
                        </div>


                        <div class="modal-footer">
                            {{ Form::hidden('lang', null) }}
                            {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-dark']) }}
                        </div>

                        {{ Form::close() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endif
@endsection
