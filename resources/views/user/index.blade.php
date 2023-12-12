@extends('layouts.admin')
@php
    // $profile=asset(Storage::url('uploads/avatar/'));
    $profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp
@section('page-title')
    {{ __('Manage User') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('User') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
            title="{{ __('Create') }}" class="btn btn-primary">
            <i class="ti ti-plus py-5"></i>
        </a>
    </div>
@endsection

<style>
    .full-card {
        min-height: 165px !important;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row w-100 m-0">
                {{-- @forelse($users as $user)
                    <div style="width: 14%" class="ps-0 pe-1">
                        <div class="card text-center">
                            <div class="card-header border-0 pb-0 pt-2 px-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <div class="badge bg-primary p-2 rounded" style="font-size: 10px;">
                                            {{ ucfirst($user->type) }}
                                        </div>
                                    </h6>
















                                    @if (Gate::check('edit user') || Gate::check('delete user'))
                                        <div class="card-header-right" style="top: 0px;right:2px;">
                                            <div class="btn-group card-option">
                                                @if ($user->is_active == 1)
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end">

                                                        @can('edit user')
                                                            <a href="#!" data-size="lg"
                                                                data-url="{{ route('users.edit', $user->id) }}"
                                                                data-ajax-popup="true" class="dropdown-item"
                                                                data-bs-original-title="{{ __('Edit User') }}">
                                                                <i class="ti ti-pencil"></i>
                                                                <span>{{ __('Edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('delete user')
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['users.destroy', $user['id']],
                                                                'id' => 'delete-form-' . $user['id'],
                                                            ]) !!}
                                                            <a href="#!" class="dropdown-item bs-pass-para">
                                                                <i class="ti ti-archive"></i>
                                                                <span>
                                                                    @if ($user->delete_status != 0)
                                                                        {{ __('Delete') }}
                                                                    @else
                                                                        {{ __('Restore') }}
                                                                    @endif
                                                                </span>
                                                            </a>

                                                            {!! Form::close() !!}
                                                        @endcan

                                                        <a href="#!"
                                                            data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                                            data-ajax-popup="true" data-size="md" class="dropdown-item"
                                                            data-bs-original-title="{{ __('Reset Password') }}">
                                                            <i class="ti ti-adjustments"></i>
                                                            <span> {{ __('Reset Password') }}</span>
                                                        </a>
                                                    </div>
                                                @else
                                                    <a href="#" class="action-item"><i class="ti ti-lock"></i></a>
                                                @endif

                                            </div>
                                        </div>
                                    @endif























                                </div>



                            </div>
                            <div class="card-body full-card px-1 pb-0" style="padding-top:5px">
                                <div class="img-fluid rounded-circle card-avatar">
                                    <img style="width: 60px;height: 60px;"
                                        src="{{ !empty($user->avatar) ? asset(Storage::url('uploads/avatar/' . $user->avatar)) : asset(Storage::url('uploads/avatar/avatar.png')) }}"
                                        class="img-user wid-80 round-img rounded-circle">
                                </div>
                                <h4 class=" mt-3 text-primary" style="font-size: 13px;">
                                    <span style="cursor:pointer" class="hyper-link"
                                        onclick="openSidebar('/users/'+{{ $user->id }}+'/user_detail')">
                                        {{ $user->name }}
                                    </span>
                                </h4>


                                @if ($user->delete_status == 0)
                                    <h5 class="office-time mb-0">{{ __('Soft Deleted') }}</h5>
                                @endif
                                <small class="text-primary" style="font-size: 10px;">{{ $user->email }}</small>
                                <div class="text-center" style="font-size: 10px;" data-bs-toggle="tooltip"
                                    title="{{ __('Last Login') }}">
                                    {{ !empty($user->last_login_at) ? $user->last_login_at : '' }}
                                </div>
                                @if (\Auth::user()->type == 'super admin')
                                    <div class="mt-4 d-none">
                                        <div class="row justify-content-between align-items-center">
                                            <div class="col-6 text-center">
                                                <span
                                                    class="d-block font-bold mb-0">{{ !empty($user->currentPlan) ? $user->currentPlan->name : '' }}</span>
                                            </div>
                                            <div class="col-6 text-center Id ">
                                                <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}"
                                                    data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary"
                                                    data-title="{{ __('Upgrade Plan') }}">{{ __('Upgrade Plan') }}</a>
                                            </div>
                                            <div class="col-12">
                                                <hr class="my-3">
                                            </div>
                                            <div class="col-12 text-center pb-2">
                                                <span class="text-dark text-xs">{{ __('Plan Expired : ') }}
                                                    {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : __('Unlimited') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 d-none">
                                        <div class="col-12 col-sm-12">
                                            <div class="card mb-0">
                                                <div class="card-body p-3">
                                                    <div class="row">

                                                        <div class="col-4">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Users') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyUser($user->id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Customers') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyCustomer($user->id) }}
                                                            </p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip"
                                                                title="{{ __('Vendors') }}"><i
                                                                    class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyVender($user->id) }}
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif



                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                {{ __('No User Found!!!') }}
                            </div>
                        </div>
                    </div>
                @endforelse --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
                                            @forelse($users as $key => $user)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>

                                                        <span style="cursor:pointer" class="hyper-link"
                                                            onclick="openSidebar('/user/employee/{{ $user->id }}/show')">
                                                            {{ $user->name }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->type }}</td>
                                                    <td>{{ $user->phone }}</td>
                                                    <td>{{ !empty($user->last_login_at) ? $user->last_login_at : '' }}
                                                    </td>
                                                    <td>
                                                        @if (Gate::check('edit user') || Gate::check('delete user'))
                                                        <div class="card-header-right" style="top: 0px; right:2px;">
                                                            <div class="btn-group card-option">
                                                                @if ($user->is_active == 1)
                                                                    <button type="button" class="btn"
                                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                        <i class="ti ti-dots-vertical"></i>
                                                                    </button>

                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        @if (Gate::check('edit user'))
                                                                            <a href="#!" data-size="lg"
                                                                                data-url="{{ route('users.edit', $user->id) }}"
                                                                                data-ajax-popup="true" class="dropdown-item"
                                                                                data-bs-original-title="{{ __('Edit User') }}">
                                                                                <i class="ti ti-pencil"></i>
                                                                                <span>{{ __('Edit') }}</span>
                                                                            </a>
                                                                        @endif

                                                                        @if (Gate::check('delete user'))
                                                                            {!! Form::open([
                                                                                'method' => 'DELETE',
                                                                                'route' => ['users.destroy', $user['id']],
                                                                                'id' => 'delete-form-' . $user['id'],
                                                                            ]) !!}
                                                                            <a href="#!" class="dropdown-item bs-pass-para">
                                                                                <i class="ti ti-archive"></i>
                                                                                <span>
                                                                                    @if ($user->delete_status != 0)
                                                                                        {{ __('Delete') }}
                                                                                    @else
                                                                                        {{ __('Restore') }}
                                                                                    @endif
                                                                                </span>
                                                                            </a>
                                                                            {!! Form::close() !!}
                                                                        @endif

                                                                        @if (Gate::check('edit user') || Gate::check('delete user'))
                                                                            <a href="#!"
                                                                                data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                                                                data-ajax-popup="true" data-size="md"
                                                                                class="dropdown-item"
                                                                                data-bs-original-title="{{ __('Reset Password') }}">
                                                                                <i class="ti ti-adjustments"></i>
                                                                                <span> {{ __('Reset Password') }}</span>
                                                                            </a>
                                                                        @endif
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
        </div>
    </div>
@endsection
