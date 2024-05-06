@extends('layouts.admin')
@section('page-title')
    {{__('Manage Tags')}}
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

                        @can('level 2')
                        <div class="float-end">
                            <a href="#" data-size="md" data-url="{{ route('tages.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Tag')}}" class="btn btn-sm btn-dark">
                                <i class="ti ti-plus"></i>
                            </a>
                        </div>
                        @endcan
                    </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Tag')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sources as $source)
                                <tr>
                                    <td>{{ $source->tag }}</td>
                                    <td class="Active">

                                        @can('level 2')
                                            <div class="action-btn ms-2">
                                                <a href="#" class="btn btn-sm btn-dark mx-1 align-items-center" data-url="{{ url('tages/edit/').'/'.$source->id }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit Tag')}}" data-title="{{__('Edit Tag')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('level 2')
                                            <div class="action-btn ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['tages.destroy', $source->id]]) !!}
                                                <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
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


    $(document).on("change", "#filter_brand_id" ,function() {
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
            if(data.status == 'success'){
                $("#tagModal").hide();
                $('#tagupdateappend').val('Create');
                $('#tagupdateappend').prop('disabled', false);
                show_toastr('success', data.message);
                window.location.href = '/tages';
            }else{
                $("#tagModal").hide();
                $('#tagupdateappend').val('Create');
                $('#tagupdateappend').prop('disabled', false);
                show_toastr('error', data.message);
            }

        },

      });
    }

    function updateTagData()
    {
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
            if(data.status == 'success'){
                $("#tagModal").hide();
                $('#tagupdateappend').val('Create');
                $('#tagupdateappend').prop('disabled', false);
                show_toastr('success', data.message);
                window.location.href = '/tages';
            }else{
                $("#tagModal").hide();
                $('#tagupdateappend').val('Create');
                $('#tagupdateappend').prop('disabled', false);
                show_toastr('error', data.message);
            }

        },

      });
    }
    </script>

@endsection
