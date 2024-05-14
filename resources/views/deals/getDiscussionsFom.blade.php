{{ Form::model($discussions->id, ['route' => ['tasks.discussion.store', $discussions->task_id], 'method' => 'POST', 'id' => 'taskDiscussion']) }}
<textarea class="form-control" style="height: 220px; width: 300px;" id="taskDiscussionInput"
    placeholder="Click here to add your Tasks Comments..." name="comment">{!! $discussions->comment !!}</textarea>

<input type="hidden" value="{{ $discussions->id }}" name="id" id="id">
<div class="row justify-content-end indivbtn">
    <div class="col-auto ">
        <button class="btn btn-dark text-white" id="SaveDiscussion">Save</button>
    </div>
</div>
{{ Form::close() }}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#taskDiscussionInput').summernote({
            height: 150, // Set the height to 600 pixels
            focus: true,
            toolbar: [
                ['link', ['link']],
    ]
        });
    });
</script>
