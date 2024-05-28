@extends('layouts.admin')

@section('page-title')
    {{__('Manage Deduction Option')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Deduction Option')}}</li>
@endsection

{{-- @section('action-btn')
    <div class="float-end">
        @can('create document type')
            <a href="#" data-url="{{ route('deductionoption.create') }}" data-ajax-popup="true" data-title="{{__('Create New Deduction Option')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>

        @endcan
    </div>
@endsection --}}


@section('content')
    <div class="row">
        <div class="col-3">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between;">
                    <h3>Manage Deduction Option</h3>
                    @can('create document type')
                    <div class="float-end">
                        <a href="#" data-size="md" data-url="{{ route('deductionoption.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Sources')}}" class="btn btn-sm btn-dark">
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
                                <th>{{__('Deduction Option')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($deductionoptions as $deductionoption)
                                <tr>
                                    <td>{{ $deductionoption->name }}</td>
                                    <td>
                                        @can('edit deduction option')
                                            <div class="action-btn  ms-2">
                                                <a href="#" class="btn btn-sm btn-dark mx-1 align-items-center" data-url="{{ URL::to('deductionoption/'.$deductionoption->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Deduction Option')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('delete deduction option')
                                            <div class="action-btn  ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['deductionoption.destroy', $deductionoption->id],'id'=>'delete-form-'.$deductionoption->id]) !!}
                                                <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para"
                                                data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white text-white"></i></a>
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
