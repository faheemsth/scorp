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
    <div class="card py-3 my-card">
        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
            <div class="col-4">
                <p class="mb-0 pb-0 ps-1">APPLICATIONS</p>
                <div class="dropdown">
                    <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        ALL APPLICATIONS
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

            <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                    <button class="btn btn-sm list-global-search-btn p-0 pb-2">
                        <span class="input-group-text bg-transparent border-0  px-1 " id="basic-addon1">
                            <i class="ti ti-search" style="font-size: 18px"></i>
                        </span>
                    </button>
                    <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2  list-global-search" placeholder="Search this list...">
                </div>


                <!-- <a href="{{ url('application') }}" class="btn filter-btn-show p-2 btn-dark" type="button">
                    <i class="ti ti-file" style="font-size:18px,color:white"></i>
                </a> -->

                <div>
                    <button class="btn px-2 pb-2 pt-2 refresh-list btn-dark d-none"><i class="ti ti-refresh" style="font-size: 18px"></i></button>
                </div>

                <!-- <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-filter" style="font-size:18px"></i>
                </button> -->

                <a href="javascript:void(0)" class="btn  btn-dark filter-btn-show px-0" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="" data-original-title="Filter" class="btn  btn-dark px-0">
                <i class="ti ti-filter"></i>
                    </a>


                    @if(auth()->user()->type == 'super admin' || auth()->user()->type == 'Admin Team')
                        <a href="{{ route('regions.download') }}" class="btn  btn-dark px-0" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="" data-original-title="Download in Csv" class="btn  btn-dark px-0">
                        <i class="ti ti-download" style="font-size:18px"></i>
                    </a>
                    @endif

                    @if(auth()->user()->type == 'super admin' || auth()->user()->can('delete application'))
                    <a href="javascript:void(0)" id="actions_div" data-bs-toggle="tooltip" title="{{ __('Bulk Delete') }}" class="btn delete-bulk text-white btn-dark d-none px-0" style="width:36px; height: 36px; margin-top:10px;">
                        <i class="ti ti-trash"></i>
                    </a>
                    @endif

                <!-- <a class="btn p-2 btn-dark  text-white assigned_to" id="actions_div" style="display:none;font-weight: 500;" onClick="massUpdate()">Mass Update</a> -->

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
                            @if (FiltersBrands())
                                @foreach (FiltersBrands() as $key => $brand)
                                   <option value="{{ $key }}" <?= isset($_GET['created_by']) && in_array($key, $_GET['created_by']) ? 'selected' : '' ?> class="">{{ $brand }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endif




                    <div class="col-md-4 mt-2">
                        <br>
                        <input type="submit" class="btn form-btn me-2 btn-dark">
                        <a href="/applications/" class="btn form-btn btn-dark">Reset</a>
                        <a type="button" id="save-filter-btn" onClick="saveFilter('applications',<?= sizeof($applications) ?>)" class="btn form-btn me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
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
                        <th scope="col">{{ __('Course') }}</th>
                        <th scope="col">{{ __('University') }}</th>
                        <th scope="col">{{ __('Stage') }}</th>
                        <th scope="col">{{ __('Assigned To') }}</th>

                        <th scope="col" class="d-none">{{ __('Intake') }}</th>
                        <th scope="col" class="d-none">{{ __('Brand') }}</th>
                        <th scope="col" class="d-none">{{ __('Branch') }}</th>


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
                                <span style="cursor:pointer" class="hyper-link" @can('view application') onclick="openSidebar('/deals/'+{{ $app->id }}+'/detail-application')" @endcan>
                                {{ strlen($app->name) > 20 ? substr($app->name, 0, 20) . '...' : $app->name }}
                            </span>
                                </td>
                                <td>{{ $app['course'] }}</td>
                                <td>{{ $universities[$app->university_id]  ?? '' }}</td>
                                <td>{{ isset($app->stage_id) && isset($stages[$app->stage_id]) ? $stages[$app->stage_id] : '' }}</td>
                                <td> {{ !empty($deal->assigned_to) ? (isset($users[$deal->assigned_to]) ? $users[$deal->assigned_to] : '') : '' }} </td>


                                <td class="d-none"> {{ $app->intake }} </td>
                                <td class="d-none"> {{ isset($users[$deal->brand_id]) ? $users[$deal->brand_id] : '' }}  </td>
                                <td class="d-none"> {{ isset($branch->name) ? $branch->name : ''  }} </td>


                            </tr>
                        @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination_div">
        @if ($total_records > 0)
        @include('layouts.pagination', [
        'total_pages' => $total_records,
        'num_results_on_page' => 50,
        ])
        @endif
        </div>
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
    $(document).ready(function() {
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
                window.location.href = '/delete-bulk-applications?ids=' + selectedIds.join(',');
            }
        });
    })

    // $(document).on('change', '.main-check', function() {
    //     $(".sub-check").prop('checked', $(this).prop('checked'));
    // });

    // $(document).on('change', '.sub-check', function() {
    //         var selectedIds = $('.sub-check:checked').map(function() {
    //             return this.value;
    //         }).get();

    //         console.log(selectedIds.length)

    //         if (selectedIds.length > 0) {
    //             selectedArr = selectedIds;
    //             $("#actions_div").css('display', 'block');
    //         } else {
    //             selectedArr = selectedIds;

    //             $("#actions_div").css('display', 'none');
    //         }
    //         let commaSeperated = selectedArr.join(",");
    //         console.log(commaSeperated)
    //         $("#app_ids").val(commaSeperated);

    //     });

    //     function massUpdate() {
    //         if (selectedArr.length > 0) {
    //             $('#mass-update-modal').modal('show')
    //         } else {
    //             alert('Please choose Tasks!')
    //         }
    //     }

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
