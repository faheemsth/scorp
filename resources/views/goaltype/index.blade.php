@extends('layouts.admin')

@section('page-title')
    {{__('Manage Goal Type')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Goal Type')}}</li>
@endsection




@section('content')
    <div class="row">
        <div class="col-3">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between;">
                    <h3>Goal Type</h3>

                        <div class="float-end">
                            @can('create goal type')
                                <a href="#" data-url="{{ route('goaltype.create') }}" data-ajax-popup="true" data-title="{{__('Create New Goal Type')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-dark">
                                    <i class="ti ti-plus"></i>
                                </a>
                            @endcan
                        </div>

                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Goal Type')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($goaltypes as $goaltype)
                                <tr>
                                    <td>{{ $goaltype->name }}</td>
                                    <td>

                                        @can('edit goal type')
                                            <div class="action-btn ms-2">
                                                <a href="#" class="btn btn-sm btn-dark mx-1 align-items-center" data-url="{{ URL::to('goaltype/'.$goaltype->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Goal Type')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('delete goal type')
                                            <div class="action-btn ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['goaltype.destroy', $goaltype->id],'id'=>'delete-form-'.$goaltype->id]) !!}
                                                <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endcan

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
