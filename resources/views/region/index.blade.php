@extends('layouts.admin')
@section('page-title')
    {{ __('Regions') }}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script>
        $(document).on("change", ".change-pipeline select[name=default_pipeline_id]", function() {
            $('#change-pipeline').submit();
        });

        $(".add-filter").on("click", function() {
            $(".filter-data").toggle();
        })
    </script>
@endpush

<style>
    .stages h2 {
        font-size: 16px;
        line-height: 14px;
        display: inline-block;
        white-space: nowrap;
        font-weight: bold;
        margin-top: 10px;
    }

    .wizard {
        font-size: 8px;
    }

    .wizard a {
        padding: 8px 8px 8px 25px !important;
        background: #efefef;
        position: relative;
        display: inline-block;

    }

    .wizard a:before {
        width: 0;
        height: 0;
        border-top: 20px inset transparent;
        border-bottom: 20px inset transparent;
        border-left: 20px solid #fff;
        position: absolute;
        content: "";
        top: 0;
        left: 0;
    }

    .wizard a:after {
        width: 0;
        height: 0;
        border-top: 20px inset transparent;
        border-bottom: 20px inset transparent;
        border-left: 20px solid #efefef;
        position: absolute;
        content: "";
        top: 0;
        right: -20px;
        z-index: 2;
    }

    .wizard a:first-child:before,
    .wizard a:last-child:after {
        border: none;
    }

    .wizard a:first-child {
        -webkit-border-radius: 4px 0 0 4px;
        -moz-border-radius: 4px 0 0 4px;
        border-radius: 4px 0 0 4px;
    }

    .wizard a:last-child {
        -webkit-border-radius: 0 4px 4px 0;
        -moz-border-radius: 0 4px 4px 0;
        border-radius: 0 4px 4px 0;
    }

    .wizard .badge {
        margin: 0 5px 0 18px;
        position: relative;
        top: -1px;
    }

    .wizard a:first-child .badge {
        margin-left: 0;
    }

    .wizard .current {
        background: #1F2735;
        color: #fff;
    }

    .wizard .current:after {
        border-left-color: #1F2735;
    }

    .wizard .done {
        background: #B3CDE1 !important;
        color: #1F2735;
    }

    .wizard .done:after {
        border-left-color: #B3CDE1 !important;
    }

    .lead-topbar {
        border-bottom: 1px solid rgb(230, 230, 230);
        background-color: white !important;
    }

    .lead-avator img {
        width: 50px;
        height: 50px;
        /* border-radius: 50px; */
    }

    .lead-basic-info {
        margin: 10px 0px -10px 10px;
    }

    .lead-basic-info span {
        font-size: 10px;
    }

    .lead-basic-info p {
        font-weight: bold;
    }

    .lead-info {
        border-bottom: 1px solid rgb(230, 230, 230);
    }

    .lead-info small {
        font-size: 13px;
        line-height: 14px;
        display: block;
    }

    .lead-info span {
        font-size: 14px;
        line-height: 18px;
        width: calc(100% - 10px);
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-btn {
        display: inline-grid !important;
    }

    .accordion-button {
        font-size: 13px !important;
        justify-content: flex-end;
        flex-direction: row-reverse;
        margin-right: 10px;
        letter-spacing: 0.02rem;
        font-weight: 700;
    }

    .accordion-button::after {
        margin-left: 0px !important;
        margin-right: 10px;
    }

    .accordion-button:focus {
        box-shadow: none;
        background: #e9ecef !important;
    }

    .accordion-button.collapsed::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='220px' height='220px'%3E%3Cpath d='M12.1717 12.0005L9.34326 9.17203L10.7575 7.75781L15.0001 12.0005L10.7575 16.2431L9.34326 14.8289L12.1717 12.0005Z'%3E%3C/path%3E%3C/svg%3E") !important;
    }

    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'  width='32' height='32' %3E%3Cpath d='M12.1717 12.0005L9.34326 9.17203L10.7575 7.75781L15.0001 12.0005L10.7575 16.2431L9.34326 14.8289L12.1717 12.0005Z'%3E%3C/path%3E%3C/svg%3E") !important;
        transform: rotate(90deg) !important;
    }

    #tfont {
        font-size: 14px;
    }

    table tr td {
        font-size: 14px !important;
    }

    @media screen and (max-width: 480px) {
        .dash-header {
            left: 0 !important;
        }
    }

    @media screen and (max-width: 768px) {
        .dash-header {
            left: 0 !important;
        }
    }

    @media screen and (max-width: 1024px) {
        .dash-header {
            left: 0 !important;
        }
    }
</style>

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('CRM Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Regions') }}</li>
@endsection

@section('content')

    <style>
        .form-controls,
        .form-btn {
            padding: 4px 1rem !important;
        }

        /* Set custom width for specific table cells */
        .action-btn {
            display: inline-grid !important;
        }

        .dataTable-bottom,
        .dataTable-top {
            display: none;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="card my-card">
                <div class="card-body table-border-style">

                    {{-- topbar --}}
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-2">
                            <p class="mb-0 pb-0 ps-1">Regions</p>
                            <div class="dropdown">
                                <button class="dropdown-toggle all-leads" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    ALL Regions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item delete-bulk-deals" href="javascript:void(0)">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-2">
                            <!-- <p class="mb-0 pb-0">Tasks</p> -->
                            <div class="dropdown" id="actions_div" style="display:none">
                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item assigned_to" onClick="massUpdate()">Mass Update</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-8 d-flex justify-content-end gap-2">
                            <div class="input-group w-25">
                                <button class="btn btn-sm list-global-search-btn">
                                    <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search"
                                    placeholder="Search this list...">
                            </div>

                            @can('create region')
                            <a href="#" data-size="lg" data-url="{{ route('region.create') }}"
                                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create New Region') }}"
                                class="btn p-2 btn-dark">
                                <i class="ti ti-plus"></i>
                            </a>
                            @endcan

                        </div>
                    </div>





                    <div class="table-responsive mt-2">

                        {{-- Filters --}}


                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px !important;">
                                            <input type="checkbox" class="main-check">
                                        </th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Location') }}</th>

                                        <th class="text-align: left;">{{ __('Region\'s Manager') }}</th>
                                        <th width="300px" class="d-none">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="deals_tbody">
                                    @if (!empty($regions))
                                        @foreach ($regions as $deal)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="deals[]" value="{{ $deal->id }}"
                                                        class="sub-check">
                                                </td>
                                                <td>
                                                    <span style="cursor:pointer" class="hyper-link"
                                                           @can('view region') onclick="openSidebar('/regions/{{ $deal->id }}/show')" @endcan >
                                                            {{ $deal->name }}
                                                    </span>
                                                </td>
                                                <td>{{ $deal->email }}</td>
                                                <td>{{ $deal->phone }}</td>
                                                <td>{{ $deal->location }}</td>
                                                <td>{{ optional($deal->manager)->name }}</td>

                                                <td class="Action d-none">
                                                    <div class="dropdown">
                                                        <button class="btn bg-transparents" type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                width="18" height="18">
                                                                <path
                                                                    d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                            @can('edit region')
                                                            <li><a class="dropdown-item"
                                                                    href="#" data-size="lg" data-url="{{ url('region/update?id=').$deal->id }}" title="{{ __('Update Origin') }}"
                                                                    data-ajax-popup="true" data-bs-toggle="tooltip">Edit</a></li>
                                                            @endcan
                                                            @can('delete region')
                                                                    <li><a class="dropdown-item"
                                                                    href="{{ url('region/delete?id=').$deal->id }}">Delete</a>
                                                            </li>
                                                            @endcan
                                                        </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="font-style">
                                            <td colspan="6" class="text-center">
                                                {{ __('No data available in table') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="mySidenav"
            style="z-index: 1065; padding-left:5px; box-shadow:0px 4px 4px 0px rgba(0, 0, 0, 0.25);background-color: #EFF3F7;"
            class="sidenav <?= isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>">
        </div>
    @endsection

    @push('script-page')
        <script></script>
    @endpush
