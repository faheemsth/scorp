@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Attendance') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
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
                    <h4>Attendance Information</h4>
                </div>
                <div class="card-body px-2">
                     <div class="table-responsive" style="margin-top: auto;">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                @if(\Auth::user()->type!='Employee')
                                <th>{{__('Employee')}}</th>
                                @endif
                                <th>{{__('Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Clock In')}}</th>
                                <th>{{__('Clock Out')}}</th>
                                <th>{{__('Late')}}</th>
                                <th>{{__('Early Leaving')}}</th>
                                <th>{{__('Overtime')}}</th>
                                
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($attendanceEmployee as $attendance)
                            <tr>
                                @if(\Auth::user()->type!='Employee')
                                <td>{{!empty($attendance->employee)?$attendance->employee->name:'' }}</td>
                                @endif
                                <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                <td>{{ $attendance->status }}</td>
                                <td>{{ ($attendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_in):'00:00' }} </td>
                                <td>{{ ($attendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_out):'00:00' }}</td>
                                <td>{{ $attendance->late }}</td>
                                <td>{{ $attendance->early_leaving }}</td>
                                <td>{{ $attendance->overtime }}</td>
                                
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
