@extends('layouts.admin')
@php
// $profile=asset(Storage::url('uploads/avatar/'));
$profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
{{__('Manage Applications')}}
@endsection
@push('script-page')
<script>
       $(document).on('change', '.sub-check', function() {
        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        console.log(selectedIds.length)

        if(selectedIds.length > 0){
            selectedArr = selectedIds;
            $("#actions_div").css('display', 'block');
        }else{
            selectedArr = selectedIds;

            $("#actions_div").css('display', 'none');
        }
        let commaSeperated = selectedArr.join(",");
        console.log(commaSeperated)
        $("#lead_ids").val(commaSeperated);

    });
</script>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('crm.dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Applications')}}</li>
@endsection
@section('content')
<div class="row">
    <div class="card py-3 my-card">
        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
            <div class="col-2">
                <p class="mb-0 pb-0 ps-1">APPLICATIONS</p>
                <div class="dropdown">
                    <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        ALL APPLICATIONS
                    </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item delete-bulk-applciations" href="javascript:void(0)">Delete</a></li>
                            <li id="actions_div" style="display:none;font-size:14px;color:#3a3b45;"><a class="dropdown-item assigned_to" onClick="massUpdate()">Mass Update</a></li>

                        </ul>
                </div>
            </div>
            <div class="col-2">
                <!-- <p class="mb-0 pb-0">Tasks</p> -->
                <div class="dropdown" id="actions_div" style="display:none">
                    <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item assigned_to" onClick="massUpdate()">Mass Update</a></li>
                        <!-- <li><a class="dropdown-item update-status-modal" href="javascript:void(0)">Update Status</a></li>
                            <li><a class="dropdown-item" href="#">Brand Change</a></li>
                            <li><a class="dropdown-item delete-bulk-tasks" href="javascript:void(0)">Delete</a></li> -->
                    </ul>
                </div>
            </div>

            <div class="col-8 d-flex justify-content-end gap-2">
                <div class="input-group w-25">
                    <button class="btn btn-sm list-global-search-btn">
                        <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                            <i class="ti ti-search" style="font-size: 18px"></i>
                        </span>
                    </button>
                    <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                </div>

                <div>
                    <button class="btn px-2 pb-2 pt-2 refresh-list btn-dark"><i class="ti ti-refresh" style="font-size: 18px"></i></button>
                </div>

                <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-filter" style="font-size:18px"></i>
                </button>
            </div>
        </div>


        <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
            <form action="/applications/" method="GET" class="">
                <div class="row my-3">
                    <div class="col-md-4"> <label for="">Name</label>
                        <select class="form form-control select2" id="choices-multiple110" name="applications[]" multiple style="width: 95%;">
                            <option value="">Select Application</option>
                            @foreach ($app_for_filer as $app)
                            <option value="{{ $app->name }}" <?= isset($_GET['applications']) && in_array($app->name, $_GET['applications']) ? 'selected' : '' ?> class="">{{ $app->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4"> <label for="">University</label>
                        <select class="form form-control select2" id="choices-multiple111" name="universities[]" multiple style="width: 95%;">
                            <option value="">Select University</option>
                            @foreach ($universities as $key => $name)
                            <option value="{{ $key }}" <?= isset($_GET['universities']) && in_array($key, $_GET['universities']) ? 'selected' : '' ?> class="">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label for="">Stages</label>
                        <select name="stages[]" id="stages" class="form form-control select2" multiple style="width: 95%;">
                            <option value="">Select Stage</option>
                            @foreach ($stages as $key => $stage)
                            <option value="{{ $key }}" <?= isset($_GET['stages']) && in_array($key, $_GET['stages']) ? 'selected' : '' ?> class="">{{ $stage }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
                    <div class="col-md-4"> <label for="">Brands</label>
                        <select class="form form-control select2" id="choices-multiple555" name="created_by[]" multiple style="width: 95%;">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" <?= isset($_GET['created_by']) && in_array($brand->id, $_GET['created_by']) ? 'selected' : '' ?> class="">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif




                    <div class="col-md-4 mt-2">
                        <br>
                        <input type="submit" class="btn form-btn me-2 btn-dark">
                        <a href="/applications/" class="btn form-btn btn-danger">Reset</a>
                    </div>
                </div>
                <div class="row my-4">
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

        <style>
            table{
                font-size: 14px !important;
            }
        </style>


        <div class="table-responsive mt-3" style="width: 100%;">
            <table class="table">
                <thead style="background-color: rgb(255, 255, 255);">
                    <tr>
                        <th style="width: 50px !important;">
                            <input type="checkbox" class="main-check">
                        </th>
                        <th scope="col">{{ __('Student Name') }}</th>
                        <th scope="col">{{ __('University') }}</th>
                        <th scope="col">{{ __('Course') }}</th>
                        <th scope="col">{{ __('Intake') }}</th>
                        <th scope="col">{{ __('Brand') }}</th>
                        <th scope="col">{{ __('Branch') }}</th>
                        <th scope="col">{{ __('Assigned To') }}</th>
                        <th scope="col">{{ __('Status') }}</th>

                    </tr>
                </thead>
                <tbody class="application_tbody">
                      @forelse($applications as $app)
                            @php
                                $university = \App\Models\University::where('id', $app->university_id)->first();
                                $deal = \App\Models\Deal::where('id', $app->deal_id)->first();
                                $users = \App\Models\User::pluck('name', 'id')->toArray();
                                $branch = \App\Models\Branch::where('id', $deal->branch_id)->first();
                            @endphp
                            <tr>
                                 <td>
                                    <input type="checkbox" name="applications[]" value="{{$app->id}}" class="sub-check">
                                </td>
                                <td>
                                <span style="cursor:pointer" class="hyper-link" @can('view application') onclick="openSidebar('deals/'+{{ $app->id }}+'/detail-application')" @endcan>
                                {{ strlen($app->name) > 10 ? substr($app->name, 0, 10) . '...' : $app->name }}
                            </span>
                                </td>
                                <td>{{ $universities[$app->university_id] }}</td>
                                <td>{{ $app['course'] }}</td>
                                <td> {{ $app->intake }} </td>
                                <td> {{ isset($users[$deal->brand_id]) ? $users[$deal->brand_id] : '' }}  </td>
                                <td> {{ isset($branch->name) ? $branch->name : ''  }} </td>
                                <td> {{ !empty($deal->assigned_to) ? (isset($users[$deal->assigned_to]) ? $users[$deal->assigned_to] : '') : '' }} </td>
                                <td>{{ isset($app->stage_id) && isset($stages[$app->stage_id]) ? $stages[$app->stage_id] : '' }}</td>
                            </tr>
                        @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($total_records > 0)
        @include('layouts.pagination', [
        'total_pages' => $total_records,
        'num_results_on_page' => 50,
        ])
        @endif
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
                <form action="{{ route('update-bulk-applications') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="bulk_field" id="bulk_field" class="form form-control">
                                    <option value="">Select Field</option>
                                    <option value="university">University</option>
                                    <option value="course">Course</option>
                                    <option value="application_key">Application ID</option>
                                    <option value="intake">Intake</option>
                                    <option value="status">Status</option>
                                </select>
                            </div>
                            <input name='app_ids' id="app_ids" hidden>
                            <div class="col-md-6" id="field_to_update">

                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Update">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script-page')
<script>
    $('.filter-btn-show').click(function() {
        $("#filter-show").toggle();
    })

    $(document).on('change', '.main-check', function() {
        $(".sub-check").prop('checked', $(this).prop('checked'));
    });

    $(document).on('change', '.sub-check', function() {
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            console.log(selectedIds.length)

            if (selectedIds.length > 0) {
                selectedArr = selectedIds;
                $("#actions_div").css('display', 'block');
            } else {
                selectedArr = selectedIds;

                $("#actions_div").css('display', 'none');
            }
            let commaSeperated = selectedArr.join(",");
            console.log(commaSeperated)
            $("#app_ids").val(commaSeperated);

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

                if (this.value == 'university') {


                    var universities = <?= json_encode($universities) ?>;
                    let options = '';

                    $.each(universities, function(keyName, keyValue) {
                        options += '<option value="' + keyName + '">' + keyValue + '</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="university" required>
                                    <option value="">Select University</option>
                                    ` + options + `
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();

                } else if (this.value == 'course') {

                    let field = `<div>
                                    <input type="text" class="form-control" placeholder="Course" name="course" value="" required="">
                               </div>`;
                    $('#field_to_update').html(field);

                }else if (this.value == 'application_key') {

                    let field = `<div>
                                    <input type="text" class="form-control" placeholder="" name="application_key" value="" required="">
                            </div>`;
                    $('#field_to_update').html(field);

                }else if (this.value == 'intake') {

                    let field = `<div>
                                <input class="form-control" required="required" style="height: 45px;" name="intake" type="month"  id="intake">
                            </div>`;
                    $('#field_to_update').html(field);

                }else if (this.value == 'status') {

                    var stages = <?= json_encode($stages) ?>;
                    let options = '';

                    $.each(stages, function(keyName, keyValue) {
                        options += '<option value="' + keyName + '">' + keyValue + '</option>';
                    });

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="status" required>
                                    <option value="">Select Status</option>
                                    ` + options + `
                                </select>`;
                    $('#field_to_update').html(field);
                    select2();
                }

            }

        });

    $(document).on("click", ".list-global-search-btn", function() {
        var search = $(".list-global-search").val();
        var ajaxCall = 'true';
        $(".application_tbody").html('Loading...');

        $.ajax({
            type: 'GET',
            url: "{{ route('applications.index') }}",
            data: {
                search: search,
                ajaxCall: ajaxCall
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    console.log(data.html);
                    $(".application_tbody").html(data.html);
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
                $(".application_tbody").html('Loading...');

                $.ajax({
                    type: 'GET',
                    url: "{{ route('applications.index') }}",
                    data: {
                        search: search,
                        ajaxCall: ajaxCall
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status == 'success') {
                            console.log(data.html);
                            $(".application_tbody").html(data.html);
                        }
                    }
                })
            }
        });
    });


    $(".refresh-list").on("click", function() {
        var ajaxCall = 'true';
        $(".application_tbody").html('Loading...');

        $.ajax({
            type: 'GET',
            url: "{{ route('applications.index') }}",
            data: {
                ajaxCall: ajaxCall
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    $(".application_tbody").html(data.html);
                }
            }
        });
    })

    $(document).on('click', '.application_stage', function() {

        var application_id = $(this).attr('data-application-id');
        var stage_id = $(this).attr('data-stage-id');
        var currentBtn = $(this);


        $.ajax({
            type: 'GET',
            url: "{{ route('update-application-stage') }}",
            data: {
                application_id: application_id,
                stage_id: stage_id
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                  //  openNav(application_id);
                    openSidebar('deals/'+application_id+'/detail-application')
                    return false;
                    // $('.lead_stage').removeClass('current');
                    // currentBtn.addClass('current');
                    // window.location.href = '/leads/list';
                } else {
                    show_toastr('Error', data.message, 'error');
                }
            }
        });
    });
    $(document).on("click", '.delete-bulk-applciations', function() {
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
                window.location.href = '/delete-bulk-applications?ids='+selectedIds.join(',');
            }
        });
    })
</script>
@endpush
