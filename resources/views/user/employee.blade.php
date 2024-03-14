@extends('layouts.admin')
@php
$profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp

@section('page-title')
{{ __('Manage Employees') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Employees') }}</li>
@endsection

<style>
    .full-card {
        min-height: 165px !important;
    }

    table {
        font-size: 14px !important;
    }
</style>
<style>
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

    .filbar .form-control:focus {
        border: 1px solid rgb(209, 209, 209) !important;
    }
</style>

@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<div class="row">
    <div class="col-xxl-12">
        <div class="row w-100 m-0">
            <div class="card my-card">
                <div class="card-body">
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2 justify-content-between">
                        <div class="col-2">
                            <p class="mb-0 pb-0">Employees</p>

                            <div class="dropdown">
                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span> ALL EMPLOYEES </span>
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
                                                    <li><a class="dropdown-item" href="#" onClick="editFilter('<?= $filter->filter_name ?>', <?= $filter->id ?>)">Rename</a></li>
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
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                                <button class="btn list-global-search-btn p-0 pb-2">
                                    <span class="input-group-text bg-transparent border-0  px-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>

                            <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false" style="color:white; width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>

                            @can('create employee')
                            <a href="#" data-size="lg" data-url="{{ route('user.employee.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create Employee') }}" class="btn btn-dark py-2 px-2" style="color:white; width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-plus"></i>
                            </a>
                            @endcan


                            @php
                            $all_params = $_GET;
                            $query_string = http_build_query($all_params);
                            @endphp

                            @if(auth()->user()->type == 'super admin' || auth()->user()->type == 'Admin Team')
                            <a href="{{ route('employees.download') }}?{{ $query_string }}" class="btn btn-dark px-0" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="Download in Csv">
                                <i class="ti ti-download" style="font-size:18px"></i>
                            </a>
                            @endif

                            @if(auth()->user()->type == 'super admin' || auth()->user()->can('delete employee'))
                            <a href="javascript:void(0)" id="actions_div" data-bs-toggle="tooltip" title="{{ __('Delete Regions') }}" class="btn delete-bulk text-white btn-dark d-none px-0" style="width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-trash"></i>
                            </a>
                            @endif
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $("#dropdownMenuButton3").click(function() {
                                $("#filterToggle").toggle();
                            });
                        });
                    </script>
                    {{-- Filters --}}
                    <div class="filter-data px-3" id="filterToggle" <?= isset($_GET['branch_id']) ? '' : 'style="display: none;"' ?>>
                        <form action="/user/employees" method="GET" class="">
                            <div class="row my-3 filbar">


                                @php
                                $type = \Auth::user()->type;
                                @endphp



                                @if($type == 'super admin'|| $type == 'Admin Team' || $type == 'Project Director' || $type == 'Project Manager' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                                <div class="col-md-3 mt-2">
                                    <label for="filter_brand_id">Brand</label>
                                    <select name="brand" class="form-control select2" id="filter_brand_id">
                                        <option value="">Select Option</option>
                                        @forelse ($filters['brands'] as $key => $Brand)
                                        <option value="{{ $key }}" {{ request('brand') == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                        @empty
                                        <option value="" disabled>No brands available</option>
                                        @endforelse
                                    </select>
                                </div>

                                @endif




                                @if($type == 'super admin'|| $type == 'Admin Team' || $type == 'Project Director' || $type == 'Project Manager' || $type == 'company' || $type == 'Region Manager' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
                                <div class="col-md-3 mt-2" id="region_filter_div">
                                    <label for="">Region</label>

                                    <select name="region_id" class="form form-control select2" id="filter_region_id">
                                        <option value="">Select Option</option>
                                        @if (!empty($filters['regions']))
                                        @foreach ($filters['regions'] as $key => $region)
                                        <option value="{{ $key }}" {{ !empty($_GET['region_id']) && $_GET['region_id'] == $key ? 'selected' : '' }}>{{ $region }}</option>
                                        @endforeach
                                        @else
                                        <option value="" disabled>No regions available</option>
                                        @endif
                                    </select>
                                </div>
                                @endif


                                <div class="col-md-3 mt-2" id="branch_filter_div">
                                    <label for="filter_branch_id">Branch</label>
                                    <select name="branch_id" class="form-control select2" id="filter_branch_id">
                                        <option value="">Select Option</option>
                                        @forelse ($filters['branches'] as $key => $branch)
                                        <option value="{{ $key }}" {{ request('branch_id') == $key ? 'selected' : '' }}>{{ $branch }}</option>
                                        @empty
                                        <!-- No branches available -->
                                        @endforelse
                                    </select>
                                </div>


                                <div class="col-md-3 mt-2">
                                    <label for="">Designation</label>
                                    <select name="Designation" class="form form-control select2" id="designation_id" style="width: 95%; border-color:#aaa">
                                        <option value="">Select Designation</option>
                                        @if (!empty($Designations))
                                        @foreach ($Designations as $Designation)
                                        <option <?= isset($_GET['Designation']) && isset($Designation) && $_GET['Designation'] == $Designation ? 'selected' : '' ?> value="{{ $Designation }}">{{ $Designation }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-5 mt-3">
                                    <br>
                                    <input type="submit" class="btn me-2 bg-dark" style=" color:white;">
                                    <a href="/user/employees" class="btn bg-dark" style="color:white;">Reset</a>
                                    <a type="button" id="save-filter-btn" onClick="saveFilter('employee',<?= sizeof($users) ?>)" class="btn btn-dark me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
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

                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px !important;">
                                        <input type="checkbox" class="main-check">
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Designation</th>
                                    <th>Branch</th>
                                    <th>Region</th>
                                    <th>Brand</th>
                                    <th>Last Login</th>
                                </tr>
                            </thead>


                            <tbody class="list-div">
                                <?php
                                if (isset($_GET['page']) && !empty($_GET['page'])) {
                                    $count = ($_GET['page'] - 1) * $_GET['num_results_on_page'] + 1;
                                } else {
                                    $count = 1;
                                }
                                ?>
                                @forelse($users as $key => $employee)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="sub-check">
                                    </td>

                                    <td>

                                        <span style="cursor:pointer" class="hyper-link" @can('view employee') onclick="openSidebar('/user/employee/{{ $employee->id }}/show')" @endcan>
                                            {{ $employee->name }}
                                        </span>
                                    </td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $employee->phone }}</td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $employee->type }}</td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $Branchs[$employee->branch_id] ?? '' }}</td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $Regions[$employee->region_id] ?? '' }}</td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $brandss[$employee->brand_id] ?? '' }}</td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($employee->last_login_at) ? $employee->last_login_at : '' }}
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No employees found</td>
                                </tr>
                                @endforelse
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


    $("#filter_brand_id").on("change", function() {
        var id = $(this).val();
        var type = 'brand';
        var filter = true;

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands ') }}',
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
            url: '{{ route('region_brands ') }}',
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


    // Attach an event listener to the input field
    $('.list-global-search').keypress(function(e) {
        // Check if the pressed key is Enter (key code 13)
        if (e.which === 13) {
            var search = $(".list-global-search").val();
            var ajaxCall = 'true';
            $(".list-div").html('Loading...');
            $.ajax({
                type: 'GET',
                url: "{{ route('user.employees') }}",
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
            url: "{{ route('user.employees') }}",
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
                window.location.href = '/delete-bulk-employees?ids=' + selectedIds.join(',');
            }
        });
    })
</script>
@endpush