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

{{ Form::open(array('url' => 'leads', 'method' => 'POST', 'id' => 'lead-creating-form')) }}
<div class="modal-body pt-0"  >
    <div class="lead-content my-2" >
        <div class="card-body px-2 py-0">
            {{-- Details Pill Start --}}
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div>
                    <div>
                        <label for="name" class="form-label">{{ __('Name') }}<span class="text-danger">*</span></label>
                        <div class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                            <input type="text" class="form-control ml-2" id="name" value=""
                            name="name" placeholder="{{ __('Enter your name') }}">
                        </div>
                    </div>



                    <div>
                        <label for="name" class="form-label">{{ __('Brand') }}
                            <span class="text-danger">*</span></label>
                        <div class="" style="padding-left: 10px; font-size: 13px;">

                            {{-- Brand Dropdown --}}
                            @if (
                                \Auth::user()->type == 'super admin' ||
                                    \Auth::user()->type == 'Project Director' ||
                                    \Auth::user()->type == 'Project Manager' ||
                                    \Auth::user()->can('level 1') ||
                                    \Auth::user()->can('level 2'))
                                        {!! Form::select('brand_id', $companies, 0, [
                                            'class' => 'form-control select2 brand_id',
                                            'id' => 'brands',
                                        ]) !!}
                            @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                                 <input type="hidden" name="brand_id" value="{{\Auth::user()->id}}">
                                <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
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

                            {{-- End Brand Dropdown --}}
                        </div>
                    </div>



                    <div>
                        <label for="name" class="form-label">{{ __('Region') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="" style="padding-left: 10px; font-size: 13px;" id="region_div">

                            @if (\Auth::user()->type == 'super admin' ||
                                    \Auth::user()->type == 'Project Director' ||
                                    \Auth::user()->type == 'Project Manager' ||
                                    \Auth::user()->type == 'company' ||
                                    \Auth::user()->type == 'Region Manager' ||
                                    \Auth::user()->can('level 1') ||
                                    \Auth::user()->can('level 2' ||
                                    \Auth::user()->can('level 3')))

                                        {!! Form::select('region_id', $regions, null, [
                                            'class' => 'form-control select2',
                                            'id' => 'region_id',
                                        ]) !!}

                            @else
                                 <input type="hidden" name="region_id" value="{{ \Auth::user()->region_id }}">
                                    {!! Form::select('region_id', $regions, \Auth::user()->region_id, [
                                        'class' => 'form-control select2',
                                        'disabled' => 'disabled',
                                        'id' => 'region_id',
                                    ]) !!}
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="name" class="form-label">{{ __('Branch') }}
                            <span class="text-danger">*</span></label>
                        <div class="" style="padding-left: 10px; font-size: 13px;" id="branch_div">

                            @if (\Auth::user()->type == 'super admin' ||
                                    \Auth::user()->type == 'Project Director' ||
                                    \Auth::user()->type == 'Project Manager' ||
                                    \Auth::user()->type == 'company' ||
                                    \Auth::user()->type == 'Region Manager' ||
                                    \Auth::user()->type == 'Branch Manager' ||
                                    \Auth::user()->can('level 1') ||
                                    \Auth::user()->can('level 2') ||
                                    \Auth::user()->can('level 3') ||
                                    \Auth::user()->can('level 4'))
                                        <select name="lead_branch" id="branch_id" class="form-control select2 branch_id"
                                            onchange="Change(this)">
                                                @foreach($branches as $key => $branch)
                                                    <option value="{{$key}}">{{$branch}}</option>
                                                @endforeach
                                        </select>
                            @else
                                     <input type="hidden" name="lead_branch" value="{{ \Auth::user()->branch_id }}">
                                        <select name="branch_id" id="branch_id" class="form-control select2 branch_id"
                                            onchange="Change(this)">
                                                @foreach($branches as $key => $branch)
                                                    <option value="{{$key}}" {{ \Auth::user()->branch_id == $key ? 'selected' : '' }}>{{$branch}}</option>
                                                @endforeach
                                        </select>
                            @endif
                        </div>
                    </div>
                   @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team')
                    <div>
                        <label for="name" class="form-label">{{ __('Status') }}</label>
                        <div class="form-check form-switch" style="padding-left: 10px; font-size: 19px;">
                            <input class="form-check-input m-auto" type="checkbox" name="status" value="1">
                        </div>
                    </div>
                   @endif
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal-footer">

    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark new-lead-btn">

</div>

{{Form::close()}}


<script>

    $(".brand_id").on("change", function(){

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
                    $('#assign_to_divs').html('');
                    $("#assign_to_divs").html(data.html);
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

    $("#lead-creating-form").on("submit", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $(".new-lead-btn").val('Processing...');
        $(".new-lead-btn").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{ route('email_template_type_save') }}",
            data: formData,
            success: function(data) {
                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    $('.leads-list-tbody').prepend(data.html);
                    openSidebar('/email_template_type_show?id=' + data.id);
                    return false;
                } else {
                    show_toastr('error', data.message, 'error');
                    $(".new-lead-btn").val('Create');
                    $('.new-lead-btn').removeAttr('disabled');
                }
            }
        });
    });
    $(document).on("click", "#leadLink", function(){
        Swal.close();
        $('#commonModal').modal('hide');
    });
</script>


<script>
    // Use the input variable in the rest of your code
    window.intlTelInput(document.getElementById('phone'), {
        utilsScript: "{{ asset('js/intel_util.js') }}",
        initialCountry: "pk",
        separateDialCode: true,
        formatOnDisplay: true,
        hiddenInput: "full_number"
    });
</script>
