@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Designation') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Designation') }}</li>
@endsection
@section('content')
    <style>
        .table td,
        .table th {
            font-size: 14px;
        }
    </style>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .list-group-item.active {
            background-color: #007bff;
            border-color: #007bff;
        }

        .list-group-item:hover {
            background-color: #f1f1f1;
        }

        .sticky-top {
            top: 30px;
        }
    </style>
    <div class="row">
        <div class="col-3">
            @include('hrmhome.hrm_setup_routes')
        </div>
        <div class="col-6">
               {{-- employee info --}}
            @include('hrmhome.employee')
               {{-- employee info --}}
            @include('hrmhome.contact')
               {{-- employee info --}}
            @include('hrmhome.emergency')
        </div>
        <div class="col-3">
            @include('hrmhome.hrm_setup_activity')
        </div>
    </div>
@endsection
