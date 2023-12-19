@extends('layouts.admin')
<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}


@section('page-title')
{{ __('Global Search') }}
@endsection

@push('css-page')
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}" id="main-style-link">
@endpush


@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Global Search') }}</li>
@endsection

@push('css-page')
<link rel="stylesheet" href="{{ asset('assets/js/drag-resize-columns/dist/jquery.resizableColumns.css') }}">
@endpush


@section('content')

<div class="row">
    <div class="col-12">

        @if(isset($tasks))
        <div class="card">
            <div class="card-body">
                <h3>Tasks</h3>
                <div class="table-responsive mt-3">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Assigned To') }}</th>
                                <th>{{ __('Company/Team') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="tasks_tbody">
                            @forelse($tasks as $key => $task)
                            @php

                            $due_date = strtotime($task->due_date);
                            $current_date = strtotime(date('Y-m-d'));

                            if ($due_date < $current_date && strtolower($task->status) == 0) {
                                $color_code = 'bg-danger-scorp';
                                }elseif (strtolower($task->status) == 1) {
                                $color_code = 'bg-success-scorp';
                                }
                                elseif ($due_date == $current_date && strtolower($task->status) == 0) {
                                $color_code = 'bg-warning-scorp';
                                }else {
                                $color_code = 'bg-secondary-scorp';
                                }

                                @endphp
                                <tr>
                                    <td> <span class="badge {{ $color_code }} text-white">{{ $task->due_date }}</span>
                                    </td>
                                    <td>
                                        <span style="cursor:pointer" class="task-name hyper-link" @can('view task') onclick="openNav(<?= $task->id ?>)" @endcan data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                    </td>
                                    <td>
                                        @if (!empty($task->assigned_to))
                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')">
                                            {{ $users[$task->assigned_to] }}
                                        </span>
                                        @endif
                                    </td>

                                    <td>

                                        @if (!empty($task->assigned_to))
                                        @if ($task->assigned_type == 'company')
                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')">
                                            {{ $users[$task->assigned_to] }}
                                        </span>
                                        @else
                                        <?php
                                        $assigned_user = \App\Models\User::findOrFail($task->assigned_to);
                                        ?>

                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $assigned_user->created_by }}+'/user_detail')">
                                            {{ isset($users[$assigned_user->created_by]) ? $users[$assigned_user->created_by] : '' }}
                                        </span>
                                        @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($task->status == 1)
                                        <span class="badge {{ $color_code }} text-white">{{ __('Completed') }}</span>
                                        @else
                                        <span class="badge {{ $color_code }} text-white">{{ __('On Going') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(isset($universities))
        <div class="card">
            <div class="card-body">
                <h3>Toolkit</h3>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#')}}</th>
                                <th scope="col">{{__('Name')}}</th>

                                <th scope="col">{{__('Country')}}</th>

                                <th scope="col">{{__('City')}}</th>
                                <th scope="col">{{__('Phone')}}</th>
                                <th scope="col">{{__('Note')}}</th>

                                @if(\Auth::user()->type == 'super admin')
                                <th scope="col">{{__('Created By')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($universities as $key => $university)

                            <tr class="font-style">
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    @if(!empty($university->name))
                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/university/'+{{$university->id}}+'/university_detail')">
                                        {{ !empty($university->name)?$university->name:'' }}
                                    </span>
                                    @endif

                                </td>
                                <td>{{ !empty($university->country)?$university->country:'' }}</td>
                                <td>{{ !empty($university->city)?$university->city:'' }}</td>
                                <td>{{ !empty($university->phone)?$university->phone:'' }}</td>
                                <td>{{ !empty($university->note)?$university->note:'' }}</td>

                                @if(\Auth::user()->type == 'super admin')
                                <td>{{ isset($users[$university->created_by]) ? $users[$university->created_by] : '' }}</td>
                                @endif
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif


        @if(isset($leads))
        <div class="card">
            <div class="card-body">
                <h3>Leads</h3>


                <div class="table-responsive leads-list-div" style="padding: 25px 3px; width:auto;">
                    <table class="table">
                        <tr>


                            <th data-resizable-columns-id="name">{{ __('Name') }}</th>
                            <th data-resizable-columns-id="email_address" class="ps-3">
                                {{ __('Email Address') }}
                            </th>
                            <th data-resizable-columns-id="phone" class="ps-3">{{ __('Phone') }}</th>
                            <th data-resizable-columns-id="stage" class="ps-3">{{ __('Stage') }}</th>
                            <th data-resizable-columns-id="users" class="ps-3">{{ __('Assigned to') }}</th>
                            @if (\Auth::user()->type == 'super admin')
                            <th data-resizable-columns-id="created_by">{{ __('Created By') }}</th>
                            @endif

                        </tr>
                        </thead>
                        <tbody class="leads-list-tbody">
                            @if (count($leads) > 0)
                            @foreach ($leads as $lead)
                            <tr>


                                <td class="">
                                    <span style="cursor:pointer" class="lead-name hyper-link" onclick="openSidebar('/get-lead-detail?lead_id='+{{$lead->id}})" data-lead-id="{{ $lead->id }}">{{ $lead->name }}</span>
                                </td>

                                <td class="">{{ $lead->email }}</td>
                                <td class="">{{ $lead->phone }}</td>
                                <td>{{ !empty($lead->stage) ? $lead->stage->name : '-' }}</td>
                                <td class="">
                                    @php
                                    $assigned_to = isset($lead->user_id) && isset($users[$lead->user_id]) ? $users[$lead->user_id] : 0;
                                    @endphp

                                    @if ($assigned_to != 0)
                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $lead->user_id }}+'/user_detail')">
                                        {{ $assigned_to }}
                                    </span>
                                    @endif
                                </td>
                                @if (\Auth::user()->type == 'super admin')
                                <td>{{ $users[$lead->created_by] }}</td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr class="font-style">
                                <td colspan="6" class="text-center">{{ __('No data available in table') }}
                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(isset($clients))
        <div class="card">
            <div class="card-body">
                <h3>Contacts</h3>
                <div class=" mt-3">
                    <table class="table">
                        <thead style="background: #ddd; color:rgb(0, 0, 0); font-size: 14px; font-weight: bold;">
                            <tr>
                                <th style="border-left: 1px solid #fff;">Name</th>
                                <th style="border-left: 1px solid #fff;">Email</th>
                                <th style="border-left: 1px solid #fff;">Admissions</th>
                                <th style="border-left: 1px solid #fff;">Applications</th>
                            </tr>
                        </thead>
                        <tbody class="leads-list-div" style="color:rgb(0, 0, 0); font-size: 14px;" class="new-organization-list-tbody">

                            @forelse($clients as $client)
                            <tr>
                                <td><span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/clients/'+{{$client->id}}+'/client_detail')">
                                        {{ $client->name }}
                                    </span>

                                </td>
                                <td>{{$client->email}}</td>
                                <td>{{$client->clientDeals->count()}}</td>
                                <td>{{$client->clientApplications($client->id)}}</td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif


        @if(isset($deals))
        <div class="card">
            <div class="card-body">
                <h3>Admissions</h3>

                <div class="mx-4 table-responsive leads-list-div">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Stage') }}</th>
                                <th style="width: 100px !important;">{{ __('Admission Name') }}</th>
                                <th>{{ __('Lead Source') }}</th>
                                <th>{{ __('Intake') }}</th>
                                <th class="">{{ __('Assigned to') }}</th>
                            </tr>
                        </thead>
                        <tbody id="deals_tbody">
                            @if (count($deals) > 0)
                            @foreach ($deals as $deal)
                            <tr>
                                <td>{{ $deal->stage->name }}</td>
                                <td style="width: 100px !important; ">
                                    <span style="cursor:pointer" class="deal-name hyper-link" onclick="openNav(<?= $deal->id ?>)" data-deal-id="{{ $deal->id }}">
                                        @if (strlen($deal->name) > 40)
                                        {{ substr($deal->name, 0, 40) }}...
                                        @else
                                        {{ $deal->name }}
                                        @endif
                                    </span>
                                </td>

                                <td>
                                    @php
                                    $lead = \App\Models\Lead::join('client_deals', 'client_deals.client_id', 'leads.is_converted')->where('client_deals.deal_id', $deal->id)->first();
                                    $source = isset($lead->sources) && isset($sources[$lead->sources]) ? $sources[$lead->sources] : '';
                                    @endphp

                                    {{ $source }}
                                </td>

                                <td>
                                    @php
                                    $month = !empty($deal->intake_month) ? $deal->intake_month : 'January';
                                    $year = !empty($deal->intake_year) ? $deal->intake_year : '2023';
                                    @endphp
                                    {{ $month.' 1 ,'.$year }}
                                </td>



                                <td class="">
                                    @php
                                    $assigned_to = isset($deal->assigned_to) && isset($users[$deal->assigned_to]) ? $users[$deal->assigned_to] : 0;
                                    @endphp

                                    @if($assigned_to != 0)
                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{$deal->assigned_to}}+'/user_detail')">
                                        {{ $assigned_to }}
                                    </span>
                                    @endif

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
        @endif

        @if(isset($applications))
        <div class="card">
            <div class="card-body">
                <h3>Applications</h3>

                <div class="table-responsive mt-3" style="width: 100%;">
                    <table class="table">
                        <thead class="" style="background-color:rgba(0, 0, 0, .08); font-weight: bold;">
                            <tr>

                                <td>
                                    {{ __('Name') }}
                                </td>
                                <td>
                                    {{ __('Application Key') }}
                                </td>

                                <td>
                                    {{ __('University') }}
                                </td>

                                <td>
                                    {{ __('Intake') }}
                                </td>

                                <td>
                                    {{ __('Status') }}
                                </td>
                            </tr>
                        </thead>
                        <tbody class="application_tbody">

                            @forelse($applications as $app)
                            <tr>
                                <td>
                                    <span style="cursor:pointer" class="hyper-link" @can('view application') onclick="openSidebar('deals/'+{{ $app->id }}+'/detail-application')" @endcan>
                                        {{ $shortened_name = substr($app->name, 0, 10) }}
                                        {{ strlen($app->name) > 10 ? $shortened_name . '...' : $app->name }}
                                    </span>
                                </td>
                                <td>
                                    {{ $shortened_name = substr($app->application_key, 0, 10) }}
                                    {{ strlen($app->application_key) > 10 ? $shortened_name . '...' : $app->application_key}}
                                </td>
                                <td>{{ isset($app->university_id) && isset($universities_arr[$app->university_id]) ? $universities_arr[$app->university_id] : '' }}</td>

                                <td>
                                    {{ $app->intake }}
                                </td>
                                <td>
                                    {{ isset($app->stage_id) && isset($deal_stages[$app->stage_id]) ? $deal_stages[$app->stage_id] : '' }}
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

        @endif


        @if(isset($organizations))
          <div class="card">
            <div class="card-body">
                <h3>Organizations</h3>

                <div class=" mt-3">
                    <table class="table">
                        <thead style="background: #ddd; color:rgb(0, 0, 0); font-size: 14px;">
                            <tr>
                                <td style="border-left: 1px solid #fff;">Organization Name</td>
                                <td style="border-left: 1px solid #fff;">Phone</td>
                                <td style="border-left: 1px solid #fff;">Billing Street</td>
                                <td style="border-left: 1px solid #fff;">Billing City</td>
                                <td style="border-left: 1px solid #fff;">Billing State</td>
                                <td style="border-left: 1px solid #fff;">Billing Country</td>
                            </tr>
                        </thead>
                        <tbody class="organization_tbody" style="color:rgb(0, 0, 0); font-size: 14px;" class="new-organization-list-tbody">

                            @forelse($organizations as $org)
                            @php
                            $org_data = $org->organization($org->id);

                            @endphp

                            <tr>
                                <td class="">
                                    <span style="cursor:pointer" class="org-name hyper-link" onclick="openSidebar('/get-organization-detail?org_id='+{{$org->id}})" data-org-id="{{ $org->id }}">{{$org->name}}</span>
                                </td>
                                <td class="">{{ isset($org_data->phone) ? $org_data->phone : '' }}</td>
                                <td class="">{{ isset($org_data->billing_street) ? $org_data->billing_street : '' }}</td>
                                <td class="">{{ isset($org_data->billing_city) ? $org_data->billing_city : ''  }}</td>
                                <td class="">{{ isset($org_data->billing_state) ? $org_data->billing_state : ''  }}</td>
                                <td class="">{{ isset($org_data->billing_country) ? $org_data->billing_country : ''  }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="">
                                    No Organizations found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
          </div>

        @endif

    </div>
</div>

@endsection
