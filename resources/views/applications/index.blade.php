@extends('layouts.admin')
@php
// $profile=asset(Storage::url('uploads/avatar/'));
$profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
{{__('Manage Applications')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Applications')}}</li>
@endsection
@section('content')
<div class="row">
    <div class="card py-3 my-card">
        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
            <div class="col-2">
                <p class="mb-0 pb-0">APPLICATIONS</p>
                <div class="dropdown">
                    <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        ALL APPLICATIONS
                    </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item delete-bulk-applciations" href="javascript:void(0)">Delete</a></li>
                        </ul>
                </div>
            </div>

            <div class="col-10 d-flex justify-content-end gap-2">
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
                    <div class="col-md-4"> <label for="">Created By</label>
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
                        <th>
                            {{ __('Name') }}
                        </th>


                        <th>
                            {{ __('Application Key') }}
                        </th>

                        <th>
                            {{ __('University') }}
                        </th>

                        <th>
                            {{ __('Intake') }}
                        </th>

                        <th>
                            {{ __('Status') }}
                        </th>

                        <th>
                            {{ __('Action') }}
                        </th>

                    </tr>
                </thead>
                <tbody class="application_tbody">

                    @forelse($applications as $app)
                    <tr>
                        <td>
                            <input type="checkbox" name="applications[]" value="{{$app->id}}" class="sub-check">
                        </td>
                        <td>
                            <span style="cursor:pointer" class="hyper-link" @can('view application') onclick="openSidebar('deals/'+{{ $app->id }}+'/detail-application')" @endcan>
                                {{ $shortened_name = substr($app->name, 0, 10) }}
                                {{ strlen($app->name) > 10 ? $shortened_name . '...' : $app->name }}
                            </span>
                        </td>
                        <td>
                            {{ $shortened_name = substr($app->application_key, 0, 10) }}
                            {{ strlen($app->application_key) > 10 ? $shortened_name . '...' : $app->application_key}}
                        </td>
                        <td>{{ isset($app->university_id) && isset($universities[$app->university_id]) ? $universities[$app->university_id] : '' }}</td>

                        <td>
                            {{ $app->intake }}
                        </td>
                        <td>
                            {{ isset($app->stage_id) && isset($stages[$app->stage_id]) ? $stages[$app->stage_id] : '' }}
                        </td>
                        <td>


                            @can('edit application')
                            <div class="action-btn ms-2">

                                <a data-size="lg" title="{{ __('Edit Application') }}" href="#" class="btn px-2 btn-dark mx-1" data-url="{{ route('deals.application.edit', $app->id) }}" data-ajax-popup="true" data-title="{{ __('Edit Application') }}" data-toggle="tooltip" data-original-title="{{ __('Edit') }}">
                                    <i class="ti ti-edit"></i>
                                </a>

                            </div>
                            @endcan

                            @can('delete application')
                            <div class="action-btn ms-2">
                                {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['deals.application.destroy', $app->id],
                                'id' => 'delete-form-' . $app->id,
                                ]) !!}
                                <a href="#" class="mx-3 btn btn-sm bg-danger  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i class="ti ti-trash text-white"></i></a>

                                {!! Form::close() !!}
                            </div>
                            @endcan




                        </td>
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
