{{ Form::open(array('url' => 'clients')) }}
<div class="modal-body" style="min-height: 65vh;">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','placeholder'=>__('Enter client Name'),'required'=>'required')) }}
        </div>

        <div class="form-group">
            {{ Form::label('passport_number', __('Passport Number'),['class'=>'form-label']) }}
            {{ Form::text('passport_number', null, array('class' => 'form-control','placeholder'=>__('Enter passport number'),'required'=>'required')) }}
        </div>


        <div class="form-group">
            {{ Form::label('email', __('E-Mail Address'),['class'=>'form-label']) }}
            {{ Form::email('email', null, array('class' => 'form-control','placeholder'=>__('Enter Client Email'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('password', __('Password'),['class'=>'form-label']) }}
            {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
            @error('password')
            <small class="invalid-password" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>

        @if(!$customFields->isEmpty())
            @include('custom_fields.formBuilder')
        @endif

    </div>
</div>

<div class="modal-footer">

    <input type="button" value="{{__('Cancel')}}" class="btn  btn-danger" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark">
</div>

{{Form::close()}}


