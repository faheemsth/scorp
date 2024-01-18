<style>
    .editable:hover {
        border: 1px solid rgb(136, 136, 136);
    }

    table tr td {

        font-size: 14px;
    }

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

                    <input type="hidden" name="org_did" value="{{ $org_detail->id }}">
                    <input type="hidden" name="ord-id" value="{{ $org->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('ORGANIZATION') }}</p>
                        <div class="d-flex align-items-baseline ">
                            <h5 class="fw-bold">{{ $org->name }}</h5>

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                    @can('edit organization')
                    <a href="#" data-size="lg" data-url="{{ route('organization.edit', $org->id) }}"
                        data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                        class="btn px-2 btn-dark text-white">
                        <i class="ti ti-pencil"></i>
                    </a>
                    @endcan

                    @can('delete organization')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['organization.destroy', $org->id],
                        'id' => 'delete-form-' . $org->id,
                        'class'=>'mb-0',
                    ]) !!}

                    <a href="#"
                        class="btn px-2 bg-danger  align-items-center bs-pass-para"
                        data-bs-toggle="tooltip"
                        title="{{ __('Delete') }}"><i
                            class="ti ti-trash text-white"></i></a>

                    {!! Form::close() !!}
                    @endcan
                </div>



            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small>{{ __('Phone') }}</small>
                    <span class="font-weight-bolder">{{ $org_detail->phone }}</span>
                </div>
                <div class="">
                    <small>{{ __('Website') }}</small>
                    <span>{{ $org_detail->website }}</span>
                </div>
                <div class="">
                    <small>{{ __('Organization Owner') }}</small>
                    <span>{{ \App\Models\User::where('id', $org->created_by)->first()->name }}</span>
                </div>
            </div>




            <div class="lead-content my-2">

                <div class="card me-3">
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
                                    aria-controls="pills-activity" aria-selected="false">{{ __('Task') }}</button>
                            </li>
                            <li class="nav-item " role="presentation">
                                <button class="nav-link pills-link" id="pills-news-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-news" type="button" role="tab"
                                    aria-controls="pills-activity" aria-selected="false">{{ __('Actvities') }}</button>
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
                                                {{ __('ORGANIZATION NAME') }}
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
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Organization Name') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 name">
                                                                            {{ $org->name }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="name"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org->name }}

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; font-size: 14px;">
                                                                    {{ __('Type of Organization') }}
                                                                </td>
                                                                <td class="type-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 type">
                                                                            {{ $org_detail->type }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="type"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->type }}
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" class="org-id" value="{{ $org->id }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeytwo">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytwo">
                                                {{ __('ORGANIZATION DETAILS') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeytwo"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeytwo">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Phone') }}
                                                                </td>
                                                                <td class="phone-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 phone">
                                                                            {{ $org_detail->phone }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="phone"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->phone }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Email') }}
                                                                </td>
                                                                <td class="email-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 email">
                                                                            {{ $org->email }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="email"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org->email }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Website') }}
                                                                </td>
                                                                <td class="website-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 website">
                                                                            {{ $org_detail->website }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="website"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->website }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('LinkedIn') }}
                                                                </td>
                                                                <td class="linkedin-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 linkedin">
                                                                            {{ $org_detail->linkedin }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="linkedin"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->linkedin }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Facebook') }}
                                                                </td>
                                                                <td class="facebook-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 facebook">
                                                                            {{ $org_detail->facebook }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="facebook"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->facebook }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Twitter') }}
                                                                </td>
                                                                <td class="twitter-td"
                                                                    style="padding-left: 10px; font-size: 14px;">


                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 twitter">
                                                                            {{ $org_detail->twitter }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="twitter"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->twitter }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Drive Link') }}
                                                                </td>
                                                                <td class="drive_link-td"
                                                                    style="padding-left: 10px; font-size: 14px;">


                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 drive_link">
                                                                            {{ $org_detail->drive_link }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="drive_link"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                  <a href="  {{ $org_detail->drive_link }}">
                                                                    {{ $org_detail->drive_link }}</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeythree">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeythree">
                                                {{ __('ADDRESS INFORMATION') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeythree"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeythree">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 115PX; font-size: 14px;">
                                                                    {{ __('Billing Address') }}
                                                                </td>
                                                                <td class="address-td"
                                                                    style="width: 80%; font-size: 13px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div
                                                                            class="input-group border-0 d-flex align-items-baseline">
                                                                            {{ $org_detail->billing_street . ' ' . $org_detail->billing_city . ' ' . $org_detail->billing_state . ' ' . $org_detail->billing_postal_code . ' ' . $org_detail->billing_country }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary edit-btn-address rounded-0 btn-effect-none"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->billing_street . ' ' . $org_detail->billing_city . ' ' . $org_detail->billing_state . ' ' . $org_detail->billing_postal_code . ' ' . $org_detail->billing_country }}


                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyaddi">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeyaddi">
                                                {{ __('ADDITIONAL INFORMATION') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeyaddi"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyaddi">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 145px; font-size: 14px;">
                                                                    {{ __('Dates to remember') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left:10px; font-size: 13px;">
                                                                    <a href=""
                                                                        style="font-size:14px">{{ __('Change') }}</a>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 145px; font-size: 14px;">
                                                                    {{ __('Organization Created') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left:10px;  font-size: 13px;">
                                                                    {{ $org->created_at }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 145px; font-size: 14px;">
                                                                    {{ __('Date of Next Activity') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left:10px;  font-size: 13px;">

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 145px; font-size: 14px;">
                                                                    {{ __('Date of Last Activity') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left:10px;font-size: 13px;">

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
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Description') }}
                                                                </td>
                                                                <td class="description-td"
                                                                    style="padding-left:15px; width: 550px; text-align: justify; font-size: 14px;">

                                                                    {{-- <div class="d-flex align-items-baseline edit-input-field-div"
                                                                        @if (!empty($org_detail->description)) style="min-height: 150px;" @endif>
                                                                        <div
                                                                            class="input-group border-0 d-flex align-items-baseline">
                                                                            {{ $org_detail->description }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary edit-input rounded-0 btn-effect-none"
                                                                                name="description">
                                                                                <i class="ti ti-pencil"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $org_detail->description }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="accordion-item d-none">
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
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Tag List') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px;">
                                                                    <a href="#" data-size="lg"
                                                                        data-url="{{ route('organization-edit-tags', $org->id) }}"
                                                                        data-ajax-popup="true"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Update Tags') }}"
                                                                        class="btn btn-dark px-2 text-white"
                                                                        style="font-size: 14px;">
                                                                        {{ __('Change') }}
                                                                    </a>
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

                            @php
                                $notes = \App\Models\OrganizationNote::where('organization_id', $org->id)->get();

                            @endphp

                            {{-- Details Pill End --}}
                            <div class="tab-pane fade" id="pills-related" role="tabpanel"
                                aria-labelledby="pills-related-tab">


                                <div class="block-items">
                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Contacts</div>
                                        <div class="block-item-count">{{ $org->organizationLeadContacts($org->id) }}
                                        </div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Discussion</div>
                                        <div class="block-item-count">
                                            {{ $org->organizationLeadDiscussions($org->id) }}</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Notes</div>
                                        <div class="block-item-count">{{ $org->organizationLeadNotes($org->id) }}
                                        </div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Links</div>
                                        <div class="block-item-count">{{ !empty($org_detail->drive_link) ? 1 : 0 }}
                                        </div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>


                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Opportunity</div>
                                        <div class="block-item-count">{{ $org->organizationOpportunity($org->id) }}
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

                                                                        <div class="card-header px-0 pt-0"
                                                                            style="padding-bottom: 18px;">
                                                                            <!-- <div class="d-flex justify-content-end">
                                                                                <div class="float-end">
                                                                                    <a data-size="lg" data-url="javascript:void(0)" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create Contact') }}" class="btn btn-sm text-white" style="background-color: #b5282f;">
                                                                                        <i class="ti ti-plus"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div> -->
                                                                        </div>
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
                                                                                    @forelse($org->organizationLeadContactsList($org->id) as $contact)
                                                                                        <tr>
                                                                                            <td>{{ $contact->name }}
                                                                                            </td>
                                                                                            <td>{{ $contact->email }}
                                                                                            </td>
                                                                                            <td>{{ $contact->created_at }}
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

                                            <div class="accordion" id="accordionPanelsStayOpenExample">
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
                                                                        <div class="card-header"
                                                                            style="padding-bottom: 18px;">
                                                                            <div class="d-flex justify-content-end">
                                                                                <div class="float-end">
                                                                                    <a data-size="lg"
                                                                                        data-url="{{ route('organization.discussions.create', $org->id) }}"
                                                                                        data-ajax-popup="true"
                                                                                        data-bs-toggle="tooltip"
                                                                                        title="{{ __('Add Message') }}"
                                                                                        class="btn px-2 btn-dark text-white"
                                                                                        >
                                                                                        <i class="ti ti-plus"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="card-body px-0">
                                                                            <ul
                                                                                class="list-group list-group-flush mt-2">
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
                                                                                                    <div
                                                                                                        class="mb-3 mb-sm-0">
                                                                                                        <h5
                                                                                                            class="mb-0">
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

                                                                        <div class="card-header "
                                                                            style="padding-bottom: 18px;">
                                                                            <div class="d-flex justify-content-end">
                                                                                <div class="float-end">
                                                                                    <a data-size="lg"
                                                                                        data-url="{{ route('organization.notes.create', $org->id) }}"
                                                                                        data-ajax-popup="true"
                                                                                        data-bs-toggle="tooltip"
                                                                                        title="{{ __('Add Message') }}"
                                                                                        class="btn px-2 btn-dark text-white"
                                                                                        >
                                                                                        <i class="ti ti-plus"></i>
                                                                                    </a>
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
                                                                                    @forelse($org->organizationLeadNotesList($org->id) as $note)
                                                                                        <tr>


                                                                                            <td>{{ $note->title }}
                                                                                            </td>
                                                                                            <td>{{ $note->description }}
                                                                                            </td>
                                                                                            <td>{{ $note->created_at }}
                                                                                            </td>
                                                                                            <td>{{ \App\Models\User::where('id', $note->created_by)->first()->name }}
                                                                                            </td>
                                                                                            <td class="d-flex">

                                                                                                <a data-url="{{ route('organization.notes.edit', $note->id) }}"
                                                                                                    data-ajax-popup="true"
                                                                                                    data-bs-toggle="tooltip"
                                                                                                    title="{{ __('Drive Link') }}"
                                                                                                    class="btn px-2 btn-dark text-white mx-2"
                                                                                                   >
                                                                                                    <i
                                                                                                        class="ti ti-pencil "></i>
                                                                                                </a>

                                                                                                <a href="javascript:void(0)"
                                                                                                    class="btn px-2 btn-dark text-white delete-notes"
                                                                                                    data-note-id="{{ $note->id }}"
                                                                                                    >
                                                                                                    <i
                                                                                                        class="ti ti-trash "></i>
                                                                                                </a>
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
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseactive">
                                                <div style="position: absolute;right: 27px;z-index: 9999;">
                                                                    @can('create task')
                                                                        <a data-size="lg"
                                                                            data-url="/organiation/1/task?type=organization&typeid={{ $org->id }}"
                                                                            data-ajax-popup="true"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Add Task') }}"
                                                                            class="btn p-2 text-white"
                                                                            style="background-color: #313949;">
                                                                            <i class="ti ti-plus"></i>
                                                                        </a>
                                                                    @endcan
                                                                </div>
                                                {{ __('Task') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseactive"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingactive">
                                            <div class="accordion-body">
                                            <ul class="list-group list-group-flush mt-2 notes-tbody">
                                                                                   @php
                                                                                       $section=1;
                                                                                       $section2=1;
                                                                                   @endphp
                                                                                    @foreach($tasks as $task)
                                                                                    @if ($task->status == 1)
                                                                                        <div class="ps-3 py-2 d-flex gap-2 align-items-baseline" style="border-bottom: 1px solid rgb(192, 192, 192);">
                                                                                            <i class="fa-regular fa-square-check" style="color: #000000;"></i>
                                                                                            <h6 class="fw-bold">
                                                                                                {{ $section == 1 ? 'Closed Activity': '' }}
                                                                                            </h6>
                                                                                        </div>
                                                                                        <li class="list-group-item px-3"
                                                                                            id="lihover">
                                                                                            <div class="d-block d-sm-flex align-items-start">
                                                                                                <div class="w-100">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                                        <div class="mb-3 mb-sm-0">
                                                                                                            <h5 class="mb-0">
                                                                                                                {{ $task->name }}

                                                                                                            </h5>
                                                                                                            <span
                                                                                                                class="text-muted text-sm">
                                                                                                                {{ $task->created_at }}
                                                                                                            </span><br>
                                                                                                            <span
                                                                                                                class="text-muted text-sm"><i class="step__icon fa fa-user" aria-hidden="true"></i>
                                                                                                                {{ \App\Models\User::where('id', $task->assigned_to)->first()->name }}

                                                                                                                <span class="d-flex">
                                                                                                                    <div>Status</div>
                                                                                                                    <div class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ms-5 py-1">
                                                                                                                      <span>
                                                                                                                        {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                                      </span>
                                                                                                                </div>
                                                                                                                </span>
                                                                                                                {{--  --}}
                                                                                                            </span>
                                                                                                        </div>

                                                                                                        <style>
                                                                                                            #editable {
                                                                                                                display: none;
                                                                                                            }

                                                                                                            #lihover:hover #editable {
                                                                                                                display: flex;
                                                                                                            }
                                                                                                        </style>
                                                                                                    <div class="d-flex gap-3" id="dellhover">

                                                                                                            <a data-size="lg"
                                                                                                            data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                            data-ajax-popup="true"
                                                                                                            data-bs-toggle="tooltip"
                                                                                                            title="{{ __('Update Task') }}"
                                                                                                            id="editable"
                                                                                                            class="btn textareaClassedit">
                                                                                                            <i
                                                                                                                class="ti ti-pencil" style="font-size: 20px;margin-right: -30px;"></i>
                                                                                                        </a>


                                                                                                        <a href="javascript:void(0)"
                                                                                                            class="btn"
                                                                                                            id="editable"
                                                                                                            onclick="deleteTask({{ $task->id }}, {{ $lead->id }}, 'lead');">
                                                                                                            <i class="ti ti-trash " style="font-size: 20px;"></i>
                                                                                                        </a>

                                                                                                    </div>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                        @php
                                                                                            $section++;
                                                                                        @endphp
                                                                                    @elseif ($task->status == 0)
                                                                                    <div class="ps-3 py-2 d-flex gap-2 align-items-baseline" style="border-bottom: 1px solid rgb(192, 192, 192);">
                                                                                        <i class="fa-regular fa-square-check" style="color: #000000;"></i>
                                                                                        <h6 class="fw-bold">
                                                                                            {{ $section2 == 1 ? 'Open Activity': '' }}
                                                                                        </h6>
                                                                                    </div>
                                                                                        <li class="list-group-item px-3"
                                                                                            id="lihover">
                                                                                            <div class="d-block d-sm-flex align-items-start">
                                                                                                <div class="w-100">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                                        <div class="mb-3 mb-sm-0">
                                                                                                            <h5 class="mb-0">
                                                                                                                {{ $task->name }}

                                                                                                            </h5>
                                                                                                            <span
                                                                                                                class="text-muted text-sm">
                                                                                                                {{ $task->created_at }}
                                                                                                            </span><br>
                                                                                                            <span
                                                                                                                class="text-muted text-sm"><i class="step__icon fa fa-user" aria-hidden="true"></i>
                                                                                                                {{ \App\Models\User::where('id', $task->assigned_to)->first()->name ?? '' }}

                                                                                                                <span class="d-flex">
                                                                                                                    <div>Status</div>
                                                                                                                    <div class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ms-5 py-1">
                                                                                                                      <span>
                                                                                                                        {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                                      </span>
                                                                                                                </div>
                                                                                                                </span>
                                                                                                                {{--  --}}
                                                                                                            </span>
                                                                                                        </div>

                                                                                                        <style>
                                                                                                            #editable {
                                                                                                                display: none;
                                                                                                            }

                                                                                                            #lihover:hover #editable {
                                                                                                                display: flex;
                                                                                                            }
                                                                                                        </style>
                                                                                                    <div class="d-flex gap-3" id="dellhover">

                                                                                                            <a data-size="lg"
                                                                                                            data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                            data-ajax-popup="true"
                                                                                                            data-bs-toggle="tooltip"
                                                                                                            title="{{ __('Update Task') }}"
                                                                                                            id="editable"
                                                                                                            class="btn textareaClassedit">
                                                                                                            <i
                                                                                                                class="ti ti-pencil" style="font-size: 20px;margin-right: -30px;"></i>
                                                                                                        </a>


                                                                                                        <a href="javascript:void(0)"
                                                                                                            class="btn"
                                                                                                            id="editable"
                                                                                                            onclick="deleteTask({{ $task->id }}, {{ $lead->id }}, 'lead');">
                                                                                                            <i class="ti ti-trash " style="font-size: 20px;"></i>
                                                                                                        </a>

                                                                                                    </div>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                        @php
                                                                                            $section2++;
                                                                                        @endphp
                                                                                    @endif

                                                                                    @endforeach

                                                                                    </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-news" role="tabpanel"
                                aria-labelledby="pills-news-tab">

                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingnews">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsenews">
                                                {{ __('Activities') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsenews" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingnews">
                                            <div class="accordion-body">
                                                <div class="mt-1">
                                                    <div id="news" class=" px-0">
                                                        <div class=" px-0" style=" padding-bottom: 18px;">
                                                            <div class="d-flex justify-content-end">
                                                                <div class="float-end">
                                                                    <a data-size="lg" data-url=""
                                                                        data-ajax-popup="true"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Add Message') }}"
                                                                        class="btn px-2 btn-dark text-white"
                                                                        >
                                                                        <i class="ti ti-plus"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class=""
                                                            style="max-height: 200px; overflow-y: scroll;">

                                                            <table class="w-100">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="p-3">

                                                                            @foreach ($org->organizationActivitiesList($org->id) as $log)
                                                                                <div class="p-2"
                                                                                    style="border-bottom: 1px solid rgb(240, 240, 240);">
                                                                                    <div
                                                                                        class="d-flex align-items-baseline p-1">
                                                                                        <p class="mb-0">
                                                                                            <b>{{ $log->log_type }}
                                                                                                :</b>
                                                                                            {{ json_decode($log->remark)->title }}
                                                                                        </p>
                                                                                    </div>
                                                                                    <small>{{ $log->created_at }}</small>

                                                                                </div>
                                                                            @endforeach
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
    </div>
