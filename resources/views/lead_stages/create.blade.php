{{ Form::open(array('url' => 'lead_stages')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Lead Stage Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('lead_stage_type', __('Type'), ['class' => 'form-label']) }}
            {{ Form::select('lead_stage_type', ['open lead' => 'Open Lead', 'close lead' => 'Close Lead'], null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('pipeline_id', __('Pipeline'),['class'=>'form-label']) }}
            {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-danger" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark">
</div>

{{Form::close()}}
