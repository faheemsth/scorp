@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Training') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Training') }}</li>
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


            <div class="card me-3">
                <div class="card-header d-flex justify-content-between align-items-baseline">
                    <h4>Training Information</h4>
                </div>
                <div class="card-body px-2">
                     <div class="table-responsive" style="margin-top: auto;">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Training Type') }}</th>
                                    <th>{{ __('Brand') }}</th>
                                    <th>{{ __('Region') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Trainer') }}</th>
                                    <th>{{ __('Training Duration') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                </tr>
                            </thead>
                            <tbody class="font-style">
                                @foreach ($trainings as $training)
                                    <tr>
                                        <td>
                                            <span style="cursor:pointer" class="lead-name hyper-link"
                                                onclick="openSidebar('/training/view?id=<?= $training->id ?>')"
                                                data-lead-id="{{ $training->id }}">{{ !empty($training->types) ? $training->types->name : '' }}</span>
                                        </td>

                                        <td>{{ $training->brand }}</td>
                                        <td>{{ $training->region }}</td>
                                        <td>{{ $training->branch }}</td>
                                        </td>
                                        <td>
                                            @if ($training->status == 0)
                                                <span
                                                    class="status_badge badge bg-warning p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                            @elseif($training->status == 1)
                                                <span
                                                    class="status_badge badge bg-primary p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                            @elseif($training->status == 2)
                                                <span
                                                    class="status_badge badge bg-success p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                            @elseif($training->status == 3)
                                                <span
                                                    class="status_badge badge bg-info p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $training->assignName }} </td>
                                        <td>{{ !empty($training->trainers) ? $training->trainers->firstname : '' }}</td>
                                        <td>{{ \Auth::user()->dateFormat($training->start_date) . ' to ' . \Auth::user()->dateFormat($training->end_date) }}
                                        </td>
                                        <td>{{ \Auth::user()->priceFormat($training->training_cost) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            @include('hrmhome.hrm_setup_activity')
        </div>
    </div>
@endsection
