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
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <span> ALL REGIONS </span>
                            </button>
                            @if(sizeof($saved_filters) > 0)
                            <ul class="dropdown-menu " aria-labelledby="dropdownMenuButton1">

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
                                                <li><a class="dropdown-item" href="#" onClick="editFilter('<?= $filter->filter_name?>', <?= $filter->id ?>)">Rename</a></li>
                                                <li><a class="dropdown-item" onclick="deleteFilter('{{$filter->id}}')" href="#">Delete</a></li>
                                            </ul>
                                        </li>
                                    </ul>

                                </li>
                                @endforeach

                            </ul>
                            @endif
                        </div>

                    </div>

                    <div class="col-2">
                        <!-- <p class="mb-0 pb-0">Tasks</p> -->
                        <div class="dropdown" id="actions_divsss" style="display:none">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item assigned_to" onClick="massUpdate()">Mass Update</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-8 d-flex justify-content-end gap-2">
                        <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                            <button class="btn btn-sm list-global-search-btn p-0 pb-2">
                                <span class="input-group-text bg-transparent border-0  px-1 " id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2  list-global-search" placeholder="Search this list...">
                        </div>

                        <button class="btn filter-btn-show  btn-dark px-0" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false" style="width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-filter " style="font-size:18px"></i>
                        </button>

                        @can('create region')
                        <a href="#" data-size="lg" data-url="{{ route('region.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create New Region') }}" class="btn  btn-dark px-0" style="width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-plus"></i>
                        </a>
                        @endcan

                        @if(auth()->user()->type == 'super admin' || auth()->user()->type == 'Admin Team')
                        <a href="{{ route('regions.download') }}" class="btn  btn-dark px-0" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="" data-original-title="Download in Csv" class="btn  btn-dark px-0">
                            <i class="ti ti-download" style="font-size:18px"></i>
                        </a>
                        @endif

                        @if(auth()->user()->type == 'super admin' || auth()->user()->can('delete region'))
                        <a href="javascript:void(0)" id="actions_div" data-bs-toggle="tooltip" title="{{ __('Delete Regions') }}" class="btn delete-bulk text-white btn-dark d-none px-0" style="width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-trash"></i>
                        </a>
                        @endif

                    </div>
                </div>
            </div>






            <div class="table-responsive mt-2">
                {{-- Filters --}}

                <script>
                    $(document).ready(function() {
                        $("#dropdownMenuButton3").click(function() {
                            $("#filterToggle").toggle();
                        });
                    });
                </script>

                {{-- Filters --}}
                <div class="filter-data px-3" id="filterToggle" <?= isset($_GET['brand_id']) ? '' : 'style="display: none;"' ?>>
                    <form action="/region/index" method="GET" class="">
                        <div class="row my-3 align-items-end">
                            @php
                            $type = \Auth::user()->type;
                            @endphp

                            @if($type == 'super admin' || $type == 'Admin Team' || $type == 'HR' || $type == 'Project Director' || $type == 'Project Manager')
                            <div class="col-md-4 mt-2">
                                <label for="">Brand</label>
                                <select name="brand_id" class="form form-control select2" id="filter_brand_id">
                                    <option value="">Select Option</option>
                                    @if (!empty($filter['brands']))
                                    @foreach ($filter['brands'] as $key => $Brand)
                                    <option value="{{ $key }}" {{ !empty($_GET['brand_id']) && $_GET['brand_id'] == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                    @endforeach
                                    @else
                                    <option value="" disabled>No brands available</option>
                                    @endif
                                </select>
                            </div>
                            @endif

                            @if($type == 'super admin' || $type == 'Admin Team' || $type == 'HR' || $type == 'Project Director' || $type == 'Project Manager' || $type == 'company' || $type == 'Region Manager')
                            <div class="col-md-4 mt-2" id="region_div">
                                <label for="">Region</label>

                                <select name="region_id" class="form form-control select2" id="filter_region_id">
                                    <option value="">Select Option</option>
                                    @if (!empty($filter['regions']))
                                    @foreach ($filter['regions'] as $key => $region)
                                    <option value="{{ $key }}" {{ !empty($_GET['region_id']) && $_GET['region_id'] == $key ? 'selected' : '' }}>{{ $region }}</option>
                                    @endforeach
                                    @else
                                    <option value="" disabled>No regions available</option>
                                    @endif
                                </select>
                            </div>
                            @endif

                            <div class="col-md-3   ">
                                <input type="submit" class="btn me-2 bg-dark" style=" color:white;">
                                <a href="/region/index" class="btn bg-dark" style="color:white;">Reset</a>
                                <a type="button" id="save-filter-btn" onClick="saveFilter('region',<?= sizeof($regions) ?>)" class="btn btn-dark me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
                            </div>
                        </div>
                    </form>
                </div>


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
                                <th>{{ __('Brand') }}</th>
                                <th width="300px" class="d-none">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="deals_tbody" class="list-div">
                            @if (!empty($regions))
                            @foreach ($regions as $region)
                            <tr>
                                <td>
                                    <input type="checkbox" name="region_ids[]" value="{{ $region->id }}" class="sub-check">
                                </td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                    <span style="cursor:pointer" class="hyper-link" @can('view region') onclick="openSidebar('/regions/{{ $region->id }}/show')" @endcan>
                                        {{ $region->name }}
                                    </span>
                                </td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="mailto:{{ $region->email }}">{{ $region->email }}</a></td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $region->phone }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $region->location }}</td>

                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $users[$region->region_manager_id] ?? '' }}</td>

                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                    @php
                                    $brands = explode(',', $region->brands);
                                    @endphp

                                    @foreach ($brands as $brand_id)
                                    {{ $users[$brand_id] ?? '' }}
                                    @endforeach
                                </td>

                                <td class="Action d-none">
                                    <div class="dropdown">
                                        <button class="btn bg-transparents" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                                <path d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                                                </path>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            @can('edit region')
                                            <li><a class="dropdown-item" href="#" data-size="lg" data-url="{{ url('region/update?id=') . $region->id }}" title="{{ __('Update Origin') }}" data-ajax-popup="true" data-bs-toggle="tooltip">Edit</a>
                                            </li>
                                            @endcan
                                            @can('delete region')
                                            <li><a class="dropdown-item" href="{{ url('region/delete?id=') . $region->id }}">Delete</a>
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
                    <div class="pagination_div">
                        @if ($total_records > 0)
                        @include('layouts.pagination', [
                        'total_pages' => $total_records,
                        'num_results_on_page' => 25,
                        ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="mySidenav" style="z-index: 1065; padding-left:5px; box-shadow:0px 4px 4px 0px rgba(0, 0, 0, 0.25);background-color: #EFF3F7;" class="sidenav <?= isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>">
</div>

<!-- Mass Update Model -->
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
                        <input name='region_ids' id="region_ids" hidden>
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
    $(document).ready(function() {
        let curr_url = window.location.href;

        if (curr_url.includes('?')) {
            $('#save-filter-btn').css('display', 'inline-block');
        }
    });

    // Attach an event listener to the input field
    $('.list-global-search').keypress(function(e) {

        // Check if the pressed key is Enter (key code 13)
        if (e.which === 13) {
            var search = $(".list-global-search").val();
            var ajaxCall = 'true';
            $(".list-div").html('Loading...');
            $.ajax({
                type: 'GET',
                url: "{{ route('region.index') }}",
                data: {
                    search: search,
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        console.log(data.html);
                        $(".list-div").html(data.html);
                        $(".pagination_div").html(data.pagination_html);
                    }
                }
            })
        }
    });


    // Attach an event listener to the input field
    $('.list-global-search-btn').click(function(e) {

        var search = $(".list-global-search").val();
        var ajaxCall = 'true';
        $(".list-div").html('Loading...');

        $.ajax({
            type: 'GET',
            url: "{{ route('region.index') }}",
            data: {
                search: search,
                ajaxCall: ajaxCall
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    console.log(data.html);
                    $(".list-div").html(data.html);
                    $(".pagination_div").html(data.pagination_html);
                }
            }
        })
    });

    $("#filter_brand_id").on("change", function() {
        var id = $(this).val();
        var type = 'brand';

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id, // Add a key for the id parameter
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.regions);
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

        // console.log(selectedIds.length)

        if (selectedIds.length > 0) {
            selectedArr = selectedIds;
            $("#actions_div").removeClass('d-none');
        } else {
            selectedArr = selectedIds;

            $("#actions_div").addClass('d-none');
        }
        let commaSeperated = selectedArr.join(",");
        //console.log(commaSeperated)
        //$("#region_ids").val(commaSeperated);

    });


    $(document).on("click", '.delete-bulk', function() {
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
                window.location.href = '/delete-bulk-regions?ids=' + selectedIds.join(',');
            }
        });
    })




    $("#brands").on("change", function() {
        var id = $(this).val();
        var type = 'brand';

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id, // Add a key for the id parameter
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.regions);
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


    $(document).on("change", "#region_div #region_id", function() {
        var id = $(this).val();
        var type = 'region';
        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id, // Add a key for the id parameter
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    if (type == 'region') {
                        $('#branch_div').html('');
                        $("#branch_div").html(data.branches);
                        select2();
                    }
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

    $(document).on("submit", "#UpdateRegion", function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Serialize form data
        var formData = $(this).serialize();

        $(".update-region").text('Updating...').prop("disabled", true);

        // AJAX request
        $.ajax({
            type: "POST",
            url: $(this).attr("action"), // Form action URL
            data: formData, // Serialized form data
            success: function(response) {
              data = JSON.parse(response);

              if(data.status == 'success'){
                show_toastr('Success', data.msg, 'success');
                  $('#commonModal').modal('hide');
                  $(".modal-backdrop").removeClass("modal-backdrop");
                  $(".block-screen").css('display', 'none');
                  $(".update-region").text('Update').prop("disabled", false);
                  openSidebar('/regions/'+data.id+'/show');
              }else{
                $(".update-region").text('Updating...').prop("disabled", true);
                show_toastr('Error', data.msg, 'error');
              }

            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    });


    $(document).on("submit", "#CreateRegion", function(event) {
        event.preventDefault(); // Prevent the default form submission
        // Serialize form data
        var formData = $(this).serialize();

         // Change button text and disable it
        $(".create-region").text('Creating...').prop("disabled", true);

        // AJAX request
        $.ajax({
            type: "POST",
            url: $(this).attr("action"), // Form action URL
            data: formData, // Serialized form data
            success: function(response) {
              data = JSON.parse(response);

              if(data.status == 'success'){
                show_toastr('Success', data.msg, 'success');
                  $('#commonModal').modal('hide');
                  $(".modal-backdrop").removeClass("modal-backdrop");
                  $(".block-screen").css('display', 'none');
                   // Change button text and disable it
                  $(".create-region").text('Create').prop("disabled", false);
                  openSidebar('/regions/'+data.id+'/show');;
              }else{
                $(".create-region").text('Create').prop("disabled", false);
                show_toastr('Error', data.msg, 'error');
              }

            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    });
</script>
@endpush
