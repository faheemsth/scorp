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

{{ Form::open(['url' => 'email/marketing/form', 'method' => 'POST', 'id' => 'lead-updating-form']) }}

<div class="modal-body pt-0">
    <div class="lead-content my-2" >
        <div class="card-body px-4 py-3">
            <input type="hidden" value="{{ $emailMarketing->id }}" name="id">
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control ml-0" id="name" value="{{ $emailMarketing->name }}"
                    name="name" placeholder="{{ __('Enter your name') }}">
            </div>
            <div  class="mb-3">
                <label for="name" class="form-label">{{ __('Brand') }}
                    <span class="text-danger">*</span></label>
                <div class="" style="padding-left: 10px; font-size: 13px;"
                    id="">
                    @if (
                        \Auth::user()->type == 'super admin' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->can('level 1') ||
                            \Auth::user()->can('level 2'))

                        <select class="form-control select2 brand_id" id="choices-1011"
                            name="brand_id"
                            {{ !\Auth::user()->can('edit brand lead') ? 'disabled' : '' }}>
                            <option value="">Select Brand</option>
                            @foreach ($companies as $key => $company)
                                <option value="{{ $key }}"
                                    {{ $key == $emailMarketing->brand_id ? 'selected' : '' }}>
                                    {{ $company }}
                                </option>
                            @endforeach
                        </select>
                    @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                        <input type="hidden" name="brand_id"
                            value="{{ \Auth::user()->id }}">
                        <select class='form-control select2 brand_id' disabled="brands"
                            id="brand_id">
                            @foreach ($companies as $key => $comp)
                                <option value="{{ $key }}"
                                    {{ $key == \Auth::user()->id ? 'selected' : '' }}>
                                    {{ $comp }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="brand_id"
                            value="{{ \Auth::user()->brand_id }}">
                        <select class='form-control select2 brand_id' disabled="brands"
                            id="brand_id">
                            @foreach ($companies as $key => $comp)
                                <option value="{{ $key }}"
                                    {{ $key == \Auth::user()->brand_id ? 'selected' : '' }}>
                                    {{ $comp }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            <div  class="mb-3">
                <label for="name" class="form-label">{{ __('Region') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="" style="padding-left: 10px; font-size: 13px;"
                    id="region_div">
                    @if (
                    \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'Region Manager' ||
                    \Auth::user()->can('level 1') ||
                    \Auth::user()->can('level 2') ||
                    \Auth::user()->can('level 3'))
                    {!! Form::select('region_id', $regions, $emailMarketing->getRawOriginal()['region_id'], [
                        'class' => 'form-control select2',
                        'id' => 'region_id',
                    ]) !!}
                @else
                    <input type="hidden" name="region_id" value="{{ $emailMarketing->region_id }}">
                    {!! Form::select('region_id', $regions, $emailMarketing->region_id, [
                        'class' => 'form-control select2',
                        'disabled' => 'disabled',
                        'id' => 'region_id',
                    ]) !!}
                @endif

                </div>
            </div>

            <div  class="mb-3">
                <label for="name" class="form-label">{{ __('Branch') }}
                    <span class="text-danger">*</span></label>
                <div class="" style="padding-left: 10px; font-size: 13px;"
                    id="branch_div">
                    @if (
                        \Auth::user()->type == 'super admin' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->type == 'company' ||
                            \Auth::user()->type == 'Region Manager' ||
                            \Auth::user()->type == 'Branch Manager' ||
                            \Auth::user()->can('level 1') ||
                            \Auth::user()->can('level 2') ||
                            \Auth::user()->can('level 3') ||
                            \Auth::user()->can('level 4'))
                        <select name="lead_branch" id="branch_id"
                            class="form-control select2 branch_id" onchange="Change(this)"
                            {{ !\Auth::user()->can('edit branch lead') ? 'disabled' : '' }}>
                            @foreach ($branches as $key => $branch)
                                <option value="{{ $key }}"
                                    {{ $emailMarketing->branch_id == $key ? 'selected' : '' }}>
                                    {{ $branch }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="lead_branch"
                            value="{{ \Auth::user()->branch_id }}">
                        <select name="branch_id" id="branch_id"
                            class="form-control select2 branch_id" onchange="Change(this)"
                            {{ !\Auth::user()->can('edit branch lead') ? 'disabled' : '' }}>
                            @foreach ($branches as $key => $branch)
                                <option value="{{ $key }}"
                                    {{ $emailMarketing->branch_id == $key ? 'selected' : '' }}>
                                    {{ $branch }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team')
            <div>
                <label for="name" class="form-label">{{ __('Status') }}</label>
                <div class="form-check form-switch" style="padding-left: 10px; font-size: 19px;">
                    @if ($emailMarketing->status == '0')
                    <input class="form-check-input m-auto" type="checkbox" name="status" value="1">
                    @else
                        <input class="form-check-input m-auto" type="checkbox" name="status" value="1" checked>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="submit" class="btn btn-dark">{{ __('Create') }}</button>
</div>

{{ Form::close() }}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            height: 150,
        });
    });
</script>
<script>
    // new lead form submitting...
    $("#lead-updating-form").on("submit", function(e) {

        e.preventDefault();
        var formData = $(this).serialize();
        $(".update-lead-btn").val('Processing...');
        $('.update-lead-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "email_template_type_updateSave",
            data: formData,
            success: function(data) {
                if (data.status == 'success') {
                    show_toastr('success', data.message, 'success');
                    // openNav(id);
                    $("#commonModal").modal('hide');
                    openSidebar('email_template_type_show?id=' + data.id);
                    return false;
                } else {
                    show_toastr('error', data.message, 'error');
                    $(".update-lead-btn").val('Update');
                    $('.update-lead-btn').removeAttr('disabled');
                }
            }
        });
    });
</script>
<script>
    $(".brand_id").on("change", function() {
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-regions') }}',
            data: {
                id: id
            },
            success: function(data) {
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


    $(document).on("change", ".region_id", function() {
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branches') }}',
            data: {
                id: id
            },
            success: function(data) {
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



    // new lead form submitting...
    $("#lead-updating-form").on("submit", function(e) {

        e.preventDefault();
        var formData = $(this).serialize();
        var id = $(".lead_id").val();
        $(".update-lead-btn").val('Processing...');
        $('.update-lead-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "/leads/update/" + id,
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('success', data.message, 'success');
                    // openNav(id);
                    $("#commonModal").modal('hide');
                    openSidebar('/get-lead-detail?lead_id=' + data.lead_id);
                    //window.location.href = '/leads/list';
                    return false;
                } else {
                    show_toastr('error', data.message, 'error');
                    $(".update-lead-btn").val('Update');
                    $('.update-lead-btn').removeAttr('disabled');
                }
            }
        });
    });
</script>


<script>
    // Use the input variable in the rest of your code
    window.intlTelInput(document.getElementById('phone'), {
        utilsScript: "{{ asset('js/intel_util.js') }}",
        initialCountry: "pk",
        separateDialCode: true,
        formatOnDisplay: true,
        hiddenInput: "full_number",
        //placeholderNumberType: "FIXED_LINE",
        // preferredCountries: ["us", "gb"]
    });
</script>
