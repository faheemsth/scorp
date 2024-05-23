{{ Form::open(['route' => ['deals.application.move.save', $id], 'method' => 'POST', 'id' => 'updating-application']) }}
<div class="modal-body">
    <div class="row">

        <div class="col-12 form-group py-0">
            {{ Form::label('Admission', __('Admission'),['class'=>'form-label']) }}
            <select class="form-control select2" name="deal_id" id="">
                <option value="">Select Admission</option>
                @foreach ($admissions as $admission)
                    <option value="{{  $admission->id }}" {{ $application_id == $admission->id ? 'selected' : '' }}>
                    {{ $admission->name.'-'.$admission->brandName.'-'.$admission->RegionName.'-'.$admission->branchName.'-'.$admission->assignedName }}
                    </option>
                @endforeach

            </select>
            <input type="hidden" value="{{ $application_id }}" name="old_deal_id">
        </div>
    </div>
</div>
<div class="modal-footer">
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
</script>
