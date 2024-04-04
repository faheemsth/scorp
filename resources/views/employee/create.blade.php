@extends('layouts.admin')
@section('page-title')
{{__('Create Employee')}}
@endsection
@section('content')
<div class="row">
    {{Form::open(array('route'=>array('employee.store'),'method'=>'post','enctype'=>'multipart/form-data'))}}
    {{-- <form method="post" action="{{route('employee.store')}}" enctype="multipart/form-data">--}}
    {{-- @csrf--}}

    @csrf

    
</div>
<div class="row">
    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{__('Personal Detail')}}</h6>
            </div>
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('name', __('Name'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::text('name', old('name'), ['class' => 'form-control','required' => 'required']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('phone', __('Phone'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::number('phone',old('phone'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('dob', __('Date of Birth'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            {!! Form::date('dob', old('dob'), ['class' => 'form-control datepicker']) !!}
                        </div>
                    </div>

                    <div class="col-md-6 ">
                        <div class="form-group ">
                            {!! Form::label('gender', __('Gender'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            <div class="d-flex radio-check">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="g_male" value="Male" name="gender" class="">
                                    <label class="custom-control-label" for="g_male">{{__('Male')}}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="g_female" value="Female" name="gender" class="">
                                    <label class="custom-control-label" for="g_female">{{__('Female')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('email', __('Email'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::email('email',old('email'), ['class' => 'form-control','required' => 'required']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('password', __('Password'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::password('password', ['class' => 'form-control','required' => 'required']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('address', __('Address'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                    {!! Form::textarea('address',old('address'), ['class' => 'form-control','rows'=>2]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{__('Company Detail')}}</h6>
            </div>
            <div class="card-body employee-detail-create-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('employee_id', __('Employee ID'),['class'=>'form-label']) !!}
                        {!! Form::text('employee_id', $employeesId, ['class' => 'form-control','disabled'=>'disabled']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('brands', __('Brand'),['class'=>'form-label']) !!}
                        {!! Form::select('brand_id', $brands,null, ['class' => 'form-control select2', 'id' => 'brand_id']) !!}
                        <input type="hidden" value="" name="brand_id" id="hidden_brand_id">
                    </div>


                    <div class="form-group col-md-6">
                        <input type="hidden" value="" name="region_id" id="hidden_region_id">
                        {!! Form::label('regions', __('Region'),['class'=>'form-label']) !!}
                        <div id="region_div">
                            {!! Form::select('region_id', $regions,null, ['class' => 'form-control select2', 'id' => 'region_id']) !!}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="hidden" value="" name="branch_id" id="hidden_branch_id">
                        {!! Form::label('branches', __('Branch'),['class'=>'form-label']) !!}
                        <div class="" id="branch_div">
                        {!! Form::select('branche_id', $branches,null, ['class' => 'form-control select2', 'id' => 'branch_id']) !!}
                    </div>
                    </div>


                    <div class="form-group col-md-6">
                        {!! Form::label('roles', __('Role'),['class'=>'form-label']) !!}
                        {!! Form::select('role', $roles,null, ['class' => 'form-control select2', 'id' => 'role']) !!}
                        <input type="hidden" name="role" value="" id="hidden_role">
                    </div>


                    <div class="form-group col-md-12 ">
                        {!! Form::label('company_doj', __('Company Date Of Joining'),['class'=>'form-label']) !!}
                        {!! Form::date('company_doj', null, ['class' => 'form-control datepicker','required' => 'required']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{__('Document')}}</h6>
            </div>
            <div class="card-body employee-detail-create-body">
                @foreach($documents as $key=>$document)
                <div class="row">
                    <div class="form-group col-12">
                        <div class="float-left col-4">
                            <label for="document" class="float-left pt-1 form-label">{{ $document->name }} @if($document->is_required == 1) <span class="text-danger">*</span> @endif</label>
                        </div>
                        <div class="float-right col-8">
                            <input type="hidden" name="emp_doc_id[{{ $document->id}}]" id="" value="{{$document->id}}">
                            <div class="choose-file form-group">
                                <label for="document[{{ $document->id }}]">
                                    <div>{{__('Choose File')}}</div>
                                    <input class="form-control  @error('document') is-invalid @enderror border-0" @if($document->is_required == 1) required @endif name="document[{{ $document->id}}]" type="file" id="document[{{ $document->id }}]" data-filename="{{ $document->id.'_filename'}}">
                                </label>
                                <p class="{{ $document->id.'_filename'}}"></p>
                            </div>

                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{__('Bank Account Detail')}}</h6>
            </div>
            <div class="card-body employee-detail-create-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('account_holder_name', __('Account Holder Name'),['class'=>'form-label']) !!}
                        {!! Form::text('account_holder_name', old('account_holder_name'), ['class' => 'form-control']) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('account_number', __('Account Number'),['class'=>'form-label']) !!}
                        {!! Form::number('account_number', old('account_number'), ['class' => 'form-control']) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('bank_name', __('Bank Name'),['class'=>'form-label']) !!}
                        {!! Form::text('bank_name', old('bank_name'), ['class' => 'form-control']) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('bank_identifier_code', __('Bank Identifier Code'),['class'=>'form-label']) !!}
                        {!! Form::text('bank_identifier_code',old('bank_identifier_code'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('branch_location', __('Branch Location'),['class'=>'form-label']) !!}
                        {!! Form::text('branch_location',old('branch_location'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('tax_payer_id', __('Tax Payer Id'),['class'=>'form-label']) !!}
                        {!! Form::text('tax_payer_id',old('tax_payer_id'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        {!! Form::submit('Create', ['class' => 'btn btn-xs btn-primary badge-blue float-right radius-10px']) !!}
        {{-- </form>--}}
        {{Form::close()}}
    </div>
</div>
@endsection

@push('script-page')

<script>
$("#brand_id").on("change", function(){
        
        var id = $(this).val();
        $("#hidden_brand_id").val(id);

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-regions') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });


    $(document).on("change", "#region_id", function(){
        var id = $(this).val();
        $("#hidden_region_id").val(id);

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branches') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#branch_div').html('');
                    $("#branch_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

    $(document).on("change", "#branch_id", function(){
        var id = $(this).val();
        $("#hidden_branch_id").val(id);
    });

    $(document).on("change","#role", function(){
        $("#hidden_role").val($(this).val());
    })
</script>
@endpush