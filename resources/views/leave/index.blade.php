@extends('layouts.admin')

@section('page-title')
{{__('Manage Leave')}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Manage Leave')}}</li>
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


    .lead-info-cell {
        max-width: 110px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .note-toolbar>.btn-group {
        position: absolute;
        top: 101px;
        z-index: 1000;
    }

    .note-toolbar>.btn-group>.note-btn>.note-icon-link {
        font-size: 22px;
        position: relative;
        padding-right: 10px;
        padding-bottom: 6px;

    }

    .note-toolbar>.btn-group>.note-btn {
        width: fit-content;
    }



    .note-toolbar>.btn-group>.note-btn>.note-icon-link::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 0;
        width: 2px;
        height: 50%;
        background-color: darkgray;
        transform: translateY(-50%);
    }

    .note-btn::after {
        content: " Add a title";
        font-size: 15px;
        color: darkgray;
        margin-left: 5px;
    }
</style>

<style>
    .form-controls,
    .form-btn {
        padding: 4px 1rem !important;
    }

    .action-btn {
        display: inline-grid !important;
    }

    .dataTable-bottom,
    .dataTable-top {
        display: none;
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

    .form-control:focus {
        border: none !important;
        outline: none !important;
    }

    .filbar .form-control:focus {
        border: 1px solid rgb(209, 209, 209) !important;
    }
</style> 

@section('content')


<div class="row">
    <div class="col-xl-12">
        <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">


            <div class="card-body table-border-style" style="padding: 25px 3px;">

                 <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-4 d-flex">
                            <span>
                                <p class="mb-0 pb-0 ps-1">Leaves</p>
                                <div class="dropdown">
                                    <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        ALL Leaves
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        @if (sizeof($saved_filters) > 0)
                                            @foreach ($saved_filters as $filter)
                                                <li class="d-flex align-items-center justify-content-between ps-2">
                                                    <div class="col-10">
                                                        <a href="{{ $filter->url }}"
                                                            class="text-capitalize fw-bold text-dark">{{ $filter->filter_name }}</a>
                                                        <span class="text-dark"> ({{ $filter->count }})</span>
                                                    </div>
                                                    <ul class="w-25" style="list-style: none;">
                                                        <li class="fil fw-bolder">
                                                            <i class=" fa-solid fa-ellipsis-vertical"
                                                                style="color: #000000;"></i>
                                                            <ul class="submenu"
                                                                style="border: 1px solid #e9e9e9;
                                                                        box-shadow: 0px 0px 1px #e9e9e9;">
                                                                <li><a class="dropdown-item" href="#"
                                                                        onClick="editFilter('<?= $filter->filter_name ?>', <?= $filter->id ?>)">Rename</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        onclick="deleteFilter('{{ $filter->id }}')"
                                                                        href="#">Delete</a></li>
                                                            </ul>
                                                        </li>
                                                    </ul>

                                                </li>
                                            @endforeach
                                        @else
                                            <li class="d-flex align-items-center justify-content-center ps-2">
                                                No Filter Found
                                            </li>
                                        @endif

                                    </ul>
                                </div>
                            </span>


                            <span class="ml-3">
                                <p class="mb-0 pb-0 ps-1">Limit</p>
                                <form action="{{ url('leads/list') }}" method="GET" id="paginationForm">
                                    <input type="hidden" name="num_results_on_page" id="num_results_on_page"
                                        value="{{ $_GET['num_results_on_page'] ?? '' }}">
                                    {{-- <input type="hidden" name="page" id="page" value="{{ $_GET['page'] ?? 1 }}"> --}}
                                    <input type="hidden" name="page" id="page" value="1">
                                    <select name="perPage" onchange="submitForm()"
                                        style="width: 100px; margin-right: 1rem;border: 1px solid lightgray;border-radius: 1px;padding: 2.5px 5px;">
                                        <option value="50"
                                            {{ Request::get('perPage') == 50 || Request::get('num_results_on_page') == 50 ? 'selected' : '' }}>
                                            50</option>
                                        <option value="100"
                                            {{ Request::get('perPage') == 100 || Request::get('num_results_on_page') == 100 ? 'selected' : '' }}>
                                            100</option>
                                        <option value="150"
                                            {{ Request::get('perPage') == 150 || Request::get('num_results_on_page') == 150 ? 'selected' : '' }}>
                                            150</option>
                                        <option value="200"
                                            {{ Request::get('perPage') == 200 || Request::get('num_results_on_page') == 200 ? 'selected' : '' }}>
                                            200</option>
                                    </select>
                                </form>

                                <script>
                                    function submitForm() {
                                        var selectValue = document.querySelector('select[name="perPage"]').value;
                                        document.getElementById("num_results_on_page").value = selectValue;
                                        // document.getElementById("page").value = {{ $_GET['page'] ?? 1 }};
                                        document.getElementById("page").value = 1;
                                        document.getElementById("paginationForm").submit();
                                    }
                                </script>
                            </span>
                        </div>

                        <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                            <div class="input-group w-25 rounded" style= "width:36px; height: 36px; margin-top:10px;">
                                <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                    <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search"
                                    class="form-control border-0 bg-transparent p-0 pb-2 list-global-search"
                                    placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>



                            <button id="filter-btn-show" class="btn filter-btn-show p-2 btn-dark" type="button"
                                data-bs-toggle="tooltip" title="{{ __('Filter') }}" aria-expanded="false"
                                style="color:white; width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>


                             @can('create leave')
                        <a href="#" data-size="lg" data-url="{{ route('leave.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Leave')}}" class="btn filter-btn-show p-2 btn-dark" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-plus"></i>
                        </a>
                        @endcan





                        </div>
                    </div>


                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                     


                    <div class="card-body table-responsive" style="padding: 25px 3px; width:auto;">
                         @include('leave.list_filter')
                        <table class="table " data-resizable-columns-id="lead-table" id="tfont">
                            <thead>
                                <tr>
                                    <th data-resizable-columns-id="employee">{{ __('Brand') }}</th>
                                    <th data-resizable-columns-id="employee">{{ __('Region') }}</th>
                                    <th data-resizable-columns-id="employee">{{ __('Branch') }}</th>
                                    <th data-resizable-columns-id="employee">{{ __('EMPLOYEE') }}</th>
                                    <th data-resizable-columns-id="leavetype">{{ __('LEAVE TYPE') }}</th>
                                    <th data-resizable-columns-id="appliedon">{{ __('APPLIED ON') }}</th>
                                    <th data-resizable-columns-id="startdate">{{ __('START DATE') }}</th>
                                    <th data-resizable-columns-id="enddate">{{ __('END DATE') }}</th>
                                    <th data-resizable-columns-id="totaldays">{{ __('TOTAL DAYS') }}</th>
                                    <th data-resizable-columns-id="status">{{ __('STATUS') }}</th>
                                    <th data-resizable-columns-id="action">{{ __('ACTION') }}</th>
                                </tr>
                            </thead>


                            <tbody>
                                @forelse ($leaves as $leave)
                                <tr>
                                     
                                    <td>{{ $allPluckUser[$leave->brand_id] }}</td>
                                    <td>{{ $allPluckregion[$leave->region_id] }}</td>
                                    <td>{{ $allPluckbranch[$leave->branch_id] }}</td>
                                    <td>{{ $allPluckUser[$leave->employee_id] }}</td>
                                     
                                    <td>{{ !empty(\Auth::user()->getLeaveType($leave->leave_type_id))?\Auth::user()->getLeaveType($leave->leave_type_id)->title:'' }}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->applied_on )}}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->start_date ) }}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->end_date )  }}</td>
                                    @php
                                    $startDate = new \DateTime($leave->start_date);
                                    $endDate = new \DateTime($leave->end_date);
                                    $total_leave_days = !empty($startDate->diff($endDate))?$startDate->diff($endDate)->days:0;
                                    @endphp
                                    <td>{{ $total_leave_days }}</td>
                                    <td>

                                        @if($leave->status=="Pending")
                                        <div class="status_badge badge bg-warning p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @elseif($leave->status=="Approved")
                                        <div class="status_badge badge bg-success p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @else($leave->status=="Reject")
                                        <div class="status_badge badge bg-danger p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(\Auth::user()->type == 'employee')
                                        @if($leave->status == "Pending")
                                        @can('edit leave')
                                        <div class="action-btn bg-primary ms-2">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Leave')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil text-white"></i></a>
                                        </div>
                                        @endcan
                                        @endif
                                        @else
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/action') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Leave Action')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Leave Action')}}" data-original-title="{{__('Leave Action')}}">
                                                <i class="ti ti-caret-right text-white"></i> </a>
                                        </div>
                                        @can('edit leave')
                                        <div class="action-btn bg-primary ms-2">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Leave')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i></a>
                                        </div>
                                        @endcan
                                        @endif
                                        @can('delete leave')
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['leave.destroy', $leave->id],'id'=>'delete-form-'.$leave->id]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$leave->id}}').submit();">
                                                <i class="ti ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty 
                                <tr>
                                    <td colspan="8">No Record Found!!!</td>
                                </tr>
                                @endforelse
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
</div>

@endsection

@push('script-page')
<script>
    $(document).on('change', '#employee_id', function() {
        var employee_id = $(this).val();

        $.ajax({
            url: '{{route('leave.jsoncount')}}',
            type: 'POST',
            data: {
                "employee_id": employee_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {

                $('#leave_type_id').empty();
                $('#leave_type_id').append('<option value="">{{__('Select Leave Type ')}}</option>');

                $.each(data, function(key, value) {

                    if (value.total_leave >= value.days) {
                        $('#leave_type_id').append('<option value="' + value.id + '" disabled>' + value.title + '&nbsp(' + value.total_leave + '/' + value.days + ')</option>');
                    } else {
                        $('#leave_type_id').append('<option value="' + value.id + '">' + value.title + '&nbsp(' + value.total_leave + '/' + value.days + ')</option>');
                    }
                });

            }
        });
    });



function deleteTage() {
    $.ajax({
        type: "GET",
        url: '{{ url('delete_tage') }}',
        data: {deal_id : $('#dealer_id').val(),old_tag_id : $('#old_tag_id').val()},
        success: function(response) {
            data = JSON.parse(response);

            if (data.status == 'success') {
                $("#UpdateTageModal").hide();
                show_toastr('success', data.msg);
                window.location.href = '/deals/list';
            }
        },
    });
}

    $(document).ready(function() {
        select2();
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
                //alert(data.status);
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
                select2();
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
@endpush