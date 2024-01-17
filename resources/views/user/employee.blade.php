@extends('layouts.admin')
@php
$profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp

@section('page-title')
{{ __('Manage Employees') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Employees') }}</li>
@endsection

<style>
    .full-card {
        min-height: 165px !important;
    }

    table {
        font-size: 14px !important;
    }
</style>

@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<div class="row">
    <div class="col-xxl-12">
        <div class="row w-100 m-0">
            <div class="card my-card">
                <div class="card-body">
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-2">
                            <p class="mb-0 pb-0">Employees</p>

                            <div class="dropdown">

                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL Employees
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-10 d-flex justify-content-end gap-2">
                            <div class="input-group w-25">
                                <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                                <input type="Search" class="form-control border-0 bg-transparent ps-0" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>

                            <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>

                            @can('create employee')
                            <a href="#" data-size="lg" data-url="{{ route('user.employee.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create Employee') }}" class="btn btn-dark py-2 px-2">
                                <i class="ti ti-plus"></i>
                            </a>
                            @endcan
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $("#dropdownMenuButton3").click(function() {
                                $("#filterToggle").toggle();
                            });
                        });
                    </script>
                        {{-- Filters --}}
                        <div class="filter-data px-3" id="filterToggle" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                            <form action="/user/employees" method="GET" class="">
                                <div class="row my-3">


                                    <div class="col-md-4 mt-2">
                                        <label for="">Brand</label>
                                        <select name="brand" class="form form-control select2" id="brand_id" style="width: 95%; border-color:#aaa">
                                            <option value="">Select Brand</option>
                                            @if (!empty($brandss))
                                            @foreach ($brandss as $key=>$brand)
                                            <option value="{{ $key }}" <?= isset($key) && isset($_GET['brand']) && $_GET['brand'] == $key ? "selected" : '' ?>> {{ $brand }}</option>
                                            @endforeach
                                            @endif

                                        </select>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label for="">Region</label>
                                        <select name="Region" class="form form-control select2" id="region_id" style="width: 95%; border-color:#aaa">
                                            <option value="">Select Region</option>

                                            @if (!empty($Regions))
                                            @foreach ($Regions as $key=> $Region)
                                            <option value="{{ $key }}" <?= isset($_GET['Region']) && isset($key) && $_GET['Region'] == $key ? "selected" : '' ?>> {{ $Region }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label for="">Branch</label>
                                        <select name="Branch" class="form form-control select2" id="branch_id" style="width: 95%; border-color:#aaa">
                                            <option value="">Select Branch</option>

                                            @if (!empty($Branchs))
                                            @foreach ($Branchs as $key=> $Branch)
                                            <option value="{{ $key }}" <?= isset($_GET['Branch']) && isset($key) && $_GET['Branch'] == $key ? "selected" : '' ?>> {{ $Branch }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label for="">Name</label>
                                        <input type="text" class="form form-control" placeholder="Search Name" name="Name" value="<?= isset($_GET['Name']) ? $_GET['Name'] : '' ?>" style="width: 95%; border-color:#aaa">
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label for="">Designation</label>
                                        <select name="Designation" class="form form-control select2" id="designation_id" style="width: 95%; border-color:#aaa">
                                            <option value="">Select Designation</option>
                                            @if (!empty($Designations))
                                            @foreach ($Designations as $Designation)
                                            <option <?= isset($_GET['Designation']) && isset($Designation) && $_GET['Designation'] == $Designation ? "selected" : '' ?> value="{{ $Designation }}">{{ $Designation }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label for="">Phone</label>
                                        <input type="text" class="form form-control" placeholder="Search Phone" name="phone" value="<?= isset($_GET['phone']) ? $_GET['phone'] : '' ?>" style="width: 95%; border-color:#aaa">
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <br>
                                        <input type="submit" class="btn me-2 bg-dark" style=" color:white;">
                                        <a href="/user/employees" class="btn bg-dark" style="color:white;">Reset</a>
                                    </div>
                                </div>
                                <div class="row">
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

                        <div class="table-responsive mt-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Designation</th>
                                        <th>Phone</th>
                                        <th>Region</th>
                                        <th>Last Login</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                         if(isset($_GET['page']) && !empty($_GET['page'])){
                                             $count = (($_GET['page'] - 1) * $_GET['num_results_on_page']) + 1;
                                         }else{
                                             $count = 1;
                                         }
                                        
                                        ?>
                                    @forelse($users as $key => $employee)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>

                                            <span style="cursor:pointer" class="hyper-link" @can('view employee') onclick="openSidebar('/user/employee/{{ $employee->id }}/show')" @endcan>
                                                {{ $employee->name }}
                                            </span>
                                        </td>
                                        <td><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
                                        <td>{{ $employee->type }}</td>
                                        <td>{{ $employee->phone }}</td>
                                        <td>{{ $Regions[$employee->region_id] ?? '' }}</td>
                                        <td>{{ !empty($employee->last_login_at) ? $employee->last_login_at : '' }}
                                        </td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">No employees found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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


<script>
    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
    function openNav(id) {
        var ww = $(window).width()


        if (ww < 500) {
            $("#mySidenav").css('width', ww + 'px');
            $("#main").css('margin-right', ww + 'px');
        } else {
            $("#mySidenav").css('width', '500px');;
            $("#main").css('margin-right', "500px");
        }

        $("#modal-discussion-add").attr('data-id', id);
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'block');
        $("#body").css('overflow', 'hidden');

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/leads/getDiscussions",
            data: {
                lead_id,
                _token: csrf_token,
            },
            type: "POST",
            cache: false,
            success: function(data) {
                data = JSON.parse(data);
                //console.log(data);

                if (data.status) {
                    $(".discussion-list-group").html(data.content);
                    $(".lead_id").val(lead_id);
                }
            }
        });

    }

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
    function closeNav() {
        $("#mySidenav").css("width", '0');
        $("#main").css("margin-right", '0');
        $("#modal-discussion-add").removeAttr('data-deal-id');
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'none');
        $("#body").css('overflow', 'visible');
    }
</script>
