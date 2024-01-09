{{Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
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


        <div class="col-md-6 d-none">
            <div class="form-group">
                {{Form::label('name',__('Domain link'),['class'=>'form-label']) }}
                {{Form::text('domain_link',$user->domain_link,array('class'=>'form-control','placeholder'=>__('Domain link')))}}
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
                {{Form::text('website_link',$user->website_link,array('class'=>'form-control','placeholder'=>__('Website link'),'required'=>'required'))}}
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
                {{Form::text('drive_link',$user->drive_link,array('class'=>'form-control','placeholder'=>__('Google Drive link'),'required'=>'required'))}}
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
                {{Form::select('project_director', $projectDirectors, $user->project_director_id, array('class'=>'form-control select2','id' => 'projectDirectors' , 'required'=>'required'))}}
                @error('project_director')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>

    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light"data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2">
</div>

{{Form::close()}}
