@extends('layouts.admin')
@section('page-title')
{{ __('Manage Branch') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Branch') }}</li>
@endsection

{{-- @section('action-btn')
    <div class="float-end">
        @can('create branch')
            <a href="#" data-url="{{ route('branch.create') }}" data-ajax-popup="true"
data-title="{{ __('Create New Branch') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}"
class="btn btn-sm btn-primary">
<i class="ti ti-plus"></i>
</a>
@endcan
</div>
@endsection --}}

@section('content')
<style>
    table {
        font-size: 14px !important;
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


    <div>


        <div class="card">

            <div class="row align-items-center mx-2 my-4 justify-content-between">
                <div class="col-2">
                    <p class="mb-0 pb-0 ps-1">Branches</p>
                    <div class="dropdown">
                        <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <span> ALL BRANCHES </span>
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
                <div class="col-8 d-flex justify-content-end gap-2">
                    <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                        <button class="btn btn-sm list-global-search-btn p-0 pb-2">
                            <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                <i class="ti ti-search" style="font-size: 18px"></i>
                            </span>
                        </button>
                        <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <button class="btn px-2 pb-2 pt-2 refresh-list btn-dark d-none" style="width:36px; height: 36px; margin-top:10px;"><i class="ti ti-refresh" style="font-size: 18px"></i></button>

                    <button class="btn filter-btn-show p-2 btn-dark" type="button" id="filter-button" data-bs-toggle="dropdown" aria-expanded="false" style="width:36px; height: 36px; margin-top:10px;">
                        <i class="ti ti-filter" style="font-size:18px"></i>
                    </button>

                    @can('create branch')
                    <!-- Modified the opening tag to match the closing tag -->
                    <a href="#" data-size="lg" data-url="{{ route('branch.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Branch')}}" class="btn px-2 btn-dark" style="width:36px; height: 36px; margin-top:10px;">
                        <i class="ti ti-plus"></i>
                    </a>
                    @endcan

                    @if(auth()->user()->type == 'super admin' || auth()->user()->type == 'Admin Team')
                    <a href="{{ route('branches.download') }}" class="btn  btn-dark px-0" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="" data-original-title="Download in Csv">
                        <i class="ti ti-download" style="font-size:18px"></i>
                    </a>
                    @endif


                    @if(auth()->user()->type == 'super admin' || auth()->user()->can('delete branch'))
                    <a href="javascript:void(0)" id="actions_div" data-bs-toggle="tooltip" title="{{ __('Delete Branches') }}" class="btn delete-bulk text-white btn-dark d-none px-0" style="width:36px; height: 36px; margin-top:10px;">
                        <i class="ti ti-trash"></i>
                    </a>
                    @endif
                    <!-- Added the missing closing div tag -->
                </div>
            </div>


            <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                <form action="/branch" method="GET" class="">

                    <div class="row my-3">
                        @php
                        $type = \Auth::user()->type;
                        @endphp

                        @if($type == 'super admin' || $type == 'Admin Team' || $type == 'Project Director' || $type == 'Project Manager')
                        <div class="col-md-3">
                            <label for="">Brand</label>
                            <select name="brand_id" id="brand_id" class="form form-control select2">
                                <option value="">Select Brand</option>
                                @foreach($filters['brands'] as $key => $brand)
                                <option value="{{ $key }}" {{ isset($_GET['brand_id']) && $_GET['brand_id'] == $key ? 'selected' : ''}}>{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif


                        @if($type == 'super admin' || $type == 'Admin Team' || $type == 'Project Director' || $type == 'Project Manager' || $type == 'Region Manager')
                        <div class="col-md-3" id="region_div">
                            <label for="">Region</label>
                            <select class="form form-control select2" id="filter_region_id" name="region_id" style="width: 95%;">
                                <option value="">Select Region</option>
                                @foreach ($filters['regions'] as $key => $region)
                                <option value="{{ $key }}" {{ isset($_GET['region_id']) && $_GET['region_id'] == $key ? 'selected' : ''}}>{{ $region }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif


                        @if($type == 'super admin' || $type == 'Admin Team' || $type == 'Project Director' || $type == 'Project Manager' || $type == 'Region Manager')
                        <div class="col-md-3" id="branch_div">
                            <label for="">Branch</label>
                            <select class="form form-control select2" id="filter_branch_id" name="branch_id" style="width: 95%;">
                                <option value="">Select Region</option>
                                @foreach ($filters['branches'] as $key => $branch)
                                <option value="{{ $key }}" {{ isset($_GET['branch_id']) && $_GET['branch_id'] == $key ? 'selected' : ''}}>{{ $region }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif




                        <div class="col-md-3 mt-4 pt-2">
                            <input type="submit" class="btn form-btn me-2 btn-dark px-2 py-2">
                            <a href="/branch" class="btn btn-dark px-2 py-2" style="background-color: #b5282f;color:white;">Reset</a>
                            <a type="button" id="save-filter-btn" onClick="saveFilter('branch',<?= sizeof($branches) ?>)" class="btn btn-dark me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
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


            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th style="width: 50px !important;">
                                    <input type="checkbox" class="main-check">
                                </th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Branch Manager') }}</th>
                                <th>{{ __('Region') }}</th>
                                <th>{{ __('Brand') }}</th>
                            </tr>
                        </thead>
                        <tbody class="font-style list-div">
                            @foreach ($branches as $branch)
                            <tr>
                                <td>
                                    <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" class="sub-check">
                                </td>

                                <td>
                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/branch/{{ $branch->id }}/show')">
                                        {{ $branch->name }}
                                    </span>
                                </td>

                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="mailto:{{ $branch->email }}">{{ $branch->email }}</a></td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $branch->phone }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($branch->branch_manager_id) && isset($users[$branch->branch_manager_id]) ? $users[$branch->branch_manager_id] : '' }}</td>
                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($regions[$branch->region_id]) ? $regions[$branch->region_id] : '' }}</td>

                                <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ isset($branch->brands) ? \App\Models\User::where('id', $branch->brands)->first()->name : '' }}</td>
                                
                            </tr>
                            @endforeach
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
@endsection


@push('script-page')
<script>
    $(document).ready(function() {
        let curr_url = window.location.href;

        if (curr_url.includes('?')) {
            $('#save-filter-btn').css('display', 'inline-block');
        }
    });

    $(document).ready(function() {
        $(".filter-btn-show").click(function() {
            $("#filter-show").toggle();
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
                url: "{{ route('branch.index') }}",
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
            url: "{{ route('branch.index') }}",
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
                window.location.href = '/delete-bulk-branches?ids=' + selectedIds.join(',');
            }
        });
    })

    $("#brand_id").on("change", function() {
        var id = $(this).val();
        var type = 'brand';
        var filter = true;

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id, // Add a key for the id parameter
                type: type,
                filter: filter
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


    $(document).on("change", "#filter_region_id", function() {
        var id = $(this).val();
        var type = 'region';
        var filter = true;

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id, // Add a key for the id parameter
                type: type,
                filter: filter
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
</script>
@endpush