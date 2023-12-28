{{ Form::open(['route' => ['deals.application.update',$application->id], 'id' => 'updating-application']) }}
<div class="modal-body" style="min-height: 65vh;">
    <div class="row">

        <div class="col-6 form-group py-0">
            {{ Form::label('university', __('University'),['class'=>'form-label']) }}
            {{ Form::select('university', $universities,$application->university_id, array('class' => 'form-control select2','required'=>'required')) }}
        </div>

        <div class="col-6 form-group py-0">
            {{ Form::label('course', __('Course'),['class'=>'form-label']) }}
            {{ Form::text('course', $application->course, array('class' => 'form-control','required'=>'required', 'style' => 'height: 45px;')) }}
        </div>

        <div class="col-6 form-group py-0">
            {{ Form::label('application_key', __('Application ID'), ['class' => 'form-label']) }}
            {{ Form::text('application_key', $application->external_app_id, ['class' => 'form-control', 'style' => 'height: 45px;']) }}
        </div>

        <div class="col-6 form-group py-0">
            {{ Form::label('intake', __('Intake'), ['class' => 'form-label']) }}
            <div class="intake_month_div" id="intake_month_div">
                <select name="intake_month" class="form form-control" id="intake_month">
                    <option value="">Select months</option>
                    @if(isset($application) && isset($application->intake))
                        @php
                            $selectedValue = date('Y-m', strtotime($application->intake));
                        @endphp
                        <option value="{{ $selectedValue }}" selected>{{ date('F', strtotime($application->intake)) }}</option>
                    @endif
                </select>

            </div>
        </div>



        <div class="col-6 form-group py-0">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
            {{ Form::select('status', $stages, $application->stage_id, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>

        {{ Form::hidden('passport_number', $deal_passport->passport_number) }}
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="passport_number" value="{{ $deal_passport->passport_number}}">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark edit-btn">
</div>
{{Form::close()}}


<script>
    $("#updating-application").on("submit", function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var url = $(this).attr('action');

                $(".edit-btn").val('Processing...');
                $('.edit-btn').attr('disabled', 'disabled');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status == 'success') {
                            show_toastr('Success', data.message, 'success');
                            $('#commonModal').modal('hide');
                            openSidebar('/deals/'+data.app_id+'/detail-application');
                            return false;
                        } else {
                            show_toastr('Error', data.message, 'error');
                            $(".edit-btn").val('Update');
                            $('.edit-btn').removeAttr('disabled');
                        }
                    }
                });
    })
    $("#university").on("change", function () {
    var id = $(this).val();

    // Use shorthand $.get for a simple GET request
    $.get('{{ route('get_university_intake') }}', { id: id }, function (data) {
        try {
            data = JSON.parse(data);

            console.log(data.html);

            if (data.status === 'success') {

                $('#intake_month').html(data.html);
                select2();
            } else {
                console.error('Unexpected response:', data);
            }
        } catch (error) {
            console.error('Error parsing JSON response:', error);
        }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
        console.error('AJAX request failed:', textStatus, errorThrown);
    });
});
</script>
