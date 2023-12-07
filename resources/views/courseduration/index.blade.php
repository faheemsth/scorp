@extends('layouts.admin')

@section('page-title')
{{__('Manage Course Level')}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Course Level')}}</li>
@endsection



@section('action-btn')
@can('create course duration')
<div class="float-end">
    <a href="#" data-size="md" data-url="{{ route('courseduration.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Course Level')}}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
</div>
@endcan
@endsection

@section('content')
<div class="row">
    <div class="col-3">
        @include('layouts.crm_setup')
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                @if(\Auth::user()->type=='company')
                                <th class="text-end ">{{__('Action')}}</th>

                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courseduration as $c_duration)

                            <tr class="font-style">
                                <td>{{ $c_duration->duration }}</td>


                                <td class="action text-end">
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('courseduration.edit',$c_duration->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Course Level')}}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['courseduration.destroy', $c_duration->id]]) !!}
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
                                    </div>
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