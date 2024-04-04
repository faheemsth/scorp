@extends('layouts.admin')
@section('page-title')
{{__('Manage Job')}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Job')}}</li>
@endsection
@push('script-page')


<script>
    function copyToClipboard(element) {

        var copyText = element.id;
        document.addEventListener('copy', function(e) {
            e.clipboardData.setData('text/plain', copyText);
            e.preventDefault();
        }, true);

        document.execCommand('copy');
        show_toastr('success', 'Url copied to clipboard', 'success');
    }
</script>


@endpush


@section('action-btn')
<div class="float-end d-none">
    @can('create job')
    <a href="{{ route('job.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Job')}}">
        <i class="ti ti-plus"></i>
    </a>
    @endcan
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-cast"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">{{__('Total')}}</small>
                                <h6 class="m-0">{{__('Jobs')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <h4 class="m-0">{{$data['total']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-info">
                                <i class="ti ti-cast"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">{{__('Active')}}</small>
                                <h6 class="m-0">{{__('Jobs')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <h4 class="m-0">{{$data['active']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-cast"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">{{__('Inactive')}}</small>
                                <h6 class="m-0">{{__('Jobs')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <h4 class="m-0">{{$data['in_active']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-xl-12">
        <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
            <div class="card-body table-border-style" style="padding: 25px 3px;">


                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-4">
                        <p class="mb-0 pb-0 ps-1">Jobs</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL Jobs
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <option value="">No Filter Found</option>
                            </ul>
                        </div>
                    </div>

                    <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                        <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                            <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        @can('create job')
                        <a href="{{ route('job.create') }}" data-url="" data-ajax-popup="true" data-title="{{__('Edit Job')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" class="btn px-2 btn-dark" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-plus" style="font-size:18px"></i>
                        </a>
                        @endcan
                    </div>

                    <div class="card-body table-responsive mt-1" style="padding: 25px 3px; width:auto;">
                        <table class="table " data-resizable-columns-id="lead-table" id="tfont">
                            <thead>
                                <tr>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Branch')}}</th>
                                    <th>{{__('Start Date')}}</th>
                                    <th>{{__('End Date')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Created At')}}</th>
                                </tr>
                            </thead>


                            <tbody class="font-style">
                                @foreach ($jobs as $job)
                                <tr>
                                <td style="cursor:pointer" class="lead-name hyper-link" @can('show job') onclick="openSidebar('/job/<?= $job->id ?>')" @endcan>{{$job->title}}</td>
                                    <td>{{ !empty($job->branches)?$job->branches->name:__('All') }}</td>
                                    <td>{{\Auth::user()->dateFormat($job->start_date)}}</td>
                                    <td>{{\Auth::user()->dateFormat($job->end_date)}}</td>
                                    <td>
                                        @if($job->status=='active')
                                        <span class="status_badge badge bg-success p-2 px-3 rounded">{{App\Models\Job::$status[$job->status]}}</span>
                                        @else
                                        <span class="status_badge badge bg-danger p-2 px-3 rounded">{{App\Models\Job::$status[$job->status]}}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Auth::user()->dateFormat($job->created_at) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection