@extends('layouts.admin')

@section('page-title')
    {{__('Manage Document Type')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Document Type')}}</li>
@endsection

{{-- @section('action-btn')
    <div class="float-end">
        @can('create document type')
            <a href="#" data-url="{{ route('document.create') }}" data-ajax-popup="true" data-title="{{__('Create New Document Type')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
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
                    <h3>Manage Document Type</h3>
                    @can('create document type')
                    <div class="float-end">
                        <a href="#" data-size="md" data-url="{{ route('document.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Document Type')}}" class="btn btn-sm btn-dark">
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
                                <th>{{__('Document')}}</th>
                                <th>{{__('Required Field')}}</th>
                                @if(Gate::check('edit document type') || Gate::check('delete document type'))
                                    <th width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($documents as $document)
                                <tr>
                                    <td>{{ $document->name }}</td>
                                    <td>
                                        <h6 class="float-left mr-1">
                                            @if( $document->is_required == 1 )
                                                <div class="doc_status_badge badge bg-success p-2 px-3 rounded">{{__('Required')}}</div>
                                            @else
                                                <div class="doc_status_badge badge bg-danger p-2 px-3 rounded">{{__('Not Required')}}</div>
                                            @endif
                                        </h6>
                                    </td>

                                    @if(Gate::check('edit document type') || Gate::check('delete document type'))
                                        <td>
                                            @can('edit document type')
                                                <div class="action-btn ms-2">
                                                    <a href="#"class="btn btn-sm btn-dark mx-1 align-items-center " data-url="{{ URL::to('document/'.$document->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Document Type')}}" data-bs-toggle="tooltip" title="{{__('Edit Document Type')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('delete document type')
                                                <div class="action-btn  ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['document.destroy', $document->id],'id'=>'delete-form-'.$document->id]) !!}
                                                    <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white text-white"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan

                                        </td>
                                    @endif
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
