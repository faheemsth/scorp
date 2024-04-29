@extends('layouts.admin')
@section('page-title')
    {{__('Manage Sources')}}
@endsection
@push('script-page')
@endpush
@section('content')
    <div class="row">
        <div class="col-3 p-0">
            @include('layouts.crm_setup')
        </div>
        <div class="col-9">
            <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                        <h3>Sources</h3>

                        @can('create source')
                        <div class="float-end">
                            <a href="#" data-size="md" data-url="{{ route('sources.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Sources')}}" class="btn btn-sm btn-dark">
                                <i class="ti ti-plus"></i>
                            </a>
                        </div>
                        @endcan
                    </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Source')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sources as $source)
                                <tr>
                                    <td>{{ $source->name }}</td>
                                    <td class="Active">

                                        @can('edit source')
                                            <div class="action-btn ms-2">
                                                <a href="#" class="btn btn-sm btn-dark mx-1 align-items-center" data-url="{{ URL::to('sources/'.$source->id.'/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Source')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete source')
                                            <div class="action-btn ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['sources.destroy', $source->id]]) !!}
                                                <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        @if ($total_records > 0)
                        @include('layouts.pagination', [
                        'total_pages' => $total_records,
                        'num_results_on_page' => 50,
                        ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
