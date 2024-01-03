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

                    <input type="hidden" name="university-id" class="university-id" value="{{ $university->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Name') }}</p>
                        <div class="d-flex align-items-baseline ">
                            @if (strlen($university->name) > 40)
                                <h6 class="fw-bold">{{ substr($university->name, 0, 40) }}...</h6 >
                            @else
                                <h6 class="fw-bold">{{ $university->name }}</h6 >
                            @endif

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                    @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit university'))
                        <div class="d-flex justify-content-end gap-1 me-3">
                            <a href="#" data-size="lg" data-url="{{ route('university.edit', $university->id) }}"
                                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Update University') }}"
                                class="btn p-2 btn-dark text-white">
                                <i class="ti ti-pencil"></i>
                            </a>
                        </div>
                    @endif
                    
                      @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete university'))
                    
                             {!! Form::open(['method' => 'DELETE', 'route' => ['university.destroy', $university->id]]) !!}
    
                            <a href="#" data-bs-toggle="tooltip" title="{{__('Delete')}}"
                                class="btn px-2 py-2 text-white bs-pass-para bg-danger">
                                <i class="ti ti-trash" ></i>
                            </a>
    
    
                            {!! Form::close() !!}
                    @endif
                    </div>
            </div>


            <div class="lead-info d-flex justify-content-between px-5 py-3 text-center">
                <div class="">
                    <small>{{ __('Phone') }}</small>
                    <span class="font-weight-bolder">
                        {{ isset($university->phone) ? $university->phone : '' }}
                    </span>
                </div>

                <div class="">
                    <small>{{ __('Contact Owner') }}</small>
                    <span>
                        {{ isset($users[$university->created_by]) ? $users[$university->created_by] : ''}}
                    </span>
                </div>

            </div>



            <div class="content my-2">

                <div class="card">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active"  id="pills-details-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>

                            <li class="nav-item" role="presentation"  >
                                <button class="nav-link pills-link "  id="pills-related-tab" data-bs-toggle="pill" data-bs-target="#pills-related" type="button" role="tab" aria-controls="pills-related" aria-selected="false">{{ __('Related') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                aria-labelledby="pills-details-tab">

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeyone">
                                                {{ __('Detail') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyone" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $university->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $university->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('University Owner') }}
                                                                </td>
                                                                <td class="created_by-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($users[$university->created_by]) ? $users[$university->created_by] : '' }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytwo">
                                                {{ __('ADDRESS INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeytwo" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Country') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $university->country }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('City') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{$university->city }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeyfour">
                                                {{ __('ADDITIONAL INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyfour"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('University Created') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $university->created_at }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('University Updated') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $university->updated_at }}
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


                            <div class="tab-pane fade" id="pills-related" role="tabpanel" aria-labelledby="pills-related-tab">

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeyone">
                                                {{ __('Applications') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyone" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px; max-height: 300px; overflow-y: scroll;">

                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Application Key') }}</th>
                                                                <th>{{ __('Admission Name') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($applications as $app)
                                                            <tr>
                                                                <td>


                                                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('deals/'+{{ $app->id }}+'/detail-application')" >
                                                                        {{ $app->application_key }}
                                                                    </span>
                                                                </td>
                                                                <td>


                                                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/get-deal-detail?deal_id='+{{$app->deal_id}})" >
                                                                        {{ isset($app->deal_id) && isset($dealArr[$app->deal_id]) ? $dealArr[$app->deal_id] : '' }}
                                                                    </span>

                                                                </td>
                                                                <td> <span class="badge {{ $app->status == "Approved" ? 'bg-success-scorp' : 'bg-warning-scorp' }}">{{ $app->status }}</span></td>
                                                            </tr>

                                                            @empty

                                                            <tr>
                                                                <td colspan="5"> No Application found !!! </td>
                                                            </tr>

                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    {{-- <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytwo">
                                                {{ __('ADMISSIONS') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeytwo" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px; max-height: 300px; overflow-y: scroll;">

                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Name') }}</th>
                                                                <th>{{ __('Organization') }}</th>
                                                                <th>{{ __('Stage') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($deals as $deal)
                                                            <tr>
                                                                <td>
                                                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/get-deal-detail?deal_id='+{{$deal->id}})" >
                                                                        {{ $deal->name }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/get-organization-detail?org_id='+{{$deal->organization_id}})" >
                                                                       {{  $organizations[$deal->organization_id] }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $stages[$deal->stage_id] }}</td>
                                                                <td><span class="badge {{ $deal->status == "Active" ? 'bg-success-scorp' : 'bg-warning-scorp' }}">{{ $deal->status }}</span></td>
                                                            </tr>

                                                            @empty

                                                            <tr>
                                                                <td colspan="5"> No Admission found !!! </td>
                                                            </tr>

                                                            @endforelse
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
