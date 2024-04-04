
{{ Form::model($client, array('route' => array('clients.update', $client->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','placeholder'=>__('Enter Client Name'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{ Form::label('email', __('E-Mail Address'),['class'=>'form-label']) }}
            {{ Form::email('email', null, array('class' => 'form-control','placeholder'=>__('Enter Client Email'),'required'=>'required')) }}
        </div>
        
        <div class="form-group">
            {{ Form::label('passport_number', __('Passport Number'),['class'=>'form-label']) }}
            {{ Form::text('passport_number', null, array('class' => 'form-control','placeholder'=>__('Enter passport number'),'required'=>'required')) }}
        </div>
        <div class="form-group">
            {{Form::hidden('password', 'study1234',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
        </div>

        @if(!$customFields->isEmpty())
            @include('custom_fields.formBuilder')
        @endif

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2">
</div>

{{Form::close()}}


