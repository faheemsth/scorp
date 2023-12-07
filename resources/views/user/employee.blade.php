@extends('layouts.admin')
@php
$profile=\App\Models\Utility::get_file('uploads/avatar');
@endphp

@section('page-title')
{{__('Manage Employees')}}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Employees')}}</li>
@endsection

<style>
    .full-card {
        min-height: 165px !important;
    }
</style>

@section('content')


<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
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
                <div>
                    <button class="btn btn-primary px-2 pb-2 pt-2"><i class="ti ti-refresh" style="font-size: 18px"></i></button>
                </div>

                <button class="btn btn-primary  p-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-filter" style="font-size:18px"></i>
                </button>


                <a href="#" data-size="lg" data-url="{{ route('user.employee.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Employee')}}" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus"></i>
                </a>
            </div>
        </div>


        <div class="row mt-5">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Designation</th>
                                <th>Phone</th>
                                <th>Last Login</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $key => $employee)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>

                                <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/user/employee/{{$employee->id}}/show')" >
                                        {{ $employee->name }}
                                    </span>
                                </td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->type }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ (!empty($employee->last_login_at)) ? $employee->last_login_at : '' }}</td>
                                <td>
                                    @if(Gate::check('edit user') || Gate::check('delete user'))
                                    <div class="card-header-right" style="top: 0px;right:2px;">
                                        <div class="btn-group card-option">
                                            @if($employee->is_active==1)
                                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <!-- <i class="ti ti-dots-vertical"></i> -->
                                                Dropdown
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-end">

                                                @can('edit user')
                                                <a href="#!" data-size="lg" data-url="{{ route('user.employee.edit', $employee->id) }}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Edit User')}}">
                                                    <i class="ti ti-pencil"></i>
                                                    <span>{{__('Edit')}}</span>
                                                </a>
                                                @endcan

                                                @can('delete user')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $employee['id']],'id'=>'delete-form-'.$employee['id']]) !!}
                                                <a href="#!" class="dropdown-item bs-pass-para">
                                                    <i class="ti ti-archive"></i>
                                                    <span> @if($employee->delete_status!=0){{__('Delete')}} @else {{__('Restore')}}@endif</span>
                                                </a>

                                                {!! Form::close() !!}
                                                @endcan

                                                <a href="#!" data-url="{{route('users.reset',\Crypt::encrypt($employee->id))}}" data-ajax-popup="true" data-size="md" class="dropdown-item" data-bs-original-title="{{__('Reset Password')}}">
                                                    <i class="ti ti-adjustments"></i>
                                                    <span> {{__('Reset Password')}}</span>
                                                </a>
                                            </div>
                                            @else
                                            <a href="#" class="action-item"><i class="ti ti-lock"></i></a>
                                            @endif

                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <!-- Add more cells as needed with corresponding data -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No employees found</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
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
