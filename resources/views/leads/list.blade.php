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


    .lead-info-cell {
        max-width: 110px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

</style>

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
{{-- comment --}}


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

            <div class="col-xl-12">
                <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
                    <div class="card-body table-border-style" style="padding: 25px 3px;">
                        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                            <div class="col-4 d-flex">
                                <span>
                                <p class="mb-0 pb-0 ps-1">Leads</p>
                                <div class="dropdown">
                                    <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        ALL LEADS
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

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
                                        <input type="hidden" name="num_results_on_page" id="num_results_on_page" value="{{ $_GET['num_results_on_page'] ?? '' }}">
                                        <input type="hidden" name="page" id="page" value="{{ $_GET['page'] ?? 1 }}">
                                        <select name="perPage" onchange="submitForm()" style="width: 100px; margin-right: 1rem;border: 1px solid lightgray;border-radius: 1px;padding: 2.5px 5px;">
                                            <option value="50" {{ Request::get('perPage') == 50 || Request::get('num_results_on_page') == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ Request::get('perPage') == 100 || Request::get('num_results_on_page') == 100 ? 'selected' : '' }}>100</option>
                                            <option value="150" {{ Request::get('perPage') == 150 || Request::get('num_results_on_page') == 150 ? 'selected' : '' }}>150</option>
                                            <option value="200" {{ Request::get('perPage') == 200 || Request::get('num_results_on_page') == 200 ? 'selected' : '' }}>200</option>
                                        </select>
                                    </form>

                                    <script>
                                        function submitForm() {
                                            var selectValue = document.querySelector('select[name="perPage"]').value;
                                            document.getElementById("num_results_on_page").value = selectValue;
                                            document.getElementById("page").value = {{ $_GET['page'] ?? 1 }};
                                            document.getElementById("paginationForm").submit();
                                        }
                                    </script>
                                </span>
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

                                <div class="btn-group">
                                    <button type="button" class="btn btn-dark dropdown-toggle-split rounded-1" style="font-weight: 500; color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="far fa-clone" style="font-size: 15px;"></i><span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <!-- Dropdown menu items -->
                                        @if(auth()->user()->can('delete lead'))
                                        <li>
                                            <button type="button" class="btn btn-link assigned_to delete-bulk-leads d-none dropdown-item" id="actions_div">
                                                Mass Delete
                                            </button>
                                        </li>
                                        @endif

                                        @if(auth()->user()->can('edit lead'))
                                        <li>
                                            {{-- <button data-size="lg" data-url="{{ route('MassUpdate') }}" id="actions_div" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Mass Update') }}"
                                            class="btn btn-link assigned_to update-bulk-leads d-none dropdown-item"  >
                                                Mass Update
                                            </button> --}}
                                            {{-- <button type="button" class="btn btn-link assigned_to update-bulk-leads d-none dropdown-item" id="actions_div">
                                            </button> --}}
                                        </li>
                                        @endif

                                        <li>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-link dropdown-item d-none" id="massAssignModalBtn" data-toggle="modal" data-target="#massAssignModal">
                                                Mass Assign
                                            </button>
                                        </li>


                                        <li>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-link dropdown-item d-none" id="tagModalBtn" data-toggle="modal" data-target="#tagModal">
                                                Tags
                                            </button>
                                        </li>


                                        <li>
                                            <button type="button" class="btn btn-link send_bulk_email dropdown-item d-none" id="actions_div">
                                                Send Mail
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                         @include('leads.list_filter')

                        <div class="card-body table-responsive" style="padding: 25px 3px; width:auto;">
                            <table class="table " data-resizable-columns-id="lead-table" id="tfont">
                                <thead>
                                    <tr>
                                        <th style="width: 50px !important;">
                                            <input type="checkbox" class="main-check">
                                        </th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Stage') }}</th>
                                        <th>{{ __('Assigned to') }}</th>
                                        <th>{{ __('Brand') }}</th>
                                        <th>{{ __('Branch') }}</th>
                                        <th>{{ __('Tag') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="leads-list-tbody leads-list-div">
                                    @if (count($leads) > 0)
                                        @foreach ($leads as $lead)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="leads[]" value="{{ $lead->id }}"
                                                        data-brand-id="{{ $lead->brand_id }}" data-brand-name="{{ $filters['brands'][$lead->brand_id] ?? '' }}"
                                                        class="sub-check">
                                                </td>

                                                <td class="lead-info-cell">
                                                    <span style="cursor:pointer" class="lead-name hyper-link"
                                                        @can('view lead') onclick="openSidebar('/get-lead-detail?lead_id=<?= $lead->id ?>')" @endcan
                                                        data-lead-id="{{ $lead->id }}">{{ $lead->name }}</span>
                                                </td>

                                                <td class="lead-info-cell">
                                                    <a href="{{ $lead->email }}">{{ $lead->email }}</a>
                                                </td>

                                                <td class="lead-info-cell">
                                                    {{ $lead->phone }}
                                                </td>

                                                <td class="lead-info-cell">
                                                    {{ !empty($lead->stage) ? $lead->stage->name : '-' }}
                                                </td>

                                                <td class="lead-info-cell">
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

                                                <td class="lead-info-cell">
                                                    {{ $users[$lead->brand_id] ?? '' }}
                                                </td>

                                                <td class="lead-info-cell">
                                                    {{ $branches[$lead->branch_id] ?? '' }}
                                                </td>

                                                <td class="lead-info-cell">
                                                    @php
                                                        $lead_tags = \App\Models\LeadTag::where('lead_id', $lead->id)->get();
                                                    @endphp

                                                    @forelse($lead_tags as $tag)
                                                        <span class="badge text-white {{ \Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'Project Manager' || \Auth::user()->type == 'Project Director' ? 'tag-badge' :''}}" data-tag-id="{{ $tag->id }}" data-tag-name="{{ $tag->tag }}" style="background-color:#cd9835;cursor:pointer;">
                                                            {{ $tag->tag }}
                                                        </span>
                                                    @empty
                                                    @endforelse
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="font-style">
                                            <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
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

    @include('leads.list_modals')
@endsection

@push('script-page')
    <script>
    function deleteTage() {
        $.ajax({
            type: "GET",
            url: '{{ url('delete_tage') }}',
            data: {id : $('#tagIdInput').val()},
            success: function(response) {
                data = JSON.parse(response);

                if (data.status == 'success') {
                    $("#UpdateTageModal").hide();
                    show_toastr('success', data.msg);
                    window.location.href = '/leads/list';
                }
            },
        });
    }

    $(".add-filter").on("click", function() {
        $(".filter-data").toggle();
    })




    $("#tagModalBtn").on("click", function(){
        $(".sub-check").prop('checked', $(this).prop('checked'));

        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        $("#selectedIds").val(selectedIds);
    })


    $("#massAssignModalBtn").on("click", function() {
        $(".sub-check").prop('checked', $(this).prop('checked'));

        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        var brandIds = $('.sub-check:checked').map(function() {
            return $(this).data('brand-id');
        }).get();

        var brandNames = $('.sub-check:checked').map(function() {
            return $(this).data('brand-name');
        }).get();

        $("#mySelectedIds").val(selectedIds);

        // Check if all brandIds are the same
        var areBrandIdsSame = brandIds.every(function(element) {
            return element === brandIds[0];
        });

        if (!areBrandIdsSame) {
            show_toastr('error', 'Brand Ids should be the same');
            return false;
        }

        var brandId = brandIds[0];
        var brandName = brandNames[0];

        var html = '<label for="">Brand</label>' +
                    '<select name="brand" class="form form-control select2" id="filter_brand_id">' +
                    '<option value="">Select Brand</option>' +
                    '<option value="' + brandId + '">' + brandName + '</option>' +
                    '</select>';
        $("#brand_id_div").html(html);
        select2();
    });


    $(".add-tags").on("click", function(e){
        e.preventDefault();
        $button = $(this);


        var formData = $("#addTagForm").serialize(); // Serialize form data
        var url = $("#addTagForm").attr('action'); // Get form action URL
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token
        $button.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            headers: {
                'X-CSRF-Token': csrfToken // Include CSRF token in headers
            },
            success: function(response){
                data = JSON.parse(response);

                if(data.status == 'success'){

                    $("#tagModal").hide();
                    $(".modal-backdrop").removeClass('modal-backdrop');
                    $(".sub-check").prop('checked', false);
                    $button.prop('disabled', false);
                    show_toastr('success', data.msg);
                    window.location.href = '/leads/list';
                }
                // Handle success response here
                console.log("Data submitted successfully:", response);
            },
            error: function(xhr, status, error){
                // Handle error response here
                console.error("error submitting data:", error);
            }
        });
    });


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
            $("#tagModalBtn").removeClass('d-none');
            $("#massAssignModalBtn").removeClass('d-none');
            $(".send_bulk_email").removeClass('d-none');
            var url = $(this).data('url') + '?' + $.param({ids: selectedIds});
            $('.update-bulk-leads').data('url', url);
            $(".update-bulk-leads").removeClass('d-none');
        } else {
            selectedArr = selectedIds;
            $("#actions_div").addClass('d-none');
            $("#tagModalBtn").addClass('d-none');
            $("#massAssignModalBtn").addClass('d-none');
            $(".send_bulk_email").addClass('d-none');
            $(".update-bulk-leads").addClass('d-none');
        }
    });

    $(document).on('change', '.sub-check', function() {
        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        if (selectedIds.length > 0) {
            selectedArr = selectedIds;
            $("#actions_div").removeClass('d-none');
            $("#tagModalBtn").removeClass('d-none');
            $("#massAssignModalBtn").removeClass('d-none');
            $(".send_bulk_email").removeClass('d-none');
            $(".update-bulk-leads").removeClass('d-none');
        } else {
            selectedArr = selectedIds;

            $("#actions_div").addClass('d-none');
            $("#tagModalBtn").addClass('d-none');
            $("#massAssignModalBtn").addClass('d-none');
            $(".send_bulk_email").addClass('d-none');
            $(".update-bulk-leads").addClass('d-none');
        }
        let commaSeperated = selectedArr.join(",");
        console.log(commaSeperated)
        $("#lead_ids").val(commaSeperated);

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

    $(".send_bulk_email").on("click", function() {
        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        $.ajax({
            method: 'POST',
            url: '{{ route("send.bulk.email") }}',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token
                ids: selectedIds  // Pass the selected IDs as data
            },
            success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                show_toastr('success', response.message);
                // Handle success response
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error response
            }
        });
    });


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
                    show_toastr('success', 'Stage updated successfully.', 'success');
                    if (stage_id == 6 || stage_id == 7) {
                        window.location.href = '/leads/list';
                    } else {
                        openSidebar('/get-lead-detail?lead_id='+lead_id);
                        return false;
                    }
                } else {
                    show_toastr('error', data.message, 'error');
                }
            }
        });
    });

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


    $(document).on("click", '#submit_btn', function(){
        var brand = $("#brands").val();
        var region_id = $("#region_id").val();
        var branch_id = $("#branch_id").val();
        var assign_to = $("#assigned_to").val();
        var user_id = $("#user_id").val();
        if(brand == 0 || region_id == 'undefined' || region_id == 0 || branch_id == 'undefined' || branch_id == 0 || user_id == 'undefined' || user_id == 0){
            show_toastr('error', 'Please fill all the required fields');
            return false;
        }        
        $("#importCsvForm").submit();
    });



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
                    show_toastr('error', data.message, 'error');
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
                    show_toastr('error', data.message, 'error');
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
                    $('#note_id').val('');
                    $('#description').val('');
                } else {
                    show_toastr('error', data.message, 'error');
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
                        $('#note_id').val('');
                        $('#description').val('');
                    } else {
                        show_toastr('error', data.message, 'error');
                    }
                }
            });
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
        $("#filter-show #filter_brand_id, #bulk-assign #filter_brand_id").on("change", function() {
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


        $(document).on("change", "#filter-show #filter_region_id, #filter-show #region_id", function() {
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

        $(document).on("change", "#filter-show #filter_branch_id, #filter-show #branch_id", function() {

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


        $(document).on("change", "#bulk-assign #filter_brand_id" ,function() {
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
                    success: function(data){
                        data = JSON.parse(data);

                        if (data.status === 'success') {
                            $('#bulkassign_to_div').html('');
                            $("#bulkassign_to_div").html(data.html);
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

        // $(document).on("change", "#filter_branch_id, #branch_id", function() {
        //    getLeads();
        // });

        // function getLeads(){
        //     var brand_id = $("#filter_brand_id").val();
        //     var region_id = $("#region_id").val();
        //     var branch_id = $("#branch_id").val();

        //     if (typeof region_id === 'undefined') {
        //         var region_id = $("#filter_region_id").val();
        //     }

        //     if (typeof branch_id === 'undefined') {
        //         var branch_id = $("#filter_branch_id").val();
        //     }




        //     var type = 'lead';

        //     $.ajax({
        //         type: 'GET',
        //         url: '{{ route('filterData') }}',
        //         data: {
        //            brand_id,
        //            region_id,
        //            branch_id,
        //            type
        //         },
        //         success: function(data) {
        //             data = JSON.parse(data);

        //             if (data.status === 'success') {
        //                 $('#filter-names').html('');
        //                 $("#filter-names").html(data.html);
        //                 select2();
        //             } else {
        //                 console.error('Server returned an error:', data.message);
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('AJAX request failed:', status, error);
        //         }
        //     });
        // }


    </script>
    <script>
    // JavaScript part

    $(document).ready(function() {
    $('.tag-badge').click(function() {
        var tagId = $(this).data('tag-id');
        var tagName = $(this).data('tag-name');
        var selectOptions = <?php echo json_encode($tags); ?>;

        // Check if selectOptions is an object
        if (typeof selectOptions === 'object' && selectOptions !== null) {
            // Generate options HTML by iterating over object keys
            var optionsHTML = '';
            for (var key in selectOptions) {
                if (selectOptions.hasOwnProperty(key) && key.trim() !== '') {
                    optionsHTML += `<option value="${key}" ${tagName === key ? 'selected' : ''}>${key}</option>`;
                }
            }
        });

    })

    ////////////////////Filters Javascript
    $("#filter-show #filter_brand_id, #bulk-assign #filter_brand_id").on("change", function() {
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


    $(document).on("change", "#filter-show #filter_region_id, #filter-show #region_id", function() {
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

    $(document).on("change", "#filter-show #filter_branch_id, #filter-show #branch_id", function() {

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


    $(document).on("change", "#bulk-assign #filter_brand_id" ,function() {
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


    $(document).ready(function() {
        $('.tag-badge').click(function() {
            var tagId = $(this).data('tag-id');
            var tagName = $(this).data('tag-name');
            var selectOptions = <?php echo json_encode($tags); ?>;

            // Check if selectOptions is an object
            if (typeof selectOptions === 'object' && selectOptions !== null) {
                // Generate options HTML by iterating over object keys
                var optionsHTML = '';
                for (var key in selectOptions) {
                    if (selectOptions.hasOwnProperty(key) && key.trim() !== '') {
                        optionsHTML += `<option value="${key}" ${tagName === key ? 'selected' : ''}>${key}</option>`;
                    }
                }

                // Append the options to the select element
                $('#sheraz').append(`
                    <input type="hidden" value="${tagId}" name="id" id="tagIdInput">
                    <div class="form-group">
                        <label for="">Tag</label>
                        <select class="form form-control select2 selectTage" name="tagid" id="tagSelectupdate" style="width: 95%;">
                            <option value="">Select Tag</option>
                            ${optionsHTML}
                            @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
                            <option value="other">Other</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group inputTageupdate" style="display: none">
                        <input type="hidden" value="${tagName}" name="tagName" id="tagIdInput">
                        <label for="">New Tag</label>
                        <input type="text" name="tags" id="tagNameInput" class="form form-control" style="width: 95%;">
                    </div>
                `);
                select2();
                $('#UpdateTageModal').modal('show');
                $('#tagSelectupdate').on('change', function() {
                    var inputTag = $('#tagNameInput');
                    if (this.value === 'other') {
                        inputTag.closest('.inputTageupdate').show();
                    } else {
                        inputTag.closest('.inputTageupdate').hide();
                    }
                });
            } else {
                console.error("Error: selectOptions is not an object.");
            }
        });
    });


    $(document).ready(function () {
        $('#UpdateTagForm').submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                url: '{{ url("leads/tag") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    data = JSON.parse(response);
                    show_toastr('success', data.msg);
                    $("#UpdateTageModal").hide();
                    window.location.href = '/leads/list';
                },

            });
        });
    });
    </script>
@endpush
