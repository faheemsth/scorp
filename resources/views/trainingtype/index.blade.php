@extends('layouts.admin')

@section('page-title')
    {{__('Manage Training Type')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Training Type')}}</li>
@endsection
@section('content')

    <div class="row">
        <div class="col-3">
            @include('layouts.hrm_setup')
        </div>

        <div class="col-9">
            <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                        <h3>Training Type</h3>

                        @can('create training type')
                        <a href="#" data-url="{{ route('trainingtype.create') }}" data-ajax-popup="true" data-title="{{__('Create New Training Type')}}" data-bs-toggle="tooltip" title="{{__('Create New Training Type')}}"  class="btn btn-sm btn-dark mx-1 align-items-center" style="margin: auto">
                            <i class="ti ti-plus"></i>
                        </a>

                    @endcan
                    </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Training Type')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($trainingtypes  as $key => $trainingtype)
                            <tr>
                                <td>{{ $trainingtype->name }}</td>

                                <td>
                                    @can('edit training type')
                                        <a href="#" data-url="{{ route('trainingtype.edit',$trainingtype->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Training Type')}}" class="edit-icon"><i class="ti ti-pencil text-white"></i></a>
                                    @endcan

                                    @can('edit training type')
                                        <div class="action-btn ms-2">
                                            <a href="#" class="btn btn-sm btn-dark mx-1 align-items-center" data-url="{{ route('trainingtype.edit',$trainingtype->id) }}" data-ajax-popup="true" data-title="{{__('Edit Training Type')}}" data-bs-toggle="tooltip" title="{{__('Edit Training Type')}}" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                    @endcan


                                    @can('delete training type')
                                        <div class="action-btn ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['trainingtype.destroy', $trainingtype->id],'id'=>'delete-form-'.$trainingtype->id]) !!}
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
