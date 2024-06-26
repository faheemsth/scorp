@extends('layouts.admin')
@section('page-title')
{{__('Edit Employee')}}
@endsection
@section('content')
<div class="row">
    {{Form::open(array('route'=>array('employee.update', $employee->id),'method'=>'post','enctype'=>'multipart/form-data'))}}
    {{-- <form method="post" action="{{route('employee.store')}}" enctype="multipart/form-data">--}}
    {{-- @csrf--}}


    @csrf
    @method('PUT')
    
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
                        {!! Form::text('name', $employee->name, ['class' => 'form-control','required' => 'required']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('phone', __('Phone'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::number('phone', $employee->phone, ['class' => 'form-control', 'id' => 'phone']) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('dob', __('Date of Birth'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            {!! Form::date('dob', $employee->dob, ['class' => 'form-control datepicker']) !!}
                        </div>
                    </div>

                    <div class="col-md-6 ">
                        <div class="form-group ">
                            {!! Form::label('gender', __('Gender'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            <div class="d-flex radio-check">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="g_male" value="Male" name="gender" class="" {{ $employee->gender == 'Male' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="g_male">{{__('Male')}}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="g_female" value="Female" name="gender" class="" {{ $employee->gender == 'Female' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="g_female">{{__('Female')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('email', __('Email'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::email('email',$employee->email, ['class' => 'form-control','required' => 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('address', __('Address'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                    {!! Form::textarea('address', $employee->address, ['class' => 'form-control','rows'=>2]) !!}
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
                        {!! Form::select('brand_id', $brands,$employee->brand_id, ['class' => 'form-control select2', 'id' => 'brand_id']) !!}
                        <input type="hidden" value="{{ $employee->brand_id}}" name="brand_id" id="hidden_brand_id">
                    </div>


                    <div class="form-group col-md-6">
                        <input type="hidden" value="{{ $employee->region_id }}" name="region_id" id="hidden_region_id">
                        {!! Form::label('regions', __('Region'),['class'=>'form-label']) !!}
                        <div id="region_div">
                            {!! Form::select('region_id', $regions, $employee->region_id, ['class' => 'form-control select2', 'id' => 'region_id']) !!}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="hidden" value="{{ $employee->branch_id }}" name="branch_id" id="hidden_branch_id">
                        {!! Form::label('branches', __('Branch'),['class'=>'form-label']) !!}
                        <div class="" id="branch_div">
                        {!! Form::select('branche_id', $branches, $employee->branch_id, ['class' => 'form-control select2', 'id' => 'branch_id']) !!}
                    </div>
                    </div>


                    <div class="form-group col-md-6">
                        {!! Form::label('roles', __('Role'),['class'=>'form-label']) !!}
                        {!! Form::select('role', $roles, $employee->type , ['class' => 'form-control select2', 'id' => 'role']) !!}
                        <input type="hidden" name="role" value="{{ $employee->type }}" id="hidden_role">
                    </div>


                    <div class="form-group col-md-12 ">
                        {!! Form::label('company_doj', __('Company Date Of Joining'),['class'=>'form-label']) !!}
                        {!! Form::date('company_doj', $employee->company_doj, ['class' => 'form-control datepicker','required' => 'required']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card emp_details">
            <div class="card-header">
                <h6 class="mb-0">{{__('Document')}}</h6>
            </div>
            <div class="card-body employee-detail-edit-body">
                @foreach($documents as $key => $document)
                    <div class="row">
                        <div class="form-group col-12">
                            <div class="float-left col-4">
                                <label for="document" class="float-left pt-1 form-label">{{ $document->name }} @if($document->is_required == 1) <span class="text-danger">*</span> @endif</label>
                            </div>
                            <div class="float-right col-4">
                                <input type="hidden" name="emp_doc_id[{{ $document->id}}]" value="{{$document->id}}">
                                <div class="choose-file form-group">
                                    <label for="document[{{ $document->id }}]">
                                        <input class="form-control @if(!empty($employeedoc[$document->id])) float-left @endif @error('document') is-invalid @enderror border-0" @if($document->is_required == 1 && empty($employeedoc[$document->id]) ) required @endif name="document[{{ $document->id}}]" onchange="document.getElementById('{{'blah'.$key}}').src = window.URL.createObjectURL(this.files[0])" type="file"  data-filename="{{ $document->id.'_filename'}}">
                                    </label>
                                    <p class="{{ $document->id.'_filename'}}"></p>
                                    @php
                                        $logo=\App\Models\Utility::get_file('uploads/document/');
                                    @endphp
                                    <img id="{{'blah'.$key}}" src="{{ (isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id])?$logo.'/'.$employeedoc[$document->id]:'') }}" width="25%">
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
                        {!! Form::text('account_holder_name', $employee->account_holder_name, ['class' => 'form-control']) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('account_number', __('Account Number'),['class'=>'form-label']) !!}
                        {!! Form::number('account_number', $employee->account_number, ['class' => 'form-control']) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('bank_name', __('Bank Name'),['class'=>'form-label']) !!}
                        {!! Form::text('bank_name', $employee->bank_name, ['class' => 'form-control']) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('bank_identifier_code', __('Bank Identifier Code'),['class'=>'form-label']) !!}
                        {!! Form::text('bank_identifier_code', $employee->bank_identifier_code, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('branch_location', __('Branch Location'),['class'=>'form-label']) !!}
                        {!! Form::text('branch_location', $employee->branch_location, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('tax_payer_id', __('Tax Payer Id'),['class'=>'form-label']) !!}
                        {!! Form::text('tax_payer_id', $employee->tax_payer_id, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        {!! Form::submit('Update', ['class' => 'btn btn-xs btn-primary badge-blue float-right radius-10px']) !!}
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





















