{{ Form::open(array('url' => 'course')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Course Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('university_id', __('University'),['class'=>'form-label']) }}
            {{ Form::select('university_id', $universities,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('courselevel_id', __('Course Level'),['class'=>'form-label']) }}
            {{ Form::select('courselevel_id', $courselevel,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('courseduration_id', __('Course Duration'),['class'=>'form-label']) }}
            {{ Form::select('courseduration_id', $courseduration,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('currency', __('Currency Name'),['class'=>'form-label']) }}
            {{ Form::select('currency', $country_curr,null, array('class' => 'form-control select select2','required'=>'required')) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('fee', __('Fee'),['class'=>'form-label']) }}
            {{ Form::text('fee', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark px-2">
</div>
{{Form::close()}}
