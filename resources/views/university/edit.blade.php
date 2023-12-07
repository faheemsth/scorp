{{ Form::model($university, array('route' => array('university.update', $university->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('University Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Country'),['class'=>'form-label']) }}
            {{ Form::text('country', null, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('City'),['class'=>'form-label']) }}
            {{ Form::text('city', null, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Phone'),['class'=>'form-label']) }}
            {{ Form::text('phone', null, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Note'),['class'=>'form-label']) }}
            {{ Form::textarea('note', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

