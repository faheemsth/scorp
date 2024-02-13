<style>
    table tr td {
        font-size: 14px
    }

    .form-select {
        height: 30px;
        padding: 2px 10px;
        margin: 4px;
    }

    .accordion-button {
        font-size: 12px !important;
    }

    .accordion-item {
        border-radius: 0px;
    }

    .accordion-item:first-of-type .accordion-button {
        border-radius: 0px;
    }

    .accordion-button:focus {
        border: 0px;
        box-shadow: none;
    }

    input {
        margin: 4px;
    }

    .col-form {
        padding: 3px;
    }

    .row {
        padding: 6px
    }

    .choices {
        width: 100%;
    }
</style>

{{ Form::open(['url' => 'region/create', 'method' => 'POST', 'id' => 'CreateRegion', 'novalidate' => 'novalidate']) }}
<div class="modal-body py-0">
    <div class="lead-content my-2" style="height: 100%;">
        <div class="card-body px-2 py-0">
            {{-- Details Pill Start --}}
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <!-- Open Accordion Item -->
                <div class="accordion-item">
                    <!--<h2 class="accordion-header" id="panelsStayOpen-headinginfo">-->
                    <!--    <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"-->
                    <!--        data-bs-target="#panelsStayOpen-collapseinfo">-->
                    <!--        {{ __('N') }}-->
                    <!--    </button>-->
                    <!--</h2>-->

                    <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                        <div class="accordion-body">

                            <div class="mt-1" style="margin-left: 10px; width: 65%;">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 150px;  font-size: 13px;">
                                                {{ __('Name') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" placeholder="Name" value="" name="name">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="" style="width: 150px;  font-size: 13px;">
                                                {{ __('Brands') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">

                                                <select class="form form-control select2" id="choices-multiple55" name="brands[]" style="width: 100% !important;" required>
                                                    <option value="">Select Brand</option>
                                                    @foreach ($brands as $key => $brand)
                                                    <option value="{{ $key }}">{{ $brand }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="" style="width: 150px;  font-size: 13px;">
                                                {{ __('Location') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" placeholder="Enter Location" value="" name="location">
                                            </td>
                                        </tr>


                                        <tr>
                                            <td class="" style="width: 150px;  font-size: 13px;">
                                                {{ __('Phone') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" placeholder="Enter Phone" value="" id="phone" name="phone">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 150px;  font-size: 13px;">
                                                {{ __('Email') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="email" class="form-control" placeholder="Enter Email" value="" name="email">
                                            </td>
                                        </tr>
                                        <tr class="d-none">
                                            <td style="width: 150px;  font-size: 13px;">
                                                {{ __('Region Manager') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form form-control select2" id="choices-multiple555" name="region_manager_id">
                                                    <option value="">Select Region Manager</option>
                                                    @if(!empty($regionmanager))
                                                    @foreach ($regionmanager as $regionmanage)
                                                    <option value="{{$regionmanage->id }}">{{$regionmanage->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
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

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-dark px-2 create-region">
</div>

{{ Form::close() }}
<script>
    const input = document.querySelector("#phone");
    window.intlTelInput(input, {
        utilsScript: "{{ asset('js/intel_util.js') }}",
    });
</script>