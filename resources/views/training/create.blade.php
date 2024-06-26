{{ Form::open(['url' => 'training', 'method' => 'post']) }}
<div class="modal-body pt-0" style="height: 80vh;">
    <div class="row" style="max-height: 100%; overflow-y: scroll;">

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('brand', __('Brand'), ['class' => 'form-label']) }}
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
                            <option value="{{ $key }}" {{ $key == \Auth::user()->brand_id ? 'selected' : '' }}>
                                {{ $comp }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('region', __('Region'), ['class' => 'form-label']) }}
                <div id="region_div">
                    @if (
                        \Auth::user()->type == 'super admin' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->type == 'company' ||
                            \Auth::user()->type == 'Region Manager' ||
                            \Auth::user()->can('level 1') ||
                            \Auth::user()->can('level 2' || \Auth::user()->can('level 3')))
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
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                <div id="branch_div">
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
                            onchange="Change(this)">
                            @foreach ($branches as $key => $branch)
                                <option value="{{ $key }}">{{ $branch }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="lead_branch" value="{{ \Auth::user()->branch_id }}">
                        <select name="branch_id" id="branch_id" class="form-control select2 branch_id"
                            onchange="Change(this)">
                            @foreach ($branches as $key => $branch)
                                <option value="{{ $key }}"
                                    {{ \Auth::user()->branch_id == $key ? 'selected' : '' }}>{{ $branch }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('trainer_option', __('Trainer Option'), ['class' => 'form-label']) }}
                {{ Form::select('trainer_option', $options, null, ['class' => 'form-control select', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('training_type', __('Training Type'), ['class' => 'form-label']) }}
                {{ Form::select('training_type', $trainingTypes, null, ['class' => 'form-control select', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('trainer', __('Trainer'), ['class' => 'form-label']) }}
                {{ Form::select('trainer', $trainers, null, ['class' => 'form-control select', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('training_cost', __('Training Cost'), ['class' => 'form-label']) }}
                {{ Form::number('training_cost', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                <div id="assign_to_divs">
                    <select class="form-control select2" id="choice-222" name="lead_assigned_user">
                        @foreach($employees as $key => $employee)
                        <option value="{{$key}}">{{$employee}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control ']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control ']) }}
            </div>
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Description')]) }}
        </div>


    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-dark px-2">
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
                        openSidebar('/training/view?id='+response.id)
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
