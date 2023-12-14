{{ Form::open(array('url' => 'courselevel')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Course Level'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-danger" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark">
</div>
{{Form::close()}}
