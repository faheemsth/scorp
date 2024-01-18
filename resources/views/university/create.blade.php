{{ Form::open(array('url' => 'university', 'files' => true, 'id' => 'university-creating-form')) }}
<div class="modal-body pt-0 " style="height: 80vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
    <div class="card-body px-2 py-0" >

    <div class="row">
        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Institute Category'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::select('category_id', $categories, null, ['class' => 'form-control select2', 'id' => 'categories' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <!-- <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Image'),['class'=>'form-label']) }}
            {{ Form::file('image', ['class' => 'form-control', 'required' => 'required']) }}
        </div> -->

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Country'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::select('country', $countries, null, ['class' => 'form-control select2', 'id' => 'countries' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Campuses'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::text('city', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('In Take Months'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::select('months[]', $months, null, ['class' => 'form-control select2', 'id' => 'in_take_months', 'multiple' => 'true' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            @php
             $countries = ['Global' => 'Global'] + $countries;
            @endphp
            {{ Form::label('name', __('Terriratory'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::select('territory[]', $countries, null, ['class' => 'form-control select2', 'id' => 'territory', 'multiple' => 'true' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Brand'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::select('company_id', $companies, null, ['class' => 'form-control select2', 'id' => 'companies' ,'required' => 'required']) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Phone'),['class'=>'form-label']) }}
            {{ Form::text('phone', '', array('class' => 'form-control')) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Resource'),['class'=>'form-label']) }}
            {{ Form::text('resource_drive_link', '', array('class' => 'form-control')) }}
        </div>

        <div class="form-group col-md-6 py-0">
            {{ Form::label('name', __('Application Method'),['class'=>'form-label']) }}
            {{ Form::text('application_method_drive_link', '', array('class' => 'form-control')) }}
        </div>

        <div class="form-group col-12 py-0">
            {{ Form::label('name', __('Note'),['class'=>'form-label']) }}
            {{ Form::textarea('note', '', array('class' => 'form-control')) }}
        </div>
    </div>
    </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark px-2 university-create-btn">
</div>
{{Form::close()}}
