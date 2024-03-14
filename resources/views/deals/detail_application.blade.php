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

                    <input type="hidden" name="application-id" class="application-id" value="{{ $application->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Application') }}</p>
                        <div class="d-flex align-items-baseline ">
                            @if (strlen($application->name) > 40)
                            <h5 class="fw-bold">{{ substr($application->name, 0, 40) }}...</h5>
                            @else
                            <h5 class="fw-bold">{{ $application->name }}</h5>
                            @endif

                        </div>
                    </div>

                </div>

                @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit application') || \Auth::user()->can('delete application'))
                <div class="d-flex justify-content-end gap-1 me-3">
                    @can('edit application')
                    <a href="#" data-size="lg" data-url="{{ route('deals.application.edit', $application->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-title="{{ __('Update Application') }}" class="btn text-white px-2 btn-dark" style="width: 36px; height: 36px;">
                        <i class="ti ti-pencil"></i>
                    </a>
                    @endcan

                    @can('delete application')
                    {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['deals.application.destroy', $application->id],
                    'id' => 'delete-form-' . $application->id,
                    ]) !!}
                    <a href="#" class="btn px-2 bg-danger  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete') }}" style="width: 36px; height: 36px;"><i class="ti ti-trash text-white"></i></a>

                    {!! Form::close() !!}
                    @endcan
                </div>
                @endif
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small>{{ __('Status') }}</small>
                    <span class="font-weight-bolder">
                        {{ isset($application->stage_id) && isset($stages[$application->stage_id]) ? $stages[$application->stage_id] : '' }}
                    </span>
                </div>
                <div class="">
                    <small>{{ __('Universtiy') }}</small>
                    <span>
                        {{ isset($application->university_id) && isset($universities[$application->university_id]) ? $universities[$application->university_id] : ''}}
                    </span>
                </div>
                <div class="">
                    <small>{{ __('Created at') }}</small>

                    <span>
                        {{ $application->created_at }}

                    </span>
                </div>

            </div>




            <div class="card content my-2 bg-white">
                <div class="stages mt-2 bg-white">
                    <h2 class="mb-3">Application STATUS: <span class="d-inline-block fw-light">{{ $stages[$application->stage_id] }}</span>
                    </h2>
                    <div class="wizard mb-2" style="    background: #EFF3F7;
                    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
                };">
                        <?php $done = true; ?>
                        @forelse ($stages as $key => $stage)
                        <?php
                        if ($application->stage_id == $key) {
                            $done = false;
                        }

                        $is_missed = false;

                        if (!empty($stage_histories) && !in_array($key, $stage_histories) && $key <= max($stage_histories)) {
                            $is_missed = true;
                        }

                        ?>
                        <style>
                            .missedup{
                                background-color:#e0e0e0 !important;
                                color:white !important;
                            }
                            .missedup::after{
                                border-left-color: #e0e0e0 !important;
                            }
                          </style>

                        <a type="button" data-application-id="{{ $application->id }}" data-stage-id="{{ $key }}"
                            class="@can('edit stage application') application_stage @endcan {{ $is_missed == true ? 'missedup' : ($application->stage_id == $key ? 'current' : ($done == true ? 'done' : '')) }} "
                            style="font-size:13px">{{ $stage }} @if($is_missed == true)<i class="fa fa-close text-danger"></i>@endif </a>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
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
                                                                    {{ $application->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Application Name') }}
                                                                </td>
                                                                <td class="application_key-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{$application->name}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Application Key') }}
                                                                </td>
                                                                <td class="application_key-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{$application->application_key}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('University') }}
                                                                </td>
                                                                <td class="university_name-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $universities[$application->university_id] ?? '' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Course') }}
                                                                </td>
                                                                <td class="course-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{ ($application->course) }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Intake') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{($application->intake) }}

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Student ID') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $application->deal_id}}

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">
                                                                   {{  App\Models\User::find(App\Models\Deal::find($application->deal_id)->brand_id)->name ?? ''}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">
                                                                   {{ App\Models\Branch::find(App\Models\Deal::find($application->deal_id)->branch_id)->name  ?? ''}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Assigned To') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">
                                                                    @if (App\Models\Deal::find($application->deal_id)->assigned_to)

                                                                    {{ App\Models\User::find(App\Models\Deal::find($application->deal_id)->assigned_to)->name }}
                                                                    @else
                                                                    {{__(" ")}}
                                                                    @endif

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Status') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{ isset($application->stage_id) && isset($stages[$application->stage_id]) ? $stages[$application->stage_id] : '' }}


                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class="created_at-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $application->created_at }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Updated at') }}
                                                                </td>
                                                                <td class="updated_at-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $application->updated_at }}
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


