@extends('layouts.admin')
@section('page-title')
    Send Bulk Email
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
    <li class="breadcrumb-item active" aria-current="page">{{ __('Send Markeeting emails') }}</li>
@endsection
@section('action-btn')
    <div class="row">
        <div class="col-lg-6">

        </div>
        <div class="col-lg-6">
            <div class="text-end">
                <div class="d-flex justify-content-end drp-languages">
                    <ul class="list-unstyled mb-0 m-2 d-none">
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
                                    <a href="{{ route('send.bulk.email.get', ['ids' => $_GET['ids'], 'templateID' => $EmailTemplate->id]) }}"
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
                    {{ Form::model($currEmailTempLang, ['route' => ['send.bulk.email',  ['ids' => $_GET['ids'], 'templateID' => $EmailTemplate->id]], 'method' => 'POST']) }}
                    <div class="row">
                      

                        <div class="form-group col-6">
                            {{ Form::label('emailFrom', __('Email From'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::email('emailFrom', $brandEmail, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <span class="clearfix"></span>

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
                            {{ Form::submit(__('Send Mail'), ['class' => 'btn btn-xs btn-dark']) }}
                        </div>

                        {{ Form::close() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endif
@endsection
