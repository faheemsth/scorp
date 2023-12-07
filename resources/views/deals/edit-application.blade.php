{{ Form::open(['route' => ['deals.application.update',$application->id], 'id' => 'updating-application']) }}
<div class="modal-body">
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
            {{ Form::month('intake', date('Y-m', strtotime($application->intake)), ['class' => 'form-control', 'required' => 'required', 'style' => 'height: 45px;']) }}
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
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary edit-btn">
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
</script>