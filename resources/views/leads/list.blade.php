@extends('layouts.admin')


@if (\Auth::user()->type == 'Project Manager' || \Auth::user()->type == 'Project Director' || \Auth::user()->can('level 2'))
    @php
        $com_permissions = [];
        $com_permissions = \App\Models\CompanyPermission::where('user_id', \Auth::user()->id)->get();

    @endphp
@endif

<?php
$lead = \App\Models\Lead::first();
if (isset($lead->is_active) && $lead->is_active) {
    $calenderTasks = [];
    $deal = \App\Models\Deal::where('id', '=', $lead->is_converted)->first();
    $stageCnt = \App\Models\LeadStage::where('pipeline_id', '=', $lead->pipeline_id)->get();

    $i = 0;
    foreach ($stageCnt as $stage) {
        $i++;
        if ($stage->id == $lead->stage_id) {
            break;
        }
    }
    $precentage = number_format(($i * 100) / count($stageCnt));

    $lead_stages = $stageCnt;
}

?>



<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}
@section('page-title')
    {{ __('Manage Leads') }}
    @if (\Auth::user()->type != 'super admin')

    @if ($pipeline)
    - {{ $pipeline->first()->name }}
    @endif

    @endif
@endsection
@section('page-title')
    {{ isset($lead->name) ? $lead->name : '' }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Leads') }}</li>
@endsection

<style>

    .action-btn {
        display: inline-grid !important;
    }

    .dataTable-bottom,
    .dataTable-top {
        display: none;
    }

    .accordion-button {
        border-bottom-left-radius: 0px !important;
        border-bottoms-right-radius: 0px !important;
    }

    .card {
        box-shadow: none !important;
    }

    .hover-text-color {
        color: #1F2735 !important;
    }


</style>
{{-- comment --}}



@php
    $products = isset($lead) ? $lead->products() : '';
    $sources = isset($lead) ? $lead->sources() : '';
    $calls = isset($lead) ? $lead->calls : '';
    $emails = isset($lead) ? $lead->emails : '';
@endphp

{{-- comment  --}}
@push('script-page')
    <script>
        $('.filter-btn-show').click(function() {
            $("#filter-show").toggle();
        });
    </script>
@endpush




@section('content')
    @if ($pipeline)
        <div class="row">

            {{-- <div class="row justify-content-center">
                <div class="col-md-3">
                    <!-- card -->
                    <div class="card my-card">
                        <div class="card-body">
                            <div class="" style="position: relative;">
                                <img src="{{ asset('assets/images/tick_mark.png') }}" alt="" style="width: 30px; position: absolute; right: 0px;">
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h1> {{ isset($total_leads_by_status['opened lead']) ? $total_leads_by_status['opened lead'] : 0}} </h1>
                                    <h5>Open Leads</h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div>
                <div class="col-md-3">
                    <!-- card -->
                    <div class="card my-card">
                        <div class="card-body">
                            <div class="" style="position: relative;">
                                <img src="{{ asset('assets/images/cross_mark.png') }}" alt="" style="width: 30px; position: absolute; right: 0px;">
                            </div>
                            <div class="mt-4">
                                <h1>{{ isset($total_leads_by_status['closed lead']) ? $total_leads_by_status['closed lead'] : 0}}</h1>
                                <h5>Close Leads</h5>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div>
            </div> --}}


            <div class="col-xl-12">
                <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
                    <div class="card-body table-border-style" style="padding: 25px 3px;">
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

                        <style>
                            /* .red-cross {
                                        position: absolute;
                                        top: 5px;
                                        right: 5px;
                                        color: red;
                                    } */
                            .boximg {
                                margin: auto;
                            }

                            .dropdown-togglefilter:hover .dropdown-menufil {
                                display: block;
                            }

                            .choices__inner {
                                border: 1px solid #ccc !important;
                                min-height: auto;
                                padding: 4px !important;
                            }

                            .fil:hover .submenu {
                                display: block;
                            }

                            .fil .submenu {
                                display: none;
                                position: absolute;
                                top: 3%;
                                left: 154px;
                                width: 100%;
                                background-color: #fafafa;
                                font-weight: 600;
                                list-style-type: none;

                            }

                            .dropdown-item:hover {
                                background-color: white !important;
                            }
                            .form-control:focus{
                                border: none !important;
                                outline:none !important;
                            }

                            .filbar .form-control:focus{
                                            border: 1px solid rgb(209, 209, 209) !important;
                                        }

                        </style>

                        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                            <div class="col-4">
                                <p class="mb-0 pb-0 ps-1">Leads</p>
                                <div class="dropdown">
                                    <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        ALL LEADS
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        {{-- <li><a class="dropdown-item delete-bulk-leads" href="javascript:void(0)">Delete</a>
                                        </li> --}}


                                        @php
                                        $saved_filters = App\Models\SavedFilter::where('created_by', \Auth::user()->id)->where('module', 'leads')->get();
                                         @endphp
                                       @if(sizeof($saved_filters) > 0)
                                        @foreach($saved_filters as $filter)
                                        <li class="d-flex align-items-center justify-content-between ps-2">
                                            <div class="col-10">
                                                <a href="{{$filter->url}}" class="text-capitalize fw-bold text-dark">{{$filter->filter_name}}</a>
                                                <span class="text-dark"> ({{$filter->count}})</span>
                                            </div>
                                            <ul class="w-25" style="list-style: none;">
                                                <li class="fil fw-bolder">
                                                    <i class=" fa-solid fa-ellipsis-vertical" style="color: #000000;"></i>
                                                    <ul class="submenu" style="border: 1px solid #e9e9e9;
                                                                                box-shadow: 0px 0px 1px #e9e9e9;">
                                                        <li><a class="dropdown-item" href="#" onClick="editFilter('<?= $filter->filter_name ?>', <?= $filter->id ?>)">Rename</a></li>
                                                        <li><a class="dropdown-item" onclick="deleteFilter('{{$filter->id}}')" href="#">Delete</a></li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                        @endforeach
                                        @endif

                                    </ul>
                                </div>
                            </div>
                            {{-- /// --}}

                            {{-- /// --}}

                            <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                                <div class="input-group w-25 rounded" style= "width:36px; height: 36px; margin-top:10px;">
                                    <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                        <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                            <i class="ti ti-search" style="font-size: 18px"></i>
                                        </span>
                                    </button>
                                    <input type="Search"
                                        class="form-control border-0 bg-transparent p-0 pb-2 list-global-search"
                                        placeholder="Search this list..." aria-label="Username"
                                        aria-describedby="basic-addon1">
                                </div>

                                <!-- <button class="btn px-2 pb-2 pt-2 refresh-list btn-dark" data-bs-toggle="tooltip" title="{{__('Refresh')}}" onclick="RefreshList()"><i class="ti ti-refresh"
                                        style="font-size: 18px"></i></button> -->

                                <button class="btn filter-btn-show p-2 btn-dark" type="button" data-bs-toggle="tooltip" title="{{__('Filter')}}" aria-expanded="false"  style="color:white; width:36px; height: 36px; margin-top:10px;">
                                    <i class="ti ti-filter" style="font-size:18px"></i>
                                </button>
                                <a  href="{{ url('/leads') }}" data-bs-toggle="tooltip" title="{{ __('Leads View') }}" class="btn px-2 btn-dark d-flex align-items-center"  style="color:white; width:36px; height: 36px; margin-top:10px;">
                                    {{-- <i class="ti ti-plus" style="font-size:18px"></i> --}}
                                    <i class="fa-solid fa-border-all" style="font-size:18px"></i>
                                </a>
                                @can('create lead')
                                <button data-size="lg" data-url="{{ route('leads.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create New Lead') }}" class="btn px-2 btn-dark"  style="color:white; width:36px; height: 36px; margin-top:10px;">
                                    <i class="ti ti-plus" style="font-size:18px"></i>
                                </button>
                                @endcan
                                @can('create lead')
                                <button data-size="lg" data-bs-toggle="tooltip" title="{{ __('Import Csv') }}"
                                    class="btn px-2 btn-dark" id="import_csv_modal_btn" data-bs-toggle="modal"
                                    data-bs-target="#import_csv"  style="color:white; width:36px; height: 36px; margin-top:10px;">
                                    <i class="fa fa-file-csv"></i>
                                </button>
                                @endcan

                                @php
                                    $all_params = $_GET;
                                    $query_string = http_build_query($all_params);
                                @endphp

                                @if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team')
                                    <a href="{{ route('leads.download') }}?{{ $query_string }}" class="btn p-2 btn-dark  text-white" style="font-weight: 500; color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="" data-original-title="Download in Csv" class="btn  btn-dark px-0">
                                        <i class="ti ti-download"></i>
                                    </a>
                                @endif



                                {{-- <a class="btn p-2 btn-dark  text-white assigned_to" data-bs-toggle="tooltip" title="{{__('Mass Update')}}" id="actions_div" style="display:none;font-weight: 500;" onClick="massUpdate()">Mass Update</a> --}}
                                @if(auth()->user()->can('delete lead'))
                                <a class="btn p-2 btn-dark  text-white assigned_to delete-bulk-leads d-none" data-bs-toggle="tooltip" title="{{__('Mass Delete')}}" id="actions_div" style="font-weight: 500; color:white; width:36px; height: 36px; margin-top:10px;">
                                    <i class="ti ti-trash"></i>
                                </a>
                                @endif
                            </div>
                        </div>


                        <div class="modal fade" style="z-index: 9999999; overflow: scroll;" id="import_csv" tabindex="-1"
                            aria-labelledby="import_csv Label" aria-hidden="true">
                            <div class="modal-dialog  modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="import_csvLabel">Leads import</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <form action="{{ url('leads/import-csv') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body pt-0" >
                                            <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
                                                <div class="card-body px-2 py-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-groups mt-2 ps-2">
                                                                <label for="extension"
                                                                    class="form-label">Extension</label>
                                                                <select type="file" class="form-control" name="extension" id="extension" required>
                                                                    <option value="">Select type</option>
                                                                    <option value="csv">CSV</option>
                                                                    <option value="excel">Excel</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-groups mt-2 pe-3">
                                                                <label for="lead-file"
                                                                    class="form-label">{{ __('Column') }}</label>
                                                                <input type="file" name="leads_file" id="lead-file"
                                                                    class="form-control" accept=".csv,.xls" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="mt-2 columns-matching">
                                                            <!-- Put any additional form elements here, if needed -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                            <button type="submit"
                                                class="btn btn-dark submit_btn">{{ __('Create') }}</button>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>




                        <div class="filter-data px-3" id="filter-show"
                            <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                            <form action="/leads/list" method="GET" class="">
                                <div class="row my-3 align-items-end">
                                @php
                                $type = \Auth::user()->type;
                                $access_levels = accessLevel();
                                @endphp



                                @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                                    <div class="col-md-3 mt-2">
                                        <label for="">Brand</label>
                                        <select name="brand" class="form form-control select2" id="filter_brand_id">
                                            @if (!empty($filters['brands']))
                                                @foreach ($filters['brands'] as $key => $Brand)
                                                <option value="{{ $key }}" {{ !empty($_GET['brand']) && $_GET['brand'] == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                                @endforeach
                                                @else
                                                <option value="" disabled>No brands available</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif



                                @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
                                    <div class="col-md-3 mt-2" id="region_filter_div">
                                        <label for="">Region</label>
                                        <select name="region_id" class="form form-control select2" id="filter_region_id">
                                            @if (!empty($filters['regions']))
                                                @foreach   ($filters['regions'] as $key => $region)
                                                <option value="{{ $key }}" {{ !empty($_GET['region_id']) && $_GET['region_id'] == $key ? 'selected' : '' }}>{{ $region }}</option>
                                                @endforeach
                                                @else
                                                <option value="" disabled>No regions available</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif


                                @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3') || \Auth::user()->can('level 4'))
                                    <div class="col-md-3 mt-2" id="branch_filter_div">
                                        <label for="">Branch</label>
                                        <select name="branch_id" class="form form-control select2" id="filter_branch_id">
                                            @if (!empty($filters['branches']))
                                                @foreach ($filters['branches'] as $key => $branch)
                                                <option value="{{ $key }}" {{ !empty($_GET['branch_id']) && $_GET['branch_id'] == $key ? 'selected' : '' }}>{{ $branch }}</option>
                                                @endforeach
                                                @else
                                                <option value="" disabled>No regions available</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                <div class="col-md-3 mt-2"> <label for="">Assigned To</label>
                                    <div class="" id="assign_to_div">
                                        <select name="lead_assgigned_user" id="choices-multiple333" class="form form-control select2" style="width: 95%;">
                                            @foreach ($filters['employees'] as $key => $user)
                                            <option value="{{ $key }}" <?= isset($_GET['lead_assgigned_user']) && $key == $_GET['lead_assgigned_user'] ? 'selected' : '' ?> class="">{{ $user }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-3"> <label for="">Name</label>
                                    <div class="" id="filter-names">
                                        <select class="form form-control select2" id="choices-multiple110" name="name[]"
                                            multiple style="width: 95%;">
                                            <option value="">Select name</option>
                                            @foreach ($leads as $lead)
                                                <option value="{{ $lead->id }}"
                                                    <?= isset($_GET['name']) && in_array($lead->id, $_GET['name']) ? 'selected' : '' ?>
                                                    class="">{{ $lead->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3"> <label for="">Stage</label>
                                    <select class="form form-control select2" id="choices-multiple444"
                                        name="stages[]" multiple style="width: 95%;">
                                        <option value="">Select Stage</option>
                                        @foreach ($stages as $stage)
                                            <option value="{{ $stage->id }}"
                                                <?= isset($_GET['stages']) && in_array($stage->id, $_GET['stages']) ? 'selected' : '' ?>
                                                class="">{{ $stage->name }}</option>
                                        @endforeach
                                    </select>
                                </div>



                                    <div class="col-md-3 mt-2">
                                        <label for="">Created at</label>
                                        <input type="date" class="form form-control" name="created_at"
                                            value="<?= isset($_GET['created_at']) ? $_GET['created_at'] : '' ?>"
                                            style="width: 95%; border-color:#aaa">
                                    </div>

                                    <div class="col-md-3 mt-3 d-flex" style="padding:0px;">
                                        <br>
                                        <input type="submit" class="btn form-btn bg-dark" style=" color:white;">
                                        <a href="/leads/list" style="margin: 0px 3px;" class="btn form-btn bg-dark" style="color:white;">Reset</a>
                                        <a type="button" id="save-filter-btn" onClick="saveFilter('leads',<?= sizeof($leads) ?>)" class="btn form-btn me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
                                    </div>
                                </div>


                                <div class="row my-4 d-none">
                                    <div class="enries_per_page" style="max-width: 300px; display: flex;">

                                        <?php
                                        $all_params = isset($_GET) ? $_GET : '';
                                        if (isset($all_params['num_results_on_page'])) {
                                            unset($all_params['num_results_on_page']);
                                        }
                                        ?>
                                        <input type="hidden" value="<?= http_build_query($all_params) ?>"
                                            class="url_params">
                                        <select name="" id="" class="enteries_per_page form form-control"
                                            style="width: 100px; margin-right: 1rem;">
                                            <option
                                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 25 ? 'selected' : '' ?>
                                                value="25">25</option>
                                            <option
                                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 100 ? 'selected' : '' ?>
                                                value="100">100</option>
                                            <option
                                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 300 ? 'selected' : '' ?>
                                                value="300">300</option>
                                            <option
                                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 1000 ? 'selected' : '' ?>
                                                value="1000">1000</option>
                                            <option
                                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == $total_records ? 'selected' : '' ?>
                                                value="{{ $total_records }}">all</option>
                                        </select>

                                        <span style="margin-top: 5px;">entries per page</span>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-responsive" style="padding: 25px 3px; width:auto;">
                            <table class="table " data-resizable-columns-id="lead-table" id="tfont">
                                <thead>
                                    <tr>
                                        <th style="width: 50px !important;">
                                            <input type="checkbox" class="main-check">
                                        </th>


                                        <th data-resizable-columns-id="name">{{ __('Name') }}</th>
                                        {{-- <th>{{ __('Subject') }}</th> --}}

                                        <th data-resizable-columns-id="email_address" class="ps-3">
                                            {{ __('Email') }}</th>
                                        <th data-resizable-columns-id="phone" class="ps-3">{{ __('Phone') }}</th>
                                        <th data-resizable-columns-id="stage" class="ps-3">{{ __('Stage') }}</th>
                                        <th data-resizable-columns-id="users" class="ps-3">{{ __('Assigned to') }}</th>
                                        <th data-resizable-columns-id="created_by">{{ __('Brand') }}</th>
                                        <th data-resizable-columns-id="created_by">{{ __('Branch') }}</th>

                                        {{-- <th data-resizable-columns-id="actions" style="width: 5%;">{{ __('Action') }}
                                        </th> --}}
                                    </tr>
                                </thead>
                                <tbody class="leads-list-tbody leads-list-div">
                                    @if (count($leads) > 0)
                                        @foreach ($leads as $lead)
                                            <tr>
                                                <td><input type="checkbox" name="leads[]" value="{{ $lead->id }}"
                                                        class="sub-check"></td>


                                                <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                                    <span style="cursor:pointer" class="lead-name hyper-link"
                                                       @can('view lead') onclick="openNav(<?= $lead->id ?>)" @endcan
                                                        data-lead-id="{{ $lead->id }}">{{ $lead->name }}</span>
                                                </td>

                                                <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;" > <a href="{{ $lead->email }}">{{ $lead->email }}</a></td>
                                                <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $lead->phone }}</td>
                                                <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($lead->stage) ? $lead->stage->name : '-' }}</td>
                                                <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                                    @php
                                                        $assigned_to = isset($lead->user_id) && isset($users[$lead->user_id]) ? $users[$lead->user_id] : 0;
                                                    @endphp

                                                    @if ($assigned_to != 0)
                                                        <span style="cursor:pointer" class="hyper-link"
                                                            onclick="openSidebar('/users/'+{{ $lead->user_id }}+'/user_detail')">
                                                            {{ $assigned_to }}
                                                        </span>
                                                    @endif
                                                </td>

                                                    <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $users[$lead->brand_id] ?? '' }}</td>
                                                    <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $branches[$lead->branch_id] ?? '' }}</td>


                                                @if (Auth::user()->type != 'client')
                                                    <td class="Action py-1 px-0" >
                                                        {{-- <span>

                                                            @if (\Auth::user()->type == 'super admin' || \Gate::check('view lead'))
                                                                @if ($lead->is_active)
                                                                    <div class="action-btn bg-warning ms-2">
                                                                        <a href="{{ route('leads.show', $lead->id) }}"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                            data-size="xl" data-bs-toggle="tooltip"
                                                                            title="{{ __('View') }}"
                                                                            data-title="{{ __('Lead Detail') }}">
                                                                            <i class="ti ti-eye text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endif


                                                            @if (\Auth::user()->type == 'super admin' || \Gate::check('edit lead'))
                                                                <div class="action-btn bg-info ms-2">
                                                                    <a href="#"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                        data-url="{{ route('leads.edit', $lead->id) }}"
                                                                        data-ajax-popup="true" data-size="xl"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Edit') }}"
                                                                        data-title="{{ __('Lead Edit') }}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>
                                                            @endif

                                                            @if (\Auth::user()->type == 'super admin' || \Gate::check('delete lead'))
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['leads.destroy', $lead->id],
                                                                        'id' => 'delete-form-' . $lead->id,
                                                                    ]) !!}
                                                                    <a href="#"
                                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Delete') }}"><i
                                                                            class="ti ti-trash text-white"></i></a>

                                                                    {!! Form::close() !!}
                                                                </div>
                                                            @endif
                                                        </span> --}}
                                                        {{-- <div class="dropdown">
                                                            <button class="btn bg-transparents" type="button"
                                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" width="18" height="18">
                                                                    <path
                                                                        d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                            <ul class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton1">
                                                                <li><a class="dropdown-item" href="#">Change</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                                                <li><a class="dropdown-item" href="#">Delete</a>
                                                                </li>
                                                            </ul>
                                                        </div> --}}
                                                    </td>
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
                            <div class="pagination_div">
                                @if ($total_records > 0)
                                    @include('layouts.pagination', [
                                        'total_pages' => $total_records,
                                    ])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        #mySidenav {
            box-shadow: -5px 0px 30px 0px #aaa;
        }
    </style>
    <div id="mySidenav" style="z-index: 1065; padding-left:5px;"
        class="sidenav <?= $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>"
        style="padding-left: 5px">


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
                                        <option value="nm">Name</option>
                                        <option value="ldst">Lead Status</option>
                                        <!-- <option value="ast">Assign Type</option> -->
                                        <option value="user_res">User Reponsible</option>
                                        <option value="loc">Location</option>
                                        <option value="agy">Agency</option>
                                        <option value="ldsrc">Lead Source</option>
                                        <option value="email">Email Address</option>
                                        <option value="email_ref">Email Address (Referrer) </option>
                                        <option value="phone">Phone</option>
                                        <option value="m_phone">Mobile Phone</option>
                                        <!-- <option value="mail_opt">Email opt out</option> -->
                                        <option value="address">Address</option>
                                        <option value="desc">Description</option>
                                        <!-- <option value="tag_list">Tag List</option> -->

                                    </select>
                            </div>
                            <input name='lead_ids' id="lead_ids" hidden>
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



@endsection

@push('script-page')
    <script>
    $(".add-filter").on("click", function() {
        $(".filter-data").toggle();
    })
        $(document).ready(function() {
            let curr_url = window.location.href;

            if(curr_url.includes('?')){
                $('#save-filter-btn').css('display','inline-block');
            }
        });
        $(document).on("click", "#import_csv_modal_btn", function() {
            $("#import_csv").modal('show');
        })

       // single check

       $(document).on('change', '.main-check', function() {
        $(".sub-check").prop('checked', $(this).prop('checked'));

            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

        // console.log(selectedIds.length)

            if (selectedIds.length > 0) {
                selectedArr = selectedIds;
                $("#actions_div").removeClass('d-none');
            } else {
                selectedArr = selectedIds;

                $("#actions_div").addClass('d-none');
            }
        });

        $(document).on('change', '.sub-check', function() {
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            if (selectedIds.length > 0) {
                selectedArr = selectedIds;
                $("#actions_div").removeClass('d-none');
            } else {
                selectedArr = selectedIds;

                $("#actions_div").addClass('d-none');
            }
            let commaSeperated = selectedArr.join(",");
            console.log(commaSeperated)
            $("#lead_ids").val(commaSeperated);

        });



        function massUpdate() {
            if (selectedArr.length > 0) {
                $('#mass-update-modal').modal('show')
            } else {
                alert('Please choose Tasks!')
            }
        }

        $('#bulk_field').on('change', function() {

            if (this.value != '') {
                $('#field_to_update').html('');

                if (this.value == 'nm') {

                    let field = `<div>
                                    <input type="text" class="w-50 form-control" placeholder="First Name" name="lead_first_name" value="" required="">
                                    <input type="text" class="w-50 form-control" placeholder="Last Name" name="lead_last_name" value="" required="">
                               </div>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'ldst') {

                    var stages = <?= json_encode($stages) ?>;

                    let options = '';
                    for (let i = 0; i < stages.length; i++) {
                        options += '<option value="' + stages[i].id + '">' + stages[i].name + '</option>';
                    }

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="lead_stage" required>
                                    <option value="">Select Status</option>
                                    ` + options + `
                                </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'user_res') {

                    var users = <?= json_encode($users) ?>;
                    let options = '';

                    $.each(users, function(keyName, keyValue) {
                        options += '<option value="' + keyName + '">' + keyValue + '</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="lead_assgigned_user">
                                    <option value="">Select User</option>
                                    ` + options + `
                                </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'loc') {

                    var branches = <?= json_encode($branches) ?>;
                    let options = '';

                    $.each(branches, function(keyName, keyValue) {
                        options += '<option value="' + keyName + '">' + keyValue + '</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="lead_branch" required>
                                    <option value="">Select Location</option>
                                    ` + options + `
                                </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'agy') {

                    var organizations = <?= json_encode($organizations) ?>;
                    let options = '';

                    $.each(organizations, function(keyName, keyValue) {
                        options += '<option value="' + keyName + '">' + keyValue + '</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="lead_organization" required>
                                    <option value="">Select Agency</option>
                                    ` + options + `
                                </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'ldsrc') {

                    var sources = <?= json_encode($sourcess) ?>;
                    let options = '';
                    $.each(sources, function(keyName, keyValue) {
                        options += '<option value="' + keyName + '">' + keyValue + '</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="lead_source" required>
                                    <option value="">Select Source</option>
                                    ` + options + `
                                </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'email') {

                    let field = `<input type="email" class="form-control" name="lead_email" required>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'email_ref') {

                    let field = `<input type="email" class="form-control" name="referrer_email">`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'phone') {

                    let field =  `<input type="text" class="form-control" name="lead_phone" value="" required>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'm_phone') {

                    let field = `<input type="text" class="form-control" name="lead_mobile_phone" >`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'address') {

                    let field = `<div class="form-floating">
                                    <textarea class="form-control" placeholder="Street" id="floatingTextarea" name="lead_street"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-form">
                                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="City" name="lead_city" >
                                    </div>
                                    <div class="col-6 col-form">
                                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="State/Province" name="lead_state" >
                                    </div>
                                    <div class="col-6 col-form">
                                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Postel Code" name="lead_postal_code" >
                                    </div>
                                    <div class="col-6 col-form" style="text-align: left;">
                                        <select class="form-control select2" id="choice-6" name="lead_country">
                                            <option>Country...</option>
                                        </select>
                                    </div>
                                </div>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'desc') {

                    let field =
                        `<textarea class="form-control" rows="4" placeholder="description" name="lead_description"></textarea>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'tag_list') {

                    let field = ``;
                    $('#field_to_update').html(field);

                }
            }

        });

        $(document).on("click", '.delete-bulk-leads', function() {
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
                    window.location.href = '/delete-bulk-leads?ids=' + selectedIds.join(',');
                }
            });
        })

        $(document).on("change", "#lead-file", function() {

            var extension = $('#lead-file').val().split('.').pop().toLowerCase();
            var ext = $('#extension').val();

            if(ext == 'csv'){
                if($.inArray(extension, ['csv']) == -1) {
                    alert('Sorry, file extension does not match with selected extension.');
                    return false;
                }
            }else{
                if($.inArray(extension, ['xls','xlsx']) == -1) {
                    alert('Sorry, file extension does not match with selected extension.');
                    return false;
                }
            }

            var form = $(this).closest('form')[0]; // Get the form element
            var formData = new FormData(form); // Pass the form element to FormData constructor
            $.ajax({
                url: "{{ route('leads.fetchColumns') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $(".columns-matching").html(response.data);
                        $(".submit_btn").removeClass('d-none');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        })

        var dropdownValues = [];
        var dropdownKeys = [];

        $(document).on("change", ".lead-columns", function() {

            var key = $(this).attr('data-id');
            var value = $(this).val();


            if (value == '') {

                if (key > -1 && key < dropdownValues.length) {
                    dropdownValues.splice(key, 1);
                }

            } else {

                if (dropdownValues.indexOf(value) !== -1) {
                    $(this).val('');
                    show_toastr('error', 'Field is already assigned. Change the existing feild first', 'error');
                    return false;
                }


                dropdownValues[key] = value;
                console.log(dropdownValues);
            }




            return true;
        })


        // $(document).on("submit", "#import_csv", function() {
        //     var assigned_to = $("#assigned_to").val();

        //     if (assigned_to == undefined || assigned_to == '') {
        //         show_toastr('error', 'Please assigned the leads', 'error');
        //         return false;
        //     }
        // })


        // new lead form submitting...
        $(document).on("submit", "#lead-creating-form", function(e) {

            e.preventDefault();
            var formData = $(this).serialize();

            $(".new-lead-btn").val('Processing...');
            $('.new-lead-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "{{ route('leads.store') }}",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.leads-list-tbody').prepend(data.html);
                        openSidebar('/get-lead-detail?lead_id=' + data.lead_id);
                        // openNav(data.lead.id);
                        return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".new-lead-btn").val('Create');
                        $('.new-lead-btn').removeAttr('disabled');
                    }
                }
            });
        });

        function RefreshList() {
            var ajaxCall = 'true';
            $(".leads-list-tbody").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "/leads/list",
                data: {
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        $(".leads-list-tbody").html('');
                        $('.leads-list-tbody').prepend(data.html);
                    }
                },
            });
        }


        // new lead form submitting...
        $(document).on("submit", "#lead-updating-form", function(e) {

            e.preventDefault();
            var formData = $(this).serialize();
            var id = $(".lead_id").val();
            $(".update-lead-btn").val('Processing...');
            $('.update-lead-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/leads/update/" + id,
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        // openNav(id);
                        $("#commonModal").modal('hide');
                        openSidebar('/get-lead-detail?lead_id=' + data.lead_id);
                        //window.location.href = '/leads/list';
                        return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".new-lead-btn").val('Create');
                        $('.new-lead-btn').removeAttr('disabled');
                    }
                }
            });
        });


        $(document).on('click', '.lead_stage', function() {

            var lead_id = $(this).attr('data-lead-id');
            var stage_id = $(this).attr('data-stage-id');
            var currentBtn = $(this);



            $.ajax({
                type: 'GET',
                url: "{{ route('update-lead-stage') }}",
                data: {
                    lead_id: lead_id,
                    stage_id: stage_id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        show_toastr('Success', 'Stage updated successfully.', 'success');
                        if (stage_id == 6 || stage_id == 7) {
                            window.location.href = '/leads/list';
                        } else {
                            openNav(lead_id);
                            return false;
                        }
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });

        /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
        function openNav(lead_id) {
            var ww = $(window).width()

            $.ajax({
                type: 'GET',
                url: "{{ route('get-lead-detail') }}",
                data: {
                    lead_id: lead_id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        $("#mySidenav").html(data.html);
                        $(".block-screen").css('display', 'none');
                    }
                }
            });


            if (ww < 500) {
                $("#mySidenav").css('width', ww + 'px');
                $("#main").css('margin-right', ww + 'px');
            } else {
                $("#mySidenav").css('width', '890px');
                $("#main").css('margin-right', "890px");
            }

            $("#modal-discussion-add").attr('data-lead-id', lead_id);
            $('.modal-discussion-add-span').removeClass('ti-minus');
            $('.modal-discussion-add-span').addClass('ti-plus');
            $(".add-discussion-div").addClass('d-none');
            $("#body").css('overflow', 'hidden');

            // var csrf_token = $('meta[name="csrf-token"]').attr('content');

            // $.ajax({
            //     url: "/leads/getDiscussions",
            //     data: {
            //         lead_id,
            //         _token: csrf_token,
            //     },
            //     type: "POST",
            //     cache: false,
            //     success: function(data) {
            //         data = JSON.parse(data);
            //         //console.log(data);

            //         if (data.status) {
            //             $(".discussion-list-group").html(data.content);
            //             $(".lead_id").val(lead_id);


            //         }
            //     }
            // });

        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
        function closeNav() {
            $("#mySidenav").css("width", '0');
            $("#main").css("margin-right", '0');
            $("#modal-discussion-add").removeAttr('data-deal-id');
            $('.modal-discussion-add-span').removeClass('ti-minus');
            $('.modal-discussion-add-span').addClass('ti-plus');
            $(".add-discussion-div").addClass('d-none');
            $(".block-screen").css('display', 'none');
            $("#body").css('overflow', 'visible');
        }


        //refresh table
        $(".refresh-lead-list").on("click", function() {
            var ajaxCall = 'true';
            $(".leads-list-div").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "{{ route('leads.list') }}",
                data: {
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        console.log(data.html);
                        $(".leads-list-div").html(data.html);
                        $(".pagination_div").html(data.pagination_html);
                    }
                }
            });
        })

        //global search
        $(document).on("click", ".list-global-search-btn", function() {
            var search = $(".list-global-search").val();
            var ajaxCall = 'true';
            $(".leads-list-div").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "{{ route('leads.list') }}",
                data: {
                    search: search,
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        console.log(data.html);
                        $(".leads-list-div").html(data.html);
                        $(".pagination_div").html(data.pagination_html);
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
                    $(".leads-list-div").html('Loading...');

                    $.ajax({
                        type: 'GET',
                        url: "{{ route('leads.list') }}",
                        data: {
                            search: search,
                            ajaxCall: ajaxCall
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            if (data.status == 'success') {
                                console.log(data.html);
                                $(".leads-list-div").html(data.html);
                                $(".pagination_div").html(data.pagination_html);
                            }
                        }
                    })
                }
            });
        });

        $(document).on("click", ".edit-input", function() {
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = $(".lead-id").val();

            $.ajax({
                type: 'GET',
                url: "/leads/get-field/" + id,
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
            var id = $(".lead-id").val();


            $.ajax({
                type: 'GET',
                url: "/leads/" + id + "/update-data",
                data: {
                    value: value,
                    name: name,
                    id: id
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'msg');
                        $('.' + name + '-td').html(data.html);
                    }
                }
            });

        });



        $(document).on("click", ".edit-lead-remove", function() {

            var name = $(this).attr('data-name');

            var value = '';
            if (name == 'organization_id') {
                value = $('.' + name).val();
                alert(value);
            } else if (name == 'sourcess') {
                value = $('.' + name).val();
            } else {
                value = $('.' + name).val();
            }




            var html = '<div class="d-flex edit-input-field-div">' +
    '  <div class="input-group border-0 d-flex">' +
    '<a href="">' +
    value +
    '</a>' +
    '</div>' +
    '<div class="edit-btn-div">' +
    '<button class="btn btn-secondary edit-input rounded-0 btn-effect-none" style="padding:7px;"><i class="ti ti-pencil"></i></button>' +
    '</div>' +
    '</div>';

$('.' + name + '-td').html(html);
        });


        $(document).on("click", ".edit-btn-address", function() {

            id = $('.lead-id').val();
            $.ajax({
                type: 'GET',
                url: "/leads/get-address/" + id,
                data: {
                    id
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        $('.address-td').html(data.html);
                    }
                }
            })

        })


        $(document).on("click", ".edit-btn-save-address", function() {

            id = $('.lead-id').val();
            street = $(".lead_street").val();
            city = $(".lead_city").val();
            state = $(".lead_state").val();
            postal_code = $(".lead_postal_code").val();
            country = $(".lead_country").val();

            $.ajax({
                type: 'GET',
                url: "/leads/save-address/" + id,
                data: {
                    id,
                    street,
                    city,
                    state,
                    postal_code,
                    country
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('.address-td').html(data.html);
                    }
                }
            })

        })

        $(document).on("click", ".remove-btn-save-address", function() {

            var id = $('.lead-id').val();
            var street = $(".lead_street").val();
            var city = $(".lead_city").val();
            var state = $(".lead_state").val();
            var postal_code = $(".lead_postal_code").val();
            var country = $(".lead_country").val();

            // Initialize an empty array
            var dataArray = [];

            // Check if each variable is non-empty before adding to the array
            if (street !== "") {
                dataArray.push(street);
            }

            if (city !== "") {
                dataArray.push(city);
            }

            if (state !== "") {
                dataArray.push(state);
            }

            if (postal_code !== "") {
                dataArray.push(postal_code);
            }

            if (country !== "") {
                dataArray.push(country);
            }

            var address = dataArray.join(',');

            var html = '<div class="d-flex edit-input-field-div">' +
                '<div class="input-group border-0 d-flex">' +
                '<a href="">'+
                address +
                '</a>'+
                '</div>' +
                '<div class="edit-btn-div">' +
                '<button class="btn btn-secondary edit-btn-address rounded-0 btn-effect-none" style="padding:7px;"><i class="ti ti-pencil"></i></button>' +
                '</div>' +
                '</div>'

            $('.address-td').html(html);
        })


        //saving discussion
        $(document).on("submit", "#create-discussion", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $('.lead-id').val();

            $(".create-discussion-btn").val('Processing...');
            $('.create-discussion-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/leads/" + id + "/discussions",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.list-group-flush').html(data.html);
                        // openNav(data.lead.id);
                        // return false;
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
            var id = $('.lead-id').val();

            $(".create-notes-btn").val('Processing...');
            $('.create-notes-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/leads/" + id + "/notes",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.note-tbody').html(data.html);
                        $('#note_id').val('');
                        $('#description').val('');

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
            var id = $('.lead-id').val();

            $(".update-notes-btn").val('Processing...');
            $('.update-notes-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/leads/" + id + "/notes-update",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.note-tbody').html(data.html);
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
            var lead_id = $('.lead-id').val();
            var currentBtn = '';

            $.ajax({
                type: "GET",
                url: "/leads/" + id + "/notes-delete",
                data: {
                    id,
                    lead_id
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('.note-tbody').html(data.html);
                        // openNav(data.lead.id);
                        // return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });

        })


        function getOrganization() {
            var html = '';
            <?php foreach($organizations as $key => $org) { ?>
            html += '<option value="{{ $key }}">{{ $org }}</option>';
            <?php } ?>
            return html;
        }

        function getSources() {
            var html = '';

            <?php foreach($sourcess as $key => $label) { ?>
            html += '<option value="{{ $key }}">{{ $label }}</option>';
            <?php } ?>
            return html;
        }



        ////////////////////Filters Javascript
        $("#filter_brand_id").on("change", function() {
            var id = $(this).val();
            var type = 'brand';
            var filter = true;

            $.ajax({
                type: 'GET',
                url: '{{ route('region_brands') }}',
                data: {
                    id: id, // Add a key for the id parameter
                    filter,
                    type: type
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status === 'success') {
                        $('#region_filter_div').html('');
                        $("#region_filter_div").html(data.regions);
                        select2();
                    } else {
                        console.error('Server returned an error:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        });


        $(document).on("change", "#filter_region_id, #region_id", function() {
            var id = $(this).val();
            var filter = true;
            var type = 'region';
            $.ajax({
                type: 'GET',
                url: '{{ route('region_brands') }}',
                data: {
                    id: id, // Add a key for the id parameter
                    filter,
                    type: type
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status === 'success') {
                        $('#branch_filter_div').html('');
                        $("#branch_filter_div").html(data.branches);
                        getLeads();
                        select2();
                    } else {
                        console.error('Server returned an error:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        });

        $(document).on("change", "#filter_branch_id, #branch_id", function() {
    
            var id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('filter-branch-users') }}',
                    data: {
                        id: id
                    },
                    success: function(data){
                        data = JSON.parse(data);

                        if (data.status === 'success') {
                            $('#assign_to_div').html('');
                            $("#assign_to_div").html(data.html);
                            select2();
                        } else {
                            console.error('Server returned an error:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                    }
                });
        });


        $(document).on("change", "#filter_branch_id, #branch_id", function() {
           getLeads();
        });

        function getLeads(){
            var brand_id = $("#filter_brand_id").val();
            var region_id = $("#region_id").val();
            var branch_id = $("#branch_id").val();

            if (typeof region_id === 'undefined') {
                var region_id = $("#filter_region_id").val();
            }

            if (typeof branch_id === 'undefined') {
                var branch_id = $("#filter_branch_id").val();
            }




            var type = 'lead';

            $.ajax({
                type: 'GET',
                url: '{{ route('filterData') }}',
                data: {
                   brand_id,
                   region_id,
                   branch_id,
                   type
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status === 'success') {
                        $('#filter-names').html('');
                        $("#filter-names").html(data.html);
                        select2();
                    } else {
                        console.error('Server returned an error:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        }


    </script>
@endpush
