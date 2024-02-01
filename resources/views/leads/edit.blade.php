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

{{ Form::open(['url' => 'leads', 'id' => 'lead-updating-form']) }}

<input type="hidden" value="{{$lead->id}}" class="lead_id">
<div class="modal-body pt-0" style="height: 80vh">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-2 py-0" >
                {{-- Details Pill Start --}}
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <!-- Open Accordion Item -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                <button class="accordion-button p-2" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                    {{ __('LEAD INFORMATION') }}
                                </button>
                            </h2>
                            <input type="hidden" value="{{$lead}}" class="lead">
                            <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headinginfo">
                            <div class="accordion-body">

                            <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Name') }}
                                                <span class="text-danger">*</span>
                                            </td>

                                            @php
                                                $name = explode(' ', $lead->name);
                                            @endphp
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control d-none" placeholder="Prefix"
                                                    name="lead_prefix" value="{{ $lead->title }}">
                                                <input type="text" class="w-50 form-control" placeholder="First Name"
                                                    name="lead_first_name" value="{{ $name[0] }}" required>
                                                <input type="text" class="w-50 form-control" placeholder="Last Name"
                                                    name="lead_last_name" value="{{ isset($name[1]) ? $name[1] : '' }}"
                                                    required>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Lead Status') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form-control select2" id="choice-1" name="lead_stage">
                                                    <option>Select status</option>
                                                    @foreach ($stages as $key => $stage)
                                                        <option value="{{ $key }}"
                                                            <?= $lead->stage_id == $key ? 'selected' : '' ?>>
                                                            {{ $stage }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Brand') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="">
                                               {{-- Brand Dropdown --}}
                                                @if (
                                                    \Auth::user()->type == 'super admin' ||
                                                        \Auth::user()->type == 'Project Director' ||
                                                        \Auth::user()->type == 'Project Manager')

                                                    <select class="form-control select2 brand_id" id="choices-1011"
                                                        name="brand_id" {{ !\Auth::user()->can('edit brand lead') ? 'disabled' : '' }}>
                                                        <option value="">Select Brand</option>
                                                        @foreach ($companies as $key => $company)
                                                            <option value="{{ $key }}"
                                                                {{ $key == $lead->brand_id ? 'selected' : '' }}>
                                                                {{ $company }}</option>
                                                        @endforeach
                                                    </select>

                                                @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                                                    <input type="hidden" name="brand_id" value="{{\Auth::user()->id}}">
                                                    <select class='form-control select2 brand_id' disabled ="brands" id="brand_id" >
                                                        @foreach($companies as $key => $comp)
                                                            <option value="{{$key}}" {{ $key == \Auth::user()->id ? 'selected' : ''}}>{{$comp}}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="hidden" name="brand_id" value="{{\Auth::user()->brand_id}}">
                                                        <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
                                                            @foreach($companies as $key => $comp)
                                                            <option value="{{$key}}" {{ $key == \Auth::user()->brand_id ? 'selected' : ''}}>{{$comp}}</option>
                                                            @endforeach
                                                        </select>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Region') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="region_div">

                                                @if (\Auth::user()->type == 'super admin' ||
                                                        \Auth::user()->type == 'Project Director' ||
                                                        \Auth::user()->type == 'Project Manager' ||
                                                        \Auth::user()->type == 'company' ||
                                                        \Auth::user()->type == 'Regional Manager')

                                                            {!! Form::select('region_id', $regions, $lead->region_id, [
                                                                'class' => 'form-control select2',
                                                                'id' => 'region_id',
                                                            ]) !!}

                                                @else
                                                     <input type="hidden" name="region_id" value="{{ $lead->region_id }}">
                                                        {!! Form::select('region_id', $regions, $lead->region_id, [
                                                            'class' => 'form-control select2',
                                                            'disabled' => 'disabled',
                                                            'id' => 'region_id',
                                                        ]) !!}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Branch') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="branch_div">


                                                @if (\Auth::user()->type == 'super admin' ||
                                                        \Auth::user()->type == 'Project Director' ||
                                                        \Auth::user()->type == 'Project Manager' ||
                                                        \Auth::user()->type == 'company' ||
                                                        \Auth::user()->type == 'Regional Manager' ||
                                                        \Auth::user()->type == 'Branch Manager')
                                                            <select name="lead_branch" id="branch_id" class="form-control select2 branch_id"
                                                                onchange="Change(this)" {{ !\Auth::user()->can('edit branch lead') ? 'disabled' : '' }}>
                                                                    @foreach($branches as $key => $branch)
                                                                        <option value="{{$key}}" {{ $lead->branch_id == $key ? 'selected' : '' }}>{{$branch}}</option>
                                                                    @endforeach
                                                            </select>
                                                @else
                                                         <input type="hidden" name="lead_branch" value="{{ \Auth::user()->branch_id }}">
                                                            <select name="branch_id" id="branch_id" class="form-control select2 branch_id"
                                                                onchange="Change(this)" {{ !\Auth::user()->can('edit branch lead') ? 'disabled' : '' }}>
                                                                    @foreach($branches as $key => $branch)
                                                                        <option value="{{$key}}" {{ $lead->branch_id == $key ? 'selected' : '' }}>{{$branch}}</option>
                                                                    @endforeach
                                                            </select>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 110px;  font-size: 13px;">
                                                {{ __('User Responsible') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="assign_to_div">
                                                <select class="form-control select2" id="choice-2"
                                                    name="lead_assgigned_user" {{ !\Auth::user()->can('edit assign to lead') ? 'disabled' : '' }}>
                                                    <option value="">Select User</option>
                                                    @foreach ($users as $key => $user)
                                                        <option value="{{ $key }}"
                                                            <?= $lead->user_id == $key ? 'selected' : '' ?>>
                                                            {{ $user }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Agency') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form-control select2" id="choice-4"
                                                    name="lead_organization">
                                                    <option value="">Select Agency</option>
                                                    @foreach ($organizations as $key => $org)
                                                        <option value="{{ $key }}"
                                                            <?= $lead->organization_id == $key ? 'selected' : '' ?>>
                                                            {{ $org }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Lead Source') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form-control select2" id="choice-5" name="lead_source">
                                                    <option value="">Select source</option>
                                                    @foreach ($sources as $key => $source)
                                                        <option value="{{ $key }}"
                                                            <?= $lead->sources == $key ? 'selected' : '' ?>>
                                                            {{ $source }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Drive Link') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form form-control" name="drive_link"
                                                    value="{{ $lead->drive_link }}">
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
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapsecust">
                            {{ __('CUSTOMER INFORMATION') }}
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
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Email Address') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="email" class="form-control" name="lead_email"
                                                    value="{{ $lead->email }}" required>
                                            </td>
                                        </tr>

                                        <tr class="d-none">
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Email Address (Referrer)') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="email" class="form-control" name="referrer_email"
                                                    value="{{ $lead->referrer_email }}">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Mobile Phone') }}
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" name="lead_phone"
                                                    value="{{ $lead->phone }}" required>
                                            </td>
                                        </tr>

                                        <tr class="d-none">
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Mobile Phone') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" name="lead_mobile_phone"
                                                    value="{{ $lead->mobile_phone }}">

                                            </td>
                                        </tr>

                                        <tr class="d-none">
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Email Opted Out') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="checkbox" name="" id="" class="ms-2">
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
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseaddress">
                            {{ __('CUSTOMER ADDRESS') }}
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
                                                style="width: 115PX; font-size: 13px;padding-right: 20px;">
                                                Address
                                            </td>
                                            <td class="" style="width: 350PX;  font-size: 13px; bg-danger">
                                                
                                                <div class="row">
                                                    <div class="col-12 col-form" style="text-align: left;">
                                                        <select class="form-control select2" id="choice-6"
                                                            name="lead_country">
                                                            <option>Country...</option>
                                                            @foreach ($countries as $con)
                                                                <option value="{{ $con }}"
                                                                    <?= $con == $lead->country ? 'selected' : '' ?>>
                                                                    {{ $con }}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>

                                                    <div class="col-6 col-form">
                                                        <input type="text" class="form-control"
                                                            id="formGroupExampleInput" placeholder="City"
                                                            name="lead_city" value="{{ $lead->city }}">
                                                    </div>
                                                    <div class="col-6 col-form">
                                                        <input type="text" class="form-control"
                                                            id="formGroupExampleInput" placeholder="State/Province"
                                                            name="lead_state" value="{{ $lead->state }}">
                                                    </div>
                                                    <div class="col-6 col-form">
                                                        <input type="text" class="form-control"
                                                            id="formGroupExampleInput" placeholder="Postel Code"
                                                            name="lead_postal_code" value="{{ $lead->postal_code }}">
                                                    </div>
                                                   
                                                    <div class="col-6 col-form">
                                                        <input type="text" class="form form-control" name="lead_street" value="{{ $lead->street }}" placeholder="Address...">
                                                    </div>
                                                </div>
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
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapsekeynote">
                            {{ __('KEYNOTE') }}
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapsekeynote" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingkeynote">
                        <div class="accordion-body">
                            <div class="mt-1" style="margin-left: 10px; width: 65%;">
                                <table>
                                    <tbody class="w-100">
                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                Description
                                            </td>
                                            <td style="width: 374px;  font-size: 13px;">
                                                <div class="" style="margin-left: 14px;">
                                                    <textarea class="form-control" rows="4" placeholder="description" name="lead_description">{{ $lead->keynotes }}</textarea>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingkeytag">
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapsekeytag">
                            {{ __('TAG LIST') }}
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapsekeytag" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingkeytag">
                        <div class="accordion-body">
                            <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Tag List') }}
                                            </td>
                                            <td class="" style="padding-left: 10px;">
                                                <select name="lead_tags_list" id="choice-6" class="form form-control select2">
                                                    <option value="Public" {{ $lead->tags == 'Public' ? 'selected' : ''}} >Public</option>
                                                    <option value="Private" {{ $lead->tags == 'Private' ? 'selected' : ''}} >Private</option>
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
    <input type="button" value="{{ __('Cancel') }}" class="btn px-2 mx-1 btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-dark px-2 update-lead-btn">
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {

        // var id = {{ $lead->brand_id }};
        // $.ajax({
        //     type: 'GET',
        //     url: '{{ route('filter-regions') }}',
        //     data: {
        //         id: id
        //     },
        //     success: function(data){
        //         data = JSON.parse(data);

        //         if (data.status === 'success') {
        //             $('#region_div').html('');
        //             $("#region_div").html(data.html);
        //             select2();
        //         } else {
        //             console.error('Server returned an error:', data.message);
        //         }
        //     },
        //     error: function(xhr, status, error) {
        //         console.error('AJAX request failed:', status, error);
        //     }
        // });



        // var id = {{ $lead->region_id }};

        //     $.ajax({
        //         type: 'GET',
        //         url: '{{ route('filter-branches') }}',
        //         data: {
        //             id: id
        //         },
        //         success: function(data){
        //             data = JSON.parse(data);

        //             if (data.status === 'success') {
        //                 $('#branch_div').html('');
        //                 $("#branch_div").html(data.html);
        //                 select2();
        //             } else {
        //                 console.error('Server returned an error:', data.message);
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('AJAX request failed:', status, error);
        //         }
        //     });

});
</script>
<script>
    $(".brand_id").on("change", function(){
        // var id = $(this).val();

        // $.ajax({
        //     type: 'GET',
        //     url: '{{ route('lead_companyemployees') }}',
        //     data: {
        //         id: id  // Add a key for the id parameter
        //     },
        //     success: function(data){
        //         data = JSON.parse(data);

        //         if (data.status === 'success') {
        //             $("#assign_to_div").html(data.employees);
        //             select2();
        //             $("#branch_div").html(data.branches);
        //             select2(); // Assuming this is a function to initialize or update a select2 dropdown
        //         } else {
        //             console.error('Server returned an error:', data.message);
        //         }
        //     },
        //     error: function(xhr, status, error) {
        //         console.error('AJAX request failed:', status, error);
        //     }
        // });


        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-regions') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });


    $(document).on("change", ".region_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branches') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#branch_div').html('');
                    $("#branch_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

    $(document).on("change", ".branch_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branch-users') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#assign_to_div').html('');
                    $("#assign_to_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });
</script>
