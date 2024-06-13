<style>
    .editable:hover {
        border: 1px solid rgb(136, 136, 136);
    }

    /* table tr td {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
        font-size: 12px;
    } */

    table tr {
        font-size: 14px;
    }

    .card-body {
        padding: 25px 15px !important;
    }

    .edit-input-field-div {
        background-color: #ffffff;
        border: 0px solid rgb(224, 224, 224);
        max-width: max-content;
        max-height: 30px;
    }


    .edit-input-field-div .input-group {
        min-width: 70px;
        min-height: 30px;
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
        padding: 7px;
    }

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

    .btn-sm {
        width: 30px;
        height: 30px;
    }
</style>
<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0">
    <div class="row">
        <div class="col-sm-12 pe-0">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrap justify-content-between align-items-center py-1 px-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" class="">
                    </div>

                    <input type="hidden" name="deal-id" class="deal-id" value="{{ $deal->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Admission') }}</p>
                        <div class="d-flex align-items-baseline ">
                            @if (strlen($deal->name) > 40)
                                <h4>{{ substr($deal->name, 0, 40) }}...</h4>
                            @else
                                <h5 class="fw-bold">{{ $deal->name }}</h5>
                            @endif

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                    @if (\Auth::user()->can('edit deal'))
                        @if (!empty($lead->phone))
                            <a href="https://wa.me/{{ formatPhoneNumber($lead->phone) }}?text=Hello ! Dear {{ $deal->name }}"
                                target="_blank" data-size="lg" data-bs-toggle="tooltip"
                                data-bs-title="{{ __('Contact on whatsapp') }}" class="btn btn-dark text-white"
                                style="background-color: #313949">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        @endif
                    @endif
                    @if (\Auth::user()->can('edit deal'))
                        <a href="#" data-size="lg" data-url="{{ route('deals.edit', $deal->id) }}"
                            data-ajax-popup="true" data-bs-toggle="tooltip" bs-original-title="{{ __('Update Deal') }}"
                            title="{{ __('Update Admission') }}" class="btn px-2 py-2 text-white"
                            style="background-color: #313949;">
                            <i class="ti ti-pencil"></i>
                        </a>
                    @endif

                    @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete deal'))
                        <a onclick="DeleteDetail('{{ route('deals.destroy', $deal->id)}}', {{ $deal->id }})"
                            class="btn py-2 px-2 btn-danger text-white">
                            <i class="ti ti-trash"></i>
                        </a>
                    @endif

                </div>


                <!-- New row added here -->
                <div class="row w-100 mt-3 border-top">
                    @php $counter = 0; @endphp
                    <div class="col-12 px-5 py-2 d-flex">
                        @foreach(\App\Models\LeadTag::whereIn('id', explode(',', $deal->tag_ids))->get() as $tag)
                            @if ($tag->tag != '')
                                @if ($counter % 5 == 0 && $counter != 0)
                                    </div><div class="col-12 px-5 py-2 d-flex">
                                @endif
                                <div class="d-flex ml-1">
                                    <span class="" style="font-size:13px;background-color: #419de4;color:white;padding: 5px 21px;border: none;border-radius: 0px 30px 30px 0px;font-size: 11.7px;">{{ limit_words($tag->tag, 1.5) }}</span>
                                    <span class="d-flex mt-1">
                                        <a style="width: 31px;height: 27px;border-redius;border-radius: 8px;" class="btn px-2 py-1 p-auto btn-sm text-white bg-dark mx-1 tag-badge"
                                        data-tag-id="{{ $tag->id }}"
                                        data-lead-id="{{ $lead->id }}"
                                        data-deal-id="{{ $deal->id }}"
                                        ><i class="ti ti-pencil"></i></a>
                                        <input type="hidden" value="{{ $deal->id }}" name="lead_id" id="lead_id">
                                        <input type="hidden" value="{{ $tag->id }}" name="old_tag_id" id="old_tag_id">
                                        <a style="width: 31px;height: 27px;border-redius;border-radius: 8px;" class="btn px-2 py-1 p-auto btn-sm text-white bg-danger" onclick="deleteTage(this,{{ $deal->id }},{{ $tag->id }})"><i class="ti ti-trash"></i></a>
                                    </span>
                                </div>
                                @php $counter++; @endphp
                            @endif
                        @endforeach
                    </div>
                </div>
                {{--  New row added end --}}
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small>{{ __('Branch') }}</small>
                    <span class="font-weight-bolder">
                        {{ !empty($deal->branch_id) && isset($branches[$deal->branch_id]) ? $branches[$deal->branch_id] : '' }}
                    </span>
                </div>
                <div class="">
                    <small>{{ __('Agency') }}</small>
                    <span>
                        {{ !empty($deal->organization_id) && isset($organizations[$deal->organization_id]) ? $organizations[$deal->organization_id] : '' }}
                    </span>
                </div>
                <div class="">
                    <small>{{ __('Institute') }}</small>

                    <span>
                        {{ isset($application->university_id) && $universities[$application->university_id] ? $universities[$application->university_id] : '' }}

                    </span>
                </div>
                <div class="">
                    <small>{{ __('User Responsible') }}</small>
                    <span>
                        {{ isset($users[$deal->assigned_to]) ? $users[$deal->assigned_to] : '' }}
                    </span>
                </div>

                <div class="">
                    <small>{{ __('Stage') }}</small>
                    <span>{{ isset($stages[$deal->stage_id]) ? $stages[$deal->stage_id] : '' }}</span>
                </div>

                <div class="">
                    <small>{{ __('Admission Owner') }}</small>
                    <span>{{ $users[$lead->created_by ?? ''] ?? '' }}</span>
                </div>
            </div>

            {{-- Stages --}}
            {{-- <div class="stages my-2 ">
                <h2 class="mb-3">Deal STATUS: <span class="d-inline-block fw-light">{{ 'List' }}</span>
            </h2>
            <div class="wizard mb-2">
                <?php $done = true; ?>
                @forelse($stages as $stage)
                <?php
                if ($lead->stage->name == $stage->name) {
                    $done = false;
                }

                $is_missed = false;

                if (!empty($stage_histories) && !in_array($stage->id, $stage_histories) && $stage->id <= max($stage_histories)) {
                    $is_missed = true;
                }
                ?>

                <a type="button" data-lead-id="{{ $lead->id }}" data-stage-id="{{ $stage->id }}" class="lead_stage {{ $lead->stage->name == $stage->name ? 'current' : ($done == true ? 'done' : '') }} " style="font-size:13px">{{ $stage->name }}  @if ($is_missed == true)<i class="fa fa-close text-danger"></i>@endif </a>
                @empty
                @endforelse
            </div>
        </div> --}}
            <div class="stages my-2  bg-white">
                <h2 class="mb-3">Deal STATUS: <span class="d-inline-block fw-light">{{ 'List' }}</span>
                </h2>
                <div class="wizard mb-2"
                    style="background: #EFF3F7;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        };">


                    <?php $done = true; ?>
                    @foreach ($stages as $key => $stage)
                        <?php
                        if ($deal->stage->name == $stage) {
                            $done = false;
                        }

                        $is_missed = false;

                        if (!empty($stage_histories) && !in_array($key, $stage_histories) && $key <= max($stage_histories)) {
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

                        <a type="button" data-lead-id="{{ $deal->id }}" data-stage-id="{{ $key }}"
                            class="lead_stage {{ Auth::user()->can('edit stage deal') ? 'deal_stage' : '' }} {{ $is_missed == true ? 'missedup' : ($deal->stage->name == $stage ? 'current' : ($done == true ? 'done' : '')) }}"
                            style="font-size:12px">{{ $stage }} @if ($is_missed == true)
                                <i class="fa fa-close text-danger"></i>
                            @endif
                        </a>
                    @endforeach

                </div>
            </div>


            <div class="lead-content my-2">

                <div class="card">
                    <div class="card-header p-1 bg-white">
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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link" id="pills-activity-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-activity" type="button" role="tab"
                                    aria-controls="pills-activity" aria-selected="false">{{ __('Timeline') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2 bg-white">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                aria-labelledby="pills-details-tab">

                                <div class="accordion accordion-flush bg-white" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeyone">
                                                {{ __('Details') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyone"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $deal->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Admission Name') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $deal->name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Passport Number') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    @php
                                                                    $client = \App\Models\User::join('client_deals', 'client_deals.client_id', 'users.id')->where('client_deals.deal_id', $deal->id)->first();
                                                                    $passport_number = isset($client->passport_number) ? $client->passport_number : '';
                                                                    @endphp
                                                                    {{ $passport_number }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $Brands[$deal->brand_id] }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Region') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $regions[$deal->region_id] ?? '' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Intake Month') }}
                                                                </td>
                                                                <td class="intake_month-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 intake_month d-flex align-items-center">
                                                                        {{ $deal->intake_month }}
                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="intake_month"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                    {{ $deal->intake_month }}
                                                                </td>
                                                            </tr>



                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Intake Year') }}
                                                                </td>
                                                                <td class="intake_year-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 intake_year d-flex align-items-center">
                                                                        {{ $deal->intake_year }}
                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="intake_year"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                    {{ $deal->intake_year }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Contact') }}
                                                                </td>
                                                                <td class="type-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($users[$clientDeal->client_id]) ? $users[$clientDeal->client_id] : '' }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Current Status') }}
                                                                </td>
                                                                <td class="stage_id-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 stage_id d-flex align-items-center">
                                                                        {{ $stages[$deal->stage_id] }}
                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="stage_id"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                    {{ $stages[$deal->stage_id] }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('User Responsible') }}
                                                                </td>
                                                                <td class="assigned_to-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 assigned_to d-flex align-items-center">
                                                                        {{ isset($users[$deal->assigned_to]) ? $users[$deal->assigned_to] : '' }}
                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="assigned_to"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                    {{ isset($users[$deal->assigned_to]) ? $users[$deal->assigned_to] : '' }}

                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Drive Link') }}
                                                                </td>
                                                                <td class="drive_link-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    <div
                                                                        class="d-flex align-items-center edit-input-field-div">
                                                                        <div
                                                                            class="input-group border-0 drive_link d-flex align-items-center">
                                                                            @if (isset($deal->drive_link) && !empty($deal->drive_link))
                                                                                <a href="{{ $deal->drive_link }}"
                                                                                    target="blank"
                                                                                    style="font-size: 14px; color: rgb(46, 134, 249);">
                                                                                    {{ $deal->drive_link }} </a>
                                                                            @else
                                                                                {{ isset($deal->drive_link) ? $deal->drive_link : '' }}
                                                                            @endif

                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="drive_link"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    {{-- @if (isset($deal->drive_link) && !empty($deal->drive_link))
                                                                <a href="{{ $deal->drive_link }}" target="blank" style="font-size: 14px; color: rgb(46, 134, 249);">
                                                                    {{ $deal->drive_link }}
                                                                </a>
                                                                @else
                                                                <a href="{{ $deal->drive_link }}">
                                                                {{ isset($deal->drive_link) ? $deal->drive_link : '' }} --}}
                                                                    </a>
                                                                    {{-- @endif --}}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeytwo">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytwo">
                                                {{ __('ADDITIONAL INFORMATION') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeytwo"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeytwo">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 102px; font-size: 14px;">
                                                                    {{ __('Institute') }}
                                                                </td>
                                                                <td class="email-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($application->university_id) && $universities[$application->university_id] ? $universities[$application->university_id] : '' }}
                                                                </td>
                                                            </tr>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Institution Link') }}
                                                                </td>
                                                                <td class="website-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Agency') }}
                                                                </td>
                                                                <td class="organization_id-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 organization_id d-flex align-items-center">
                                                                        {{ !empty($deal->organization_id) && isset($organizations[$deal->organization_id]) ? $organizations[$deal->organization_id] : '' }}
                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="organization_id"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                    {{ !empty($deal->organization_id) && isset($organizations[$deal->organization_id]) ? $organizations[$deal->organization_id] : '' }}

                                                                </td>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Agency Link') }}
                                                                </td>
                                                                <td class="linkedin-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    @if (!empty($deal->organization_id) && isset($organizations[$deal->organization_id]))
                                                                        @php
                                                                            $link = \App\Models\Organization::where(
                                                                                'user_id',
                                                                                $deal->organization_id,
                                                                            )->first()->website;
                                                                        @endphp

                                                                        <a href="{{ $link }}"
                                                                            class="text-primary"
                                                                            style="font-size: 14px;">{{ $link }}</a>
                                                                    @endif
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class="branch_id-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 branch_id d-flex align-items-center">
                                                                        {{ !empty($deal->branch_id) && isset($branches[$deal->branch_id]) ? $branches[$deal->branch_id] : '' }}
                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="branch_id"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                    {{ !empty($deal->branch_id) && isset($branches[$deal->branch_id]) ? $branches[$deal->branch_id] : '' }}

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Phone') }}
                                                                </td>
                                                                <td class="twitter-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->phone ?? '' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Email') }}
                                                                </td>
                                                                <td class="twitter-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->email ?? '' }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Admission Created') }}
                                                                </td>
                                                                <td class="twitter-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $deal->created_at }}
                                                                </td>
                                                            </tr>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Date of Next Activity') }}
                                                                </td>
                                                                <td class="twitter-td"
                                                                    style="padding-left: 10px; font-size: 14px;">


                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 twitter d-flex align-items-center">

                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="twitter"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                </td>
                                                            </tr>

                                                            <tr class="d-none">
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Date of Last Activity') }}
                                                                </td>
                                                                <td class="twitter-td"
                                                                    style="padding-left: 10px; font-size: 14px;">


                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div">
                                                                    <div class="input-group border-0 twitter d-flex align-items-center">

                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="twitter"><i class="ti ti-pencil"></i></button>
                                                                    </div>
                                                                </div> --}}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>



                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeydesc">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeydesc">
                                                {{ __('DESCRIPTION INFORMATION') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeydesc"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeydesc">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Description') }}
                                                                </td>
                                                                <td class="description-td"
                                                                    style="min-height: 50px; padding-left:15px; width: 550px; text-align: justify; font-size: 14px;">

                                                                    {{-- <div class="d-flex align-items-center edit-input-field-div" style="min-height:30px; max-height: 200px; overflow-y: scroll; width: 100%;">
                                                                    <div class="input-group border-0 d-flex align-items-center p-2">
                                                                        {{ $deal->description }}
                                                                    </div>
                                                                    <div class="edit-btn-div">
                                                                        <button class="btn btn-sm btn-secondary edit-input rounded-0 btn-effect-none" name="description">
                                                                            <i class="ti ti-pencil"></i>
                                                                        </button>
                                                                    </div>
                                                                </div> --}}
                                                                    {{ $deal->description }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
                                                <div class="d-flex justify-content-end align-items-center p-2 pb-0">
                                                    <div class="float-end">
                                                        @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('create application'))
                                                            <a data-size="lg"
                                                                data-url="{{ route('deals.application.create', $deal->id) }}"
                                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                title="{{ __('Create Application') }}"
                                                                class="btn btn-dark p-2 text-white">
                                                                <i class="ti ti-plus" style="color: white"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div style="max-height: 400px; overflow-y: auto;">
                                                    <table class="table table-hover">
                                                        <thead
                                                            style="background-color:rgba(0, 0, 0, .08); font-weight: bold;color:#000000">
                                                            <tr>
                                                                <td>{{ __('Name') }}</td>
                                                                <td>{{ __('Application Key') }}</td>
                                                                <td>{{ __('University') }}</td>
                                                                <td>{{ __('Intake') }}</td>
                                                                <td>{{ __('Applications Stage') }}</td>
                                                                <td>{{ __('Action') }}</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($applications as $app)
                                                                <tr style="background-color: rgb(255, 255, 255);">
                                                                    <td>
                                                                        <span style="cursor:pointer"
                                                                            class="hyper-link"
                                                                            onclick="openSidebar('/deals/'+{{ $app->id }}+'/detail-application')">
                                                                            {{ $app->name }}
                                                                        </span>
                                                                    </td>
                                                                    <td>{{ $app->application_key }}</td>
                                                                    <td>{{ $universities[$app->university_id] ?? '' }}
                                                                    </td>
                                                                    <td>{{ $app->intake }}</td>
                                                                    <td>{{ $stages[$app->stage_id] ?? '' }}</td>
                                                                    <td>
                                                                        <div
                                                                            class="d-flex justify-center align-items-center">
                                                                            @can('edit application')
                                                                                @if (
                                                                                    \Auth::user()->type == 'super admin' ||
                                                                                        \Auth::user()->type == 'Project Director' ||
                                                                                        \Auth::user()->type == 'Project Manager')
                                                                                    <a data-size="lg"
                                                                                        title="{{ __('Edit Application') }}"
                                                                                        href="#"
                                                                                        class="btn px-2 btn-dark text-white mx-1"
                                                                                        data-url="{{ route('deals.application.edit', $app->id) }}"
                                                                                        data-ajax-popup="true"
                                                                                        data-title="{{ __('Edit Application') }}"
                                                                                        data-toggle="tooltip"
                                                                                        data-original-title="{{ __('Edit') }}">
                                                                                        <i class="ti ti-edit"
                                                                                            style="color: white"></i>
                                                                                    </a>
                                                                                @endif
                                                                            @endcan

                                                                            @can('delete application')
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'route' => ['deals.application.destroy', $app->id],
                                                                                    'id' => 'delete-form-' . $app->id,
                                                                                    'class' => 'mb-0',
                                                                                ]) !!}
                                                                                <a href="#"
                                                                                    class=" btn px-2 bg-danger bs-pass-para"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('Delete') }}"><i
                                                                                        class="ti ti-trash text-white"></i></a>
                                                                                {!! Form::close() !!}
                                                                            @endcan
                                                                        </div>
                                                                    </td>
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


                            {{-- Details Pill End --}}
                            <div class="tab-pane fade" id="pills-related" role="tabpanel"
                                aria-labelledby="pills-related-tab">
                                <div class="block-items">
                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Contacts</div>
                                        <div class="block-item-count">{{ 1 }}</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block d-none" id="con-stats"
                                        title="1 Linked Contacts" data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Discussion</div>
                                        <div class="block-item-count discussion_count">{{ count($discussions) }}</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Notes</div>
                                        <div class="block-item-count">{{ count($notes) }}</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>


                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Organization</div>
                                        <div class="block-item-count">{{ !empty($deal->organization_id) ? 1 : 0 }}
                                        </div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div id="discussion_note">
                                        <div class="row">
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
                                                        <style>
                                                            .indivbtn {
                                                                position: absolute;
                                                                bottom: 10px;
                                                                right: 10px;
                                                                z-index: 1000;
                                                            }
                                                        </style>
                                                        <div id="panelsStayOpen-collapsenote"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="panelsStayOpen-headingnote">
                                                            <div class="accordion-body">
                                                                <div class="position-relative">
                                                                    {{ Form::model($deal, ['route' => ['deals.notes.store', $deal->id], 'method' => 'POST', 'id' => 'create-notes', 'style' => 'z-index: 9999999 !important;']) }}
                                                                    <textarea name="description" id="description" style="height: 120px;" class="form-control"
                                                                        placeholder="Click here add your Notes Comments..."></textarea>
                                                                    <input type="hidden" id="note_id" name="note_id">
                                                                    <div class="row justify-content-end indivbtn">
                                                                        <div class="col-auto ">
                                                                            <button
                                                                                class="btn btn-dark text-white create-notes-btn">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    {{ Form::close() }}
                                                                </div>


                                                                <div class="card-body px-0">
                                                                    <ul class="list-group list-group-flush mt-2 note-body">

                                                                        @foreach ($notes as $note)
                                                                            <div
                                                                                style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                                <div
                                                                                    class="row my-2 justify-content-between  ps-4">
                                                                                    <div class="col-12 my-2">
                                                                                        <p class="text-dark"
                                                                                            style="font-size: 18px;">
                                                                                            {{ $note->description }}</p>
                                                                                    </div>
                                                                                    <div class="col-8">
                                                                                        <div
                                                                                            class="row align-items-center">

                                                                                            <div class="col-8">
                                                                                                <p
                                                                                                    class="mb-0 text-secondary">
                                                                                                    {{ \App\Models\User::where('id', $note->created_by)->first()->name }}
                                                                                                </p>
                                                                                                <p class="mb-0">
                                                                                                    {{ \App\Models\User::where('id', $note->created_by)->first()->type }}
                                                                                                </p>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-4 text-end px-1">
                                                                                        <p>{{ $note->created_at }}</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex gap-1 justify-content-end pb-2 px-3"
                                                                                    id="dellhover">
                                                                                    <div class="btn btn-outline-dark text-dark textareaClassedit"
                                                                                        data-note="{{ $note->description }}"
                                                                                        data-note-id="{{ $note->id }}"
                                                                                        id="editable"
                                                                                        style="font-size: ;">Edit</div>

                                                                                    <div class="delete-notes btn btn-dark  text-white"
                                                                                        id="editable"
                                                                                        style="font-size: ;"
                                                                                        data-note-id="{{ $note->id }}">
                                                                                        Delete</div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </ul>
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
                                                        <h2 class="accordion-header" id="panelsStayOpen-headingnote">
                                                            <button class="accordion-button  px-2 py-3 " type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapsetasks">
                                                                {{ __('Tasks') }}
                                                                <div style="position: absolute;right: 27px;z-index: 9999;">
                                                                    @can('create task')
                                                                        <a data-size="lg"
                                                                            data-url="/organiation/1/task?type=deal&typeid={{ $deal->id }}"
                                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                            title="{{ __('Add Task') }}"
                                                                            class="btn p-2 btn-dark text-white">
                                                                            <i class="ti ti-plus" style="color: white"></i>
                                                                        </a>
                                                                    @endcan
                                                                </div>
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
                                                                    @foreach ($tasks as $task1)
                                                                        @if ($task1->status == 1)
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
                                                                                        @foreach ($tasks as $task)
                                                                                            @if ($task->status == 1)
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
                                                                                                                        {{ $task->name }}
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
                                                                                                                    onclick="deleteTask({{ $task->id }}, {{ $deal->id }}, 'deal');">
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
                                                    @elseif ($task1->status == 0)
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
                                                                                                    {{ $task->name }}
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
                                                                                                onclick="deleteTask({{ $task->id }}, {{ $deal->id }}, 'deal');">
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
                                            </div>

                                    @endcan
                                    </div>
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <!-- Open Accordion Item -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingcontact">
                                                <button class="accordion-button p-2" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#panelsStayOpen-collapsecontact">
                                                    {{ __('Contacts') }}
                                                </button>
                                            </h2>

                                            <div id="panelsStayOpen-collapsecontact"
                                                class="accordion-collapse collapse show"
                                                aria-labelledby="panelsStayOpen-headingcontact">
                                                <div class="accordion-body">
                                                    <div class="">

                                                        <div class="col-12">
                                                            <div class="card" style="box-shadow: none;">
                                                                <div class="card-body px-0"
                                                                    style="max-height: 300px; overflow-y: scroll;">
                                                                    <table class="table">
                                                                        <thead class="table-bordered">
                                                                            <tr>
                                                                                <th>Contact Name</th>
                                                                                <th>Email</th>
                                                                                <th>Created at</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $contact = \App\Models\User::where(
                                                                                    'id',
                                                                                    $clientDeal->client_id,
                                                                                )->first();
                                                                            @endphp

                                                                            <tr>
                                                                                <td>
                                                                                    <span style="cursor:pointer"
                                                                                        class="hyper-link"
                                                                                        onclick="openSidebar('/clients/'+{{ $contact->id }}+'/client_detail')">
                                                                                        {{ $contact->name }}
                                                                                    </span>
                                                                                </td>
                                                                                <td>{{ $contact->email }}</td>
                                                                                <td>{{ $contact->created_at }}
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
                                                                <div class="card-header px-0 pt-1 pb-3">
                                                                    <div
                                                                        class="d-flex justify-content-end align-items-center p-2 pb-0">
                                                                        <div class="float-end">
                                                                            <a data-size="lg"
                                                                                data-url="{{ route('deals.discussions.create', $deal->id) }}"
                                                                                data-ajax-popup="true"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ __('Add Discussion') }}"
                                                                                class="btn btn-sm text-white">
                                                                                <i class="ti ti-plus"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="card-body px-0"
                                                                    style="max-height: 300px; overflow-y: scroll;">
                                                                    <ul class="list-group list-group-flush mt-2">
                                                                        @foreach ($discussions as $discussion)
                                                                            <li class="list-group-item px-3">
                                                                                <div
                                                                                    class="d-block d-sm-flex align-items-start">
                                                                                    <img src="@if ($discussion['avatar'] && $discussion['avatar'] != '') {{ asset('/storage/uploads/avatar/' . $discussion['avatar']) }} @else {{ asset('/storage/uploads/avatar/avatar.png') }} @endif"
                                                                                        class="img-fluid wid-40 me-3 mb-2 mb-sm-0"
                                                                                        alt="image">
                                                                                    <div class="w-100">
                                                                                        <div
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <div class="mb-3 mb-sm-0">
                                                                                                <h5 class="mb-0">
                                                                                                    {{ $discussion['comment'] }}
                                                                                                </h5>
                                                                                                <span
                                                                                                    class="text-muted text-sm">{{ $discussion['name'] }}</span>
                                                                                            </div>
                                                                                            <div
                                                                                                class=" form-switch form-switch-right mb-4">
                                                                                                {{ $discussion['created_at'] }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
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
                                                    {{ __('Organization') }}
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
                                                                                <th scope="col">Phone
                                                                                </th>
                                                                                <th scope="col">Website Link
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $organization = \App\Models\User::where(
                                                                                    'id',
                                                                                    $deal->organization_id,
                                                                                )->first();
                                                                            @endphp

                                                                            @if ($organization)
                                                                                <tr>
                                                                                    <td>{{ $organization->name }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $organization->phone }}
                                                                                    </td>
                                                                                    <td><a href="{{ $organization->website }}"
                                                                                            class="">{{ $organization->website }}</a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif

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
                                                            <div class="bold time">
                                                                {{ $activity->created_at }}</div>
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

<style>
    #AdmissionCheck .modal-content {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

#AdmissionCheck .modal-header {
    border-bottom: none;
}

#AdmissionCheck .modal-title {
    font-weight: bold;
    font-size: 1.5rem;
    color: #007bff;
}

#AdmissionCheck .close {
    font-size: 1.25rem;
}

#AdmissionCheck .modal-body {
    padding: 2rem;
}

#AdmissionCheck .modal-footer {
    border-top: none;
}

#AdmissionCheck .btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
}

#AdmissionCheck .btn-outline-primary:hover {
    background-color: #007bff;
    color: #fff;
}

#AdmissionCheck .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

#AdmissionCheck .btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

#AdmissionCheck #AppendApplication {
    margin-top: 1rem;
}

#AdmissionCheck #AppendApplication li {
    margin: 0 0.5rem;
    padding: 0.5rem 1rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

#AdmissionCheck #AppendApplication li:hover {
    background-color: #e2e6ea;
}
.name-cell {
    max-width: 200px; /* Adjust the max-width as per your design */
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

</style>
<div class="modal" id="AdmissionCheck" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-primary">Admissions</h5>
                <button type="button" class="close text-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-0">Would you like to delete those applications?</p>
                <table class="table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>University</th>
                        <th>Intake</th>
                        <th>Stage</th>
                      </tr>
                    </thead>
                    <tbody id="AppendApplication">
                      
                    </tbody>
                  </table>
            </div>
            <div class="modal-footer border-0 justify-content-center d-flex">
                <a class="btn btn-outline-primary px-4 me-2" data-bs-dismiss="modal">Close</a>
                <span id="AppendApplicationDelete">
                    
                </span>
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
        });
    });
    $('.tag-badge').click(function() {
            $('#TagModalBody').html('');
            var tagId = $(this).data('tag-id');
            var leadId = $(this).data('lead-id');
            var dealId = $(this).data('deal-id');
            $('#UpdateTageModal').css('z-index', 99999);
            var selectOptions = <?php echo json_encode($tags); ?>;
            // Check if selectOptions is an object
            if (typeof selectOptions === 'object' && selectOptions !== null) {
                // Generate options HTML by iterating over object keys
                var optionsHTML = '';
                for (var key in selectOptions) {
                    if (selectOptions.hasOwnProperty(key) && key.trim() !== '') {
                        optionsHTML += `<option value="${selectOptions[key]}" ${tagId === selectOptions[key] ? 'selected' : ''}>${key}</option>`;
                    }
                }
                // Append the options to the select element
                $('#TagModalBody').append(`
                    <input type="hidden" value="${tagId}" name="old_tag_id" id="old_tag_id">
                    <div class="form-group">
                        <label for="">Tag</label>
                        <select class="form form-control select2 selectTage" name="new_tag_id" id="tagSelectupdate" style="width: 95%;">
                            <option value="">Select Tag</option>
                            ${optionsHTML}
                        </select>
                    </div>
                    <input type="hidden" value="${leadId}" name="lead_id" id="lead_id">
                    <input type="hidden" value="${dealId}" name="deal_id" id="dealer_id">
                `);
                select2();
                $('#UpdateTageModal').modal('show');
            }
        });
        // update tage post request
    $(document).ready(function () {
        $('#UpdateTagForm').submit(function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                url: '{{ url("leads/tag") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    data = JSON.parse(response);
                    show_toastr('success', data.msg);
                    $("#UpdateTageModal").modal('hide');
                    openSidebar('/get-deal-detail?deal_id='+$('#dealer_id').val())
                },
            });
        });
    });
    function deleteTage(element,deal_id,old_tag_id) {
    $(element).attr('disabled', 'disabled'); // Disable the anchor tag
    $(element).css('pointer-events', 'none'); // Prevent further clicks
    $(element).find('i').remove(); // Remove the <i> tag
    $(element).append(`
      <div class="spinner-border spinner-border-sm text-white" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    `);
    // Add the <div> tag with spinner and loading text
        $.ajax({
            type: "GET",
            url: '{{ url('delete_tage') }}',
            data: {deal_id : deal_id,old_tag_id : old_tag_id},
            success: function(response) {
                data = JSON.parse(response);

                if (data.status == 'success') {
                    $("#UpdateTageModal").modal('hide');
                    show_toastr('success', data.msg);
                    openSidebar('/get-deal-detail?deal_id='+deal_id)
                }
            },
        });
    }

 function DeleteDetail(url, id) {
    $('#AppendApplication').html('');
    // Add Ajax to remove admission
    $.ajax({
            type: "GET",
            url: '{{ url('get_applicatrion_of_admission') }}',
            data: {id : id},
            success: function(response) {
            $('#AppendApplication').html(response);
            },
    });
    $("#AppendApplicationDelete").html('');
    $("#AppendApplicationDelete").append(`
        <form method="POST" action="${url}" accept-charset="UTF-8" id="delete-form-${id}" class="mb-0">
            <input name="_method" type="hidden" value="DELETE">
            @csrf
            <a href="#" data-bs-toggle="tooltip" title="Delete" class="btn btn-outline-danger px-4 me-2 bs-pass-para">
                Delete
            </a>
        </form>
    `);
    $("#AdmissionCheck").modal('show');
  } 
</script>
