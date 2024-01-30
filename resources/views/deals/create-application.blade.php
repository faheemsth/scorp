{{ Form::open(['route' => ['deals.application.store', $id], 'id' => 'create-application']) }}
<div class="modal-body">
    <div class="row">

        <div class="col-6 form-group py-0">
            {{ Form::label('university', __('University'), ['class' => 'form-label']) }}
            {{ Form::select('university', $universities, null, ['class' => 'form-control select2', 'id' => 'university' ,'required' => 'required']) }}
        </div>

        <div class="col-6 form-group py-0">
            {{ Form::label('course', __('Course'), ['class' => 'form-label']) }}
            {{ Form::text('course', null, ['class' => 'form-control', 'required' => 'required', 'style' => 'height: 45px;']) }}
        </div>

        <div class="col-6 form-group py-0">
            {{ Form::label('application_key', __('Application ID'), ['class' => 'form-label']) }}
            {{ Form::text('application_key', null, ['class' => 'form-control', 'style' => 'height: 45px;']) }}
        </div>

        <div class="col-6 form-group py-0">
            {{ Form::label('intake', __('Intake'), ['class' => 'form-label']) }}
            <div class="intake_month_div" id="intake_month_div">
                <select name="intake_month" class="form form-control" id="intake_month">
                    <option value="">Select months</option>
                </select>
            </div>

        </div>


        <div class="col-6 form-group py-0">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
            {{ Form::select('status', $stages, 1, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>

        {{ Form::hidden('passport_number', $deal_passport->passport_number) }}
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="passport_number" value="{{ $deal_passport->passport_number }}">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-dark create-btn" data-entity="application">
</div>
{{ Form::close() }}


<script>
    $("#create-application").on("submit", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = $(this).attr('action');

        $(".create-btn").val('Processing...');
        //$('.create-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    openSidebar('/deals/' + data.app_id + '/detail-application');
                    return false;
                } else {
                    Swal.fire({
                    title: "Already Exist",
                    text: data.message,
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                });
                $(".create-btn").val('Create');
                $('.create-btn').removeAttr('disabled');
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
