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
</style>

{{ Form::open(['url' => 'region/create', 'method' => 'POST' ,'id' => 'CreateRigon']) }}
<div class="modal-body py-0" style="height: 80vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-2 py-0">
            {{-- Details Pill Start --}}
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <!-- Open Accordion Item -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseinfo">
                            {{ __('REGION INFORMATION') }}
                        </button>
                    </h2>

                    <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headinginfo">
                        <div class="accordion-body">

                            <div class="mt-1" style="margin-left: 10px; width: 65%;">
                                <input type="hidden" value="{{ optional($regions)->id ?? '' }}" name="id">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class=""
                                                style="width: 100px; text-align: right; font-size: 13px;">
                                                {{ __('Name') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" placeholder="Name"
                                                    value="{{ optional($regions)->name ?? '' }}" name="name">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class=""
                                                style="width: 100px; text-align: right; font-size: 13px;">
                                                {{ __('Location') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" placeholder="Enter Location"
                                                    value="{{ optional($regions)->location ?? '' }}" name="location">
                                            </td>
                                        </tr>


                                        <tr>
                                            <td class=""
                                                style="width: 100px; text-align: right; font-size: 13px;">
                                                {{ __('Phone') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" placeholder="Enter Phone"
                                                    value="{{ optional($regions)->phone ?? '' }}" name="phone">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class=""
                                                style="width: 100px; text-align: right; font-size: 13px;">
                                                {{ __('Email') }}
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="email" class="form-control" placeholder="Enter Email"
                                                    value="{{ optional($regions)->email ?? '' }}" name="email">
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
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-dark px-2 new-lead-btn">
</div>

{{ Form::close() }}


