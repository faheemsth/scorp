{{Form::open(array('url'=>route('user.employee.store'),'method'=>'post', 'novalidate' => 'novalidate'))}}
<div class="modal-body">

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
     {{-- ////////////////////////////////////////////// FOR ADMIN ///////////////////////////////////////////////// --}}
        @if (\Auth::user()->type == 'super admin')

            {{-- companies --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
                <div class="form-group col-md-6">
                    {{ Form::label('role', __('Brand'), ['class' => 'form-label']) }}
                    {!! Form::select('companies', $companies, null, [
                        'class' => 'form-control select2',
                        'id' => 'companies',
                        'required' => 'required',
                    ]) !!}
                    @error('role')
                        <small class="invalid-role" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            @elseif(\Auth::user()->type == 'super admin')
            @endif
            {{-- region_id --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
                <div class="form-group col-md-6">
                    {{ Form::label('role', __('Region'), ['class' => 'form-label']) }}
                    {!! Form::select('region_id', $Region, null, [
                        'class' => 'form-control select2',
                        'id' => 'region_id',
                        'required' => 'required',
                    ]) !!}
                    @error('role')
                        <small class="invalid-role" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            @elseif(\Auth::user()->type == 'super admin')
            @endif

            {{-- branch_id --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
                <div class="form-group col-md-6">
                    <label for="region_id">{{ __('Branch') }}</label>
                    <div id="branch_div">
                        <select name="branch_id" id="branch_id" class="form-control select2">
                            <option value="">Select Branch</option>
                        </select>
                    </div>
                    @error('branch_id')
                        <small class="invalid-branch" branch="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            @endif
            {{-- role --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
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
            @elseif(\Auth::user()->type == 'super admin')
                {!! Form::hidden('role', 'company', null, ['class' => 'form-control select2', 'required' => 'required']) !!}
            @endif
        {{-- ////////////////////////////////////////////// FOR COMPANY ///////////////////////////////////////////////// --}}
        @else
            {{-- companies --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
                <div class="form-group col-md-6"  >
                    {{ Form::label('role', __('Brand'), ['class' => 'form-label']) }}
                    {!! Form::select('companies', $companies, null, [
                        'class' => 'form-control select2',
                        'id' => 'companies',
                        'required' => 'required',
                    ]) !!}
                    @error('role')
                        <small class="invalid-role" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            @elseif(\Auth::user()->type == 'super admin')
            @endif
            {{-- region_id --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
                <div class="form-group col-md-6" id="RegionID" style="display: none">
                    {{ Form::label('role', __('Region'), ['class' => 'form-label']) }}
                    {!! Form::select('region_id', $Region, null, [
                        'class' => 'form-control select2',
                        'id' => 'region_id',
                        'required' => 'required',
                    ]) !!}
                    @error('role')
                        <small class="invalid-role" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            @elseif(\Auth::user()->type == 'super admin')
            @endif
            {{-- branch_id --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
                <div class="form-group col-md-6" id="branchID" style="display: none">
                    <label for="region_id">{{ __('Branch') }}</label>
                    <div id="branch_div">
                        <select name="branch_id" id="branch_id" class="form-control select2" onchange="toggleDiv()">
                            <option value="">Select Branch</option>
                        </select>
                    </div>
                    @error('branch_id')
                        <small class="invalid-branch" branch="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            @endif
            {{-- role --}}
            @if (
                \Auth::user()->type == 'super admin' ||
                    \Auth::user()->type == 'company' ||
                    \Auth::user()->type == 'team' ||
                    \Auth::user()->type == 'Project Director' ||
                    \Auth::user()->type == 'Project Manager')
                <div class="form-group col-md-6" id="roleID" style="display: none">
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
            @elseif(\Auth::user()->type == 'super admin')
                {!! Form::hidden('role', 'company', null, ['class' => 'form-control select2', 'required' => 'required']) !!}
            @endif
        @endif
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



        <div class="col-md-6">
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


        @if (!$customFields->isEmpty())
            <div class="col-md-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light px-2 py-2" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-dark px-2 py-2">
</div>

{{ Form::close() }}

<script>
    $("#region_id").on("change", function() {
        $('#branchID').show();
    });
    $("#companies").on("change", function() {
        var id = $(this).val();
        $('#RegionID').show();
        $.ajax({
            type: 'GET',
            url: '{{ route('deal_companyemployees') }}',
            data: {
                id: id // Add a key for the id parameter
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $("#branch_div").html(data.branches);
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
    $("#branch_id").on("change", function() {
        $('#roleID').show();
    });
</script>
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

</script>
