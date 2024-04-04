{{Form::open(array('url'=>'leave','method'=>'post'))}}
    <div class="modal-body">

    <input type="hidden" name="employee_id" value="{{ \Auth::user()->id }}">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('leave_type_id',__('Leave Type') ,['class'=>'form-label'])}}
                <select name="leave_type_id" id="leave_type_id" class="form-control select2">
                    @foreach($leavetypes as $leave)
                        <option value="{{ $leave->id }}">{{ $leave->title }} (<p class="float-right pr-5">{{ $leave->days }}</p>)</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}
                {{Form::date('start_date',null,array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}
                {{Form::date('end_date',null,array('class'=>'form-control'))}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('leave_reason',__('Leave Reason') ,['class'=>'form-label'])}}
                {{Form::textarea('leave_reason',null,array('class'=>'form-control','placeholder'=>__('Leave Reason'), 'rows'=>'3'))}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('remark',__('Remark'),['class'=>'form-label'])}}
                {{Form::textarea('remark',null,array('class'=>'form-control','placeholder'=>__('Leave Remark'), 'rows'=>'3'))}}
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark px-2">
</div>
{{Form::close()}}
