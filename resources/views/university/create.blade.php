{{ Form::open(array('url' => 'university')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('University Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Country'),['class'=>'form-label']) }}
            {{ Form::text('country', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('City'),['class'=>'form-label']) }}
            {{ Form::text('city', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Phone'),['class'=>'form-label']) }}
            {{ Form::text('phone', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Note'),['class'=>'form-label']) }}
            {{ Form::textarea('note', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
