{{Form::open(array('url'=>'goaltracking','method'=>'post'))}}
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
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('goal_type',__('GoalTypes'),['class'=>'form-control-label'])}}
                {{Form::select('goal_type',$goalTypes,null,array('class'=>'form-control select','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('start_date',__('Start Date'),['class'=>'form-control-label'])}}
                {{Form::date('start_date',null,array('class' => 'form-control '))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('end_date',__('End Date'),['class'=>'form-control-label'])}}
                {{Form::date('end_date',null,array('class' => 'form-control '))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('subject',__('Subject'),['class'=>'form-control-label'])}}
                {{Form::text('subject',null,array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('target_achievement',__('Target Achievement'),['class'=>'form-control-label'])}}
                {{Form::text('target_achievement',null,array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('description',__('Description'),['class'=>'form-control-label'])}}
                {{Form::textarea('description',null,array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('status',__('Status'),['class'=>'form-control-label'])}}
                {{Form::select('status',$status,null,array('class'=>'form-control select'))}}
            </div>
        </div>

        <div class="col-md-12">
            <fieldset id='demo1' class="rating">
                <input class="stars" type="radio" id="rating-5" name="rating" value="5" >
                <label class="full" for="rating-5" title="Awesome - 5 stars"></label>
                <input class="stars" type="radio" id="rating-4" name="rating" value="4" >
                <label class="full" for="rating-4" title="Pretty good - 4 stars"></label>
                <input class="stars" type="radio" id="rating-3" name="rating" value="3" >
                <label class="full" for="rating-3" title="Meh - 3 stars"></label>
                <input class="stars" type="radio" id="rating-2" name="rating" value="2" >
                <label class="full" for="rating-2" title="Kinda bad - 2 stars"></label>
                <input class="stars" type="radio" id="technical-1" name="rating" value="1" >
                <label class="full" for="technical-1" title="Sucks big time - 1 star"></label>
            </fieldset>
        </div>



    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2">
    </div>
    </div>

{{Form::close()}}
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
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (response.status == 'success') {
                        show_toastr('Success', response.message, 'success');
                        $('#commonModal').modal('hide');
                        openSidebar('/GoalTrackingShow?id='+response.id)
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
