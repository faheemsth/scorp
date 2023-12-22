<style>
    table tr td{
        font-size: 14px
    }
    .form-select{
        height: 30px;
        padding: 2px 10px;
        margin: 4px;
    }
    .accordion-button{
        font-size: 12px !important;
    }
    .accordion-item{
        border-radius: 0px;
    }
    .accordion-item:first-of-type .accordion-button{
        border-radius: 0px;
    }
    .accordion-button:focus{
        border: 0px;
        box-shadow: none;
    }
    input{
        margin: 4px;
    }
    .col-form{
        padding: 3px;
    }
    .row{
        padding: 6px
    }
</style>

{{ Form::open(array('url' => 'deals', 'id' => 'deal-updating-form')) }}
<div class="modal-body pt-0" style="height: 80vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-2 py-0" >
                {{-- Details Pill Start --}}
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <!-- Open Accordion Item -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                <button class="accordion-button p-2" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                    {{ __('ADMISSION INFORMATION') }}
                                </button>
                            </h2>

                            <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headinginfo">
                            <div class="accordion-body">

                                    <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                        <table class="w-100">
                                            <tbody>
                                                <tr>
                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                        {{ __('Admission Name') }}
                                                    </td>
                                                    <td class="d-flex gap-1 mb-1"
                                                        style="padding-left: 10px; font-size: 13px; text-align: left;">
                                                        <input type="text" class="form-control" placeholder="" name="name" value="{{ $deal->name }}">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                    {{ __('Intake Month') }}
                                                    </td>
                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-control select2" id="choice-1"  name="intake_month">
                                                            <option>Select Month</option>
                                                            @foreach($months as $key => $month)
                                                                <option value="{{$key}}" {{ $deal->intake_month == $key ? 'selected' : '' }} >{{$month}}</option>
                                                            @endforeach
                                                          </select>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                    {{ __('Intake Year') }}
                                                    </td>
                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-control select2" id="choice-2"  name="intake_year">
                                                            <option>Select year</option>
                                                            @foreach($years as $key => $year)
                                                                <option value="{{$key}}" {{ $deal->intake_year == $key ? 'selected' : '' }}>{{$year}}</option>
                                                            @endforeach
                                                          </select>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td class=""
                                                        style="width: 110px; text-align: right; font-size: 13px;">
                                                    {{ __('Linked Contact') }}
                                                    </td>
                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-control select2" id="choice-3"  name="contact[]" multiple>
                                                           <option value="">Select contact</option>

                                                           @foreach($clients as $key => $client)
                                                            <option value="{{$key}}" {{ in_array($key, $contacts) ? 'selected' : '' }}>{{$client}}</option>
                                                           @endforeach
                                                          </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class=""
                                                        style="width: 110px; text-align: right; font-size: 13px;">
                                                    {{ __('User Responsible') }}
                                                    </td>
                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-control select2" id="choice-4"  name="assigned_to">
                                                           <option value="">Select Employee</option>
                                                           @foreach($users as $key => $user)
                                                            <option value="{{$key}}" {{ $deal->assigned_to == $key ? 'selected' : '' }}>{{$user}}</option>
                                                           @endforeach
                                                          </select>
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
                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsecust">
                                {{ __('ADDITIONAL INFORMATION') }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapsecust" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingcust">
                                <div class="accordion-body">
                                    <!-- Accordion Content -->
                                    <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                        <table class="w-100">
                                            <tbody>
                                                <tr>
                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                    {{ __('Category') }}
                                                    </td>

                                                     <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-control select2" id="choice-5" name="category">
                                                           <option value="">Select Category</option>
                                                            <option value="Australia" {{ $deal->category == "Australia" ? "selected" : ""}}>Australia</option>
                                                            <option value="Canada" {{ $deal->category == "Canada" ? "selected" : "" }}>Canada</option>
                                                            <option value="China" {{ $deal->category == "China" ? "selected" : ""}}>China</option>
                                                            <option value="E-Learning" {{ $deal->category == "E-Learning" ? "selected" : "" }}>E-Learning</option>
                                                            <option value="Europe" {{ $deal->category == "Europe" ? "selected" : ""}}>Europe</option>
                                                            <option value="Malaysia" {{ $deal->category == "Malaysia" ? "selected" : "" }}>Malaysia</option>
                                                            <option value="Russia" {{ $deal->category == "Russia" ? "selected" : "" }}>Russia</option>
                                                            <option value="Turkey" {{ $deal->category == "Turkey" ? "selected" : "" }}>Turkey</option>
                                                            <option value="Ukraine" {{ $deal->category == "Ukraine" ? "selected" : "" }}>Ukraine</option>
                                                            <option value="United Kingdom" {{ $deal->category == "United Kingdom" ? "selected" : "" }}>United Kingdom</option>
                                                            <option value="United States of America" {{ $deal->category == "United Stated of America" ? "selected" : "" }}>United States of America</option>
                                                          </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class=""
                                                        style="width: 153px; text-align: right; font-size: 13px;">
                                                        {{ __('Institute') }}
                                                    </td>

                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-control select2" id="choice-6" name="university_id">
                                                           <option value="">Select University</option>
                                                           @foreach($universities as $key => $university)
                                                            <option value="{{$key}}" {{ $key == $deal->university_id ? "selected" : "" }}>{{$university}}</option>
                                                           @endforeach
                                                          </select>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td class=""
                                                        style="width: 153px; text-align: right; font-size: 13px;">
                                                        {{ __('Organization') }}
                                                    </td>

                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-select select2" id="choice-7" name="organization_id">
                                                           <option value="">Select Organization</option>
                                                           @foreach($organizations as $key => $organization)
                                                            <option value="{{$key}}" {{ $key == $deal->organization_id ? "selected" : "" }}>{{$organization}}</option>
                                                           @endforeach
                                                          </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                        {{ __('Office Responsible') }}
                                                    </td>
                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-select select2" id="choice-8" name="branch_id">
                                                           <option value="">Select Office</option>
                                                           @foreach($branches as $key => $branch)
                                                            <option value="{{$key}}" {{ $key == $deal->branch_id ? "selected" : "" }}>{{$branch}}</option>
                                                           @endforeach
                                                          </select>
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
                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseaddress">
                                {{ __('PIPELINE AND STAGES') }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseaddress" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingaddress">
                            <div class="accordion-body">
                                    <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                        <table class="w-100">
                                            <tbody>
                                                <tr>

                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                        {{ __('Pipeline') }}
                                                    </td>
                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-select select2" id="choice-9" name="pipeline_id">
                                                           <option value="">Select Pipeline</option>
                                                           @foreach($pipelines as $key => $pipeline)
                                                            <option value="{{$key}}" {{ $key == $deal->pipeline_id ? "selected" : "" }}>{{$pipeline}}</option>
                                                           @endforeach
                                                          </select>
                                                    </td>

                                                </tr>



                                                <tr>

                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                        {{ __('Stage') }}
                                                    </td>
                                                    <td class=""
                                                        style="padding-left: 10px; font-size: 13px; text-align: left; ">
                                                        <select class="form-select select2" id="choice-10" name="stage_id">
                                                           <option value="">Select Stage</option>
                                                           @foreach($stages as $key => $stage)
                                                            <option value="{{$key}}" {{ $key == $deal->stage_id ? "selected" : "" }}>{{$stage}}</option>
                                                           @endforeach
                                                          </select>
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
                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeynote">
                                {{ __('DESCRIPTION INFORMATION') }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapsekeynote" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingkeynote">
                            <div class="accordion-body">
                                    <div class="mt-1" style="margin-left: 10px; width: 65%;">
                                        <table class="w-100">
                                            <tbody>
                                                <tr>
                                                    <td class=""
                                                        style="width: 100px; text-align: right; font-size: 13px;">
                                                    Description
                                                    </td>
                                                    <td style="width: 374px; text-align: right; font-size: 13px;">
                                                        <div class="" style="margin-left: 14px;">
                                                            <textarea class="form-control" rows="4" placeholder="description" name="deal_description">{{ $deal->description }}</textarea>
                                                          </div>
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
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary edit-lead-btn">
</div>

{{Form::close()}}

