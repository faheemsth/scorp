@extends('layouts.admin')

@section('page-title')
    {{__('Manage Department')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Department')}}</li>
@endsection


{{-- @section('action-btn')
    <div class="float-end">
    @can('create department')
            <a href="#" data-url="{{ route('department.create') }}" data-ajax-popup="true" data-title="{{__('Create New Department')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
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
        <div class="col-3">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: baseline;">
                    <h4>Manage Department</h4>
                    @can('create department')
                    <div class="float-end">
                        <a href="#" data-size="md" data-url="{{ route('department.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Department')}}" class="btn btn-sm btn-dark">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                    @endcan
                </div>
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Branch')}}</th>
                                <th>{{__('Department')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ !empty($department->branch)?$department->branch->name:'' }}</td>
                                    <td>{{ $department->name }}</td>

                                    <td class="Action">
                                        <span>
                                            @can('edit department')
                                            <div class="action-btn ms-2">

                                                <a href="#" data-url="{{ URL::to('department/'.$department->id.'/edit') }}"  data-ajax-popup="true" data-title="{{__('Edit Department')}}" class="btn btn-sm btn-dark mx-1 align-items-center " data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                    <i class="ti ti-pencil text-white"></i></a>
                                            </div>
                                                @endcan
                                            @can('delete department')
                                                    <div class="action-btn ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['department.destroy', $department->id],'id'=>'delete-form-'.$department->id]) !!}


                                                <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para"                                                data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$department->id}}').submit();"><i class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                                    </div>
                                            @endcan
                                        </span>
                                    </td>
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
