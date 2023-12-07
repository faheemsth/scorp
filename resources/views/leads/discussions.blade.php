
{{ Form::model($lead, array('method' => 'POST', 'id' => 'create-discussion')) }}
<div class="modal-body" style="z-index: 9999999 !important;">
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('comment', __('Message'),['class'=>'form-label']) }}
            {{ Form::textarea('comment', null, array('class' => 'form-control')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary create-discussion-btn">
</div>
{{Form::close()}}

