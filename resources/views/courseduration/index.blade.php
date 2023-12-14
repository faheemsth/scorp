@extends('layouts.admin')

@section('page-title')
{{__('Manage Course Level')}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Course Level')}}</li>
@endsection



@section('content')
<div class="row">
    <div class="col-3">
        @include('layouts.crm_setup')
    </div>
    <div class="col-9">
        <div class="card">

            <div class="card-header" style="display: flex; justify-content: space-between;">
                <h3>Course Duration</h3>

                @can('create course duration')
                <div class="float-end">
                    <a href="#" data-size="md" data-url="{{ route('courseduration.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Course Level')}}" class="btn btn-sm btn-dark">
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
                                <th>{{__('Name')}}</th>
                                @php
                                    $canEdit = auth()->user()->can('edit course duration');
                                    $canDelete = auth()->user()->can('delete course duration');
                                @endphp

                                @if($canEdit || $canDelete)
                                    <th class="text-center">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courseduration as $c_duration)

                            <tr class="font-style">
                                <td>{{ $c_duration->duration }}</td>


                                <td class="action">
                                    
                                    <span class="d-flex justify-content-center">
                                        
                                        @can('edit course duration')
                                        <a href="#" class="btn btn-sm btn-dark d-inline-flex align-items-center" data-url="{{ route('courseduration.edit',$c_duration->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Course Level')}}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                        @endcan

                                        @can('delete course duration')
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['courseduration.destroy', $c_duration->id]]) !!}
                                        <a href="#" class="mx-1 btn btn-sm btn-danger align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
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