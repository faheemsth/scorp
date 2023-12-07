{{ Form::model($lead, array('route' => array('leads.courses.update', $lead->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('universities', __('Universities'),['class'=>'form-label']) }}
            {{ Form::select('universities[]', $universities,false, array('class' => 'form-control','id'=>'universities', 'data-lead-id' => $lead->id,'required'=>'required')) }}
        </div>

        <div class="col-12 form-group get-courses">
           
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Save')}}" class="btn  btn-primary">
</div>

{{Form::close()}}

<script>
    $(document).ready(function() {

        $("#universities").on('change', function() {
            let university_id = $(this).val();
            let lead_id = $(this).attr('data-lead-id');
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            let courses = $("#choices-multiple3");
            $.ajax({
                url: "getCourses",
                data: {university_id, lead_id,  _token: csrf_token,},
                type: "POST",
                cache: false,
                success: function(data) {
                    data = JSON.parse(data);
                    
                    if(data.status){
                      
                      $(".get-courses").html(data.content);
                    }
                }
            });
        })

    })
</script>