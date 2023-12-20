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
<style>
    .full-card {
        min-height: 165px !important;
    }
    table{
        font-size: 14px;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row w-100 m-0">
                <div class="card my-card">
                    <div class="card-body">
                        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                            <div class="col-2">
                                <p class="mb-0 pb-0">Institutes</p>
                                <div class="dropdown">
                                    <button class="All-leads" type="button">
                                        ALL Institutes
                                    </button>
                                </div>
                            </div>
                            <div class="col-10 d-flex justify-content-end gap-2">
                                <div class="input-group w-25">
                                    <button class="btn list-global-search-btn">
                                        <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                            <i class="ti ti-search" style="font-size: 18px"></i>
                                        </span>
                                    </button>
                                    <input type="Search"
                                        class="form-control border-0 bg-transparent ps-0 list-global-search"
                                        placeholder="Search this list..." aria-label="Username"
                                        aria-describedby="basic-addon1">
                                </div>

                                <a href="#" data-size="lg" data-url="{{ route('users.create') }}"
                                    data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                    class="btn btn-dark px-2 py-2">
                                    <i class="ti ti-plus "></i>
                                </a>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Designation</th>
                                                <th>Phone</th>
                                                <th>Last Login</th>
                                                {{-- <th>Action</th> --}}
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
                                                    {{-- <td>
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
                                                                                    data-ajax-popup="true"
                                                                                    class="dropdown-item"
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
                                                                                <a href="#!"
                                                                                    class="dropdown-item bs-pass-para">
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
                                                                                    <span>
                                                                                        {{ __('Reset Password') }}</span>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    @else
                                                                        <a href="#" class="action-item"><i
                                                                                class="ti ti-lock"></i></a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </td> --}}
                                                    <!-- Add more cells as needed with corresponding data -->
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
                                            'num_results_on_page' => 10,
                                        ])
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
