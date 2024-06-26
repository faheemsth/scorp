@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Tags') }}
@endsection
@push('script-page')
@endpush
@section('content')
    <div class="row">
        <div class="col-3 p-0">
            @include('layouts.crm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between;">
                    <h3>Tags</h3>
                    <div class="float-end d-flex">
                        @can('level 2')
                            <a href="#" data-size="md" data-url="{{ route('tages.create') }}" data-ajax-popup="true"
                                data-bs-toggle="tooltip" style="margin: auto" title="{{ __('Create New Tag') }}"
                                class="btn btn-sm btn-dark">
                                <i class="ti ti-plus"></i>
                            </a>
                        @endcan

                        <button class="btn filter-btn-show btn-sm ml-1 p-2 btn-dark" type="button" id="filter-btn-show"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            style="width:33px; height: 32px; margin-top:4px;">
                            <i class="ti ti-filter" style="font-size:16px"></i>
                        </button>

                        {!! Form::open(['method' => 'GET', 'id' => 'tagModalBtnForm', 'class' => 'd-none']) !!}
                        <input type="hidden" name="id" id="selectedIdsInput">
                        <button type="submit" class="btn btn-sm btn-dark mx-1 mt-1" form="tagModalBtnForm">
                            Bulk Delete
                        </button>
                        {!! Form::close() !!}

                    </div>
                </div>
                <div class="card-header" id="filter-show"
                    <?= isset($_GET) && !empty($_GET) && empty($_GET['perPage']) ? '' : 'style="display: none;"' ?>>
                    <form action="/tages" method="GET" class="">
                        @if (!empty($_GET['num_results_on_page']))
                            <input type="hidden" name="num_results_on_page" id="num_results_on_page" value="{{ $_GET['num_results_on_page'] }}">
                        @endif
                        <input type="hidden" name="page" id="page" value="{{ $_GET['page'] ?? 1 }}">
                        <div class="row my-3 align-items-end">
                            @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                                <div class="col-md-3 mt-2">
                                    <label for="">Brand</label>
                                    <select name="brand" class="form form-control select2" id="filter_brand_id">
                                        @if (!empty($filters['brands']))
                                            @foreach ($filters['brands'] as $key => $brand)
                                                <option value="{{ $key }}" {{ (!empty($_GET['brand']) && $_GET['brand'] == $key) ? 'selected' : '' }}>{{ $brand }}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No brands available</option>
                                        @endif
                                    </select>
                                </div>
                            @endif

                            @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
                                <div class="col-md-3 mt-2" id="region_filter_div">
                                    <label for="">Region</label>
                                    <select name="region_id" class="form form-control select2" id="filter_region_id">
                                        @if (!empty($filters['regions']))
                                            @foreach ($filters['regions'] as $key => $region)
                                                <option value="{{ $key }}" {{ (!empty($_GET['region_id']) && $_GET['region_id'] == $key) ? 'selected' : '' }}>{{ $region }}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No regions available</option>
                                        @endif
                                    </select>
                                </div>
                            @endif

                            @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3') || \Auth::user()->can('level 4'))
                                <div class="col-md-3 mt-2" id="branch_filter_div">
                                    <label for="">Branch</label>
                                    <select name="branch_id" class="form form-control select2" id="filter_branch_id">
                                        @if (!empty($filters['branches']))
                                            @foreach ($filters['branches'] as $key => $branch)
                                                <option value="{{ $key }}" {{ (!empty($_GET['branch_id']) && $_GET['branch_id'] == $key) ? 'selected' : '' }}>{{ $branch }}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No branches available</option>
                                        @endif
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-5 mt-3 d-flex">
                                <br>
                                <input type="submit" class="btn form-btn bg-dark" style=" color:white;">
                                <a href="/tages" style="margin: 0px 3px;" class="btn form-btn bg-dark" style="color:white;">Reset</a>
                                <a type="button" id="save-filter-btn" onClick="saveFilter('leads',<?= isset($leads) && is_countable($leads) ? sizeof($leads) : 0 ?>)" class="btn form-btn me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th style="width: 250px !important;">
                                        <input type="checkbox" class="main-check">
                                    </th>
                                    <th width="250px">{{ __('Tag') }}</th>
                                    <th width="250px">{{ __('Brand') }}</th>
                                    <th width="250px">{{ __('Region') }}</th>
                                    <th width="250px">{{ __('Branch') }}</th>
                                    <th width="250px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tags as $tag)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="leads[]" value="{{ $tag['id'] }}"
                                                class="sub-check">
                                        </td>
                                        <td>{{ $tag['tag'] }}</td>
                                        <td>{{ $tag['brand'] }}</td>
                                        <td>{{ $tag['region'] }}</td>
                                        <td>{{ $tag['branch'] }}</td>
                                        <td class="Active">

                                            @can('level 2')
                                                <div class="action-btn ms-2">
                                                    <a href="#" class="btn btn-sm btn-dark mx-1 align-items-center"
                                                        data-url="{{ url('tages/edit/') . '/' . $tag['id'] }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="{{ __('Edit Tag') }}" data-title="{{ __('Edit Tag') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('level 2')
                                                <div class="action-btn ms-2">
                                                    {{-- {!! Form::open(['method' => 'DELETE', 'route' => ['tages.destroy', $tag['id']]]) !!}
                                                    <a href="#"
                                                        class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!} --}}
                                                </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#filter-btn-show').on('click', function() {
                $('#filter-show').toggle();
            });
            $(".main-check").on('change', function() {
                $(".sub-check").prop('checked', $(this).prop('checked'));
                updateSelectedIds();
            });

            $(".sub-check").on('change', function() {
                updateSelectedIds();
            });

            function updateSelectedIds() {
                var selectedIds = $('.sub-check:checked').map(function() {
                    return this.value;
                }).get();

                if (selectedIds.length > 0) {
                    $("#selectedIdsInput").val(selectedIds.join(','));
                    $("#tagModalBtnForm").attr('action', '{{ route('tages.bulk.delete.d') }}')
                    $("#tagModalBtnForm").removeClass('d-none');
                } else {
                    $("#selectedIdsInput").val('');
                    $("#tagModalBtnForm").removeAttr('action').addClass('d-none');
                    $("#tagModalBtnForm").addClass('d-none');
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
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


            $(document).on("change", "#filter_region_id", function() {
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

            $(document).on("change", "#filter_branch_id", function() {

                var id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('filter-branch-users') }}',
                    data: {
                        id: id,
                        page: 'lead_list'
                    },
                    success: function(data) {
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


            $(document).on("change", "#filter_brand_id", function() {
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
                            $('#region_bulkassign_div').html('');
                            $("#region_bulkassign_div").html(data.regions);
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

            $(document).on("change", "#bulk-assign #region_id, #bulk-assign #filter_region_id", function() {
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
                            $('#branch_bulkassign_div').html('');
                            $("#branch_bulkassign_div").html(data.branches);
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

            $(document).on("change", "#bulk-assign #branch_id, #bulk-assign #filter_branch_id", function() {
                var id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('filter-branch-users') }}',
                    data: {
                        id: id,
                        page: 'lead_list'
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status === 'success') {
                            $('#bulkassign_to_div').html(data.html);
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
        });
    </script>
    <script>
        function saveTagData() {
            var formData = $("#tagForm").serialize();
            $button = $(this);
            $button.prop('disabled', true);
            $('#tagupdateappend').val('Processing');
            $('#tagupdateappend').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "{{ route('tages.store') }}",
                data: formData,
                success: function(response) {
                    data = JSON.parse(response);
                    if (data.status == 'success') {
                        $("#tagModal").hide();
                        $('#tagupdateappend').val('Create');
                        $('#tagupdateappend').prop('disabled', false);
                        show_toastr('success', data.message);
                        window.location.href = '/tages';
                    } else {
                        $("#tagModal").hide();
                        $('#tagupdateappend').val('Create');
                        $('#tagupdateappend').prop('disabled', false);
                        show_toastr('error', data.message);
                    }

                },

            });
        }

        function updateTagData() {
            var formData = $("#tagForm").serialize();
            $button = $(this);
            $button.prop('disabled', true);
            $('#tagupdateappend').val('Processing');
            $('#tagupdateappend').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "{{ url('tages/update') }}",
                data: formData,
                success: function(response) {
                    data = JSON.parse(response);
                    if (data.status == 'success') {
                        $("#tagModal").hide();
                        $('#tagupdateappend').val('Create');
                        $('#tagupdateappend').prop('disabled', false);
                        show_toastr('success', data.message);
                        window.location.href = '/tages';
                    } else {
                        $("#tagModal").hide();
                        $('#tagupdateappend').val('Create');
                        $('#tagupdateappend').prop('disabled', false);
                        show_toastr('error', data.message);
                    }

                },

            });
        }
    </script>

    <script>
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
    </script>
@endsection
