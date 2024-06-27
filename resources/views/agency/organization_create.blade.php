<form id="organization-creating-form">
    @csrf

    <style>
        .form-group {
            margin-bottom: 0px;
            padding-top: 0px;
        }

        .col-form-label {
            text-align: left;
        }

        .space {
            padding: 3px 3px;
        }
    </style>
    <div class="modal-body pt-0 " style="height: 80vh;">
        <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
            <div class="card-body px-2 py-0">
                {{-- ACCORDION --}}
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    {{-- Organizaiton Basic Info --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseOne">
                                ORGANIZATION NAME
                            </button>
                        </h2>


                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">

                                <div class="form-group row ">
                                    <label for="organization-name" class="col-sm-3 col-form-label"> Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="organization-name" value=""
                                            placeholder="Organization Name" name='organization_name'>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    <label for="brand_id" class="col-sm-3 col-form-label"> Brand</label>
                                    <div class="col-sm-6">
                                        {!! Form::select('brand_id', $companies, 0, [
                                            'class' => 'form-control select2 brand_id',
                                            'id' => 'brand_id',
                                        ]) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    {{-- Organizaiton Contact Info --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseTwo">
                                ORGANIZATION CONTACT DETAILS
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <div class="form-group row">
                                    <label for="phone" class="col-sm-3 col-form-label">Phone <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="phone" value=""
                                            name="organization_phone">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control" id="email" value=""
                                            name="organization_email">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="website" class="col-sm-3 col-form-label">Website</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="website" value=""
                                            name="organization_website">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="linkedin" class="col-sm-3 col-form-label">Linkedin</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="linkedin" value=""
                                            name="organization_linkedin">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="facebook" class="col-sm-3 col-form-label">Facebook</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="facebook" value=""
                                            name="organization_facebook">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="twitter" class="col-sm-3 col-form-label">Twitter</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="twitter" value=""
                                            name="organization_twitter">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    {{-- Organizaiton contact person Info --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseThree">
                                CONTACT PERSON INFORMATION
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingThree">
                            <div class="accordion-body">

                                <div class="form-group row ">
                                    <label for="contactname" class="col-sm-3 col-form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="contactname" value=""
                                            placeholder="Name" name='contactname'>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="contactemail" class="col-sm-3 col-form-label">Email </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="contactemail" value=""
                                            placeholder="Email" name='contactemail'>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="contactphone" class="col-sm-3 col-form-label">Phone </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="contactphone" value=""
                                            placeholder="Phone" name='contactphone'>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="contactjobroll" class="col-sm-3 col-form-label">Job Roll </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="contactjobroll"
                                            value="" placeholder="Job Roll" name='contactjobroll'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Organizaiton Address Info --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseThree">
                                ADDRESS INFORMATION
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingThree">
                            <div class="accordion-body">

                                <div class="form-group row">
                                    <label for="billing-addres" class="col-sm-3 col-form-label">Billing
                                        Address</label>
                                    <div class="col-6">
                                        <div class="col-12 px-0">
                                            <textarea name="organization_billing_street" class="form form-control" id="" cols="30"
                                                rows="3"></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="billing-addres" class="col-sm-3 col-form-label">Country
                                    </label>
                                    <div class="col-6">
                                        <div class="col-12 px-0">
                                            <select name="organization_billing_country" id="countries"
                                                class="form form-select select2 CountryCode">
                                                <option value="">Select country</option>
                                                @foreach ($countries as $key => $country)
                                                    <option value="{{ $country['name'] }}-{{ $country['code'] }}">
                                                        {{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="billing-addres" class="col-sm-3 col-form-label">City
                                    </label>
                                    <div class="col-6">
                                        <div class="col-12 px-0" id="Cities_divs_create">
                                            <select name="city" id="city" class="form form-select select2">
                                                <option value="">Select cities</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="billing-addres" class="col-sm-3 col-form-label">Complete Address</label>
                                <div class="col-6">
                                    <div class="col-12 px-0">
                                        <textarea name="c_address" class="form form-control" id="" cols="30"
                                            rows="3"></textarea>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                {{-- Organizaiton Description --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-description" aria-expanded="false"
                            aria-controls="panelsStayOpen-description">
                            DESCRIPTION INFORMATION
                        </button>
                    </h2>
                    <div id="panelsStayOpen-description" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            <textarea name="organization_description" id="" cols="30" rows="3" class="form form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-dark new-organization-btn">Create</button>
    </div>
</form>

@push('script-page')
    <script>
        $(document).on("submit", "#organization-creating-form", function(e) {

            e.preventDefault();
            var formData = $(this).serialize();

            $(".new-new-organization-btn").val('Processing...');
            $('.new-new-organization-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "{{ route('agency.store') }}",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').css('display', 'none');
                        $("#commonModal").removeClass('show');
                        openSidebar('/get-agency-detail?id=' + data.org);
                        return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".new-organization-btn").val('Create');
                        $('.new-organization-btn').removeAttr('disabled');
                    }
                }
            });
        });
    </script>
@endpush
