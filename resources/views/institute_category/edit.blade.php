{{ Form::open(['route' => ['institute-category.update', $category->id], 'method' => 'PUT']) }}
{{ csrf_field() }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12 py-0">
            {{ Form::label('institute_category', __('Institute Category'),['class'=>'form-label']) }}
            {{ Form::text('institute_category', $category->name, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2">
</div>

{{ Form::close() }}