@extends('layouts.admin')
@section('page-title')
    {{__('Manage Pipelines')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('crm.dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Pipelines')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-3 p-0">
            @include('layouts.crm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between;">
                    <h3>Pipelines</h3>
                    <div class="float-end">
                        <a href="#" data-size="md" data-url="{{ route('pipelines.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Pipeline')}}" class="btn btn-sm btn-dark">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Pipeline')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($pipelines as $pipeline)
                                <tr>
                                    <td>{{ $pipeline->name }}</td>
                                    <td class="Action">
                                        <span class="d-flex">

                                            @can('delete pipeline')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['pipelines.destroy', $pipeline->id]]) !!}
                                                    <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                            @endcan

                                            @can('edit pipeline')
                                                <a href="#" class="btn btn-sm bg-dark d-inline-flex align-items-center" data-url="{{ URL::to('pipelines/'.$pipeline->id.'/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Pipeline')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            @endcan

                                        </span>
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

