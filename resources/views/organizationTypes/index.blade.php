@extends('layouts.admin')
@section('page-title')
{{__('Manage Organization Type')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Organization Type')}}</li>
@endsection
@section('action-btn')
<div class="float-end">
    @if(\Auth::user()->type == 'super admin' || \Auth::user()->can('create organization_type'))
    <a href="#" data-size="md" data-url="{{ route('organization-type.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Organization type')}}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
    @endif
</div>
@endsection


@section('content')
<div class="row w-100">
    <div class="col-3">
        @include('layouts.crm_setup')
    </div>

    <div class="col-9">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive w-100">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#')}}</th>
                                <th scope="col">{{__('Name')}}</th>


                                @if(\Auth::user()->type == 'super admin' || \Auth::user()->can('edit organization_type'))
                                <th scope="col">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($types as $key => $type)

                            <tr class="font-style">
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>{{ !empty($type->name)?$type->name:'' }}</td>


                                @if(\Auth::user()->type == 'super admin' || \Auth::user()->can('edit organization_type') || \Auth::user()->can('delete organization_type'))
                                <td class="action ">
                                    @if(\Auth::user()->type == 'super admin' || \Auth::user()->can('edit organization_type'))
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('organization-type.edit',$type->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Type')}}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endif

                                    @if(\Auth::user()->type == 'super admin' || \Auth::user()->can('delete organization_type'))
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['organization-type.destroy', $type->id]]) !!}
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
                                    </div>
                                    @endif
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