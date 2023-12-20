{{ Form::model($task, array('route' => array('tasks.discussion.store', $task->id), 'method' => 'POST', 'id' => 'taskDiscussion')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('comment', __('Message'),['class'=>'form-label']) }}
            {{ Form::textarea('comment', null, array('class' => 'form-control')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light create-discussion-btn" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark px-2">
</div>
{{Form::close()}}



