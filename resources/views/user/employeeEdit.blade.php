{{Form::open(array('url'=>route('user.employee.update', $user->id),'method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                {{Form::text('name',$user->name,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
                @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>

        @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'company' || \Auth::user()->type == 'team' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
            <div class="form-group col-md-6">
                {{ Form::label('role', __('Brand'),['class'=>'form-label']) }}
                {!! Form::select('companies', $companies, $user->brand_id,array('class' => 'form-control select2', 'id' => 'companies' ,'required'=>'required', 'disabled' => !\Auth::user()->can('edit brand employee') ? 'disabled' : null)) !!}
                @error('role')
                <small class="invalid-role" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        @elseif(\Auth::user()->type == 'super admin')
            
        @endif

        @if(!\Auth::user()->can('edit role employee')) 
            @php 
           $userRole = Spatie\Permission\Models\Role::where('name', $user->type)->first();
    $roleId = $userRole ? $userRole->id : null;
            @endphp
            <input type="hidden" name="role" value="{{ $roleId }}">
        @endif 

        @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'company' || \Auth::user()->type == 'team' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
            <div class="form-group col-md-6">
                {{ Form::label('role', __('User Role'),['class'=>'form-label']) }}
                {!! Form::select('role', $roles, $user->type,array('class' => 'form-control select2',  'id' => 'roles'  ,'required'=>'required', 'disabled' => !\Auth::user()->can('edit role employee') ? 'disabled' : null)) !!}
                @error('role')
                <small class="invalid-role" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        @elseif(\Auth::user()->type == 'super admin')
            {!! Form::hidden('role', 'company', null,array('class' => 'form-control select2','required'=>'required')) !!}
        @endif

        @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'company' || \Auth::user()->type == 'team' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
        <div class="form-group col-md-6">
            {{ Form::label('branch_id', __('Branch'),['class'=>'form-label']) }}
            {!! Form::select('branch_id', $branches, $user->branch_id,array('class' => 'form-control select2','required'=>'required', 'disabled' => !\Auth::user()->can('edit branch employee') ? 'disabled' : null)) !!}
            @error('branch_id')
            <small class="invalid-branch" branch="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        @endif

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                {{Form::text('email',$user->email,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
                @error('email')
                <small class="invalid-email" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>

        <!-- <div class="col-md-6">
            <div class="form-group">
                {{Form::label('password',__('Password'),['class'=>'form-label'])}}
                <input type="text" class="form-control" placeholder="Enter User Password" value="" name="{{ $user->password }}" required minlength="6">
                @error('password')
                <small class="invalid-password" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div> -->


        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('phone',__('Phone'),['class'=>'form-label']) }}
                {{Form::text('phone',$user->phone,array('class'=>'form-control', 'required'=>'required'))}}
                @error('phone')
                <small class="invalid-phone" role="alert">
                    <strong class="text-danger">{{ $phone }}</strong>
                </small>
                @enderror
            </div>
        </div>



        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('dob',__('Date of Birth'),['class'=>'form-label']) }}
                {{Form::date('dob',$user->date_of_birth,array('class'=>'form-control', 'required'=>'required'))}}
                @error('dob')
                <small class="invalid-dob" role="alert">
                    <strong class="text-danger">{{ $dob }}</strong>
                </small>
                @enderror
            </div>
        </div>


        @if(!$customFields->isEmpty())
            <div class="col-md-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2">
</div>

{{Form::close()}}
