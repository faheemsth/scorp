@extends('layouts.admin')
@section('page-title')
    {{ __('Jobs') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Jobs') }}</li>
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
                    <h4>Jobs Information</h4>
                </div>
                <div class="card-body px-2">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                            aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <!-- Open Accordion Item -->

                                @foreach ($jobs as $job)
                                    <div class="card content my-2 bg-white">
                                        <div class="card-header">
                                            {{$job->title}}  
                                        </div>


                                            <div class="card-body px-2">
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade active show" id="pills-details"
                                                        role="tabpanel" aria-labelledby="pills-details-tab">
                                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    id="panelsStayOpen-headingkeyone{{$job->id}}">
                                                                    <button class="accordion-button p-2" type="button"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#panelsStayOpen-collapsekeyone{{$job->id}}">
                                                                        Details
                                                                    </button>
                                                                </h2>

                                                                <div id="panelsStayOpen-collapsekeyone{{$job->id}}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="panelsStayOpen-headingkeyone{{$job->id}}">
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
                                                                                            {{ $job->id }}
                                                                                        </td>
                                                                                    </tr>
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Title') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$job->title}}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Brand') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$filters['users'][$job->brand_id] ?? ''}}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Region') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$filters['regions'][$job->region_id] ?? ''}}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Branch') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{$filters['branches'][$job->branch] ?? ''}}
                                                                                        </td>
                                                                                    </tr>
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Status') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            @if($job->status=='active')
                                                                                            <span class="px-4 py-2 rounded badge bg-success">{{App\Models\Job::$status[$job->status]}}</span>
                                                                                            @else
                                                                                            <span class="px-4 py-2 rounded badge bg-danger">{{App\Models\Job::$status[$job->status]}}</span>
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Created at') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{\Auth::user()->dateFormat($job->created_at)}}
                                                                                        </td>
                                                                                    </tr>
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('Start Date') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{\Auth::user()->dateFormat($job->start_date)}}
                                                                                        </td>
                                                                                    </tr>
                        
                        
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            {{ __('End Date') }}
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            {{\Auth::user()->dateFormat($job->end_date)}}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="" style="width: 150px; font-size: 14px;">
                                                                                            Skills
                                                                                        </td>
                                                                                        <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                                            @foreach(explode(',', $job->skill) as $skill)
                                                                                                <span class="p-2 px-3 rounded badge bg-primary">{{ $skill }}</span>
                                                                                            @endforeach
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>




                                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    id="panelsStayOpen-headingkeytwo{{$job->id}}">
                                                                    <button class="accordion-button p-2" type="button"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#panelsStayOpen-collapsekeytwo{{$job->id}}">
                                                                        Requirements
                                                                    </button>
                                                                </h2>

                                                                <div id="panelsStayOpen-collapsekeytwo{{$job->id}}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="panelsStayOpen-headingkeytwo{{$job->id}}">
                                                                    <div class="accordion-body">
                                                                        <div class="col-12">
                                                                            <div class="row">
                        
                                                                                @if(($job->applicant))
                                                                                <div class="col-6">
                                                                                    <h6>{{__('Need to ask ?')}}</h6>
                                                                                    <ul class="">
                                                                                        @foreach(explode(',', $job->applicant) as $applicant)
                                                                                        <li>{{ucfirst($applicant)}}</li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                                @endif
                                                                                @if(!empty($job->visibility))
                                                                                <div class="col-6">
                                                                                    <h6>{{__('Need to show option ?')}}</h6>
                                                                                    <ul class="">
                                                                                        @foreach(explode(',', $job->visibility) as $visibility)
                                                                                        <li>{{ucfirst($visibility)}}</li>
                                                                                        @endforeach
                                                                                    </ul>
                        
                                                                                </div>
                                                                                @endif
                        
                                                                                @if(count($job->questions())>0)
                                                                                <div class="col-12">
                                                                                    <h6>{{__('Custom Question')}}</h6>
                                                                                    <ul class="">
                                                                                        @foreach($job->questions() as $question)
                                                                                        <li>{{$question->question}}</li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                        
                                                                            <div class="row ">
                                                                                <div class="col-12 mt-3">
                                                                                    <h6>{{__('Job Description')}}</h6>
                                                                                    {!! $job->description !!}
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-12 mt-3">
                                                                                    <h6>{{__('Job Requirement')}}</h6>
                                                                                    {!! $job->requirement !!}
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
