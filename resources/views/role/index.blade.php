@extends('layouts.admin')
@section('page-title')
    {{__('Manage Role')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('crm.dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Role')}}</li>
@endsection
{{-- @section('action-btn')
    <div class="float-end">
        <a href="#" data-size="lg" data-url="{{ route('roles.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Role')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection --}}

@section('content')
<style>
    .nav-item .nav-link{
        color: #313949 !important;
    }
    .nav-item .nav-link:active{
        background-color: #313949 !important;
        color: white !important;
    }
</style>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-2">
                            <p class="mb-0 pb-0">Tasks</p>
                            <div class="dropdown">
                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    ALL Roles
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    {{-- <li><a class="dropdown-item assigned_to" href="javascript:void(0)">Assigned to</a></li>
                                    <li><a class="dropdown-item update-status-modal" href="javascript:void(0)">Update Status</a></li>
                                    <li><a class="dropdown-item" href="#">Brand Change</a></li> --}}
                                    <li><a class="dropdown-item delete-bulk-tasks" href="javascript:void(0)">Delete</a></li>
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

                            {{-- <button class="btn px-2 pb-2 pt-2 refresh-list btn-dark" ><i class="ti ti-refresh" style="font-size: 18px"></i></button>

                            <button class="btn filter-btn-show p-2 btn-dark"  type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button> --}}

                            @can('create Roles')
                            <a href="#" data-size="lg" data-url="{{ route('roles.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Role')}}" class="btn btn-dark py-2 px-2">
                                <i class="ti ti-plus"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        {{-- ///  --}}

                        {{-- //// --}}
                        <table class="table ">
                            <thead>
                            <tr>
                                <th>{{__('Role')}} </th>
                                <th>{{__('Permissions')}} </th>
                                <th width="150">{{__('Action')}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($roles as $role)
                                @if($role->name != 'client')
                                    <tr class="font-style">
                                        <td class="Role">{{ $role->name }}</td>
                                        <td class="Permission">
                                            @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                                                <span class="badge rounded-pill bg-primary">{{$role->permissions()->pluck('name')[$j]}}</span>
                                            @endfor
                                        </td>
                                        <td class="Action">
                                        <span class="d-flex">
                                            @can('edit role')
                                                <div class=" ms-2">
                                                <a href="#" class="mx-2 btn p-2 btn-dark" data-url="{{ route('roles.edit',$role->id) }}" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Role Edit')}}">
                                                <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endcan
                                            @can('delete role')
                                                <div class="">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id],'id'=>'delete-form-'.$role->id]) !!}
                                                    <a href="#" class=" btn p-2 btn-danger bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>


                                                    {!! Form::close() !!}
                                                 </div>
                                            @endcan
                                        </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
