{{ Form::model($appraisal, ['route' => ['appraisal.update', $appraisal->id], 'method' => 'PUT']) }}
<div class="modal-body pt-0" style="height: 80vh;">
    <div class="row" style="max-height: 100%; overflow-y: scroll;">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('brand_id', __('Brand'), ['class' => 'form-label']) }}
                <span>
                    {{-- Brand Dropdown --}}
                    @if (
                        \Auth::user()->type == 'super admin' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->can('level 1') ||
                            \Auth::user()->can('level 2'))

                        <select class="form-control select2 brand_id" id="choices-1011" name="brand_id"
                            {{ !\Auth::user()->can('edit brand lead') ? 'disabled' : '' }}>
                            <option value="">Select Brand</option>
                            @foreach ($companies as $key => $company)
                                <option value="{{ $key }}" {{ $key == $appraisal->brand_id ? 'selected' : '' }}>
                                    {{ $company }}</option>
                            @endforeach
                        </select>
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
                                    {{ $key == \Auth::user()->brand_id ? 'selected' : '' }}>{{ $comp }}
                                </option>
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
                    @if (
                        \Auth::user()->type == 'super admin' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->type == 'company' ||
                            \Auth::user()->type == 'Region Manager' ||
                            \Auth::user()->can('level 1') ||
                            \Auth::user()->can('level 2') ||
                            \Auth::user()->can('level 3'))
                        {!! Form::select('region_id', $regions, $appraisal->region_id, [
                            'class' => 'form-control select2',
                            'id' => 'region_id',
                        ]) !!}
                    @else
                        <input type="hidden" name="region_id" value="{{ $appraisal->region_id }}">
                        {!! Form::select('region_id', $regions, $appraisal->region_id, [
                            'class' => 'form-control select2',
                            'disabled' => 'disabled',
                            'id' => 'region_id',
                        ]) !!}
                    @endif
                </span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                <span id="branch_div">
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
                        <select name="lead_branch" id="branch_id" class="form-control select2 branch_id"
                            onchange="Change(this)" {{ !\Auth::user()->can('edit branch lead') ? 'disabled' : '' }}>
                            @foreach ($branches as $key => $branch)
                                <option value="{{ $key }}" {{ $appraisal->branch == $key ? 'selected' : '' }}>
                                    {{ $branch }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="lead_branch" value="{{ \Auth::user()->branch_id }}">
                        <select name="branch_id" id="branch_id" class="form-control select2 branch_id"
                            onchange="Change(this)" {{ !\Auth::user()->can('edit branch lead') ? 'disabled' : '' }}>
                            @foreach ($branches as $key => $branch)
                                <option value="{{ $key }}" {{ $appraisal->branch == $key ? 'selected' : '' }}>
                                    {{ $branch }}</option>
                            @endforeach
                        </select>
                    @endif
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('employees', __('Employee*'), ['class' => 'col-form-label']) }}
                <span id="assign_to_divs">
                    <select class="form-control select2" id="choice-2" name="lead_assigned_user"
                        {{ !\Auth::user()->can('edit assign to lead') ? 'disabled' : '' }}>
                        <option value="">Select User</option>
                        @foreach ($employees as $key => $user)
                            <option value="{{ $key }}" <?= $appraisal->employee == $key ? 'selected' : '' ?>>
                                {{ $user }}</option>
                        @endforeach
                    </select>
                </span>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('appraisal_date', __('Select Month*'), ['class' => 'col-form-label']) }}
                {{ Form::text('appraisal_date', null, ['class' => 'form-control d_filter', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remarks'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '3']) }}
            </div>
        </div>
        <div class="row" id="stares"></div>
    </div>
    </div>



    <div class="modal-footer">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn btn-dark BulkSendButton" >
    </div>
    {{ Form::close() }}
    <script>

        $('#employee').change(function(){

            var emp_id = $('#employee').val();
            $.ajax({
                url: "{{ route('empByStar') }}",
                type: "post",
                data:{
                    "employee": emp_id,
                    "_token": "{{ csrf_token() }}",
                },

                cache: false,
                success: function(data) {

                    $('#stares').html(data.html);
                }
            })
        });
    </script>

    <script>
        var employee_id = '{{ $appraisal->employee}}';
        var appraisal_id = '{{ $appraisal->id}}';
        $( document ).ready(function() {
            $.ajax({
                url: "{{ route('empByStar1') }}",
                type: "post",
                data:{
                    "employee": employee_id,
                    "appraisal": appraisal_id,

                    "_token": "{{ csrf_token() }}",
                },

                cache: false,
                success: function(data) {

                    $('#stares').html(data.html);
                }
            })
        });
    </script>


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
                    processData: false,
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        if (response.status == 'success') {
                            show_toastr('Success', response.message, 'success');
                            $('#commonModal').modal('hide');
                            openSidebar('/appraisalShow?id='+response.id)
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
