{{ Form::model($lead, array('route' => array('leads.drive.store', $lead->id), 'method' => 'POST', 'style' => 'z-index: 9999999 !important;')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            <label for="">Drive Link</label>
            <input type="text" class="form form-control" name="drive_link" value="{{$lead->drive_link}}" required>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?= empty($lead->drive_link) ? 'Create' : 'Update' ?>" class="btn  btn-primary">
</div>
{{Form::close()}}