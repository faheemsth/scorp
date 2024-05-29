{{ Form::model($indicator, ['route' => ['indicator.update', $indicator->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('brand', __('Brand'), ['class' => 'form-label']) }}
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
                            <option value="{{ $key }}" {{ $key == $indicator->brand_id ? 'selected' : '' }}>
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
                                {{ $key == \Auth::user()->brand_id ? 'selected' : '' }}>{{ $comp }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('brand', __('Brand'), ['class' => 'form-label']) }}
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
                    {!! Form::select('region_id', $regions, $indicator->region_id, [
                        'class' => 'form-control select2',
                        'id' => 'region_id',
                    ]) !!}
                @else
                    <input type="hidden" name="region_id" value="{{ $indicator->region_id }}">
                    {!! Form::select('region_id', $regions, $indicator->region_id, [
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
                            <option value="{{ $key }}" {{ $indicator->branch == $key ? 'selected' : '' }}>
                                {{ $branch }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="lead_branch" value="{{ \Auth::user()->branch_id }}">
                    <select name="branch" id="branch_id" class="form-control select2 branch_id" onchange="Change(this)"
                        {{ !\Auth::user()->can('edit branch lead') ? 'disabled' : '' }}>
                        @foreach ($branches as $key => $branch)
                            <option value="{{ $key }}" {{ $indicator->branch == $key ? 'selected' : '' }}>
                                {{ $branch }}</option>
                        @endforeach
                    </select>
                @endif
                </span>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                    {{ Form::select('department', $departments, null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'department_id']) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('designation', __('Designation'), ['class' => 'form-label']) }}
                    <select class="select form-control select2-multiple" id="designation_id" name="designation"
                        data-toggle="select2" data-placeholder="{{ __('Select Designation ...') }}" required>
                    </select>
                </div>
            </div>

        </div>

        @foreach ($performance as $performances)
            <div class="row">
                <div class="col-md-12 mt-3">
                    <h6>{{ $performances->name }}</h6>
                    <hr class="mt-0">
                </div>
                @foreach ($performances->types as $types)
                    <div class="col-6">
                        {{ $types->name }}
                    </div>
                    <div class="col-6">
                        <fieldset id='demo1' class="rating">
                            <input class="stars" type="radio" id="technical-5-{{ $types->id }}"
                                name="rating[{{ $types->id }}]" value="5"
                                {{ isset($ratings[$types->id]) && $ratings[$types->id] == 5 ? 'checked' : '' }}>
                            <label class="full" for="technical-5-{{ $types->id }}"
                                title="Awesome - 5 stars"></label>
                            <input class="stars" type="radio" id="technical-4-{{ $types->id }}"
                                name="rating[{{ $types->id }}]" value="4"
                                {{ isset($ratings[$types->id]) && $ratings[$types->id] == 4 ? 'checked' : '' }}>
                            <label class="full" for="technical-4-{{ $types->id }}"
                                title="Pretty good - 4 stars"></label>
                            <input class="stars" type="radio" id="technical-3-{{ $types->id }}"
                                name="rating[{{ $types->id }}]" value="3"
                                {{ isset($ratings[$types->id]) && $ratings[$types->id] == 3 ? 'checked' : '' }}>
                            <label class="full" for="technical-3-{{ $types->id }}" title="Meh - 3 stars"></label>
                            <input class="stars" type="radio" id="technical-2-{{ $types->id }}"
                                name="rating[{{ $types->id }}]" value="2"
                                {{ isset($ratings[$types->id]) && $ratings[$types->id] == 2 ? 'checked' : '' }}>
                            <label class="full" for="technical-2-{{ $types->id }}"
                                title="Kinda bad - 2 stars"></label>
                            <input class="stars" type="radio" id="technical-1-{{ $types->id }}"
                                name="rating[{{ $types->id }}]" value="1"
                                {{ isset($ratings[$types->id]) && $ratings[$types->id] == 1 ? 'checked' : '' }}>
                            <label class="full" for="technical-1-{{ $types->id }}"
                                title="Sucks big time - 1 star"></label>
                        </fieldset>
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn  btn-dark px-2 BulkSendButton">
    </div>
    {{ Form::close() }}

    <script type="text/javascript">
        function getDesignation(did) {
            $.ajax({
                url: '{{ route('employee.json') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log(data);
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">Select any Designation</option>');
                    $.each(data, function(key, value) {
                        var select = '';
                        if (key == '{{ $indicator->designation }}') {
                            select = 'selected';
                        }

                        $('#designation_id').append('<option value="' + key + '"  ' + select + '>' +
                            value + '</option>');
                    });
                }
            });
        }

        $(document).ready(function() {
            var d_id = $('#department_id').val();
            getDesignation(d_id);
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
                    select2();
                    if (data.status === 'success') {
                        $('#branch_div').html('');
                        $("#branch_div").html(data.html);

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
                    dataType: 'json', // Don't process the data, let jQuery handle it
                    success: function(response) {
                        if (response.status == 'success') {
                            show_toastr('Success', response.message, 'success');
                            $('#commonModal').modal('hide');
                            openSidebar('/show-trainer?id='+response.id);
                            return false;
                        } else {
                            show_toastr('Error', response.message, 'error');
                            $(".BulkSendButton").val('Update');
                            $('.BulkSendButton').removeAttr('disabled');
                        }
                    },
                });
            });
        });
    </script>
