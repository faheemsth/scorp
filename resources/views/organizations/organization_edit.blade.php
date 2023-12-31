    <div class="modal-content">
        <form action="{{route('organization.update', $org->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header pt-3">
                <div class="float-left d-flex">
                    <div class="lead-avator">
                        <img src="https://d3rqem538l0q4a.cloudfront.net/img/placeholder-organization.png" alt="" class="" style="width: 40px; height: 40px;">
                    </div>

                <div class="lead-basic-info my-auto" style="margin-left: 10px;">
                    <span
                        style="font-size: 14px; line-height: 11px; color: rgb(119, 119, 119);">{{ __('Create Organizaiton') }}</span><br>
                    <h1 class=""
                        style="font-weight: normal;
                font-size: 21px;
                line-height: 25px;
                display: inline;
                white-space-collapse: collapse;
                text-wrap: nowrap;
                margin: 0px;">
                        {{ __('Update Organization') }}
                    </h1>
                </div>

            </div>
        </div>
            <style>
                .form-group {
                    margin-bottom: 0px;
                    padding-top: 0px;
                }

                .col-form-label {
                    text-align: center;
                }

                .space {
                    padding: 3px 3px;
                }
            </style>
            {{-- ACCORDION --}}
            <div class="modal-body pt-0 " style="height: 80vh;">
                <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
                <div class="card-body px-2 py-0" >

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

                            <div class="form-group row">
                                <label for="organization-name" class="col-sm-3 col-form-label">Organization
                                    Name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="organization-name"
                                        value="{{ $org->name }}" placeholder="Organization Name"
                                        name='organization_name'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="type-of-organization" class="col-sm-3 col-form-label">Type Of
                                    Organization</label>
                                <div class="col-sm-6">
                                    <select name="organization_type" id="" class="form form-select">
                                        <option value="">Select Type</option>
                                         @foreach($org_types as $key => $type)
                                                <option value="{{$type}}" <?= $org_detail->type == $type ? 'selected' : ''?> >{{$type}}</option>
                                                @endforeach
                                    </select>
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
                                <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="phone"
                                        value="{{ $org_detail->phone }}" name="organization_phone">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" id="email"
                                        value="{{ $org->email }}" name="organization_email">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="website" class="col-sm-3 col-form-label">Website</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="website"
                                        value="{{ $org_detail->website }}" name="organization_website">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="linkedin" class="col-sm-3 col-form-label">Linkedin</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="linkedin"
                                        value="{{ $org_detail->linkedin }}" name="organization_linkedin">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="facebook" class="col-sm-3 col-form-label">Facebook</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="facebook"
                                        value="{{ $org_detail->facebook }}" name="organization_facebook">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="twitter" class="col-sm-3 col-form-label">Twitter</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="twitter"
                                        value="{{ $org_detail->twitter }}" name="organization_twitter">
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
                                <label for="billing-addres" class="col-sm-3 col-form-label">Billing Address</label>
                                <div class="col-sm-6">
                                    <div class="col-12">
                                        <textarea name="organization_billing_street" class="form form-control" id="" cols="30"
                                            rows="3">{{ $org_detail->billing_street }}</textarea>
                                    </div>

                                    <div class="row mt-1 mx-0">
                                        <div class="col-6 mt-1 space">
                                            <input type="text" class="form-control" id="billing-city"
                                                placeholder="Billing City" value="{{ $org_detail->billing_city }}"
                                                name="organization_billing_city">
                                        </div>

                                        <div class="col-6 mt-1 space">
                                            <input type="text" class="form-control" id="billing-state"
                                                placeholder="Billing State" value="{{ $org_detail->billing_state }}"
                                                name="organization_billing_state">
                                        </div>

                                        <div class="col-6 mt-1 space">
                                            <input type="text" class="form-control" id="billing-postal-code"
                                                placeholder="Billing Postal Code"
                                                value="{{ $org_detail->billing_postal_code }}"
                                                name="organization_billing_postal_code">
                                        </div>


                                        <div class="col-6 mt-1 space">
                                            <select name="organization_billing_country" id="" class="form form-select">
                                                <option value="">Select country</option>
                                                @foreach($countries as $country)
                                                        <option value="{{$country}}" <?= isset($org_detail->billing_country) && $org_detail->billing_country  == $country ? 'selected' : '' ?> >{{$country}}</option>
                                                        @endforeach
                                            </select>
                                        </div>
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
                            <textarea name="organization_description" id="" cols="30" rows="3" class="form form-control">{{ $org_detail->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-dark">Save changes</button>
        </div>
    </form>
</div>
