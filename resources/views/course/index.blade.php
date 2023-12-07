@extends('layouts.admin')
@section('page-title')
    {{__('Manage Courses')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Courses')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
         @can('create courses')
            <a href="#" data-size="md" data-url="{{ route('course.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Course')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th scope="col">{{__('#')}}</th>
                                <th scope="col">{{__('Name')}}</th>
                             
                                <th scope="col">{{__('University')}}</th>

                                <th scope="col">{{__('Course Level')}}</th>
                                <th scope="col">{{__('Course Duration')}}</th>
                                <th scope="col">{{__('Fee')}}</th>
                               
                                @if(\Auth::user()->type == 'super admin')
                                <th scope="col">{{__('Created By')}}</th>
                                @endif
                                
                                @if(\Auth::user()->type != 'super admin')
                                <th scope="col" >{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($courses as $key => $course)

                                <tr class="font-style">
                                    <td>
                                        {{ $key+1 }}
                                    </td>
                                    <td>{{ !empty($course->name)?$course->name:'' }}</td>
                                    <td>{{  !empty($course->university->name)?$course->university->name:'' }}</td>
                                    <td>{{  !empty($course->courselevel->name)?$course->courselevel->name:'' }}</td>
                                    <td>{{  !empty($course->courseduration->duration)?$course->courseduration->duration:'' }}</td>
                                    <td>{{  !empty($course->fee)?$course->currency.' '.number_format($course->fee):'' }}</td>

                                    @if(\Auth::user()->type == 'super admin')
                                        <td>{{  $users[$course->created_by] }}</td>
                                    @endif

                                    @if(\Auth::user()->type != 'super admin')
                                    <td class="action ">
                                        @can('edit courses')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('course.edit',$course->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Course')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a></div>
                                        @endcan
                                        @can('delete courses')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['course.destroy', $course->id]]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
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
