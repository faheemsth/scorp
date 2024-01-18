{{ Form::model($university, array('route' => array('university.update', $university->id), 'method' => 'PUT', 'id' => 'update-university-form')) }}

<div class="modal-body pt-0 " style="height: 80vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">

        <div class="row">
            <input type="hidden" id="university_id" value="{{ $university->id }}">
            <div class="form-group col-12 py-0">
                {{ Form::label('name', __('Institute Category'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {{ Form::select('category_id', $categories, $university->institute_category_id, ['class' => 'form-control select2', 'id' => 'categories' ,'required' => 'required']) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {{ Form::text('name', $university->name, array('class' => 'form-control','required'=>'required')) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('Country'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {{ Form::select('country', $countries, $university->country, ['class' => 'form-control select2', 'id' => 'countries' ,'required' => 'required']) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('Campuses'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {{ Form::text('city', $university->city, array('class' => 'form-control','required'=>'required')) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('In Take Months'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {{ Form::select('months[]', $months, explode(',', $university->intake_months), ['class' => 'form-control select2', 'id' => 'in_take_months', 'multiple' => 'true' ,'required' => 'required']) }}
            </div>

            <div class="form-group col-md-6 py-0">
                @php
                $countries = ['Global' => 'Global'] + $countries;
                @endphp
                {{ Form::label('name', __('Terriratory'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {{ Form::select('territory[]', $countries, explode(',', $university->territory), ['class' => 'form-control select2', 'id' => 'territory', 'multiple' => 'true' ,'required' => 'required']) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('Brand'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {{ Form::select('company_id', $companies, $university->company_id, ['class' => 'form-control select2', 'id' => 'companies' ,'required' => 'required']) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('Phone'),['class'=>'form-label']) }}
                {{ Form::text('phone', $university->phone, array('class' => 'form-control')) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('Resource'),['class'=>'form-label']) }}
                {{ Form::text('resource_drive_link', $university->resource_drive_link, array('class' => 'form-control')) }}
            </div>

            <div class="form-group col-md-6 py-0">
                {{ Form::label('name', __('Application Method'),['class'=>'form-label']) }}
                {{ Form::text('application_method_drive_link', $university->application_method_drive_link, array('class' => 'form-control')) }}
            </div>

            <div class="form-group col-12 py-0">
                {{ Form::label('name', __('Note'),['class'=>'form-label']) }}
                {{ Form::textarea('note', $university->note, array('class' => 'form-control')) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2 update-university-btn">
</div>
{{Form::close()}}


@push("script-page")
<script>

    $(document).on("submit", "#update-university-form", function(e) {

        e.preventDefault();
        var formData = $(this).serialize();
        var id = $("#university_id").val();
        $(".update-university-btn").val('Processing...');
        $('.update-university-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "/university/" + id,
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    //$('.leads-list-tbody').prepend(data.html);
                    //openSidebar('/get-lead-detail?lead_id=' + data.lead_id);
                    // openNav(data.lead.id);
                    openSidebar('university/' + data.id + '/university_detail');
                    return false;
                } else {
                    show_toastr('Error', data.message, 'error');
                    $(".update-university-btn").val('Create');
                    $('.update-university-btn').removeAttr('disabled');
                }
            }
        });
    });
</script>
@endpush