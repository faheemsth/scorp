

{{ Form::model($employee, array('route' => array('user.convert', $employee->id), 'method' => 'POST')) }}
<div class="modal-body">
    <div class="row">
        <div class="row align-items-baseline px-4">
            <div class="form-group col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="UserTransfer" id="UserTransfer1" value="0" required>
                    <label class="form-check-label" for="UserTransfer1">
                        {{ __('Change Branch') }}
                    </label>
                </div>
            </div>
            
            <div class="form-group col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="UserTransfer" id="UserTransfer2" value="1" required>
                    <label class="form-check-label" for="UserTransfer2">
                        {{ __('Change User Responsible') }}
                    </label>
                </div>
            </div>
        </div>
        
        <div id="divToShowHide0" style="display: none;">
            <label class="form-check-label">{{ __('Branch') }}</label>
            <select name="branch_id" id="branch_id" class="form-control select2" required>
                 @foreach($branches as $key => $branch)
                        <option value="{{$key}}">{{$branch}}</option>
                  @endforeach
            </select>
        </div>

        <div id="divToShowHide1" style="display: none;">
            <label class="form-check-label">{{ __('Assigned To') }}</label>
            <select name="assigned_to" id="assigned_to" class="form-control select2" required>
                 @foreach($employees as $key => $employee)
                        <option value="{{$key}}">{{$employee}}</option>
                  @endforeach
            </select>
        </div>
        
        <script>
            $(document).ready(function() {
                $('input[name="UserTransfer"]').change(function() {
                    if ($(this).val() === "0") {
                        $('#divToShowHide0').show();
                        $('#divToShowHide1').hide();
                    } else {
                        $('#divToShowHide0').hide();
                        $('#divToShowHide1').show();
                    }
                });
            });
        </script>
        
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark px-2 BulkSendButton">
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
                        location.reload();

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
