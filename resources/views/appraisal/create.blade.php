{{ Form::open(['url' => 'appraisal', 'method' => 'post']) }}
<div class="modal-body pt-0" style="height: 80vh;">
    <div class="row" style="max-height: 100%; overflow-y: scroll;">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('brand_id', __('Brand'), ['class' => 'form-label']) }}
                <span>
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
                        <input type="hidden" name="brand_id" value="{{ \Auth::user()->id }}">
                        <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
                            @foreach ($companies as $key => $comp)
                                <option value="{{ $key }}" {{ $key == \Auth::user()->id ? 'selected' : '' }}>
                                    {{ $comp }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="brand_id" value="{{ \Auth::user()->brand_id }}">
                        <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
                            @foreach ($companies as $key => $comp)
                                <option value="{{ $key }}"
                                    {{ $key == \Auth::user()->brand_id ? 'selected' : '' }}>{{ $comp }}</option>
                            @endforeach
                        </select>
                    @endif
                </span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('region_id', __('Region'), ['class' => 'form-label']) }}
                <span id="region_div">
                    <select class="form-control select2" id="chose-1123">
                        <option value="">{{ __('select Region') }}</option>
                    </select>
                </span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                <span id="branch_div">
                    <select class="form-control select2" id="chose-1124">
                        <option value="">{{ __('select Branch') }}</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                <span id="assign_to_divs">
                    <select class="form-control select2" id="chose-1125">
                        <option value="">{{ __('select Employee') }}</option>
                    </select>
                </span>
            </div>
        </div>




        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('appraisal_date', __('Select Month*'), ['class' => 'col-form-label']) }}
                {{ Form::month('appraisal_date', '', ['class' => 'form-control ', 'autocomplete' => 'off', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remarks'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Enter remark']) }}
            </div>
        </div>
        <div class="row" id="stares"></div>
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary BulkSendButton">
</div>
{{ Form::close() }}
<script>
    $(document).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData($(this)[0]); // Create FormData object from the form
            $(".BulkSendButton").val('Processing...');
            $('.BulkSendButton').attr('disabled', 'disabled');
            $.ajax({
                url: $(this).attr('action'), // Get the form action URL
                type: $(this).attr('method'), // Get the form method (POST in this case)
                data: formData, // Set the form data
                contentType: false, // Don't set contentType, let jQuery handle it
                processData: false, // Don't process the data, let jQuery handle it
                success: function(response) {
                    if (response.status == 'success') {
                        show_toastr('Success', response.message, 'success');
                        $('#commonModal').modal('hide');
                        return false;
                    } else {
                        show_toastr('Error', response.message, 'error');
                        $(".BulkSendButton").val('Create');
                        $('.BulkSendButton').removeAttr('disabled');
                    }
                },
            });
        });
    });
</script>
<script>
    $(document).on("change", ".user_id", function() {
        var emp_id = $(this).val();

        $.ajax({
            url: "{{ route('empByStar') }}",
            type: "POST",
            data: {
                employee: emp_id,
                _token: "{{ csrf_token() }}"
            },
            cache: false,
            success: function(data) {
                if (data && data.html) {
                    $('#stares').html(data.html);
                } else {
                    console.error("Unexpected response format:", data);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.error("Response:", xhr.responseText);
            }
        });
    });

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

    $(document).on("change", ".branch_id", function() {
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branch-users') }}',
            data: {
                id: id
            },
            success: function(data) {
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
</script>
