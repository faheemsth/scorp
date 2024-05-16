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
</style>
<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0">
    <div class="row">
        <div class="col-sm-12">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between align-items-center p-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" style="width:50px; height:50px;" class="">
                    </div>
                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Email Template') }}</p>
                        <div class="d-flex align-items-baseline">
                            <h5 class="fw-bold">{{ $emailMarketing->name }}</h5>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">

                    <a href="#!" data-size="lg" data-url="{{ url('email_template_type_update?id=').$emailMarketing->id }}" data-ajax-popup="true" class="btn px-2 py-2 btn-dark text-white" data-bs-original-title="{{__('Edit Email Template')}}" data-bs-toggle="tooltip" title="{{ __('Edit Email Template') }}">
                    <i class="ti ti-pencil"></i>
                      </a>

                      {!! Form::open(['method' => 'GET', 'class' => 'mb-0', 'url' => [url('email_template_type_delete')], 'id' => 'delete-form-'.$emailMarketing->id]) !!}
                      <input type="hidden" name="id" value="{{ $emailMarketing->id }}" id="">
                      <button type="submit" class="btn px-2 py-2 btn-danger text-white bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete Email Template') }}">
                          <i class="ti ti-archive"></i>
                      </button>
                      {!! Form::close() !!}
                </div>
            </div>





            <div class="lead-content my-2">

                <div class="card me-3">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">

                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                                {{ __('EMAIL INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $emailMarketing->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $emailMarketing->name }}
                                                                </td>
                                                            </tr>


                                                            <tr >
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $users[$emailMarketing->brand_id]  ?? ''}}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Region') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $branches[$emailMarketing->branch_id] ?? '' }}
                                                                </td>
                                                            </tr>

                                                            <tr >
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $regiones[$emailMarketing->region_id]  ?? ''}}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $emailMarketing->created_at }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Update at') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $emailMarketing->updated_at }}
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
                        <!-- End of Open Accordion Item -->

                        <!-- Add More Accordion Items Here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
