@extends('layouts.admin')
@section('page-title')
{{ __('Manage Admissions') }}
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

    table th:last-child {
        text-align: end;
    }

    table td:last-child {
        text-align: end;
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
<li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Admissions') }}</li>
@endsection

@section('content')
{{-- @if ($pipeline) --}}
@if (\Auth::user()->type == 'super admin')
<div class="row d-none">
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{ __('Total Deals') }}</small>
                        <h3 class="m-0">{{ count($deals) }}</h3>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-info">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{ __('This Month Total Deals') }}</small>
                        <h3 class="m-0">{{ 0 }}</h3>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-dark">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{ __('This Week Total Deals') }}</small>
                        <h3 class="m-0">{{ 0 }}</h3>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-dark">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{ __('Last 30 Days Total Deals') }}</small>
                        <h3 class="m-0">{{ 0 }}</h3>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-dark">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
                    <div class="col-4">
                        <p class="mb-0 pb-0 ps-1">ADMISSIONS</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle all-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL ADMISSIONS
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item delete-bulk-deals" href="javascript:void(0)">Delete</a></li>
                          </ul>
                        </div>
                    </div>


                    <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                        <div class="input-group w-25">
                            <button class="btn btn-sm list-global-search-btn">
                                <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list...">
                        </div>





                            <button data-bs-toggle="tooltip" title="{{__('Refresh')}}" class="btn px-2 pb-2 pt-2 refresh-list btn-dark" ><i class="ti ti-refresh" style="font-size: 18px"></i></button>

                        <button class="btn filter-btn-show p-2 btn-dark" type="button" data-bs-toggle="tooltip" title="{{__('Filter')}}">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>

                        {{-- <a href="#" data-size="lg" data-url="{{ route('deals.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create New Deal') }}" class="btn p-2 btn-dark">
                            <i class="ti ti-plus"></i>
                        </a> --}}
                        <a class="btn p-2 btn-dark  text-white assigned_to" data-bs-toggle="tooltip" title="{{__('Mass Update')}}" id="actions_div" style="display:none;font-weight: 500;" onClick="massUpdate()">Mass Update</a>


                    </div>
                </div>





                <div class="table-responsive mt-2">

                    {{-- Filters --}}
                    <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                        <form action="/deals/list" method="GET" class="">

                            <div class="row my-3">
                                <div class="col-md-4">
                                    <label for="">Name</label>
                                    <select name="name[]" id="deals" class="form form-control select2" multiple style="width: 95%;">
                                        <option value="">Select name</option>
                                        @foreach ($deals as $deal)
                                        <option value="{{ $deal->name }}" <?= isset($_GET['name']) && in_array($deal->name, $_GET['name']) ? 'selected' : '' ?> class="">{{ $deal->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label for="">Stages</label>
                                    <select name="stages[]" id="stages" class="form form-control select2" multiple style="width: 95%;">
                                        <option value="">Select Pipeline</option>
                                        @foreach ($stages as $key => $stage)
                                        <option value="{{ $key }}" <?= isset($_GET['stages']) && in_array($key, $_GET['stages']) ? 'selected' : '' ?> class="">{{ $stage }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
                                <div class="col-md-4"> <label for="">Brands</label>
                                    <select class="form form-control select2" id="choices-multiple555" name="created_by[]" multiple style="width: 95%;">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $key => $brand)
                                        <option value="{{ $key }}" <?= isset($_GET['created_by']) && in_array($key, $_GET['created_by']) ? 'selected' : '' ?> class="">{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <style>
                                    .form-control:focus {
                                        border: 1px solid rgb(209, 209, 209) !important;
                                    }
                                </style>
                                <div class="col-md-4 mt-2">
                                    <label for="">Created at</label>
                                    <input type="date" class="form form-control" name="created_at" value="<?= isset($_GET['created_at']) ? $_GET['created_at'] : '' ?>" style="width: 95%; border-color:#aaa">
                                </div>

                                <div class="col-md-4 mt-3">
                                    <br>
                                    <input type="submit" class="btn me-2 bg-dark" style=" color:white;">
                                    <a type="button" id="save-filter-btn" onClick="saveFilter('deals',<?= sizeof($deals) ?>)" class="btn form-btn me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
                                    <a href="/deals/list" class="btn bg-dark" style="color:white;">Reset</a>
                                </div>
                            </div>

                            <div class="row d-none">
                                <div class="enries_per_page" style="max-width: 300px; display: flex;">

                                    <?php
                                    $all_params = isset($_GET) ? $_GET : '';
                                    if (isset($all_params['num_results_on_page'])) {
                                        unset($all_params['num_results_on_page']);
                                    }
                                    ?>
                                    <input type="hidden" value="<?= http_build_query($all_params) ?>" class="url_params">
                                    <select name="" id="" class="enteries_per_page form form-control" style="width: 100px; margin-right: 1rem;">
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 25 ? 'selected' : '' ?> value="25">25</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 100 ? 'selected' : '' ?> value="100">100</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 300 ? 'selected' : '' ?> value="300">300</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 1000 ? 'selected' : '' ?> value="1000">1000</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == $total_records ? 'selected' : '' ?> value="{{ $total_records }}">all</option>
                                    </select>

                                    <span style="margin-top: 5px;">entries per page</span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table" data-resizable-columns-id="lead-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px !important;">
                                        <input type="checkbox" class="main-check">
                                    </th>
                                    <th style="width: 100px !important;">{{ __('Admission Name') }}</th>
                                    <th>{{ __('Passport') }}</th>
                                    <th>{{ __('Stage') }}</th>

                                    <th>{{ __('Lead Source') }}</th>

                                    <th>{{ __('Intake') }}</th>


                                    <th class="">{{ __('Assigned to') }}</th>
                                    <th width="300px" class="d-none">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="deals_tbody">
                                @if (count($deals) > 0)
                                @foreach ($deals as $deal)

                                @php 
                                $client = \App\Models\User::join('client_deals', 'client_deals.client_id', 'users.id')->where('client_deals.deal_id', $deal->id)->first();
                                $passport_number = isset($client->passport_number) ? $client->passport_number : '';
                                @endphp 
                                
                                <tr>
                                    <td>
                                        <input type="checkbox" name="deals[]" value="{{$deal->id}}" class="sub-check">
                                    </td>
                                    <td style="width: 100px !important; ">
                                        <span style="cursor:pointer" class="deal-name hyper-link" @can('view deal') onclick="openSidebar('/get-deal-detail?deal_id='+{{ $deal->id }})" @endcan data-deal-id="{{ $deal->id }}">

                                            @if (strlen($deal->name) > 40)
                                            {{ substr($deal->name, 0, 40) }}...
                                            @else
                                            {{ $deal->name }}
                                            @endif
                                        </span>
                                    </td>
                                    <td> {{ $passport_number }}</td>
                                    <td>{{ $deal->stage->name }}</td>
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
                                            {{$users[$deal->assigned_to] }}
                                        </span>
                                        @endif

                                    </td>


                                    @if (\Auth::user()->type != 'Client')
                                    <td class="Action d-none">
                                        <div class="dropdown">
                                            <button class="btn bg-transparents" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                                    <path d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="#">Change</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                                <li><a class="dropdown-item" href="#">Delete</a>
                                                </li>
                                            </ul>

                                    </td>
                                    @endif


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

                        @if ($total_records > 0)
                        @include('layouts.pagination', [
                        'total_pages' => $total_records,
                        'num_results_on_page' => 50,
                        ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" tabindex="-1" role="dialog" id="deal_applications" style="z-index: 1150;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Admission Applications</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="form form-control" id="admission-application" >

                    </select>

                    <input type="hidden" id="stage_id" value="">
                    <input type="hidden" id="deal_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark px-2" id="save-changes-application-status">Save changes</button>
                    <button type="button" class="btn btn-light px-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div id="mySidenav" style="z-index: 1065; padding-left:5px; box-shadow:0px 4px 4px 0px rgba(0, 0, 0, 0.25);background-color: #EFF3F7;" class="sidenav <?= isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>">


    </div>

<div class="modal" id="mass-update-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg my-0" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mass Update</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update-bulk-deals') }}" method="POST">
                @csrf
                <div class="modal-body" style="min-height: 40vh;">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="bulk_field" id="bulk_field" class="form form-control">
                                <option value="">Select Field</option>
                                <option value="adm_name">Admission Name</option>
                                <option value="intake_month">Intake Month</option>
                                <option value="intake_year">Intake Year</option>
                                <option value="linked_contact">Linked Contact</option>
                                <option value="user_res">User Reponsible</option>
                                <option value="category">Category</option>
                                <option value="institute">Institute</option>
                                <option value="org">Organization</option>
                                <option value="ofc_res">Office Responsible</option>
                                <option value="pipeline">Pipeline</option>
                                <option value="stage">Stage</option>
                                <option value="desc">Description</option>
                            </select>
                        </div>
                        <input name='deal_ids' id="deal_ids" hidden>
                        <div class="col-md-6" id="field_to_update">

                        </div>
                    </div>

                </div>
                <br>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-dark px-2" value="Update">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- @endif --}}
    @endsection


    @push('script-page')
    <script>
        $(document).ready(function() {
            let curr_url = window.location.href;
        
            if(curr_url.includes('?')){
                $('#save-filter-btn').css('display','inline-block');
            }
        });
        $('.filter-btn-show').click(function() {
            $("#filter-show").toggle();
        });

        $(document).on('change', '.main-check', function() {
            $(".sub-check").prop('checked', $(this).prop('checked'));
        });

        $(document).on('change', '.sub-check', function() {
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            console.log(selectedIds.length)

            if(selectedIds.length > 0){
                selectedArr = selectedIds;
                $("#actions_div").css('display', 'block');
            }else{
                selectedArr = selectedIds;

                $("#actions_div").css('display', 'none');
            }
            let commaSeperated = selectedArr.join(",");
            console.log(commaSeperated)
            $("#deal_ids").val(commaSeperated);

        });

        function massUpdate(){
            if(selectedArr.length > 0){
                $('#mass-update-modal').modal('show')
            }else{
                alert('Please choose Tasks!')
            }
        }

        $('#bulk_field').on('change', function() {

            if(this.value != ''){
                $('#field_to_update').html('');

                if(this.value == 'adm_name'){

                    let field = `<div>
                                    <input type="text" class=" form-control" placeholder="Admission Name" name="name" value="" required="">
                            </div>`;
                    $('#field_to_update').html(field);

                }else if(this.value == 'intake_month'){

                    var months = <?= json_encode($months) ?>;

                    let options = '';
                    $.each(months, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="intake_month" required>
                                    <option value="">Select Month</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'intake_year'){

                    var years = <?= json_encode($years) ?>;

                    let options = '';
                    $.each(years, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="intake_year" required>
                                    <option value="">Select Year</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'linked_contact'){

                    var clients = <?= json_encode($clients) ?>;
                    let options = '';

                    $.each(clients, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="contact[]" required>
                                    <option value="">Select Contact</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'user_res'){

                    var users = <?= json_encode($users) ?>;
                    let options = '';

                    $.each(users, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="assigned_to" required>
                                    <option value="">Select Employee</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'category'){

                    let field = `<select class="form-control select2" id="choice-5" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Canada">Canada</option>
                                    <option value="China">China</option>
                                    <option value="E-Learning">E-Learning</option>
                                    <option value="Europe">Europe</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States of America">United States of America</option>
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'institute'){

                    var universities = <?= json_encode($universities) ?>;
                    let options = '';

                    $.each(universities, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="university_id" required>
                                    <option value="">Select Institute</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'org'){

                    var organizations = <?= json_encode($organizations) ?>;
                    let options = '';

                    $.each(organizations, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="organization_id" required>
                                    <option value="">Select Organization</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'ofc_res'){

                    var branches = <?= json_encode($branches) ?>;
                    let options = '';

                    $.each(branches, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="branch_id" required>
                                    <option value="">Select Branch</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'pipeline'){

                    var pipelines = <?= json_encode($pipelines) ?>;
                    let options = '';

                    $.each(pipelines, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="pipeline_id" required>
                                    <option value="">Select Pipeline</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                }else if(this.value == 'stage'){

                    var stages = <?= json_encode($stages) ?>;
                    let options = '';

                    $.each(stages, function(keyName, keyValue) {
                        options += '<option value="'+keyName+'">'+keyValue+'</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="stage_id" required>
                                    <option value="">Select Stage</option>
                                    `+options+`
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();
                }else if(this.value == 'desc'){

                    let field = `<textarea class="form-control" rows="4" placeholder="description" name="deal_description"></textarea>`;
                    $('#field_to_update').html(field);

                }
            }

            });

        // new lead form submitting...
        $(document).on("submit", "#deal-creating-form", function(e) {

            e.preventDefault();
            var formData = $(this).serialize();

            $(".new-lead-btn").val('Processing...');
            $('.new-lead-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "{{ route('deals.store') }}",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.leads-list-tbody').prepend(data.html);
                       // openNav(data.deal.id);
                        openSidebar('/get-deal-detail?deal_id='+data.deal.id);
                        return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".new-lead-btn").val('Create');
                        $('.new-lead-btn').removeAttr('disabled');
                    }
                }
            });
        });

        // new lead form submitting...
        $(document).on("submit", "#deal-updating-form", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $(".deal-id").val();

            $(".update-lead-btn").val('Processing...');
            $('.update-lead-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/deals/update/" + id,
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                       // openNav(data.deal.id);
                        openSidebar('/get-deal-detail?deal_id='+data.deal.id);
                        return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".new-lead-btn").val('Create');
                        $('.new-lead-btn').removeAttr('disabled');
                    }
                }
            });
        });


        $(document).on("click", ".edit-lead-remove", function() {
            var id = $(".deal-id").val();
            openSidebar('/get-deal-detail?deal_id='+id);
            //openNav(id);
        });


        $(document).on('click', '.deal_stage', function() {

            var deal_id = $('.deal-id').val();
            var stage_id = $(this).attr('data-stage-id');
            var currentBtn = $(this);


            if(stage_id == 6){
                $.ajax({
                    method: 'GET',
                    url: '{{ route('get_deal_applications') }}',
                    data: {
                        id: deal_id
                    },
                    success: function(data){
                        data = JSON.parse(data);

                        if(data.status == 'success'){
                            $("#deal_id").val(deal_id);
                            $("#stage_id").val(stage_id);
                            $("#admission-application").html(data.html);
                            $("#deal_applications").modal('show');
                        }
                    }
                });
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "{{ route('update-deal-stage') }}",
                data: {
                    deal_id: deal_id,
                    stage_id: stage_id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                       // openNav(deal_id);
                        openSidebar('/get-deal-detail?deal_id='+deal_id);
                        //window.location.href = '/deals/list';

                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });


        $(document).on('click', '#save-changes-application-status', function(){
            var deal_id = $('#deal_id').val();
            var stage_id = $('#stage_id').val();
            var application_id = $("#admission-application").val();


            $.ajax({
                type: 'GET',
                url: "{{ route('update-deal-stage') }}",
                data: {
                    deal_id: deal_id,
                    stage_id: stage_id,
                    application_id: application_id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        $("#deal_applications").modal('hide');
                        show_toastr('Success', data.message, 'success');
                       // openNav(deal_id);
                        openSidebar('/get-deal-detail?deal_id='+deal_id);
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });

        });


        $(document).on("click", ".edit-input", function() {
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = $(".deal-id").val();

            $.ajax({
                type: 'GET',
                url: "/deals/get-field/" + id,
                data: {
                    name,
                    id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        $('.' + name + '-td').html(data.html);
                    }
                }
            });

        });

        $(document).on("click", ".edit-btn-data", function() {
            var name = $(this).attr('data-name');
            var value = $(this).parent().siblings('.input-group').children('.' + name).val();
            var id = $(".deal-id").val();


            $.ajax({
                type: 'GET',
                url: "/deals/" + id + "/update-data",
                data: {
                    value: value,
                    name: name,
                    id: id
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'msg');
                        // $('.' + name + '-td').html(data.html);
                       // openNav(id);
                        openSidebar('/get-deal-detail?deal_id='+id);
                    }
                }
            });

        });



        //saving discussion
        $(document).on("submit", "#create-discussion", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $('.deal-id').val();

            $(".create-discussion-btn").val('Processing...');
            $('.create-discussion-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/deals/" + id + "/discussions",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.list-group-flush').html(data.html);
                        $(".discussion_count").text(data.total_discussions);
                        // openNav(data.lead.id);
                        return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".create-discussion-btn").val('Create');
                        $('.create-discussion-btn').removeAttr('disabled');
                    }
                }
            });
        })



        //saving notes
        $(document).on("submit", "#create-notes", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $('.deal-id').val();

            $(".create-notes-btn").val('Processing...');
            $('.create-notes-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/deals/" + id + "/notes",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.note-body').html(data.html);
                        $('textarea[name="description"]').val('');
                        $('#note_id').val('');

                        // openNav(data.lead.id);
                        // return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".create-notes-btn").val('Create');
                        $('.create-notes-btn').removeAttr('disabled');
                    }
                }
            });
        })


        $(document).on("submit", "#update-notes", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $('.deal-id').val();

            $(".update-notes-btn").val('Processing...');
            $('.update-notes-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/deals/" + id + "/notes-update",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.note-body').html(data.html);
                        $('textarea[name="description"]').val('');

                        $('#note_id').val('');

                        // openNav(data.lead.id);
                        // return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".update-notes-btn").val('Update');
                        $('.update-notes-btn').removeAttr('disabled');
                    }
                }
            });
        })


        //delete-notes
        $(document).on("click", '.delete-notes', function(e) {
            e.preventDefault();

            var id = $(this).attr('data-note-id');
            var deal_id = $('.deal-id').val();
            var currentBtn = '';

            $.ajax({
                type: "GET",
                url: "/deals/" + id + "/notes-delete",
                data: {
                    id,
                    deal_id
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('.note-body').html(data.html);
                        $('textarea[name="description"]').val('');
                        $('#note_id').val('');

                        // openNav(data.lead.id);
                        // return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });

        })


        $(document).on("click", ".list-global-search-btn", function() {
            var search = $(".list-global-search").val();
            var ajaxCall = 'true';
            $("#deals_tbody").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "{{ route('deals.list') }}",
                data: {
                    search: search,
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        $("#deals_tbody").html(data.html);
                    }
                }
            })
        })

        $(document).ready(function () {
            // Attach an event listener to the input field
            $('.list-global-search').keypress(function (e) {
                // Check if the pressed key is Enter (key code 13)
                if (e.which === 13) {
                    var search = $(".list-global-search").val();
                    var ajaxCall = 'true';
                    $("#deals_tbody").html('Loading...');

                    $.ajax({
                        type: 'GET',
                        url: "{{ route('deals.list') }}",
                        data: {
                            search: search,
                            ajaxCall: ajaxCall
                        },
                        success: function(data) {
                            data = JSON.parse(data);
                            if (data.status == 'success') {
                                $("#deals_tbody").html(data.html);
                            }
                        }
                    })
                }
            });
        });

        $(".refresh-list").on("click", function() {
            var ajaxCall = 'true';
            $("#deals_tbody").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "{{ route('deals.list') }}",
                data: {
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        console.log(data.html);
                        $("#deals_tbody").html(data.html);
                    }
                }
            });
        })
        $(document).on("click", '.delete-bulk-deals', function() {
            var task_ids = $(".sub-check:checked");
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/delete-bulk-deals?ids='+selectedIds.join(',');
                }
            });
        })
    </script>
    @endpush
