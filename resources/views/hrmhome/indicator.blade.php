@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Indicator') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Indicator') }}</li>
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
                    <h4>Indicator Information</h4>
                </div>
                <div class="card-body px-2">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                            aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <!-- Open Accordion Item -->
                                <div class="card me-3">
                                    <div class="card-body px-2">
                                        <div class="tab-content" id="pills-tabContent">
                                            {{-- Details Pill Start --}}
                                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                                aria-labelledby="pills-details-tab">

                                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                                    <!-- Open Accordion Item -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                                            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                                                {{ __('INDICATOR INFORMATION') }}
                                                            </button>
                                                        </h2>
                
                                                        <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                                                            <div class="accordion-body">
                
                                                                <div class="table-responsive mt-1" style="margin-left: 10px;">
                
                                                                    <table>
                                                                        <tbody>
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Record ID') }}
                                                                                </td>
                                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                    {{ $indicator->id }}
                                                                                </td>
                                                                            </tr>
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Brand') }}
                                                                                </td>
                                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                {{ $indicator->brand }}
                                                                                </td>
                                                                            </tr>
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Region') }}
                                                                                </td>
                                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                   <a href="{{ $indicator->region }}" target="_blank" >{{ $indicator->region }}</a>
                                                                                </td>
                                                                            </tr>
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Branch') }}
                                                                                </td>
                                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                     {{ $indicator->branch }}
                                                                                </td>
                                                                            </tr>
                
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Designation') }}
                                                                                </td>
                                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                    {{ !empty($indicator->designations)?$indicator->designations->name:'' }}
                                                                                </td>
                                                                            </tr>
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Department') }}
                                                                                </td>
                                                                                <td style="padding-left: 10px; font-size: 14px;">
                                                                                        <span>{{ !empty($indicator->departments)?$indicator->departments->name:'' }}</span>
                                                                                </td>
                                                                            </tr>
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Created at') }}
                                                                                </td>
                                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                {{ $indicator->created_at }}
                                                                                </td>
                                                                            </tr>
                
                                                                            <tr>
                                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                                    {{ __('Update at') }}
                                                                                </td>
                                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                {{ $indicator->updated_at }}
                                                                                </td>
                                                                            </tr>
                
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            @include('hrmhome.hrm_setup_activity')
        </div>
    </div>
@endsection
