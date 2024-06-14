@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Leave') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave') }}</li>
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
                    <h4>Leave Information</h4>
                </div>
                <div class="card-body px-2">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                            aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <!-- Open Accordion Item -->
                                
                                @foreach ($leaves as $leave)
                                    <div class="card content my-2 bg-white">
                                            <div class="card-body px-2">
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade active show" id="pills-details"
                                                        role="tabpanel" aria-labelledby="pills-details-tab">
                                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    id="panelsStayOpen-headingkeyone{{$leave->id}}">
                                                                    <button class="accordion-button p-2" type="button"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#panelsStayOpen-collapsekeyone{{$leave->id}}">
                                                                        Leave Details
                                                                    </button>
                                                                </h2>

                                                                <div id="panelsStayOpen-collapsekeyone{{$leave->id}}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="panelsStayOpen-headingkeyone{{$leave->id}}">
                                                                    <div class="accordion-body">

                                                                        <div class="table-responsive mt-1"
                                                                            style="margin-left: 10px;">

                                                                            <table>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Record ID') }}
                                                                                        </td>
                                                                                        <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{ $leave->id }}
                                                                                        </td>
                                                                                    </tr>
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            Leave Type
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            <?php $leavetype = App\Models\LeaveType::find($leave->leave_type_id);?>
                                                                                            {{ $leavetype->title }}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Brand') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$leave->brand}}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Region') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$leave->region}}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Branch') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$leave->branch}}
                                                                                        </td>
                                                                                    </tr>
                        
                                                                                    
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Start Date') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{ \Auth::user()->dateFormat($leave->start_date ) }}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                  

                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('End Date') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{ \Auth::user()->dateFormat($leave->end_date )  }}
                                                                                        </td>
                                                                                    </tr>



                                                                                    
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Applied On') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{ \Auth::user()->dateFormat($leave->applied_on )}}
                                                                                        </td>
                                                                                    </tr>




                                                                                    {{--  --}}

                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Status') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            @if($leave->status=="Pending")
                                                                                            <div class="status_badge badge bg-warning p-2 px-3 rounded">{{ $leave->status }}</div>
                                                                                            @elseif($leave->status=="Approved")
                                                                                            <div class="status_badge badge bg-success p-2 px-3 rounded">{{ $leave->status }}</div>
                                                                                            @else($leave->status=="Reject")
                                                                                            <div class="status_badge badge bg-danger p-2 px-3 rounded">{{ $leave->status }}</div>
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>


                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Total Days') }}
                                                                                        </td>
                                                                                        @php
                                                                                        $startDate = new \DateTime($leave->start_date);
                                                                                        $endDate = new \DateTime($leave->end_date);
                                                                                        $total_leave_days = !empty($startDate->diff($endDate))?$startDate->diff($endDate)->days:0;
                                                                                        @endphp
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{ $total_leave_days }}
                                                                                        </td>
                                                                                    </tr>

                                                                                    {{--  --}}

                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Created at') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$leave->created_at}}
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
                                @endforeach
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
