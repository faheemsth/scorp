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
                        <p class="pb-0 mb-0 fw-normal">{{ __('Appraisal') }}</p>
                        <div class="d-flex align-items-baseline">
                            <h5 class="fw-bold">{{ $appraisal->name }}</h5>
                        </div>
                    </div>

                </div>
                <div class="d-flex justify-content-end gap-1 me-3">
                    @can('edit appraisal')
                        <div class="action-btn bg-primary ms-2">
                            <a href="#" data-url="{{ route('appraisal.edit',$appraisal->id) }}"
                                data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Appraisal')}}"
                                data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}"
                                class="btn px-2 py-2 btn-dark text-white">
                            <i class="ti ti-pencil text-white"></i></a>
                        </div>
                    @endcan
                    @can('delete appraisal')
                            <div class="action-btn bg-danger ms-2">
                            {!! Form::open(['method' => 'DELETE', 'route' => ['appraisal.destroy', $appraisal->id],'id'=>'delete-form-'.$appraisal->id]) !!}
                                <a href="#" class="btn px-2 py-2 btn-danger text-white bs-pass-para" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="document.getElementById('delete-form-{{$appraisal->id}}').submit();">
                                <i class="ti ti-archive"></i></a>
                            {!! Form::close() !!}
                            </div>
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
                                                {{ __('INDICATOR INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headinginfo">
                                            <div class="accordion-body">

                                                <div class="accordion-body">

                                                    <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                        <table>
                                                            <tbody>

                                                                <tr>
                                                                    <td class=""
                                                                        style="width: 150px; font-size: 14px;">
                                                                        {{ __('Brand') }}
                                                                    </td>
                                                                    <td class=""
                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                        {{ $appraisal->brand }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=""
                                                                        style="width: 150px; font-size: 14px;">
                                                                        {{ __('Region') }}
                                                                    </td>
                                                                    <td class=""
                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                        {{ $appraisal->region }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=""
                                                                        style="width: 150px; font-size: 14px;">
                                                                        {{ __('Branch') }}
                                                                    </td>
                                                                    <td class=""
                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                        {{ $appraisal->branch }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td class=""
                                                                        style="width: 150px; font-size: 14px;">
                                                                        {{ __('Assign To') }}
                                                                    </td>
                                                                    <td class=""
                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                        {{ $appraisal->created_user }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=""
                                                                        style="width: 150px; font-size: 14px;">
                                                                        {{ __('Appraisal Date') }}
                                                                    </td>
                                                                    <td class=""
                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                        {{ $appraisal->appraisal_date }}
                                                                    </td>
                                                                </tr>


                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>

                                                            <div class="modal-body">



                                                                <div class="row">


                                                                    <div class="col-5  text-end"
                                                                        style="margin-left: 51px;">
                                                                        <h5>{{ __('Indicator') }}</h5>
                                                                    </div>
                                                                    <div class="col-4  text-end">
                                                                        <h5>{{ __('Appraisal') }}</h5>
                                                                    </div>
                                                                    @foreach ($performance_types as $performance_type)
                                                                        <div class="col-md-12 mt-3">
                                                                            <h6>{{ $performance_type->name }}</h6>
                                                                            <hr class="mt-0">
                                                                        </div>

                                                                        @foreach ($performance_type->types as $types)
                                                                            <div class="col-4">
                                                                                {{ $types->name }}
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <fieldset id='demo' class="rating">
                                                                                    <input class="stars" type="radio"
                                                                                        id="technical-5*-{{ $types->id }}"
                                                                                        name="ratings[{{ $types->id }}]"
                                                                                        value="5"
                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 5 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-5*-{{ $types->id }}"
                                                                                        title="Awesome - 5 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-4*-{{ $types->id }}"
                                                                                        name="ratings[{{ $types->id }}]"
                                                                                        value="4"
                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 4 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-4*-{{ $types->id }}"
                                                                                        title="Pretty good - 4 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-3*-{{ $types->id }}"
                                                                                        name="ratings[{{ $types->id }}]"
                                                                                        value="3"
                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 3 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-3*-{{ $types->id }}"
                                                                                        title="Meh - 3 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-2*-{{ $types->id }}"
                                                                                        name="ratings[{{ $types->id }}]"
                                                                                        value="2"
                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 2 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-2*-{{ $types->id }}"
                                                                                        title="Kinda bad - 2 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-1*-{{ $types->id }}"
                                                                                        name="ratings[{{ $types->id }}]"
                                                                                        value="1"
                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 1 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-1*-{{ $types->id }}"
                                                                                        title="Sucks big time - 1 star"></label>
                                                                                </fieldset>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <fieldset id='demo1'
                                                                                    class="rating">
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-5-{{ $types->id }}"
                                                                                        name="rating[{{ $types->id }}]"
                                                                                        value="5"
                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 5 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-5-{{ $types->id }}"
                                                                                        title="Awesome - 5 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-4-{{ $types->id }}"
                                                                                        name="rating[{{ $types->id }}]"
                                                                                        value="4"
                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 4 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-4-{{ $types->id }}"
                                                                                        title="Pretty good - 4 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-3-{{ $types->id }}"
                                                                                        name="rating[{{ $types->id }}]"
                                                                                        value="3"
                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 3 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-3-{{ $types->id }}"
                                                                                        title="Meh - 3 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-2-{{ $types->id }}"
                                                                                        name="rating[{{ $types->id }}]"
                                                                                        value="2"
                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 2 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-2-{{ $types->id }}"
                                                                                        title="Kinda bad - 2 stars"></label>
                                                                                    <input class="stars"
                                                                                        type="radio"
                                                                                        id="technical-1-{{ $types->id }}"
                                                                                        name="rating[{{ $types->id }}]"
                                                                                        value="1"
                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 1 ? 'checked' : '' }}
                                                                                        disabled>
                                                                                    <label class="full"
                                                                                        for="technical-1-{{ $types->id }}"
                                                                                        title="Sucks big time - 1 star"></label>
                                                                                </fieldset>
                                                                            </div>
                                                                        @endforeach
                                                                    @endforeach
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <h6>{{ __('Remark') }}</h6>
                                                                    </div>
                                                                    <div class="col-md-12 mt-3">
                                                                        <p class="text-sm">{{ $appraisal->remark }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                            </div>








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
