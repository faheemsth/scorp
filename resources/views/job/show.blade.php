<!-- New Code -->
<style>
    .btn-sm {
        width: 35px;
        height: 35px;
    }
</style>
<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0">
    <div class="row">
        <div class="col-sm-12 pe-0">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between align-items-center p-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" class="">
                    </div>

                    <input type="hidden" name="job-id" class="job-id" value="{{ $job->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Job') }}</p>
                        <div class="d-flex align-items-baseline ">
                            <h5 class="fw-bold"> {{$job->title}}</h5>
                        </div>
                    </div>

                </div>

                <div class="d-flex">
                    @if($job->status!='in_active')
                    <div class="action-btn bg-warning mx-2">
                        <a href="#" id="{{ route('job.requirement',[$job->code,!empty($job)?$job->createdBy->lang:'en']) }}" class="btn text-white px-2 btn-dark" style="width: 36px; height: 36px;" onclick="copyToClipboard(this)" data-bs-toggle="tooltip" title="{{__('Copy')}}" data-original-title="{{__('Click to copy')}}"><i class="ti ti-link text-white"></i></a>
                    </div>
                    @endif

                    @can('edit job')
                    <a href="{{ route('job.edit',$job->id) }}" data-url="" data-ajax-popup="true" data-title="{{__('Edit Job')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" class="btn text-white px-2 btn-dark" style="width: 36px; height: 36px;">
                        <i class="ti ti-pencil"></i>
                    </a>
                    @endcan

                    @can('delete job')
                    <div class="action-btn bg-danger ms-2">
                        {!! Form::open(['method' => 'DELETE', 'route' => ['job.destroy', $job->id],'id'=>'delete-form-'.$job->id]) !!}

                        <a href="#" class="btn text-white px-2 btn-dark bs-pass-para" style="width: 36px; height: 36px;" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$job->id}}').submit();">
                            <i class="ti ti-trash text-white"></i></a>
                        {!! Form::close() !!}
                    </div>
                    @endcan
                </div>
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small>{{ __('Status') }}</small>
                    <span class="font-weight-bolder">
                        @if($job->status=='active')
                        <span class="px-4 rounded badge bg-success">{{App\Models\Job::$status[$job->status]}}</span>
                        @else
                        <span class="px-4 rounded badge bg-danger">{{App\Models\Job::$status[$job->status]}}</span>
                        @endif
                    </span>
                </div>

                <div class="">
                    <small>{{ __('Created at') }}</small>

                    <span>
                        {{ \Auth::user()->dateFormat($job->created_at) }}
                    </span>
                </div>

            </div>

            <div class="card content my-2 bg-white">
                <div class="">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link" id="pills-skills-tab" data-bs-toggle="pill" data-bs-target="#pills-skills" type="button" role="tab" aria-selected="true">{{ __('Skills') }}</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link" id="pills-requirements-tab" data-bs-toggle="pill" data-bs-target="#pills-requirements" type="button" role="tab" aria-selected="true">{{ __('Requirements') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeyone">
                                                {{ __('Details') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyone" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $job->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Title') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{$job->title}}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{$filters['users'][$job->brand_id] ?? ''}}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Region') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{$filters['regions'][$job->region_id] ?? ''}}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{$filters['branches'][$job->branch] ?? ''}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Status') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    @if($job->status=='active')
                                                                    <span class="px-4 py-2 rounded badge bg-success">{{App\Models\Job::$status[$job->status]}}</span>
                                                                    @else
                                                                    <span class="px-4 py-2 rounded badge bg-danger">{{App\Models\Job::$status[$job->status]}}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{\Auth::user()->dateFormat($job->created_at)}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Start Date') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{\Auth::user()->dateFormat($job->start_date)}}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('End Date') }}
                                                                </td>
                                                                <td class="key-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{\Auth::user()->dateFormat($job->end_date)}}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="pills-skills" role="tabpanel" aria-labelledby="pills-skills-tab">
                                <div class="row">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeydesc">
                                            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeydesc">
                                                {{ __('Required Skills') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeydesc" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingkeydesc">
                                            <div class="accordion-body">
                                                <div style="max-height: 400px; overflow-y: auto;">
                                                    @foreach($job->skill as $skill)
                                                    <span class="p-2 px-3 rounded badge bg-primary">{{$skill}}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="pills-requirements" role="tabpanel" aria-labelledby="pills-requirements-tab">
                                <div class="row">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeydesc">
                                            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeydesc">
                                                {{ __('Requirments') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeydesc" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingkeydesc">
                                            <div class="accordion-body">
                                                <div class="col-12">
                                                    <div class="row">

                                                        @if(($job->applicant))
                                                        <div class="col-6">
                                                            <h6>{{__('Need to ask ?')}}</h6>
                                                            <ul class="">
                                                                @foreach($job->applicant as $applicant)
                                                                <li>{{ucfirst($applicant)}}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        @endif
                                                        @if(!empty($job->visibility))
                                                        <div class="col-6">
                                                            <h6>{{__('Need to show option ?')}}</h6>
                                                            <ul class="">
                                                                @foreach($job->visibility as $visibility)
                                                                <li>{{ucfirst($visibility)}}</li>
                                                                @endforeach
                                                            </ul>

                                                        </div>
                                                        @endif

                                                        @if(count($job->questions())>0)
                                                        <div class="col-12">
                                                            <h6>{{__('Custom Question')}}</h6>
                                                            <ul class="">
                                                                @foreach($job->questions() as $question)
                                                                <li>{{$question->question}}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <div class="row ">
                                                        <div class="col-12 mt-3">
                                                            <h6>{{__('Job Description')}}</h6>
                                                            {!! $job->description !!}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <h6>{{__('Job Requirement')}}</h6>
                                                            {!! $job->requirement !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>