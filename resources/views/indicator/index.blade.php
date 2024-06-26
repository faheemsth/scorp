@extends('layouts.admin')
@section('page-title')
    {{__('Manage Indicator')}}
@endsection
@push('css-page')
    <style>
        @import url({{ asset('css/font-awesome.css') }});
    </style>
@endpush
@push('script-page')
    <script src="{{ asset('js/bootstrap-toggle.js') }}"></script>
    <script>

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
            $("fieldset[id^='demo'] .stars").click(function () {
                $(this).attr("checked");
            });
        });


        $(document).ready(function () {
            var d_id = $('#department_id').val();
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department]', function () {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {
            $.ajax({
                url: '{{route('employee.json')}}',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">{{__('Select Designation')}}</option>');
                    $.each(data, function (key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Indicator')}}</li>
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
        <div class="col-md-12">
            <div class="card">
            <div class="card-body table-border-style">
                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-4 d-flex mb-3">
                        <span>
                            <p class="mb-0 pb-0 ps-1">Leads</p>
                            <div class="dropdown">
                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    ALL Indicator
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
                            <form action="{{ url('indicator') }}" method="GET" id="paginationForm">
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

                    <div class="col-8 d-flex justify-content-end gap-2 pe-0 mb-3">
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


                        @can('create indicator')
                        <a href="#" data-size="lg" data-url="{{ route('indicator.create') }}"
                            data-ajax-popup="true"
                            data-bs-toggle="tooltip" title="{{__('Create')}}" style="color:white; width:36px; height: 36px; margin-top:10px;" data-title="{{__('Create New Indicator')}}"
                            class="btn px-2 btn-dark">
                            <i class="ti ti-plus"></i>
                         </a>
                         @endcan
                    </div>
                </div>
                @include('indicator.list_filter')

                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Brand')}}</th>
                                <th>{{__('Region')}}</th>
                                <th>{{__('Branch')}}</th>

                                <th>{{__('Designation')}}</th>
                                <th>{{__('Overall Rating')}}</th>
                                <th>{{__('Added By')}}</th>
                                <th>{{__('Created At')}}</th>

                            </tr>
                            </thead>
                            <tbody class="font-style">


                            @foreach ($indicators as $indicator)

                                @php
                                    if(!empty($indicator->rating)){
                                        $rating = json_decode($indicator->rating,true);
                                        if(!empty($rating)){
                                            $starsum = array_sum($rating);
                                            $overallrating = $starsum/count($rating);
                                        }else{
                                                $overallrating = 0;
                                        }

                                    }
                                    else{
                                        $overallrating = 0;
                                    }
                                @endphp
                                <tr>
                                    <td>

                                        <span style="cursor:pointer" class="lead-name hyper-link"
                                                onclick="openSidebar('/IndicatorShowing?id=<?= $indicator->id ?>')"
                                                data-lead-id="{{ $indicator->id }}">
                                            {{ !empty($indicator->departments)?$indicator->departments->name:'' }}
                                        </span>

                                    </td>
                                    <td>{{ $indicator->brand }}</td>
                                    <td>{{ $indicator->region }}</td>
                                    <td>{{ $indicator->branch }}</td>


                                    <td>{{ !empty($indicator->designations)?$indicator->designations->name:'' }}</td>
                                    <td>

                                        @for($i=1; $i<=5; $i++)
                                            @if($overallrating < $i)
                                                @if(is_float($overallrating) && (round($overallrating) == $i))
                                                    <i class="text-warning fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="fas fa-star"></i>
                                                @endif
                                            @else
                                                <i class="text-warning fas fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="theme-text-color">({{number_format($overallrating,1)}})</span>
                                    </td>


                                    <td>{{ !empty($indicator->user)?$indicator->user->name:'' }}</td>
                                    <td>{{ \Auth::user()->dateFormat($indicator->created_at) }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
<script>

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
                data = JSON.parse(data);
                select2();
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
                    select2();
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
