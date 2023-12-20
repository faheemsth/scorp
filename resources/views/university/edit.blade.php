{{ Form::model($university, array('route' => array('university.update', $university->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Institute Category'),['class'=>'form-label']) }}
            {{ Form::select('category_id', $categories, $university->institute_category_id, ['class' => 'form-control select2', 'id' => 'categories' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', $university->name, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Country'),['class'=>'form-label']) }}
            {{ Form::select('country', $countries, $university->country, ['class' => 'form-control select2', 'id' => 'countries' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Campuses'),['class'=>'form-label']) }}
            {{ Form::text('city', $university->city, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('In Take Months'),['class'=>'form-label']) }}
            {{ Form::select('months[]', $months, explode(',', $university->intake_months), ['class' => 'form-control select2', 'id' => 'in_take_months', 'multiple' => 'true' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            @php
             $countries = ['Global' => 'Global'] + $countries;
            @endphp
            {{ Form::label('name', __('Terriratory'),['class'=>'form-label']) }}
            {{ Form::select('territory[]', $countries, explode(',', $university->territory), ['class' => 'form-control select2', 'id' => 'territory', 'multiple' => 'true' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Companies'),['class'=>'form-label']) }}
            {{ Form::select('company_id', $companies, $university->company_id, ['class' => 'form-control select2', 'id' => 'companies' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Phone'),['class'=>'form-label']) }}
            {{ Form::text('phone', $university->phone, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Resource'),['class'=>'form-label']) }}
            {{ Form::text('resource_drive_link', $university->resource_drive_link, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Application Method'),['class'=>'form-label']) }}
            {{ Form::text('application_method_drive_link', $university->application_method_drive_link, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Note'),['class'=>'form-label']) }}
            {{ Form::textarea('note', $university->note, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2">
</div>
{{Form::close()}}

