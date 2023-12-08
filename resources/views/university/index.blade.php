@extends('layouts.admin')
@section('page-title')
{{__('Manage university')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('University')}}</li>
@endsection

@section('content')



<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">

                <div class="row">
                    @forelse($statuses as $key => $status)
                    <div class="col-md-2">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            <i class="fa fa-regular fa-window-close fa-2x" style="color: #b5282f"></i>
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">

                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h2 class="fs-22 fw-semibold ff-secondary mb-4 fw-bold"> <span class="counter-value" data-target="730000">{{ $status }}</span>
                                        </h2>

                                        <h4>{{ $key }}</h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div>
                    @empty

                    @endforelse
                </div>


                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-2">
                        <p class="mb-0 pb-0">Institutes</p>
                        <div class="dropdown">
                            <button class="All-leads" type="button">
                                ALL Institutes
                            </button>
                        </div>
                    </div>

                    <div class="col-10 d-flex justify-content-end gap-2">
                        <div class="input-group w-25">
                            <button class="btn btn-sm list-global-search-btn">
                                <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        @can('create university')
                        <a href="#" data-size="md" data-url="{{ route('university.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-size="lg" title="{{__('Create University')}}" class="btn btn-sm btn-primary pt-2">
                            <i class="ti ti-plus"></i>
                        </a>
                        @endcan

                    </div>
                </div>


                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#')}}</th>
                                <th scope="col">{{__('Name')}}</th>

                                <th scope="col">{{__('Country')}}</th>

                                <th scope="col">{{__('City')}}</th>
                                <th scope="col">{{__('Phone')}}</th>
                                <th scope="col">{{__('Note')}}</th>

                                @if(\Auth::user()->type == 'super admin')
                                <th scope="col">{{__('Created By')}}</th>
                                @endif

                                @if(\Auth::user()->type != 'super admin')
                                <th scope="col">{{__('Action')}}</th>
                                @endif



                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($universities as $key => $university)

                            <tr class="font-style">
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    @if(!empty($university->name))
                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/university/'+{{$university->id}}+'/university_detail')">
                                        {{ !empty($university->name)?$university->name:'' }}
                                    </span>
                                    @endif

                                </td>
                                <td>{{ !empty($university->country)?$university->country:'' }}</td>
                                <td>{{ !empty($university->city)?$university->city:'' }}</td>
                                <td>{{ !empty($university->phone)?$university->phone:'' }}</td>
                                <td>{{ !empty($university->note)?$university->note:'' }}</td>

                                @if(\Auth::user()->type == 'super admin')
                                <td>{{ isset($users[$university->created_by]) ? $users[$university->created_by] : '' }}</td>
                                @endif

                                @if(\Auth::user()->type != 'super admin')
                                <td class="action ">
                                    @can('edit university')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('university.edit',$university->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit University')}}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('delete university')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['university.destroy', $university->id]]) !!}
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