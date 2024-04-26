
<style>
    .block-items {
        overflow: auto;
        padding-right: 7px;
        padding-bottom: 5px;
        padding-top: 1px;
        padding-left: 1px;
        width: 100%;
        display: flex;
    }


    .block-item {
        display: inline-block;
        vertical-align: top;
        padding: 10px;
        text-align: left;
        white-space: nowrap;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08);
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08);
        border-radius: 2px;
        margin-right: 10px;
        line-height: initial;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .top-label {
        text-transform: uppercase;
        white-space: nowrap;
        width: 100%;
        color: #757575;
        font-size: 11px;
        line-height: 12px;
        font-weight: normal;
        padding-bottom: 4px;
        display: block;
    }


    .block-item-count-total {
        font-weight: bold;
        font-size: 14px;
        text-align: left;
    }
</style>
@php
    $type = \Auth::user()->type;
    $is_show = true;

    if($type == 'super admin' || \Auth::user()->can('level 1')){

    }else if($type == 'Project Director' || $type == 'Project Manager' || \Auth::user()->can('level 2')){
            $per_brands = \App\Models\CompanyPermission::where('user_id', \Auth::user()->id)->where('permitted_company_id', $deal->brand_id)->first();

            if($per_brands){
                $is_show = true;
            }
    }else if($type == 'Region Manager' || \Auth::user()->can('level 3')){
            $is_show = ($lead->region_id == \Auth::user()->region_id);
    }else if($type == 'Branch Manager' || $type == 'Admissions Manager' || $type == 'Admissions Officer' || $type == 'Marketing Officer' || \Auth::user()->can('level 4')){
        $is_show = ($deal->branch_id == \Auth::user()->branch_id);
    }else{
        $is_show = false;
    }

@endphp
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

                    <input type="hidden" name="client-id" class="client-id" value="{{ $client->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Contact') }}</p>
                        <div class="d-flex align-items-baseline ">
                            @if (strlen($client->name) > 40)
                                <h5 class="fw-bold">{{ substr($client->name, 0, 40) }}...</h5>
                            @else
                                <h5 class="fw-bold">{{ $client->name }}</h5>
                            @endif

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                @if($is_show)
                <a href="https://wa.me/{{ !empty($client->phone) ? formatPhoneNumber($client->phone) : '' }}?text=Hello ! Dear {{ $client->name }}" target="_blank" data-size="lg" data-bs-toggle="tooltip" data-bs-title="{{ __('Whatsapp') }}" class="btn p-2 btn-dark text-white" style="color:white; width:36px; height: 36px; margin-top:10px;">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                @endif

                @if (\Auth::user()->can('edit client'))

                        <a href="#" data-size="lg" data-url="{{ route('clients.edit', $client->id) }}"
                            data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-title="{{ __('Update Client') }}"
                            class="btn btn-dark text-white p-2"  style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-pencil "></i>
                        </a>

                @endif

                @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete client'))
                    {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy', $client->id],'id'=>'delete-form-'.$client->id]) !!}
                    {{-- <a href="#!"  class="dropdown-item bs-pass-para">
                        <i class="ti ti-archive"></i>
                        <span> @if($client->delete_status!=0){{__('Delete')}} @else {{__('Restore')}}@endif</span>
                    </a> --}}

                    <a href="#" data-bs-toggle="tooltip" title="{{ __('Delete') }}" class="btn p-2 btn-danger text-white bs-pass-para" style="color:white; width:36px; height: 36px; margin-top:10px;" >
                        <i class="ti ti-trash"></i>
                    </a>
                    {!! Form::close() !!}
                @endcan

                </div>
            </div>


            <div class="content my-2">

                <div class="card">
                    <div class="card-header p-1">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>


                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link" id="pills-related-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-related" type="button" role="tab"
                                    aria-controls="pills-related" aria-selected="false">{{ __('Related') }}</button>
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
                                                {{ __('NAME AND OCCUPATION') }}
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
                                                                    {{ $client->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $client->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Organizaiton') }}
                                                                </td>
                                                                <td class="university_id-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($lead->organization_id) && isset($organizations[$lead->organization_id]) ? $organizations[$lead->organization_id] : '' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Contact Type') }}
                                                                </td>
                                                                <td class="status-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ __('Customer') }}

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    @if($is_show)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytwo">
                                                {{ __('CONTACT DETAIL') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeytwo"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Passport Number') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $client->passport_number ?? '' }}
                                                                </td>
                                                            </tr>
                                                           @if($client->brand_id == Auth::user()->brand_id)
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Email') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                   <a href="{{ $client->email }}" target="_blank" >{{ $client->email }}</a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Phone') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($lead->phone) ? $lead->phone : '' }}
                                                                </td>
                                                            </tr>
                                                            @elseif(\Auth::user()->type == 'super admin')
                                                             <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Email') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                   <a href="{{ $client->email }}" target="_blank" >{{ $client->email }}</a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Phone') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($lead->phone) ? $lead->phone : '' }}
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif


                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeythree">
                                                {{ __('ADDRESS INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeythree"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Mailing Address') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ (isset($lead->city) ? $lead->city : ' ') . (isset($lead->state) ? $lead->state : ' ') . (isset($lead->postal_code) ? $lead->postal_code : ' ') . (isset($lead->country) ? $lead->country : ' ') }}
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
                                                                    {{ __('Contact Owner') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Contact Created') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $client->created_at }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Contact Updated') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $client->updated_at }}
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



                            <div class="tab-pane fade" id="pills-related" role="tabpanel"
                                aria-labelledby="pills-related-tab">



                                <div class="row">

                                    <div id="discussion_note">
                                        <div class="row">

                                            <div class="block-items">
                                                <div class="block-item large-block text-center" id="con-stats" title="1 Linked Contacts"
                                                    data-bs-target="#contacts-grid-container">
                                                    <div class="top-label">Admissions</div>
                                                    <div class="block-item-count">{{ count($deals) }}</div>
                                                    <div class="fp-product-count-holder">
                                                        <div class="fp-product-count-total"></div>
                                                        <div class="fp-product-count-percent" style="width: 0px;"></div>
                                                    </div>
                                                </div>

                                                <div class="block-item large-block text-center" id="con-stats" title="1 Linked Contacts"
                                                    data-bs-target="#contacts-grid-container">
                                                    <div class="top-label">Applications</div>
                                                    <div class="block-item-count discussion_count">{{ count($applications) }}</div>
                                                    <div class="fp-product-count-holder">
                                                        <div class="fp-product-count-total"></div>
                                                        <div class="fp-product-count-percent" style="width: 0px;"></div>
                                                    </div>
                                                </div>


                                            </div>



                                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                                <!-- Open Accordion Item -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="panelsStayOpen-headingnote">
                                                        <button class="accordion-button p-2" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapsenote">
                                                            {{ __('Admissions') }}
                                                        </button>
                                                    </h2>

                                                    <div id="panelsStayOpen-collapsenote"
                                                        class="accordion-collapse collapse show"
                                                        aria-labelledby="panelsStayOpen-headingnote">
                                                        <div class="accordion-body">


                                                            <div class="">

                                                                <div class="col-12">
                                                                    <div class="card">

                                                                        <div class="card-body px-0">
                                                                            <table class="table">
                                                                                <thead class="table-bordered">
                                                                                    <tr>
                                                                                        <th scope="col">Name</th>
                                                                                        <th scope="col">InTake</th>
                                                                                        <th scope="col">Brand</th>
                                                                                        <th scope="col">Branch</th>
                                                                                        <th scope="col">Assign To</th>
                                                                                        <th scope="col">Stage</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="notes-tbody">
                                                                                    @forelse($deals as $deal)
                                                                                        @php
                                                                                            $users = \App\Models\User::pluck('name', 'id')->toArray();
                                                                                            $branch = \App\Models\Branch::where('id', $deal->branch_id)->first();
                                                                                        @endphp

                                                                                        <tr>
                                                                                            <td>@php
                                                                                                $name = $deal->name;
                                                                                                if (strlen($name) > 15) {
                                                                                                    $name = substr($name, 0, 15) . '...';
                                                                                                }
                                                                                                echo $name;
                                                                                            @endphp </td>

                                                                                            <td>{{ '1-' . (empty($deal->intake_month) ? 'Jan' : $deal->intake_month) . '-' . (empty($deal->intakeYear) ? date('Y') : $deal->intakeYear) }}</td>
                                                                                            <td>{{ !empty($deal->brand_id) ? (isset($users[$deal->brand_id]) ? $users[$deal->brand_id] : '') : '' }}</td>
                                                                                            <td>{{ isset($branch->name) ? $branch->name : '' }}</td>
                                                                                            <td>{{ isset($deal->assigned_to) && isset($organizations[$deal->assigned_to]) ? $organizations[$deal->assigned_to] : '' }}</td>
                                                                                            <td><span class="badge bg-success-scorp"> {{ $stages[$deal->stage_id] }} </span></td>
                                                                                        </tr>
                                                                                    @empty
                                                                                    @endforelse

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

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingkeydesc">
                                                    <button class="accordion-button p-2" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapsekeydesc">
                                                        {{ __('APPLICATIONS') }}
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapsekeydesc"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingkeydesc">
                                                    <div class="accordion-body">
                                                        <div class="table-responsive mt-1" style="margin-left: 10px;">
                                                            <table class="table">
                                                                <thead class=""
                                                                    style="background-color:rgba(0, 0, 0, .08); font-weight: bold;">
                                                                    <tr>
                                                                        <th scope="col">{{ __('University') }}</th>
                                                                        <th scope="col">{{ __('Intake') }}</th>
                                                                        <th scope="col">{{ __('Brand') }}</th>
                                                                        <th scope="col">{{ __('Branch') }}</th>
                                                                        <th scope="col">{{ __('Assigned To') }}</th>
                                                                        <th scope="col">{{ __('Applications Stage') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    @forelse($applications as $app)
                                                                        @php
                                                                            $university = \App\Models\University::where('id', $app->university_id)->first();
                                                                            $deal = \App\Models\Deal::where('id', $app->deal_id)->first();
                                                                            $users = \App\Models\User::pluck('name', 'id')->toArray();
                                                                            $branch = \App\Models\Branch::where('id', $deal->branch_id)->first();
                                                                            $app_stage = \App\Models\ApplicationStage::pluck('name', 'id')->toArray();
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $universities[$app->university_id] ?? '' }}</td>
                                                                            <td> {{ $app->intake }} </td>
                                                                            <td> {{ isset($users[$deal->brand_id]) ? $users[$deal->brand_id] : '' }}  </td>
                                                                            <td> {{ isset($branch->name) ? $branch->name : ''  }} </td>
                                                                            <td> {{ $users[$deal->assigned_to] ?? '
                                                                                '}} </td>
                                                                            <td><span class="badge bg-success-scorp"> {{ $app_stage[$app->status] ?? '' }}</span></td>
                                                                        </tr>
                                                                    @empty
                                                                @endforelse
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
</div>
