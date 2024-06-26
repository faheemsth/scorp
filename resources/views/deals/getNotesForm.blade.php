{{ Form::model($note, ['route' => ['deals.notes.store', $note->deal_id], 'method' => 'POST', 'id' => 'create-notes', 'style' => 'z-index: 9999999 !important;']) }}
<textarea name="description" id="description" style="height: 120px;" class="form-control"
    placeholder="Click here add your Notes Comments...">{!! $note->description !!}</textarea>
<input type="hidden" id="note_id" value="{{ $note->id }}" name="note_id">
<div class="row justify-content-end indivbtn">
    <div class="col-auto ">
        <button class="btn btn-dark text-white create-notes-btn">Save</button>
    </div>
</div>
{{ Form::close() }}

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            height: 150, // Set the height to 600 pixels
            focus: true,
            toolbar: [
                ['link', ['link']],
            ]
        });
    });
</script>
