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
    }

    .edit-input-field-div .input-group input {
        border: 0px !important;
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
        border: 1px solid rgb(224, 224, 224);
    }

    .edit-input-field-div:hover .edit-btn-div {
        display: block;
    }

    .edit-input {
        padding: 7px !important;
    }

    .btn-sm{
        width: 30px;
        height: 30px;
    }
</style>
<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0">
    <div class="row">
        <div class="col-sm-12">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between align-items-center p-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" class="">
                    </div>

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('LEAD') }}</p>
                        <div class="d-flex align-items-baseline">
                            <h4 class="">{{ $lead->name }}</h4>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                    @can('edit lead')
                    
                    @if (!empty($deal))
                            <a href="@can('View Deal') @if ($deal->is_active) {{ '/get-deal-detail?deal_id='.$deal->id }} @else # @endif @else # @endcan"
                                data-size="lg" data-bs-toggle="tooltip"
                                data-bs-title=" {{ __('Already Converted To Deal') }}" class="btn btn-sm text-white"
                                style="background-color: #b5282f">
                                <i class="ti ti-exchange"></i>
                            </a>
                        @else
                            <a href="#" data-size="lg"
                                data-url="{{ URL::to('leads/' . $lead->id . '/show_convert') }}" data-ajax-popup="true"
                                data-bs-toggle="tooltip" title="{{ __('Convert [' . $lead->subject . '] To Deal') }}"
                                class="btn btn-sm btn-primary">
                                <i class="ti ti-exchange"></i>
                            </a>
                        @endif
                    
                        @endcan

                    <a href="#" data-url="{{ URL::to('leads/' . $lead->id . '/labels') }}" data-ajax-popup="true"
                        data-size="lg" data-bs-toggle="tooltip" title="{{ __('Label') }}"
                        class="btn btn-sm text-white" style="background-color: #b5282f;">
                        <i class="ti ti-bookmark"></i>
                    </a>
                    <a href="#" data-size="lg" data-url="{{ route('leads.edit', $lead->id) }}"
                        data-ajax-popup="true" data-bs-toggle="tooltip" bs-original-title="{{ __('Edit') }}"
                        class="btn btn-sm text-white" style="background-color: #b5282f;">
                        <i class="ti ti-pencil"></i>
                    </a>

                     @can('delete lead')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['leads.destroy', $lead->id],
                        'id' => 'delete-form-' . $lead->id,
                    ]) !!}

                    <a href="#"  data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                    class="btn btn-sm text-white bs-pass-para" style="background-color: #b5282f;">
                    <i class="ti ti-trash"></i>
                    </a>


                    {!! Form::close() !!}
                    @endcan

                </div>
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small>{{ __('Stage') }}</small>
                    <span class="font-weight-bolder">{{ $lead->stage->name }}</span>
                </div>
                <div class="">
                    <small>{{ __('Email') }}</small>
                    <span>{{ !empty($lead->email) ? $lead->email : '' }}</span>
                </div>
                <div>
                    <small>{{ __('Phone') }}</small>
                    <span>{{ !empty($lead->phone) ? $lead->phone : '' }}</span>
                </div>
                <div>
                    <small> {{ __('Pipeline') }} </small>
                    <span>{{ $lead->pipeline->name }}</span>
                </div>
                <div class="">
                    <small>{{ __('Created') }}</small>
                    <span>{{ \Auth::user()->dateFormat($lead->created_at) }}</span>
                </div>
            </div>

            {{-- Stages --}}
            <div class="stages my-2 ">
                <h2 class="mb-3">LEAD STATUS: <span class="d-inline-block fw-light">{{ $lead->stage->name }}</span>
                </h2>
                <div class="wizard mb-2">
                    <?php $done = true; ?>
                    @forelse ($lead_stages as $stage)
                        <?php
                        if ($lead->stage->name == $stage->name) {
                            $done = false;
                        }
                        
                        ?>

                        <a type="button" data-lead-id="{{ $lead->id }}" data-stage-id="{{ $stage->id }}"
                            class="lead_stage {{ $lead->stage->name == $stage->name ? 'current' : ($done == true ? 'done' : '') }} "
                            style="font-size:13px">{{ $stage->name }}</a>
                    @empty
                    @endforelse
                </div>
            </div>





            <div class="lead-content my-2">

                <div class="card me-3">
                    <div class="card-header p-1">
                        <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
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
                                    aria-controls="pills-activity"
                                    aria-selected="false">{{ __('Activity') }}</button>
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
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Lead Stage') }}
                                                                </td>
                                                                <td style="padding-left: 10px; font-size: 14px;">
                                                                    <div class="bg-danger text-white">
                                                                        <p class="mb-0"
                                                                            style="padding-left: 10px; font-size: 14px;">
                                                                            {{ $lead->stage->name }}
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Pipeline') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->pipeline->name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Location') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($branches[ $lead->branch_id] ) ? $branches[ $lead->branch_id] : '' }}
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('User Responsible') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ isset($users[ $lead->user_id] ) ? $users[ $lead->user_id] : '' }}
                                                                </td>
                                                            </tr>

                                                            @php
                                                                $org_name = '';
                                                                if (isset($lead->organization_id) && !empty($lead->organization_id)) {
                                                                    $org = \App\Models\User::where('id', $lead->organization_id)->first();

                                                                    if ($org) {
                                                                        $org_name = $org->name;
                                                                    }
                                                                }

                                                            @endphp
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Agency') }}
                                                                </td>
                                                                <td class="organization_id-td"
                                                                    style="padding-left: 10px; font-size: 14px;"
                                                                    class="edit-td">

                                                                    {{-- <div class="d-flex edit-input-field-div">
                                                                        <div class="input-group border-0 organization_id">
                                                                            {{ $org_name }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button class="btn btn-secondary rounded-0 btn-effect-none edit-input" name="organization_id" style="padding: 7px;"><i class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}

                                                                </td>
                                                            </tr>
                                                            <input type="hidden" class="lead-id"
                                                                value="{{ $lead->id }}">
                                                            <input type="hidden" class="temp-field" value="">

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
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

                                                                    <div class="d-flex edit-input-field-div">
                                                                        <div class="input-group border-0 sources">
                                                                            {{ $sources }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="sources"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>

                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 194px; text-align: right; font-size: 14px;">
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
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
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
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Drive Link') }}
                                                                </td>
                                                                <td class="drive_link-td"
                                                                    style="padding-left: 10px; font-size: 14px; width:300px">

                                                                    <div class="d-flex edit-input-field-div">
                                                                        <div class="input-group border-0 drive_link">
                                                                            
                                                                            <a href="{{ $lead->drive_link }}" style="font-size: 12px; color:blue;text-decoration: underline;" target="_blank">
                                                                                {{ $lead->drive_link }}
                                                                            </a>
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="drive_link"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                            </tr>



                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Lead Created') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $lead->created_at }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Date of Last Activity') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
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
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Email Address') }}
                                                                </td>
                                                                <td class="email-td"
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
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200px; text-align: right; font-size: 14px;">
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
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
                                                                    {{ __('Mobile Phone') }}
                                                                </td>
                                                                <td class="mobile_phone-td"
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

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 14px;">
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
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 130px; text-align: right; font-size: 14px;">
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
                                                                                class="ti ti-pencil"></i></button>

                                                                    </div>
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
                                                                    style="width: 190PX; text-align: right; font-size: 14px;">
                                                                    {{ __('Address') }}
                                                                </td>
                                                                <td class="address-td"
                                                                    style="min-width: 250PX; text-align: right; font-size: 13px;padding-left:10px;">
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
                                                                    style="width: 196px; text-align: right; font-size: 14px;">
                                                                    {{ __('Description') }}
                                                                </td>
                                                                <td class=""
                                                                    style="width:550px; padding-left:15px; text-align: justify; font-size: 14px;">
                                                                    {{ $lead->keynotes }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
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
                                                                    style="width:200px;font-size: 14px;text-align:right;">
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
                                    </div>
                                    <div class="accordion-item">
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
                                                                    style="width: 200PX; text-align: right; font-size: 14px;">
                                                                    {{ __('Converted Contact') }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200PX; text-align: right; font-size: 14px;">
                                                                    {{ __('Converted Organization') }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 200PX; text-align: right; font-size: 14px;">
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

                                <div class="row">

                                    <div id="discussion_note">
                                        <div class="row">

                                            @can('manage task')
                                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                                <!-- Open Accordion Item -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="panelsStayOpen-headingnote">
                                                        <button class="accordion-button p-2" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapsetasks">
                                                            {{ __('Tasks') }}
                                                        </button>
                                                    </h2>

                                                    <div id="panelsStayOpen-collapsetasks"
                                                        class="accordion-collapse collapse show"
                                                        aria-labelledby="panelsStayOpen-headingnote">
                                                        <div class="accordion-body">


                                                            <div class="">
                                                                <div class="col-12">
                                                                    <div class="card">
                                                                        <div class="card-header px-0 pt-0"
                                                                            style="padding-bottom: 18px;">
                                                                            <div class="d-flex justify-content-end">
                                                                                <div class="float-end">
                                                                                    @can('create task')
                                                                                    <a data-size="lg"
                                                                                        data-url="/organiation/1/task?type=lead&typeid={{ $lead->id }}"
                                                                                        data-ajax-popup="true"
                                                                                        data-bs-toggle="tooltip"
                                                                                        title="{{ __('Add Task') }}"
                                                                                        class="btn btn-sm text-white"
                                                                                        style="background-color: #b5282f;">
                                                                                        <i class="ti ti-plus"></i>
                                                                                    </a>
                                                                                    @endcan
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-body px-0">
                                                                            <table class="table">
                                                                                <thead class="table-bordered">
                                                                                    <tr>
                                                                                        <th scope="col">Name</th>
                                                                                        <th scope="col">Description
                                                                                        </th>
                                                                                        <th scope="col">Visibility
                                                                                        </th>
                                                                                        <th scope="col">Status
                                                                                        </th>
                                                                                        <th scope="col">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="tasks-tbody">

                                                                                    @forelse($tasks as $task)
                                                                                        <tr>
                                                                                            <td>
                                                                                                <span
                                                                                                    style="cursor:pointer"
                                                                                                    class="task-name hyper-link"
                                                                                                    onclick="openSidebar('/get-task-detail?task_id='+{{ $task->id }})"
                                                                                                    data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                                                                            </td>
                                                                                            <td>{{ $task->description }}
                                                                                            </td>
                                                                                            <td>{{ $task->visibility }}
                                                                                            </td>
                                                                                            <td>
                                                                                                <span class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }}"> {{ $task->status == 1 ? 'Completed' : 'On Going' }}</span>
                                                                                            </td>
                                                                                            <td>

                                                                                                <div class="d-flex">
                                                                                                    <a data-size="lg"
                                                                                                        data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                        data-ajax-popup="true"
                                                                                                        data-bs-toggle="tooltip"
                                                                                                        title="{{ __('Update Task') }}"
                                                                                                        class="btn btn-sm text-white mx-2"
                                                                                                        style="background-color: #b5282f;">
                                                                                                        <i
                                                                                                            class="ti ti-pencil"></i>
                                                                                                    </a>


                                                                                                    <a href="javascript:void(0)"
                                                                                                        class="btn btn-sm text-white"
                                                                                                        style="background-color: #b5282f;"
                                                                                                        onclick="deleteTask({{ $task->id }}, {{ $lead->id }}, 'lead');">
                                                                                                    <i
                                                                                                        class="ti ti-trash "></i>
                                                                                                    </a>
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
                                            </div>
                                            @endcan


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
                                                                    <div class="card-header px-0 pt-0"
                                                                        style="padding-bottom: 18px;">
                                                                        <div class="d-flex justify-content-end">
                                                                            <div class="float-end">
                                                                                <a data-size="lg"
                                                                                    data-url="{{ route('leads.discussions.create', $lead->id) }}"
                                                                                    data-ajax-popup="true"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('Add Message') }}"
                                                                                    class="btn btn-sm text-white"
                                                                                    style="background-color: #b5282f;">
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

                                                            <div class="col-12">
                                                                <div class="card">

                                                                    <div class="card-header px-0 pt-0"
                                                                        style="padding-bottom: 18px;">
                                                                        <div class="d-flex justify-content-end">
                                                                            <div class="float-end">
                                                                                @can('create notes')
                                                                                <a data-size="lg"
                                                                                    data-url="{{ route('leads.notes.create', $lead->id) }}"
                                                                                    data-ajax-popup="true"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('Add Message') }}"
                                                                                    class="btn btn-sm text-white"
                                                                                    style="background-color: #b5282f;">
                                                                                    <i class="ti ti-plus"></i>
                                                                                </a>
                                                                                @endcan
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body px-0">
                                                                        <table class="table">
                                                                            <thead class="table-bordered">
                                                                                <tr>
                                                                                    <th scope="col">Title</th>
                                                                                    <th scope="col">Description
                                                                                    </th>
                                                                                    <th scope="col">Date Added
                                                                                    </th>
                                                                                    <th scope="col">Added By
                                                                                    </th>
                                                                                   <th scope="col">Action</th>
                                                                                   
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody class="notes-tbody">
                                                                                @php
                                                                                    $notes = \App\Models\LeadNote::where('lead_id', $lead->id)
                                                                                        ->orderBy('created_at', 'DESC')
                                                                                        ->get();
                                                                                @endphp

                                                                                @forelse($notes as $note)
                                                                                    <tr>


                                                                                        <td>{{ $note->title }}
                                                                                        </td>
                                                                                        <td style="white-space: normal;">{{ $note->description }}
                                                                                        </td>
                                                                                        <td>{{ $note->created_at }}
                                                                                        </td>
                                                                                        <td>{{ \App\Models\User::where('id', $note->created_by)->first()->name }}
                                                                                        </td>
                                                                                        <td style="text-align: -webkit-center;">
                                                                                            @can('edit notes')
                                                                                            <a data-url="{{ route('leads.notes.edit', $note->id) }}"
                                                                                                data-ajax-popup="true"
                                                                                                data-bs-toggle="tooltip"
                                                                                                title="{{ __('Drive Link') }}"
                                                                                                class="btn btn-sm text-white mx-2"
                                                                                                style="background-color: #b5282f;">
                                                                                                <i
                                                                                                    class="ti ti-pencil "></i>
                                                                                            </a>
                                                                                            @endcan
                                                                                            @can('delete notes')
                                                                                            <a href="javascript:void(0)"
                                                                                                class="btn btn-sm text-white delete-notes"
                                                                                                data-note-id="{{ $note->id }}"
                                                                                                style="background-color: #b5282f;">
                                                                                                <i
                                                                                                    class="ti ti-trash "></i>
                                                                                            </a>
                                                                                            @endcan
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
                                            </div>
                                        </div>
                                        @endcan

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Open Accordion Item -->

                        <!-- Add More Accordion Items Here -->



                        <div class="tab-pane fade" id="pills-activity" role="tabpanel"
                            aria-labelledby="pills-activity-tab">

                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <!-- Open Accordion Item -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingactive">
                                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseactive">
                                            {{ __('Activity') }}
                                        </button>
                                    </h2>

                                    <div id="panelsStayOpen-collapseactive" class="accordion-collapse collapse show"
                                        aria-labelledby="panelsStayOpen-headingactive">
                                        <div class="accordion-body">
                                            <!-- Accordion Content -->
                                            <div class="mt-1">
                                                <div id="activity" class=" px-0">
                                                    <div class=" px-0" style=" padding-bottom: 18px;">
                                                        <div class="d-flex justify-content-end">
                                                            <div class="float-end">
                                                                <a data-size="lg"
                                                                    data-url="{{ route('leads.discussions.create', $lead->id) }}"
                                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                    title="{{ __('Add Message') }}"
                                                                    class="btn btn-sm text-white"
                                                                    style="background-color: #b5282f">
                                                                    <i class="ti ti-plus"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <table class="table">
                                                        <thead class="table-bordered">
                                                            <tr>
                                                                <th scope="col"></th>
                                                                <th scope="col">{{ __('Type') }}
                                                                </th>
                                                                <th scope="col">{{ __('Activity Name') }}
                                                                </th>
                                                                <th scope="col">{{ __('Assigned To') }}
                                                                </th>
                                                                <th scope="col">{{ __('Date Due') }}</th>
                                                                <th scope="col"></th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr style="font-size: 14px">


                                                                <td> <input class="form-check-input py-1"
                                                                        type="checkbox" value=""
                                                                        id="flexCheckDefault">
                                                                </td>
                                                                <td class="py-1">{{ __('TASK') }}
                                                                </td>
                                                                <td class="py-1">{{ __('Name') }}
                                                                </td>
                                                                <td class="py-1"><a href=""
                                                                        style="font-size: 14px">{{ __('Aleena Arif') }}</a>
                                                                </td>
                                                                <td class="py-1">{{ __('07-oct-19') }}</td>
                                                                <td class="py-1">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="flexCheckDefault" checked>
                                                                </td>
                                                                <td class="py-1" style="width: 10px">
                                                                    <div class="dropdown">
                                                                        <button class="btn bg-transparents"
                                                                            type="button" id="dropdownMenuButton1"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 24 24" width="18"
                                                                                height="18">
                                                                                <path
                                                                                    d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                                                                                </path>
                                                                            </svg>
                                                                        </button>
                                                                        <ul class="dropdown-menu"
                                                                            aria-labelledby="dropdownMenuButton1">
                                                                            <li><a class="dropdown-item"
                                                                                    href="#">Change</a></li>
                                                                            <li><a class="dropdown-item"
                                                                                    href="#">Edit</a></li>
                                                                            <li><a class="dropdown-item"
                                                                                    href="#">Delete</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
