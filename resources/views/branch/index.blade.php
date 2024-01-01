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
    table{
        font-size: 14px !important;
    }
</style>
    <div class="row">


        <div>


            <div class="card">

                <div class="row align-items-center mx-2 my-4">
                    <div class="col-2">
                        <p class="mb-0 pb-0 ps-1">Branches</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL Branch
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item delete-bulk-tasks" href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-10 d-flex justify-content-end gap-2">
                        <div class="input-group w-25">
                            <button class="btn btn-sm list-global-search-btn">
                                <span class="input-group-text bg-transparent border-0 px-2 py-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <button class="btn px-2 pb-2 pt-2 refresh-list btn-dark"><i class="ti ti-refresh" style="font-size: 18px"></i></button>

                        <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>

                        @can('create branch')
                        <!-- Modified the opening tag to match the closing tag -->
                        <a href="#" data-size="lg" data-url="{{ route('branch.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Branch')}}" class="btn px-2 btn-dark">
                            <i class="ti ti-plus"></i>
                        </a>
                        @endcan
                        <!-- Added the missing closing div tag -->
                    </div>
                </div>


                {{-- <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                    <form action="/deals/get-user-tasks" method="GET" class="">

                        <div class="row my-3">
                            <div class="col-md-4 mt-2">
                                <label for="">Due Date</label>
                                <input type="date" class="form form-control" name="due_date" value="<?= isset($_GET['due_date']) ? $_GET['due_date'] : '' ?>" style="width: 95%; border-color:#aaa">
                            </div>


                            <div class="col-md-4"> <label for="">Subject</label>
                                <select class="form form-control select2" id="choices-multiple110" name="subjects[]" multiple style="width: 95%;">
                                    <option value="">Select Subject</option>
                                    @foreach ($tasks_for_filter as $filter_task)
                                    <option value="{{ $filter_task->name }}" <?= isset($_GET['subjects']) && in_array($filter_task->name, $_GET['subjects']) ? 'selected' : '' ?> class="">{{ $filter_task->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-4"> <label for="">Assigned To</label>
                                <select name="assigned_to[]" id="choices-multiple333" class="form form-control select2" multiple style="width: 95%;">
                                    <option value="">Select user</option>
                                    @foreach ($users as $key => $user)
                                    <option value="{{ $key }}" <?= isset($_GET['assigned_to']) && in_array($key, $_GET['assigned_to']) ? 'selected' : '' ?> class="">{{ $user }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-4"> <label for="">Company/Brand</label>
                                <select class="form form-control select2" id="choices-multiple444" name="brands[]" multiple style="width: 95%;">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $key => $brand)

                                            @if ($key == optional($currentUserCompany)->id)
                                            <option value="{{ $key }}" class="" <?= isset($_GET['brands']) && in_array($key, $_GET['brands']) ? 'selected' : '' ?>>{{ $brand }}</option>
                                            @endif
                                                @foreach ($com_permissions as $permissions)
                                                        @if ($permissions->permitted_company_id == $key)
                                                        <option value="{{ $permissions->permitted_company_id }}" class="" <?= isset($_GET['brands']) && in_array($permissions->permitted_company_id, $_GET['brands']) ? 'selected' : '' ?>>{{ $brand }}</option>
                                                        @endif
                                                @endforeach

                                            @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="">Status</label>
                                <select class="form form-control select2" id="status444" name="status" multiple style="width: 95%;">
                                    <option value="">Select Brand</option>
                                    <option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>Completed</option>
                                    <option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>>On Going</option>
                                </select>
                            </div>

                            <div class="col-md-4 mt-4 pt-2">
                                <input type="submit" class="btn form-btn me-2 btn-dark px-2 py-2" >
                                <a href="/deals/get-user-tasks" class="btn form-btn px-2 py-2" style="background-color: #b5282f;color:white;">Reset</a>
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
                </div> --}}

                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Region') }}</th>
                                    <th>{{ __('Branch Manager') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Google Link') }}</th>
                                    <th>{{ __('Social Media') }}</th>
                                </tr>
                            </thead>
                            <tbody class="font-style">
                                @foreach ($branches as $branch)
                                    <tr>
                                        <td>
                                            <span style="cursor:pointer" class="hyper-link"
                                                    onclick="openSidebar('/branch/{{ $branch->id }}/show')">
                                                    {{ $branch->name }}
                                            </span>
                                        </td>
                                        <td>{{ !empty($regions[$branch->region_id]) ? $regions[$branch->region_id] : '' }}</td>
                                        <td>{{ !empty($branch->branch_manager_id) ? $users[$branch->branch_manager_id] : '' }}</td>
                                        <td>{{ $branch->phone }}</td>
                                        <td>{{ $branch->email }}</td>
                                        <td>{{ $branch->google_link }}</td>
                                        <td>{{ $branch->social_media_link }}</td>
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
