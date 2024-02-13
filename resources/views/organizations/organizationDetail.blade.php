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

    .btn-sm {
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
                            class="btn px-2 btn-dark text-white" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-pencil"></i>
                        </a>
                    @endcan

                    @can('delete organization')
                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['organization.destroy', $org->id],
                            'id' => 'delete-form-' . $org->id,
                            'class' => 'mb-0',
                        ]) !!}

                        <a href="#" class="btn px-2 bg-danger  align-items-center bs-pass-para"
                            data-bs-toggle="tooltip" title="{{ __('Delete') }}" style="color:white; width:36px; height: 36px; margin-top:10px;"><i class="ti ti-trash text-white"></i></a>

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
                            <li class="nav-item d-none" role="presentation">
                                <button class="nav-link pills-link" id="pills-activity-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-activity" type="button" role="tab"
                                    aria-controls="pills-activity" aria-selected="false">{{ __('Task') }}</button>
                            </li>
                            <li class="nav-item " role="presentation">
                                <button class="nav-link pills-link" id="pills-news-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-news" type="button" role="tab"
                                    aria-controls="pills-activity" aria-selected="false">{{ __('Timeline') }}</button>
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
                                                                   <a href="{{ $org->email }}" target="_blank" >{{ $org->email }}</a> 
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Website') }}
                                                                </td>
                                                                <td class="website-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                   <a href="{{ $org_detail->website }}" target="_blank">{{ $org_detail->website }}</a> 
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('LinkedIn') }}
                                                                </td>
                                                                <td class="linkedin-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                   <a href="{{ $org_detail->linkedin }}" target="_blank" >{{ $org_detail->linkedin }}</a> 
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Facebook') }}
                                                                </td>

                                                                <td class="facebook-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                   <a href="{{ $org_detail->facebook }}" target="_blank" >{{ $org_detail->facebook }}</a> 
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Twitter') }}
                                                                </td>
                                                                <td class="twitter-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                   <a href="{{ $org_detail->twitter }}" target="_blank" >{{ $org_detail->twitter }}</a> 
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Drive Link') }}
                                                                </td>
                                                                <td class="drive_link-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                   <a href="{{ $org_detail->drive_link }}" target="_blank">{{ $org_detail->drive_link }}</a> 
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
                                                                    
                                                                        style="font-size:14px">{{ __('Change') }}
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

                                    <div class="block-item large-block d-none" id="con-stats"
                                        title="1 Linked Contacts" data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Discussion</div>
                                        <div class="block-item-count">
                                            {{ $org->organizationLeadDiscussions($org->id) }}
                                        </div>
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
                                        <div class="top-label">Tasks</div>
                                        <div class="block-item-count">{{ $org->organizationLeadTasks($org->id) }}
                                        </div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block d-none" id="con-stats"
                                        title="1 Linked Contacts" data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Links</div>
                                        <div class="block-item-count">{{ !empty($org_detail->drive_link) ? 1 : 0 }}
                                        </div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>


                                    <div class="block-item large-block d-none" id="con-stats"
                                        title="1 Linked Contacts" data-bs-target="#contacts-grid-container">
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

                                            <!-- Contacts related to organizations -->
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

                                            <!-- Noted related to organization -->
                                            <div class="accordion" id="accordionPanelsStayOpenExample">

                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="panelsStayOpen-headingdisc">
                                                        <button class="accordion-button p-2" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapsedisc">
                                                            {{ __('Notes') }}
                                                        </button>
                                                    </h2>

                                                    <div id="panelsStayOpen-collapsedisc"
                                                        class="accordion-collapse collapse show"
                                                        aria-labelledby="panelsStayOpen-headingdisc">
                                                        <div class="accordion-body">
                                                            <div class="">

                                                                <div class="col-12">
                                                                    <div class="card">
                                                                        <textarea name="" id="" cols="95"
                                                                            class="form-control @can('create notes') textareaClass @endcan " readonly style="cursor: pointer"></textarea>
                                                                        <span id="textareaID" style="display: none;">
                                                                            <div class="card-header px-0 pt-0"
                                                                                style="padding-bottom: 18px;">
                                                                                {{ Form::model($org, ['route' => ['organization.notes.store', $org->id], 'method' => 'POST', 'id' => 'create-notes', 'style' => 'z-index: 9999999 !important;']) }}
                                                                                <textarea name="description" id="description" class="form form-control" cols="10" rows="1"></textarea>
                                                                                <input type="hidden" id="note_id"
                                                                                    name="note_id">
                                                                                <input type="hidden" name="org_id"
                                                                                    id="org_id"
                                                                                    value="{{ $org->id }}">
                                                                                <div
                                                                                    class="d-flex justify-content-end mt-2">
                                                                                    <button type="button"
                                                                                        id="cancelNote"
                                                                                        class="btn btn-secondary mx-2">Cancel</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-secondary">Save</button>
                                                                                </div>
                                                                                {{ Form::close() }}
                                                                            </div>
                                                                        </span>

                                                                        <div class="card-body px-0 py-0">
                                                                            @php
                                                                                $notes = \App\Models\OrganizationNote::where('organization_id', $org->id)
                                                                                    ->orderBy('created_at', 'DESC')
                                                                                    ->get();
                                                                            @endphp

                                                                            <ul
                                                                                class="list-group list-group-flush mt-2 note-tbody">

                                                                                @foreach ($notes as $note)
                                                                                    <li class="list-group-item px-3 pb-0"
                                                                                        id="lihover">

                                                                                        <div
                                                                                            class="d-block d-sm-flex align-items-start">
                                                                                            <div class="w-100">
                                                                                                <div
                                                                                                    class="d-flex align-items-center justify-content-between w-100">
                                                                                                    <div
                                                                                                        class="mb-3 mb-sm-0 w-50 pb-3">
                                                                                                        <p
                                                                                                            class="mb-0">
                                                                                                            {{ $note->description }}
                                                                                                        </p>
                                                                                                        <span
                                                                                                            class="text-muted text-sm">{{ $note->created_at }}
                                                                                                        </span><br>
                                                                                                        <span
                                                                                                            class="text-muted text-sm"><i
                                                                                                                class="step__icon fa fa-user me-2"
                                                                                                                aria-hidden="true"></i>{{ \App\Models\User::where('id', $note->created_by)->first()->name }}
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
                                                                                                    <div class="d-flex gap-3"
                                                                                                        id="dellhover">
                                                                                                        <i class="ti ti-pencil textareaClassedit"
                                                                                                            data-note="{{ $note->description }}"
                                                                                                            data-note-id="{{ $note->id }}"
                                                                                                            id="editable"
                                                                                                            style="font-size: 20px;cursor:pointer;"></i>
                                                                                                        <script></script>
                                                                                                        <i class="ti ti-trash delete-notes"
                                                                                                            id="editable"
                                                                                                            data-note-id="{{ $note->id }}"
                                                                                                            style="font-size: 20px;cursor:pointer;"></i>
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

                                                <!-- Tasks -->
                                                @can('manage task')
                                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                                        <!-- Open Accordion Item -->
                                                        <div class="accordion-item">
                                                            <h2 class="d-flex justify-between align-items-center accordion-header"
                                                                id="panelsStayOpen-headingnote">
                                                                <button class="accordion-button p-2" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#panelsStayOpen-collapsetasks">

                                                                    <div
                                                                        style="position: absolute;right: 27px;z-index: 9999;">
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
                                                                    <span>
                                                                        {{ __('Tasks') }}
                                                                    </span>
                                                                </button>

                                                            </h2>

                                                            <div id="panelsStayOpen-collapsetasks"
                                                                class="accordion-collapse collapse show"
                                                                aria-labelledby="panelsStayOpen-headingnote">
                                                                <div class="accordion-body">


                                                                    <div class="">
                                                                        <div class="col-12">
                                                                            <div class="card">
                                                                                <div class="card-header ">
                                                                                    <div
                                                                                        class="d-flex justify-content-end">

                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-body px-0">

                                                                                    <ul
                                                                                        class="list-group list-group-flush mt-2 notes-tbody">
                                                                                        @php
                                                                                            $section = 1;
                                                                                            $section2 = 1;
                                                                                        @endphp
                                                                                        @foreach ($tasks as $task)
                                                                                            @if ($task->status == 1)
                                                                                                <div class="ps-3 py-2 d-flex gap-2 align-items-baseline"
                                                                                                    style="border-bottom: 1px solid rgb(192, 192, 192);">
                                                                                                    <i class="fa-regular fa-square-check"
                                                                                                        style="color: #000000;"></i>
                                                                                                    <h6 class="fw-bold">
                                                                                                        {{ $section == 1 ? 'Closed Activity' : '' }}
                                                                                                    </h6>
                                                                                                </div>
                                                                                                <li class="list-group-item px-3"
                                                                                                    id="lihover">
                                                                                                    <div
                                                                                                        class="d-block d-sm-flex align-items-start">
                                                                                                        <div
                                                                                                            class="w-100">
                                                                                                            <div
                                                                                                                class="d-flex align-items-center justify-content-between">
                                                                                                                <div
                                                                                                                    class="mb-3 mb-sm-0">
                                                                                                                    <h5
                                                                                                                        class="mb-0">
                                                                                                                        {{ $task->name }}

                                                                                                                    </h5>
                                                                                                                    <span
                                                                                                                        class="text-muted text-sm">
                                                                                                                        {{ $task->created_at }}
                                                                                                                    </span><br>
                                                                                                                    <span
                                                                                                                        class="text-muted text-sm"><i
                                                                                                                            class="step__icon fa fa-user"
                                                                                                                            aria-hidden="true"></i>
                                                                                                                        {{ \App\Models\User::where('id', $task->assigned_to)->first()->name }}

                                                                                                                        <span
                                                                                                                            class="d-flex">
                                                                                                                            <div>
                                                                                                                                Status
                                                                                                                            </div>
                                                                                                                            <div
                                                                                                                                class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ms-5 py-1">
                                                                                                                                <span>
                                                                                                                                    {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                                                </span>
                                                                                                                            </div>
                                                                                                                        </span>
                                                                                                                        {{-- --}}
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
                                                                                                                <div class="d-flex gap-3"
                                                                                                                    id="dellhover">

                                                                                                                    <a data-size="lg"
                                                                                                                        data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                                        data-ajax-popup="true"
                                                                                                                        data-bs-toggle="tooltip"
                                                                                                                        title="{{ __('Update Task') }}"
                                                                                                                        id="editable"
                                                                                                                        class="btn textareaClassedit">
                                                                                                                        <i class="ti ti-pencil"
                                                                                                                            style="font-size: 20px;margin-right: -30px;"></i>
                                                                                                                    </a>


                                                                                                                    <a href="javascript:void(0)"
                                                                                                                        class="btn"
                                                                                                                        id="editable"
                                                                                                                        onclick="deleteTask({{ $task->id }}, {{ $org->id }}, 'lead');">
                                                                                                                        <i class="ti ti-trash "
                                                                                                                            style="font-size: 20px;"></i>
                                                                                                                    </a>

                                                                                                                </div>

                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </li>
                                                                                                @php
                                                                                                    $section++;
                                                                                                @endphp

                                                                                                @elseif($task->status == 0)
                                                                                                <div class="ps-3 py-2 d-flex gap-2 align-items-baseline"
                                                                                                    style="border-bottom: 1px solid rgb(192, 192, 192);">
                                                                                                    <i class="fa-regular fa-square-check"
                                                                                                        style="color: #000000;"></i>
                                                                                                    <h6 class="fw-bold">
                                                                                                        {{ $section2 == 1 ? 'Open Activity' : '' }}
                                                                                                    </h6>
                                                                                                </div>
                                                                                                <li class="list-group-item px-3"
                                                                                                    id="lihover">
                                                                                                    <div
                                                                                                        class="d-block d-sm-flex align-items-start">
                                                                                                        <div
                                                                                                            class="w-100">
                                                                                                            <div
                                                                                                                class="d-flex align-items-center justify-content-between">
                                                                                                                <div
                                                                                                                    class="mb-3 mb-sm-0">
                                                                                                                    <h5
                                                                                                                        class="mb-0">
                                                                                                                        {{ $task->name }}

                                                                                                                    </h5>
                                                                                                                    <span
                                                                                                                        class="text-muted text-sm">
                                                                                                                        {{ $task->created_at }}
                                                                                                                    </span><br>
                                                                                                                    <span
                                                                                                                        class="text-muted text-sm"><i
                                                                                                                            class="step__icon fa fa-user"
                                                                                                                            aria-hidden="true"></i>
                                                                                                                        {{ \App\Models\User::where('id', $task->assigned_to)->first()->name ?? '' }}

                                                                                                                        <span
                                                                                                                            class="d-flex">
                                                                                                                            <div>
                                                                                                                                Status
                                                                                                                            </div>
                                                                                                                            <div
                                                                                                                                class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ms-5 py-1">
                                                                                                                                <span>
                                                                                                                                    {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                                                </span>
                                                                                                                            </div>
                                                                                                                        </span>
                                                                                                                        {{-- --}}
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
                                                                                                                <div class="d-flex gap-3"
                                                                                                                    id="dellhover">

                                                                                                                    <a data-size="lg"
                                                                                                                        data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                                        data-ajax-popup="true"
                                                                                                                        data-bs-toggle="tooltip"
                                                                                                                        title="{{ __('Update Task') }}"
                                                                                                                        id="editable"
                                                                                                                        class="btn textareaClassedit">
                                                                                                                        <i class="ti ti-pencil"
                                                                                                                            style="font-size: 20px;margin-right: -30px;"></i>
                                                                                                                    </a>


                                                                                                                    <a href="javascript:void(0)"
                                                                                                                        class="btn"
                                                                                                                        id="editable"
                                                                                                                        onclick="deleteTask({{ $task->id }}, {{ $org->id }}, 'lead');">
                                                                                                                        <i class="ti ti-trash "
                                                                                                                            style="font-size: 20px;"></i>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endcan

                                            </div>


                                            <!-- Contacts related to organizations -->
                                            <div class="accordion d-none" id="accordionPanelsStayOpenExample">
                                                <!-- Open Accordion Item -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="panelsStayOpen-headingcontact">
                                                        <button class="accordion-button p-2" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapsecontact">
                                                            {{ __('Opportunities') }}
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
                                                                                        <th>Student Name</th>
                                                                                        <th>Course</th>
                                                                                        <th>University</th>
                                                                                        <th>Stage</th>
                                                                                        <th>Created at</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

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

                                <div class="tab-pane fade" id="pills-news" role="tabpanel"
                                    aria-labelledby="pills-news-tab">

                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                       <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingactive">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseactive">
                                                {{ __('Timeline') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseactive"
                                            class="accordion-collapse collapse show"
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
                                                                    <div class="bold" style="text-align: left; margin-left: 80px;">
                                                                          <p class="bold" style="margin-bottom: 0rem; color: #000000;">{{ $remark->title }}</p>
                                                                          <p class="m-0">{{ $remark->message }}</p>
                                                                          <span class="text-muted text-sm" style="cursor: pointer;" @can('show employee') onclick="openSidebar('/user/employee/{{ isset($activity->created_by) ? $activity->created_by : '' }}/show')"  @endcan ><i class="step__icon fa fa-user me-2" aria-hidden="true"></i>{{ isset($users[$activity->created_by]) ? $users[$activity->created_by] : '' }}</span>
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
                                    </div>
                                </div>

                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('script-page')
            <script>
                $('#create-notes').submit(function(event) {
                    event.preventDefault(); // Prevents the default form submission
                    $('#textareaID, .textareaClass').toggle("slide");
                });
            </script>
        @endpush
