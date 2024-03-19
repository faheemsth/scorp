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

@section('action-btn')
<div class="float-end d-none">
    <a href="#" data-size="md" data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('employee.file.import') }}" data-ajax-popup="true" data-title="{{__('Import employee CSV file')}}" class="btn btn-sm btn-primary">
        <i class="ti ti-file-import"></i>
    </a>
    <a href="{{route('employee.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
        <i class="ti ti-file-export"></i>
    </a>

    <a href="{{ route('employee.create') }}" data-size="lg" data-url="{{ route('employee.create') }}" data-ajax-popup="false" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Employee')}}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
</div>
@endsection



@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">

                <!-- Card Topbar -->
                <div class="row align-items-center ps-0 ms-0 pe-4 my-2 justify-content-between">
                    <div class="col-2">
                        <p class="mb-0 pb-0">Employees</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <span> ALL EMPLOYEES </span>
                            </button>
                            @if(isset($saved_filters) && sizeof($saved_filters) > 0)
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                @foreach($saved_filters as $filter)
                                <li class="d-flex align-items-center justify-content-between ps-2">
                                    <div class="col-10">
                                        <a href="{{$filter->url}}" class="text-capitalize fw-bold text-dark">{{$filter->filter_name}}</a>
                                        <span class="text-dark"> ({{$filter->count}})</span>
                                    </div>
                                    <ul class="w-25" style="list-style: none;">
                                        <li class="fil fw-bolder">
                                            <i class=" fa-solid fa-ellipsis-vertical" style="color: #000000;"></i>
                                            <ul class="submenu" style="border: 1px solid #e9e9e9; box-shadow: 0px 0px 1px #e9e9e9;">
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
                                <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <button class="btn filter-btn-show p-2 btn-dark d-flex justify-content-center align-items-center" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>


                        @can('create employee')
                        <a href="{{ route('employee.create') }}" data-size="lg" data-url="{{ route('employee.create') }}" data-ajax-popup="false" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Employee')}}" class="btn btn-dark d-flex justify-content-center align-items-center" style="color:white; width: 36px; height: 36px; margin-top: 10px;">
                            <i class="ti ti-plus" style="font-size: 18px;"></i>
                        </a>
                        @endcan

                        @php
                        $all_params = $_GET;
                        $query_string = http_build_query($all_params);
                        @endphp

                        @if(auth()->user()->type == 'super admin' || auth()->user()->type == 'Admin Team')
                        <a href="{{ route('employees.download') }}?{{ $query_string }}" class="btn btn-dark d-flex justify-content-center align-items-center" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="Download in Csv">
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
                <!-- end card topbar -->


                <!-- Filter Start -->
                <div class="filter-data px-3" id="filterToggle" <?= isset($_GET['branch_id']) ? '' : 'style="display: none;"' ?>>
                    <form action="">
                        <div class="row my-3 filbar">
                            @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
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

                            @if(\Auth::user()->can('level 3'))
                            <div class="col-md-3 mt-2" id="region_filter_div">
                                <label for="filter_region_id">Region</label>
                                <select name="region_id" class="form-control select2" id="filter_region_id">
                                    <option value="">Select Option</option>
                                    @forelse ($filters['regions'] as $key => $region)
                                    <option value="{{ $key }}" {{ request('region_id') == $key ? 'selected' : '' }}>{{ $region }}</option>
                                    @empty
                                    <option value="" disabled>No regions available</option>
                                    @endforelse
                                </select>
                            </div>
                            @endif

                            @if(\Auth::user()->can('level 4'))
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
                            @endif

                            <div class="col-md-3 mt-2" style="padding-top: 2rem;">
                                <div class="d-flex align-item-end gap-2">
                                    <input type="submit" class="btn btn-dark" value="Submit">
                                    <a href="/user/employees" class="btn btn-dark">Reset</a>
                                    <a type="button" id="save-filter-btn" onClick="saveFilter('employee',{{ sizeof($employees) }})" class="btn btn-dark" style="display: none;">Save Filter</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <!-- Filter End -->



                <!-- Employee Table -->
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
                            @forelse($employees as $key => $employee)
                            <tr>
                                <td>
                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="sub-check">
                                </td>
                                <td>
                                    <span style="cursor:pointer" class="hyper-link" @can('view employee') onclick="openSidebar('/employee/{{ $employee->id }}')" @endcan>{{ $employee->name }}</span>
                                </td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $employee->phone }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $employee->type }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $userRegionBranch['branches'][$employee->branch_id] ?? '' }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $userRegionBranch['regions'][$employee->region_id] ?? '' }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $userRegionBranch['users'][$employee->brand_id] ?? '' }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ !empty($employee->last_login_at) ? $employee->last_login_at : '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9">No employees found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($total_records > 0)
                    <div class="pagination_div">
                        @include('layouts.pagination', [
                        'total_pages' => $total_records,
                        'num_results_on_page' => 25,
                        ])
                    </div>
                    @endif
                </div>
                <!-- End Employee Table -->

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

        $("#dropdownMenuButton3").click(function() {
            $("#filterToggle").toggle();
        });


        $("#filter_brand_id").on("change", function() {
            var id = $(this).val();
            var type = 'brand';
            var filter = true;

            $.ajax({
                type: 'GET',
                url: '/region/regionBrands',
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
                url: '/region/regionBrands',
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


        // Global search
        $('.list-global-search-btn').add('.list-global-search').on('click keypress', function(e) {
            if (e.which === 13 || e.type === 'click') {
                var search = $(".list-global-search").val();
                var ajaxCall = 'true';
                $(".list-div").html('Loading...');
                $.ajax({
                    type: 'GET',
                    url: "{{ route('user.employees') }}",
                    data: {
                        search,
                        ajaxCall
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data.status == 'success') {
                            $(".list-div").html(data.html);
                            $(".pagination_div").html(data.pagination_html);
                        }
                    }
                })
            }
        });

        // Checkboxes
        $(document).on('change', '.main-check, .sub-check', function() {
            $(".sub-check").prop('checked', $(this).prop('checked'));
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();
            $("#actions_div").toggleClass('d-none', selectedIds.length === 0);
        });

        // Delete bulk
        $(document).on("click", '.delete-bulk', function() {
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
        });
    });
</script>
@endpush