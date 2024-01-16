{{Form::open(array('url'=>route('user.employee.store'),'method'=>'post', 'novalidate' => 'novalidate'))}}
<div class="modal-body" style="height:75vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-2 py-0">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter User Name'), 'required' => 'required']) }}
                        @error('name')
                            <small class="invalid-name" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-6">
                    {{ Form::label('role', __('User Role'), ['class' => 'form-label']) }}
                    {!! Form::select('role', $roles, null, [
                        'class' => 'form-control select2',
                        'id' => 'roles',
                        'required' => 'required',
                    ]) !!}
                    @error('role')
                        <small class="invalid-role" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>

                <div class="form-group col-md-6" id="brand_div">
                    {{ Form::label('brand', __('Brand'), ['class' => 'form-label']) }}
                    {!! Form::select('companies', $companies, null, [
                        'class' => 'form-control select2',
                        'id' => 'brands'
                    ]) !!}
                </div>

                <div class="form-group col-md-6" id="region_div">
                    {{ Form::label('role', __('Region'), ['class' => 'form-label']) }}
                    {!! Form::select('region', $Region, null, [
                        'class' => 'form-control select2',
                        'id' => 'region_id'
                    ]) !!}
                </div>


                <div class="form-group col-md-6" id="branch_div">
                    <label for="branch">{{ __('Branch') }}</label>
                    <select name="branch_id" id="branch_id" class="form-control select2">
                        <option value="">Select Branch</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter User Email'), 'required' => 'required']) }}
                        @error('email')
                            <small class="invalid-email" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                        <input type="text" class="form-control" placeholder="Enter User Password"
                            value="{{ $autoGeneratedPassword }}" name="password" required minlength="6">
                        @error('password')
                            <small class="invalid-password" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </small>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
                        {{ Form::text('phone', null, ['class' => 'form-control', 'required' => 'required']) }}
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
                        {{ Form::date('dob', null, ['class' => 'form-control', 'required' => 'required']) }}
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
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Address'), ['class' => 'form-label']) }}
                    <textarea class="form-control" name="address"></textarea>
                </div>

                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Account Holder Name'), ['class' => 'form-label']) }}
                    <input class="form-control" type="text" name="account_holder_name" />
                </div>

                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Account Number'), ['class' => 'form-label']) }}
                    <input class="form-control" type="text" name="account_number" />
                </div>

                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Bank Name'), ['class' => 'form-label']) }}
                    <input class="form-control" type="text" name="bank_name" />
                </div>

                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Bank Code'), ['class' => 'form-label']) }}
                    <input class="form-control" type="text" name="bank_identifier_code" />
                </div>
                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Branch Location'), ['class' => 'form-label']) }}
                    <input class="form-control" type="text" name="branch_location" />
                </div>
                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Tax Payer ID'), ['class' => 'form-label']) }}
                    <input class="form-control" type="text" name="tax_payer_id" />
                </div>
                <div class="form-group col-md-6" >
                    {{ Form::label('role', __('Salary'), ['class' => 'form-label']) }}
                    <input class="form-control" type="number" name="salary" />
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
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light px-2 py-2" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-dark px-2 py-2">
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

        $("#roles").on("change", function(){
            var role = $(this).text();
            if(role == 'Project Director' || role == 'Project Manager'){
                //$("#brand_div").css('display', 'none');
                $("#region_div").css('display', 'none');
                $("#branch_div").css('display', 'none');
            }else if(role == 'Region Manager') {
                $("#branch_div").css('display', 'none');
            }else{
               // $("#brand_div").css('display', 'block');
                $("#region_div").css('display', 'block');
                $("#branch_div").css('display', 'block');
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
