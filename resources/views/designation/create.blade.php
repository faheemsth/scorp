{{Form::open(array('url'=>'designation','method'=>'post'))}}
    <div class="modal-body">

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('branch_id', __('Branch'),['class'=>'form-label']) }}
                {{ Form::select('branch_id', $branches,null, array('class' => 'form-control select branch_id','required'=>'required')) }}
            </div>

            <div class="form-group">
                {{ Form::label('department_id', __('Department'),['class'=>'form-label']) }}
                <select name="department_id" id="department_id" class="form-control select department_id" required>
                    <option value="">Select Department</option>
                </select>
          
            </div>
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Designation Name')))}}
                @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

    </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-dark px-2">
    </div>
    {{Form::close()}}

<script>
    $(".branch_id").on("change", function(){
        var branch_id = $(this).val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/get-departments',
            method: 'POST',
          //  dataType: 'json',
            data: {
                branch_id: branch_id,
                _token: csrfToken
            },
            success: function(response) {
                //response = JSON.parse(response);
                // Request is successful
                //console.log(response.content);
               $('.department_id').html(response);
            },
            error: function(xhr, status, error) {
                // Request encountered an error
                console.log('Error: ' + status);
            }
            });
    })
</script>