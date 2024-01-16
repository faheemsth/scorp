{{ Form::open(['url' => route('user.employee.update', $user->id), 'method' => 'post', 'novalidate' => 'novalidate']) }}

<div class="modal-body" style="height:75vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-2 py-0">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                        {{ Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => __('Enter User Name'), 'required' => 'required']) }}
                        @error('name')
                            <small class="invalid-name" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-6">
                    {{ Form::label('role', __('User Role'), ['class' => 'form-label']) }}
                    <select name="role" id="roles" class="form form-control select2">
                        @foreach($roles as $role)
                        <option value="{{$role}}" {{ $role == $user->type ? "selected":"" }}>{{ $role }}</option>
                        @endforeach 
                    </select>
                
                    @error('role')
                        <small class="invalid-role" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>

                <div class="form-group col-md-6" id="brand_div" >
                    {{ Form::label('brand', __('Brand'), ['class' => 'form-label']) }}
                    {!! Form::select('companies', $companies, $user->brand_id, [
                        'class' => 'form-control select2',
                        'id' => 'brands'
                    ]) !!}
                </div>

                <div class="form-group col-md-6 {{ $user->type == 'Project Director' || $user->type == 'Project Manager' ? 'd-none' : ''}}" id="region_div">
                    {{ Form::label('region', __('Region'), ['class' => 'form-label']) }}
                    {!! Form::select('region', $Region, $user->region_id, [
                        'class' => 'form-control select2',
                        'id' => 'region_id'
                    ]) !!}
                </div>


                <div class="form-group col-md-6 {{ $user->type == 'Project Director' || $user->type == 'Project Manager' ? 'd-none' : ''}}" id="branch_div">
                    <label for="branch">{{ __('Branch') }}</label>
                    <select name="branch_id" id="branch_id" class="form-control select2">
                        @foreach($branches as $key => $branch)
                            <option value="{{$key}}">{{$branch}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                        {{ Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => __('Enter User Email'), 'required' => 'required']) }}
                        @error('email')
                            <small class="invalid-email" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </small>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
                        {{ Form::text('phone', $user->phone, ['class' => 'form-control', 'required' => 'required']) }}
                        @error('phone')
                            <small class="invalid-phone" role="alert">
                                <strong class="text-danger">{{ $phone }}</strong>
                            </small>
                        @enderror
                    </div>
                </div>



                <div class="col-md-6 ">
                    <div class="form-group">
                        {{ Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) }}
                        {{ Form::date('dob', $user->date_of_birth, ['class' => 'form-control', 'required' => 'required']) }}
                        @error('dob')
                            <small class="invalid-dob" role="alert">
                                <strong class="text-danger">{{ $dob }}</strong>
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Gender'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="gender">
                                <option value="male" {{ $employee ? $employee->gender == 'male' ? 'selected' : '' : ''}}>Male</option>
                                <option value="female" {{ $employee ? $employee->gender == 'female' ? 'selected' : '' : ''}}>Female</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Address'), ['class' => 'form-label']) }}
                            <textarea class="form-control" name="address">{{ $employee ? $employee->address : ''}}</textarea>
                        </div>

                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Account Holder Name'), ['class' => 'form-label']) }}
                            <input class="form-control" value="{{ $employee ? $employee->account_holder_name : ''}}" type="text" name="account_holder_name" />
                        </div>

                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Account Number'), ['class' => 'form-label']) }}
                            <input class="form-control" value="{{ $employee ? $employee->account_number : ''}}" type="text" name="account_number" />
                        </div>

                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Bank Name'), ['class' => 'form-label']) }}
                            <input class="form-control" value="{{ $employee ? $employee->bank_name : ''}}" type="text" name="bank_name" />
                        </div>

                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Bank Code'), ['class' => 'form-label']) }}
                            <input class="form-control" value="{{ $employee ? $employee->bank_identifier_code : ''}}" type="text" name="bank_identifier_code" />
                        </div>
                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Branch Location'), ['class' => 'form-label']) }}
                            <input class="form-control" value="{{ $employee ? $employee->branch_location : ''}}" type="text" name="branch_location" />
                        </div>
                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Tax Payer ID'), ['class' => 'form-label']) }}
                            <input class="form-control" value="{{ $employee ? $employee->tax_payer_id : ''}}" type="text" name="tax_payer_id" />
                        </div>
                        <div class="form-group col-md-6" >
                            {{ Form::label('role', __('Salary'), ['class' => 'form-label']) }}
                            <input class="form-control" value="{{ $employee ? $employee->salary : ''}}" type="number" name="salary" />
                        </div>


                @if (!$customFields->isEmpty())
                    <div class="col-md-6">
                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                            @include('customFields.formBuilder')
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-dark px-2">
</div>

{{ Form::close() }}


<script>
    function toggleDiv() {
        var branchSelect = document.getElementById("branch_id");
        var hiddenDiv = document.getElementById("roleID");
        if (branchSelect.value !== "") {
            hiddenDiv.style.display = "block";
        } else {
            hiddenDiv.style.display = "none";
        }
    }

    $(document).on("change", "#roles" ,function(){
        var role = $(this).text();
        if (role == 'Project Director' || role == 'Project Manager') {
            $("#region_div, #branch_div").addClass('d-none');
        } else {
            $("#region_div, #branch_div").removeClass('d-none');
        }

    })




    $("#brands").on("change", function(){
        var id = $(this).val();
        var type = 'brand';

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id,  // Add a key for the id parameter
                type: type
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.regions);
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


    $(document).on("change", "#region_id" ,function(){
        var id = $(this).val();
        var type = 'region';
        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id,  // Add a key for the id parameter
                type: type
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    if(type == 'region'){
                        $('#branch_div').html('');
                        $("#branch_div").html(data.branches);
                        select2();
                    }
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

</script>

