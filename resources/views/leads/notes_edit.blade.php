{{ Form::model($note, array('method' => 'POST', 'id' => 'update-notes','style' => 'z-index: 9999999 !important;')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            <label for="">Title</label>
            <input type="text" class="form form-control" name="title" value="{{$note->title}}" required>
            <input type="hidden" value="{{$note->id}}" name="note_id">
        </div>

        <div class="col-12 form-group">
            <label for="">Description</label>
            <textarea name="description" class="form form-control" cols="10" rows="1">{{$note->description}} </textarea>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="Update" class="btn  btn-primary edit-notes">
</div>
{{Form::close()}}
