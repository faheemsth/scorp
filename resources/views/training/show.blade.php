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
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt=""
                            style="width:50px; height:50px;" class="">
                    </div>

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Training') }}</p>
                        <div class="d-flex align-items-baseline">
                            <h5 class="fw-bold">{{ $training->name }}</h5>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
























                    {{-- @can('edit trainer')
                        <a href="#!" data-size="lg" data-url="{{ route('trainer.edit', $training->id) }}"
                            data-ajax-popup="true" class="btn px-2 py-2 btn-dark  text-white" style="margin: auto"
                            data-bs-original-title="{{ __('Edit Trainer') }}" data-bs-toggle="tooltip"
                            title="{{ __('Edit Trainer') }}">
                            <i class="ti ti-pencil"></i>
                        </a>
                    @endcan

                    @can('delete trainer')
                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['trainer.destroy', $training->id],
                            'id' => 'delete-form-' . $training->id,
                        ]) !!}
                        <a href="#!" class="btn px-2 py-2 btn-danger mt-3 text-white bs-pass-para "
                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                            data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                            data-confirm-yes="document.getElementById('delete-form-{{ $training->id }}').submit();"
                            data-bs-toggle="tooltip" title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}">
                            <i class="ti ti-archive"></i>
                        </a>
                        {!! Form::close() !!}
                    @endcan --}}








                    @can('edit training')
                    <div class="action-btn bg-primary ms-2">
                        <a href="#"
                            data-url="{{ route('training.edit', $training->id) }}"
                            data-size="lg" data-ajax-popup="true"
                            data-title="{{ __('Edit Training') }}" data-bs-toggle="tooltip"
                            title="{{ __('Edit Training') }}"
                            data-original-title="{{ __('Edit Training ') }}"
                            class="btn px-2 py-2 btn-dark  text-white">
                            <i class="ti ti-pencil text-white"></i></a>
                    </div>
                @endcan
                @can('delete training')

                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['training.destroy', $training->id],
                            'id' => 'delete-form-' . $training->id,
                        ]) !!}
                        <a href="#"
                            class="btn px-2 py-2 btn-danger mx-1 text-white bs-pass-para "
                            data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                            data-confirm-yes="document.getElementById('delete-form-{{ $training->id }}').submit();"
                            data-bs-toggle="tooltip" title="{{ __('Delete Training') }}"
                            data-original-title="{{ __('Delete Training') }}">
                            <i class="ti ti-trash text-white"></i></a>
                        {!! Form::close() !!}

                @endcan























                </div>
            </div>





            <div class="lead-content my-2">

                <div class="card me-3">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
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
                                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                                {{ __('TRAINING INFORMATION') }}
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
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $training->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Training Type') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ !empty($training->types) ? $training->types->name : '' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Training Status') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    @if ($training->status == 0)
                                                                        <span
                                                                            class="status_badge badge bg-warning p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                                                    @elseif($training->status == 1)
                                                                        <span
                                                                            class="status_badge badge bg-primary p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                                                    @elseif($training->status == 2)
                                                                        <span
                                                                            class="status_badge badge bg-success p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                                                    @elseif($training->status == 3)
                                                                        <span
                                                                            class="status_badge badge bg-info p-2 px-3 rounded">{{ __($status[$training->status]) }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>






                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $training->brand }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Region') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $training->region }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $training->branch }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Employee') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $training->assignName }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Trainer') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ !empty($training->trainers) ? $training->trainers->firstname : '' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Training Duration') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ \Auth::user()->dateFormat($training->start_date) . ' to ' . \Auth::user()->dateFormat($training->end_date) }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $training->created_at }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Update at') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $training->updated_at }}
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
