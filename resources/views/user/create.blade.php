{{Form::open(array('url'=>'users','method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
                @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>


        <div class="col-md-6 d-none">
            <div class="form-group">
                {{Form::label('name',__('Domain link'),['class'=>'form-label']) }}
                {{Form::text('domain_link',null,array('class'=>'form-control','placeholder'=>__('Domain link')))}}
                @error('domain_link')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('name',__('Website link'),['class'=>'form-label']) }}
                {{Form::text('website_link',null,array('class'=>'form-control','placeholder'=>__('Website link'),'required'=>'required'))}}
                @error('website_link')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('name',__('Google Drive Link'),['class'=>'form-label']) }}
                {{Form::text('drive_link',null,array('class'=>'form-control','placeholder'=>__('Google Drive link'),'required'=>'required'))}}
                @error('drive_link')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('name',__('Project Director'),['class'=>'form-label']) }}
                {{Form::select('project_director', $projectDirectors, null, array('class'=>'form-control select2','id' => 'projectDirectors' , 'required'=>'required'))}}
                @error('project_director')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>

        {!! Form::hidden('role', 'company', null,array('class' => 'form-control select2','required'=>'required')) !!}
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark px-2">
</div>

{{Form::close()}}
