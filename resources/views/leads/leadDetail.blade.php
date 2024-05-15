<style>
    .editable:hover {
        border: 1px solid rgb(136, 136, 136);
    }

    .lead-info small {
        font-weight: 700 !important;
    }

    .accordion-button:focus {
        box-shadow: none !important;
        outline: 0;
        border-radius: 0px !important;
    }

    /* table tr td {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
    } */

    .btn-effect-none:focus {
        box-shadow: none !important;
    }


    .edit-input-field-div {
        background-color: #ffffff;
        border: 0px solid rgb(224, 224, 224);
        max-width: max-content;
        max-height: 35px;
        align-items: center !important;
    }


    .edit-input-field-div .input-group {
        min-width: 70px;
        min-height: 30px;
        align-items: center !important;
        border: none !important;
    }

    .edit-input-field-div .input-group input {
        border: none !important;
    }

    .edit-input-field {
        border: 0px;
        box-shadow: none;
        padding: 4px !important;

    }

    .edit-input-field-div .edit-btn-div {
        display: none;
    }

    .edit-input-field-div:hover {
        /* border: 1px solid rgb(224, 224, 224); */
    }

    .edit-input-field-div:hover .edit-btn-div {
        display: block;
    }

    .edit-input {
        padding: 7px !important;
    }

    .btn-sm {
        width: 30px;
        height: 30px;
    }

    .lead-topbar {
        border-radius: 8px;
        background: #FFF !important;

        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) !important;
    }

    form {
        margin: 0px !important;
    }

    .card-header .nav-pills .nav-link:focus,
    .card-header .nav-pills .nav-link.active {
        color: #ffffff !important;
        background: #313949 !important;
    }

    .card-header .nav-item .nav-link {
        color: #313949 !important;
    }
</style>
<style>
    .indivbtn {
        position: absolute;
        bottom: 12px;
        right: 10px;
        z-index: 1000;
    }
</style>

<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid ps-2 mx-0 pe-0">
    <div class="row">
        <div class="col-sm-12">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between align-items-center py-1 px-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" class="">
                    </div>

                    <div class="lead-basic-info mt-1">
                        <p class="pb-0 mb-0 fw-normal">{{ __('LEAD') }}</p>
                        <div class="d-flex align-items-baseline">
                            <h5 class="fw-bold">{{ $lead->name }}</h5>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                    {{-- @can('view lead') --}}
                    <a href="https://wa.me/{{ !empty($lead->phone) ? formatPhoneNumber($lead->phone) : '' }}?text=Hello ! Dear {{ $lead->name }}"
                        target="_blank" data-size="lg" data-bs-toggle="tooltip"
                        title="{{ __('Already Converted To Admission') }}" class="btn px-2 py-2 btn-dark text-white"
                        style="background-color: #313949;color:white; width:36px; height: 36px; margin-top:10px;">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    {{-- @endcan --}}


                    @can('edit lead')

                        @if (!empty($deal))
                            <a href="javascript:void(0)"
                                @can('View Deal') @if ($deal->is_active)   onclick="openSidebar('/get-deal-detail?deal_id='+{{ $deal->id }}) @else '' @endif @else '' @endcan"
                                data-size="lg" data-bs-toggle="tooltip"
                                data-bs-title=" {{ __('Already Converted to Admission') }}"
                                class="btn px-2 py-2 btn-dark text-white"
                                style="background-color: #313949 color:white; width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-exchange"></i>
                            </a>
                        @else
                            @can('convert lead')
                                <a href="#" data-size="lg"
                                    data-url="{{ URL::to('leads/' . $lead->id . '/show_convert') }}" data-ajax-popup="true"
                                    data-bs-toggle="tooltip" title="{{ __('Convert [' . $lead->subject . '] to Admission') }}"
                                    class="btn px-2 py-2 btn-dark text-white"style="width:36px; height: 36px; margin-top:10px;">
                                    <i class="ti ti-exchange"></i>
                                </a>
                            @endcan
                        @endif

                    @endcan

                    <a href="#" data-url="{{ URL::to('leads/' . $lead->id . '/labels') }}" data-ajax-popup="true"
                        data-size="lg" data-bs-toggle="tooltip" title="{{ __('Label') }}"
                        class="btn px-2 py-2 text-white"
                        style="background-color: #313949;color:white; width:36px; height: 36px; margin-top:10px;">
                        <i class="ti ti-bookmark"></i>
                    </a>



                    @can('edit lead')
                        <a href="#" data-size="lg" data-url="{{ route('leads.edit', $lead->id) }}"
                            data-ajax-popup="true" data-bs-toggle="tooltip" bs-original-title="{{ __('Update Lead') }}"
                            title="Update Lead" data-original-title="{{ __('Update Lead') }}"
                            class="btn px-2 py-2 text-white"
                            style="background-color: #313949;color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-pencil"></i>
                        </a>
                    @endcan

                    @can('delete lead')
                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['leads.destroy', $lead->id],
                            'id' => 'delete-form-' . $lead->id,
                        ]) !!}

                        <a href="#" data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                            class="btn px-2 py-2 text-white bs-pass-para bg-danger"style="width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-trash"></i>
                        </a>


                        {!! Form::close() !!}
                    @endcan


                </div>
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                {{-- <div class="">
                    <small>{{ __('Stage') }}</small>
                    <span class="font-weight-bolder">{{ $lead->stage->name }}</span>
                </div> --}}
                <div>
                    <small>{{ __('Phone') }}</small>
                    <span style="color: #313949;">{{ !empty($lead->phone) ? $lead->phone : '' }}</span>
                </div>
                <div class="">
                    <small>{{ __('Email') }}</small>
                    <span style="color: #313949;">{{ !empty($lead->email) ? $lead->email : '' }}</span>
                </div>

                {{-- <div>
                    <small> {{ __('Pipeline') }} </small>
                    <span>{{ $lead->pipeline->name }}</span>
                </div> --}}
                <div class="">
                    <small>{{ __('Created') }}</small>
                    <span style="color: #313949;">{{ \Auth::user()->dateFormat($lead->created_at) }}</span>
                </div>
            </div>

            {{-- Stages --}}
            <div class="lead-content my-2"
                style="border-radius: 8px;
            background: #FFF;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
">
                <div class="stages my-2 ">
                    <div class="d-flex justify-content-between">
                        <h2 class="mb-2 py-2 ps-3">LEAD STATUS: <span
                                class="d-inline-block fw-light ms-1">{{ $lead->stage->name }}</span>
                        </h2>

                        <div class="">
                            @php
                                $lead_tags = \App\Models\LeadTag::where('lead_id', $lead->id)->get();
                            @endphp

                            @forelse($lead_tags as $tag)
                                <span class="badge  text-white"
                                    style="background-color:#cd9835; margin-top: 1rem; margin-right: 1rem;">{{ $tag->tag }}</span>
                            @empty
                            @endforelse
                        </div>
                    </div>
                    <div class="wizard mb-2"
                        style="background: #EFF3F7;
                    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);">
                        <?php $done = true; ?>
                        @forelse ($lead_stages as $stage)
                            <?php
                            if ($lead->stage->name == $stage->name) {
                                $done = false;
                            }

                            $is_missed = false;

                            if (!empty($stage_histories) && !in_array($stage->id, $stage_histories) && $stage->id <= max($stage_histories)) {
                                $is_missed = true;
                            }

                            ?>

                            <style>
                                .missedup {
                                    background-color: #e0e0e0 !important;
                                    color: white !important;
                                }

                                .missedup::after {
                                    border-left-color: #e0e0e0 !important;
                                }
                            </style>

                            <a type="button" data-lead-id="{{ $lead->id }}" data-stage-id="{{ $stage->id }}"
                                class="@can('edit stage lead') lead_stage @endcan {{ $is_missed == true ? 'missedup' : ($lead->stage->name == $stage->name ? 'current' : ($done == true ? 'done' : '')) }}"
                                style="font-size:13px;"> {{ $stage->name }} @if ($is_missed == true)
                                    <i class="fa fa-close text-danger"></i>
                                @endif </a>
                        @empty
                        @endforelse
                    </div>
                </div>


                <div class="card me-3">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active fw-bold" id="pills-details-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link text-dark fw-bold" id="pills-related-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-related" type="button"
                                    role="tab" aria-controls="pills-related"
                                    aria-selected="false">{{ __('Related') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link text-dark fw-bold" id="pills-activity-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-activity" type="button"
                                    role="tab" aria-controls="pills-activity"
                                    aria-selected="false">{{ __('Timeline') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                aria-labelledby="pills-details-tab">

                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseinfo">
                                                {{ __('LEAD INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headinginfo">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Lead Stage') }}
                                                                </td>
                                                                <td style="padding-left: 10px; font-size: 14px;">
                                                                    <div class="text-white"
                                                                        style="background-color:#B3CDE1;width: 200px;">
                                                                        <p class="mb-0"
                                                                            style="padding-left: 10px; font-size: 14px;">
                                                                            {{ $lead->stage->name }}
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Pipeline') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->pipeline->name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ optional(App\Models\Branch::find($lead->branch_id))->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('User Responsible') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($users[$lead->user_id]) ? $users[$lead->user_id] : '' }}
                                                                </td>
                                                            </tr>

                                                            @php
                                                                $org_name = '';
                                                                if (
                                                                    isset($lead->organization_id) &&
                                                                    !empty($lead->organization_id)
                                                                ) {
                                                                    $org = \App\Models\User::where(
                                                                        'id',
                                                                        $lead->organization_id,
                                                                    )->first();

                                                                    if ($org) {
                                                                        $org_name = $org->name;
                                                                    }
                                                                }

                                                            @endphp
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Agency') }}
                                                                </td>
                                                                <td class="organization_id-td"
                                                                    style="padding-left: 10px; font-size: 14px;"
                                                                    class="edit-td">

                                                                    <div class="d-flex edit-input-field-div">
                                                                        <div
                                                                            class="input-group border-0 organization_id">
                                                                            {{ $org_name }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="organization_id"
                                                                                style="padding: 7px;"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                            <input type="hidden" class="lead-id"
                                                                value="{{ $lead->id }}">
                                                            <input type="hidden" class="temp-field" value="">

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Lead Source') }}
                                                                </td>
                                                                <td class="sources-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    @php
                                                                        $sources = '';
                                                                        if ($lead->sources()) {
                                                                            foreach ($lead->sources() as $source) {
                                                                                $sources .= $source->name . ' ';
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    {{-- <div class="d-flex edit-input-field-div">
                                                                        <div class="input-group border-0 sources"
                                                                            style="width: 316px;">
                                                                            <span
                                                                                style="width: 300px; word-wrap: break-word; font-size: 12px; color: blue; text-decoration: underline;">
                                                                                {{ substr($sources, 0, 60) }}{{ strlen($sources) > 70 ? '...' : '' }}
                                                                            </span>
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="sources"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    <span
                                                                        style="width: 300px; word-wrap: break-word; font-size: 14px; color: blue; text-decoration: underline;">
                                                                        {{ substr($sources, 0, 60) }}{{ strlen($sources) > 70 ? '...' : '' }}
                                                                    </span>


                                                            </tr>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 194px; font-size: 14px;">
                                                                    {{ __('Link Email Address') }}
                                                                </td>
                                                                <td class="d-flex align-items-center"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    <a href=""
                                                                        style="font-size: 14px;color:blue;text-decoration: underline;">
                                                                        {{ $lead->email }}
                                                                        <i class="ti ti-clipboard"></i>
                                                                    </a>
                                                                    <i class="ti ti-help"></i>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Lead Owner') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    <a href="" style="font-size: 14px;">
                                                                        {{ \App\Models\User::where('id', $lead->created_by)->first()->name }}</a>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Drive Link') }}
                                                                </td>
                                                                <td class="drive_link-td"
                                                                    style="padding-left: 10px; font-size: 14px; width:300px">
                                                                    <a href="">
                                                                        <div class="d-flex edit-input-field-div">
                                                                            <div class="input-group  drive_link"
                                                                                style="width: 316px;border: none;">

                                                                                <a href="{{ $lead->drive_link }}"
                                                                                    style="width: 300px; word-wrap: break-word; font-size: 12px; color: blue; text-decoration: underline;"
                                                                                    target="_blank">
                                                                                    <span
                                                                                        style="width: 300px; word-wrap: break-word; font-size: 12px; color: blue; text-decoration: underline;">
                                                                                        {{ substr($lead->drive_link, 0, 60) }}{{ strlen($lead->drive_link) > 70 ? '...' : '' }}
                                                                                    </span>
                                                                                </a>

                                                                            </div>
                                                                            <div class="edit-btn-div">
                                                                                <a class="btn btn-dark  text-white px-2 py-2 edit-input"
                                                                                    name="drive_link"><i
                                                                                        class="ti ti-pencil"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    {{-- <a href="{{ $lead->drive_link }}"
                                                                        style="width: 300px; word-wrap: break-word; font-size: 14px; color: blue; text-decoration: underline;"
                                                                        target="_blank">
                                                                        <span
                                                                            style="width: 300px; word-wrap: break-word; font-size: 14px; color: blue; text-decoration: underline;">
                                                                            {{ substr($lead->drive_link, 0, 60) }}{{ strlen($lead->drive_link) > 70 ? '...' : '' }}
                                                                        </span>
                                                                    </a> --}}

                                                                </td>
                                                            </tr>



                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Lead Created') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->created_at }}
                                                                </td>
                                                            </tr>
                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Date of Last Activity') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                </td>
                                                            </tr>
                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Date of Next Activity') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ '' }}
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingcust">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsecust">
                                                {{ __('CUSTOMER INFORMATION') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsecust" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingcust">
                                            <div class="accordion-body">
                                                <!-- Accordion Content -->
                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 210px; font-size: 14px;">
                                                                    {{ __('Email Address') }}
                                                                </td>
                                                                {{-- <td class="email-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 email">
                                                                            {{ $lead->email }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="email"
                                                                                style="padding: 10px;"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td> --}}
                                                                <td>
                                                                    <a href="mailto:{{ $lead->email }}"
                                                                        style="font-size: 14px;">
                                                                        {{ $lead->email }}</a>
                                                                </td>
                                                            </tr>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Email Address (Referrer)') }}
                                                                    <i class="ti ti-help"></i>
                                                                </td>
                                                                <td class="referrer_email-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    <div class="d-flex edit-input-field-div">
                                                                        <div
                                                                            class="input-group border-0 referrer_email">
                                                                            {{ $lead->referrer_email }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="referrer_email"
                                                                                style="padding: 10px;"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ $lead->referrer_email }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 210px; font-size: 14px;">
                                                                    {{ __('Mobile Phone') }}
                                                                </td>
                                                                {{-- <td class="mobile_phone-td"
                                                                    style="padding-left: 10px; font-size: 14px;width:300px">

                                                                    <div class="d-flex edit-input-field-div">
                                                                        <div class="input-group border-0 mobile_phone">
                                                                            {{ $lead->mobile_phone }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="mobile_phone"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>

                                                                </td> --}}
                                                                <td>
                                                                    {{ $lead->phone }}
                                                                </td>
                                                            </tr>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 200px; font-size: 14px;">
                                                                    {{ __('Phone') }}
                                                                </td>
                                                                <td class="phone-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    <div class="d-flex edit-input-field-div">
                                                                        <div class="input-group border-0 phone">
                                                                            {{ $lead->phone }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="phone"
                                                                                style="padding: 10px;"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ $lead->phone }}
                                                                </td>
                                                            </tr>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 130px; font-size: 14px;">
                                                                    {{ __('Email Opted Out') }}
                                                                </td>

                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">


                                                                    <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <input type="checkbox" name=""
                                                                            id="" value=""
                                                                            class="mx-3 my-1">
                                                                        <button
                                                                            class="btn btn-sm btn-secondary edit-btn-data rounded-0 btn-effect-none"
                                                                            style="padding: 3px 6px;"><i
                                                                                class="ti ti-pencil"></i>
                                                                        </button>

                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <input type="checkbox" name=""
                                                                            id="" value=""
                                                                            class="mx-3 my-1">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingaddress">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseaddress">
                                                {{ __('CUSTOMER ADDRESS') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapseaddress"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingaddress">
                                            <div class="accordion-body">
                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 190PX; font-size: 14px;">
                                                                    {{ __('Address') }}
                                                                </td>
                                                                {{-- <td class="address-td"
                                                                    style="min-width: 2200px; font-size: 13px;padding-left:10px;">
                                                                    <div class="d-flex edit-input-field-div">
                                                                        <div class="input-group border-0 d-flex">
                                                                            {{ $lead->street . ' ' . $lead->city . ' ' . $lead->satate . ' ' . $lead->country }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary edit-btn-address rounded-0 btn-effect-none"
                                                                                style="padding: 7px"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td> --}}
                                                                <td>
                                                                    {{ $lead->street . ' ' . $lead->city . ' ' . $lead->satate . ' ' . $lead->country }}

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeynote">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeynote">
                                                {{ __('KEYNOTE') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeynote"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeynote">
                                            <div class="accordion-body">
                                                <div class="table-responsive mt-1" style="margin-left: 10px;">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 250px; font-size: 14px;">
                                                                    {{ __('Description') }}
                                                                </td>
                                                                <td class=""
                                                                    style="width:5200px; padding-left:15px; text-align: justify; font-size: 14px;">
                                                                    {{ $lead->keynotes }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeytag">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytag">
                                                {{ __('TAG LIST') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeytag"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeytag">
                                            <div class="accordion-body">
                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width:200px;font-size: 14px;">
                                                                    {{ __('Tag List') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px;">
                                                                    <a href="" style="font-size: 14px;">
                                                                        {{ '' }}
                                                                        {{ __('Change') }}</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="accordion-item d-none">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeydetails">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeydetails">
                                                {{ __('LEAD CONVERSION DETAILS') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeydetails"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeydetails">
                                            <div class="accordion-body">
                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200PX; font-size: 14px;">
                                                                    {{ __('Converted Contact') }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200PX; font-size: 14px;">
                                                                    {{ __('Converted Organization') }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200PX; font-size: 14px;">
                                                                    {{ __('Converted Opportunity') }}
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

                            {{-- Details Pill End --}}
                            <div class="tab-pane fade" id="pills-related" role="tabpanel"
                                aria-labelledby="pills-related-tab">

                                <div >








































                                    <div id="discussion_note">
                                        <div>

                                            @can('manage notes')
                                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                                    <!-- Open Accordion Item -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="panelsStayOpen-headingnote">
                                                            <button class="accordion-button p-2" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapsenote">
                                                                {{ __('Notes') }}
                                                            </button>
                                                        </h2>
                                                        <div id="panelsStayOpen-collapsenote"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="panelsStayOpen-headingnote">
                                                            <div class="accordion-body">


                                                                <div class="">

                                                                    <div>


                                                                        <div class="card position-relative"
                                                                            id="leadsNoteForm">
                                                                            {{ Form::model($lead, ['route' => ['leads.notes.store', $lead->id], 'method' => 'POST', 'id' => 'create-notes', 'style' => 'z-index: 9999999 !important;']) }}
                                                                            <textarea class="form-control" style="height: 120px;" name="description" id="description"
                                                                                placeholder="Click here add your Notes Comments..."></textarea>
                                                                            <input type="hidden" id="note_id"
                                                                                value="" name="note_id">
                                                                            <div class="row justify-content-end indivbtn">
                                                                                <div class="col-auto ">
                                                                                    <button class="btn btn-dark text-white"
                                                                                        id="SaveDiscussion">Save</button>
                                                                                </div>
                                                                            </div>
                                                                            {{ Form::close() }}
                                                                        </div>
                                                                        <div class="card-body px-0 py-0">
                                                                            @php
                                                                                $notesQuery = \App\Models\LeadNote::where(
                                                                                    'lead_id',
                                                                                    $lead->id,
                                                                                );

                                                                                if (
                                                                                    \Auth::user()->type !=
                                                                                        'super admin' &&
                                                                                    \Auth::user()->type !=
                                                                                        'Project Director' &&
                                                                                    \Auth::user()->type !=
                                                                                        'Project Manager'
                                                                                ) {
                                                                                    $notesQuery->where(
                                                                                        'created_by',
                                                                                        \Auth::user()->id,
                                                                                    );
                                                                                }

                                                                                $notes = $notesQuery
                                                                                    ->orderBy('created_at', 'DESC')
                                                                                    ->get();
                                                                            @endphp

                                                                            <span
                                                                                class="list-group list-group-flush mt-2 note-tbody">

                                                                                @foreach ($notes as $note)
                                                                                    <div
                                                                                        style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                                        <div
                                                                                            class="row my-2 justify-content-between ps-4">
                                                                                            <div class="col-12 my-2">
                                                                                                <p class="text-dark"
                                                                                                    style="font-size: 18px;">
                                                                                                    {!! $note->description !!}
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-8">
                                                                                                <div
                                                                                                    class="row align-items-center">
                                                                                                    {{-- <div class="col-2 text-center">

                                                                                            </div> --}}
                                                                                                    <div class="col-8">
                                                                                                        <p
                                                                                                            class="mb-0 text-secondary">
                                                                                                            {{ \App\Models\User::where('id', $note->created_by)->first()->name }}
                                                                                                        </p>
                                                                                                        <p class="mb-0">
                                                                                                            {{ optional(App\models\User::find($note->created_by))->type }}
                                                                                                        </p>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-4 text-end px-1">
                                                                                                @php
                                                                                                    $dateTime = new DateTime(
                                                                                                        $note->created_at,
                                                                                                    );
                                                                                                @endphp
                                                                                                <p>{{ $dateTime->format('Y-m-d H:i:s') }}
                                                                                                </p>
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="d-flex gap-1 justify-content-end pb-2 px-3"
                                                                                            id="dellhover">
                                                                                            <div class="btn btn-outline-dark text-dark textareaClassedit"
                                                                                                data-note-id="{{ $note->id }}"
                                                                                                id="editable"
                                                                                                style="font-size: ;">Edit
                                                                                            </div>

                                                                                            <div class="delete-notes btn btn-dark  text-white"
                                                                                                id="editable"
                                                                                                style="font-size: ;"
                                                                                                data-note-id="{{ $note->id }}">
                                                                                                Delete</div>
                                                                                        </div>

                                                                                    </div>
                                                                                @endforeach

                                                                            </span>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endcan
                                            @can('manage task')
                                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                                    <!-- Open Accordion Item -->
                                                    <div class="accordion-item">
                                                        <h2 class="d-flex justify-between align-items-center accordion-header"
                                                            id="panelsStayOpen-headingnote">
                                                            <button class="accordion-button px-2 py-3 " type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapsetasks">

                                                                <div style="position: absolute;right: 27px;z-index: 9999;">
                                                                    @can('create task')
                                                                        <a data-size="lg"
                                                                            data-url="/organiation/1/task?type=lead&typeid={{ $lead->id }}"
                                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                            title="{{ __('Add Task') }}"
                                                                            class="btn p-2 text-white"
                                                                            style="background-color: #313949; color: #fff !important;">
                                                                            <i class="ti ti-plus"></i>
                                                                        </a>
                                                                    @endcan
                                                                </div>
                                                                <span>
                                                                    {{ __('Tasks') }}
                                                                </span>
                                                            </button>

                                                        </h2>

                                                        <div id="panelsStayOpen-collapsetasks"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="panelsStayOpen-headingnote">
                                                            <div class="accordion-body">
                                                                @if (!empty($tasks) && $tasks->count() > 0)
                                                                    @php
                                                                        $section = 1;
                                                                        $section2 = 1;
                                                                    @endphp
                                                                    @foreach ($tasks as $task)
                                                                        @if ($task->status == 1)
                                                                            <div class="accordion"
                                                                                id="accordionPanelsStayOpenExample">
                                                                                <div class="accordion-item">
                                                                                    @if ($section == 1)
                                                                                        <h2 class="accordion-header"
                                                                                            id="panelsStayOpen-headingOnedds">
                                                                                            <button
                                                                                                class="accordion-button"
                                                                                                type="button"
                                                                                                data-bs-toggle="collapse"
                                                                                                data-bs-target="#panelsStayOpen-collapseOnedds"
                                                                                                aria-expanded="true"
                                                                                                aria-controls="panelsStayOpen-collapseOnedds">
                                                                                                {{ $section == 1 ? 'Closed Activity' : '' }}
                                                                                            </button>
                                                                                        </h2>
                                                                                        @foreach ($tasks as $task3)
                                                                                            @if ($task3->status == 1)
                                                                                                <div id="panelsStayOpen-collapseOnedds"
                                                                                                    class="accordion-collapse collapse"
                                                                                                    aria-labelledby="panelsStayOpen-headingOnedds">
                                                                                                    <div
                                                                                                        class="accordion-body">
                                                                                                        {{--  --}}
                                                                                                        <div
                                                                                                            style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                                                            <div
                                                                                                                class="row my-2 justify-content-between  ps-4">
                                                                                                                <div
                                                                                                                    class="col-12 my-2">
                                                                                                                    <p class="text-dark"
                                                                                                                        style="font-size: 18px;">
                                                                                                                        <span
                                                                                                                            style="cursor:pointer"
                                                                                                                            class="task-name hyper-link"
                                                                                                                            @can('view task') onclick="openSidebar('/get-task-detail?task_id=<?= $task->id ?>')" @endcan
                                                                                                                            data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                                <div
                                                                                                                    class="col-8">
                                                                                                                    <div
                                                                                                                        class="row align-items-center">

                                                                                                                        <div
                                                                                                                            class="col-8">
                                                                                                                            <p
                                                                                                                                class="mb-0 text-secondary">
                                                                                                                            <p class="text-muted text-sm"
                                                                                                                                style="font-size: 18px;">
                                                                                                                                <i class="step__icon fa fa-user"
                                                                                                                                    aria-hidden="true"></i>
                                                                                                                                {{ optional(\App\Models\User::where('id', $task->assigned_to)->first())->name }}
                                                                                                                            </p>
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="col-8">
                                                                                                                            <span
                                                                                                                                class="d-flex mt-0">
                                                                                                                                <p>Status
                                                                                                                                </p>
                                                                                                                                <p class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ml-2"
                                                                                                                                    style="font-size: 10px;">
                                                                                                                                    {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                                                </p>
                                                                                                                            </span>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div
                                                                                                                    class="col-4 text-end px-1">
                                                                                                                    <p>{{ $task->created_at }}
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="d-flex gap-1 justify-content-end pb-2 px-3"
                                                                                                                id="dellhover">
                                                                                                                <button
                                                                                                                    class="btn btn-outline-dark text-dark textareaClassedit"
                                                                                                                    data-size="lg"
                                                                                                                    data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                                    data-ajax-popup="true"
                                                                                                                    data-bs-toggle="tooltip"
                                                                                                                    title="{{ __('Update Task') }}"
                                                                                                                    id="editable"
                                                                                                                    style="font-size: ;">Edit</button>

                                                                                                                <div class="btn btn-dark  text-white"
                                                                                                                    id="editable"
                                                                                                                    style="font-size: ;"
                                                                                                                    onclick="deleteTask({{ $task->id }}, {{ $lead->id }}, 'lead');">
                                                                                                                    Delete
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                {{--  --}}
                                                                                </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>

                                                        @php
                                                            $section++;
                                                        @endphp
                                                    @elseif ($task->status == 0)
                                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                                            <div class="accordion-item">
                                                                @if ($section2 == 1)
                                                                    <h2 class="accordion-header"
                                                                        id="panelsStayOpen-headingOneddsd">
                                                                        <button class="accordion-button" type="button"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#panelsStayOpen-collapseOneddsd"
                                                                            aria-expanded="true"
                                                                            aria-controls="panelsStayOpen-collapseOneddsd">
                                                                            {{ $section2 == 1 ? 'Open Activity' : '' }}
                                                                        </button>
                                                                    </h2>

                                                                    @foreach ($tasks as $task)
                                                                        @if ($task->status == 0)
                                                                            <div id="panelsStayOpen-collapseOneddsd"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="panelsStayOpen-headingOneddsd">
                                                                                <div class="accordion-body">
                                                                                    {{--  --}}
                                                                                    <div
                                                                                        style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                                        <div
                                                                                            class="row my-2 justify-content-between  ps-4">
                                                                                            <div class="col-12 my-2">
                                                                                                <p class="text-dark"
                                                                                                    style="font-size: 18px;">
                                                                                                    <span
                                                                                                        style="cursor:pointer"
                                                                                                        class="task-name hyper-link"
                                                                                                        @can('view task') onclick="openSidebar('/get-task-detail?task_id=<?= $task->id ?>')" @endcan
                                                                                                        data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-8">
                                                                                                <div
                                                                                                    class="row align-items-center">

                                                                                                    <div class="col-8">
                                                                                                        <p
                                                                                                            class="mb-0 text-secondary">
                                                                                                        <p class="text-muted text-sm"
                                                                                                            style="font-size: 18px;">
                                                                                                            <i class="step__icon fa fa-user"
                                                                                                                aria-hidden="true"></i>
                                                                                                            {{ optional(\App\Models\User::where('id', $task->assigned_to)->first())->name }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                    <div class="col-8">
                                                                                                        <span
                                                                                                            class="d-flex mb-0">
                                                                                                            <p>Status
                                                                                                            </p>
                                                                                                            <p class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ml-2"
                                                                                                                style="font-size: 10px;">
                                                                                                                {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                            </p>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-4 text-end px-1">
                                                                                                <p>{{ $task->created_at }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex gap-1 justify-content-end pb-2 px-3"
                                                                                            id="dellhover">
                                                                                            <button
                                                                                                class="btn btn-outline-dark text-dark textareaClassedit "
                                                                                                data-size="lg"
                                                                                                data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                data-ajax-popup="true"
                                                                                                data-bs-toggle="tooltip"
                                                                                                title="{{ __('Update Task') }}"
                                                                                                id="editable"
                                                                                                style="font-size: ;">Edit</button>

                                                                                            <div class="btn btn-dark  text-white"
                                                                                                id="editable"
                                                                                                style="font-size: ;"
                                                                                                onclick="deleteTask({{ $task->id }}, {{ $lead->id }}, 'lead');">
                                                                                                Delete</div>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{--  --}}
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif

                                                            </div>
                                                        </div>

                                                        @php
                                                            $section2++;
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                    </div>
                                            </div>
                                        @endcan
                                        <div class="accordion d-none" id="accordionPanelsStayOpenExample">
                                            <!-- Open Accordion Item -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingdisc">
                                                    <button class="accordion-button p-2" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapsedisc">
                                                        {{ __('Discussion') }}
                                                    </button>
                                                </h2>

                                                <div id="panelsStayOpen-collapsedisc"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingdisc">
                                                    <div class="accordion-body">


                                                        <div class="">

                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-header ">
                                                                        <div class="d-flex justify-content-end">
                                                                            <div class="float-end">
                                                                                <a data-size="lg"
                                                                                    data-url="{{ route('leads.discussions.create', $lead->id) }}"
                                                                                    data-ajax-popup="true"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('Add Message') }}"
                                                                                    class="btn p-2 text-white"
                                                                                    style="background-color: #313949;">
                                                                                    <i class="ti ti-plus"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body px-0">
                                                                        <ul class="list-group list-group-flush mt-2">
                                                                            @if (!$lead->discussions->isEmpty())
                                                                                @foreach ($lead->discussions as $discussion)
                                                                                    <li class="list-group-item px-0"
                                                                                        style="list-style: none;">
                                                                                        <div
                                                                                            class="d-block d-sm-flex align-items-start">
                                                                                            <img src="@if ($discussion->user->avatar) {{ asset('/storage/uploads/avatar/' . $discussion->user->avatar) }} @else {{ asset('/storage/uploads/avatar/avatar.png') }} @endif"
                                                                                                class="img-fluid wid-40 me-3 mb-2 mb-sm-0"
                                                                                                alt="image">
                                                                                            <div class="w-100">
                                                                                                <div
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <div
                                                                                                        class="mb-3 mb-sm-0">
                                                                                                        <h6
                                                                                                            class="mb-0">
                                                                                                            {{ $discussion->comment }}
                                                                                                        </h6>
                                                                                                        <span
                                                                                                            class="text-muted text-sm">{{ $discussion->user->name }}</span>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="form-check form-switch form-switch-right mb-2">
                                                                                                        {{ $discussion->created_at->diffForHumans() }}
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                @endforeach
                                                                            @else
                                                                                <li class="text-center">
                                                                                    {{ __(' No Data Available.!') }}
                                                                                </li>
                                                                            @endif
                                                                        </ul>
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
                    <!-- End of Open Accordion Item -->

                    <div class="tab-pane fade" id="pills-activity" role="tabpanel"
                        aria-labelledby="pills-activity-tab">

                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <!-- Open Accordion Item -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingactive">
                                    <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseactive">
                                        {{ __('Timeline') }}
                                    </button>
                                </h2>

                                <div id="panelsStayOpen-collapseactive" class="accordion-collapse collapse show"
                                    aria-labelledby="panelsStayOpen-headingactive">
                                    <div class="accordion-body">
                                        <!-- Accordion Content -->


                                        <div class="mt-1">
                                            <div class="timeline-wrapper">
                                                <ul class="StepProgress">
                                                    @foreach ($log_activities as $activity)
                                                        @php
                                                            $remark = json_decode($activity->note);
                                                        @endphp

                                                        <li class="StepProgress-item is-done">
                                                            <div class="bold time">{{ $activity->created_at }}</div>
                                                            <div class="bold"
                                                                style="text-align: left; margin-left: 80px;">
                                                                <p class="bold"
                                                                    style="margin-bottom: 0rem; color: #000000;">
                                                                    {{ $remark->title }}</p>
                                                                <p class="m-0">{{ $remark->message }}</p>
                                                                <span class="text-muted text-sm"
                                                                    style="cursor: pointer;"
                                                                    @can('show employee') onclick="openSidebar('/user/employee/{{ isset($activity->created_by) ? $activity->created_by : '' }}/show')"  @endcan><i
                                                                        class="step__icon fa fa-user me-2"
                                                                        aria-hidden="true"></i>{{ isset($users[$activity->created_by]) ? $users[$activity->created_by] : '' }}</span>
                                                            </div>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </div>
                                        <!-- End of Accordion Content -->
                                    </div>
                                </div>
                            </div>
                            <!-- End of Open Accordion Item -->

                            <!-- Add More Accordion Items Here -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script>
    $(document).ready(function() {
        $('.textareaClass').click(function() {
            $('#textareaID, .textareaClass').toggle("slide");
        });

        $('#create-notes').submit(function(event) {
            event.preventDefault(); // Prevents the default form submission
            $('#textareaID, .textareaClass').toggle("slide");
        });

        $('#cancelNote').click(function() {
            $('textarea[name="description"]').val('');
            $('#note_id').val('');
            $('#textareaID, .textareaClass').toggle("slide");
        });
        $('.textareaClassedit').click(function() {
            var dataId = $(this).data('note-id');
            var dataNote = $(this).data('note');
            $('textarea[name="description"]').val(dataNote);
            $('#note_id').val(dataId);
            $('#textareaID, #dellhover, .textareaClass').show();
            $('.textareaClass').toggle("slide");
        });
    });
</script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            height: 150, // Set the height to 600 pixels
            focus: true,
            toolbar: [
                ['link', ['link']],
            ]
        });
    });
</script>
<script>
    $('.textareaClassedit').click(function() {
        var dataId = $(this).data('note-id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ url('update/from/leadsNoteForm') }}",
            method: 'POST',
            data: {
                id: dataId
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },

            success: function(data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    $("#leadsNoteForm").html('');
                    $("#leadsNoteForm").html(data.html);
                } else {
                    console.error('Server returned an error:', data.message);
                }


            },

            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });

    });
</script>
