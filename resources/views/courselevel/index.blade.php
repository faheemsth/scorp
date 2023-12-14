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
                <h3>Course Level</h3>

                @can('create course level')
                <div class="float-end">
                    <a href="#" data-size="md" data-url="{{ route('courselevel.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Course Level')}}" class="btn btn-sm btn-dark">
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
                                <th class="text-center">{{__('Name')}}</th>
                                @php
                                    $canEdit = auth()->user()->can('edit course level');
                                    $canDelete = auth()->user()->can('delete course level');
                                @endphp

                                @if($canEdit || $canDelete)
                                    <th class="text-center">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courselevel as $c_level)

                            <tr class="font-style">
                                <td>{{ $c_level->name }}</td>


                                <td class="action">

                                    <span class="d-flex justify-content-center">
                                        @can('delete course level')
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['courselevel.destroy', $c_level->id]]) !!}
                                        <a href="#" class="mx-1 btn btn-sm btn-dark align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
                                        @endcan

                                        @can('edit course level')
                                        <a href="#" class="btn btn-sm btn-dark d-inline-flex align-items-center" data-url="{{ route('courselevel.edit',$c_level->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Course Level')}}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
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