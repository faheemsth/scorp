{{Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group ">
                {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter User Name')))}}
                @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                @error('email')
                <small class="invalid-email" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'company' || \Auth::user()->type == 'team')
            <div class="form-group col-md-6">
                {{ Form::label('role', __('User Role'),['class'=>'form-label']) }}
                {!! Form::select('role', $roles, $user->roles,array('class' => 'form-control select2','required'=>'required')) !!}
                @error('role')
                <small class="invalid-role" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        @endif

        @if(\Auth::user()->type == 'company' || \Auth::user()->type == 'team')
        <div class="form-group col-md-6">
            {{ Form::label('branch_id', __('Branch'),['class'=>'form-label']) }}
            {!! Form::select('branch_id', $branches, null,array('class' => 'form-control select2','required'=>'required')) !!}
            @error('branch_id')
            <small class="invalid-branch" branch="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        @endif

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('phone',__('Phone'),['class'=>'form-label']) }}
                {{Form::text('phone', $user->phone,array('class'=>'form-control', 'required'=>'required'))}}
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
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light"data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>

{{Form::close()}}
