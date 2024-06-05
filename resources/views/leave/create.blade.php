{{Form::open(array('url'=>'leave','method'=>'post'))}}
    <div class="modal-body">

    <input type="test" name="employee_id" value="{{ \Auth::user()->id }}">

     <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                <table class="w-100">
                                    <tbody>
                                        

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Brand') }} <span
                                                class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">

                                                {{-- Brand Dropdown --}}
                                                @if (
                                                    \Auth::user()->type == 'super admin' ||
                                                        \Auth::user()->type == 'Project Director' ||
                                                        \Auth::user()->type == 'Project Manager' ||
                                                        \Auth::user()->can('level 1') ||
                                                        \Auth::user()->can('level 2'))
                                                            {!! Form::select('brand_id', $companies, 0, [
                                                                'class' => 'form-control select2 brand_id',
                                                                'id' => 'brands',
                                                            ]) !!}
                                                @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                                                     <input type="hidden" name="brand_id" value="{{\Auth::user()->id}}">
                                                    <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
                                                        @foreach($companies as $key => $comp)
                                                            <option value="{{$key}}" {{ $key == \Auth::user()->id ? 'selected' : ''}}>{{$comp}}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="hidden" name="brand_id" value="{{\Auth::user()->brand_id}}">
                                                        <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
                                                            @foreach($companies as $key => $comp)
                                                             <option value="{{$key}}" {{ $key == \Auth::user()->brand_id ? 'selected' : ''}}>{{$comp}}</option>
                                                            @endforeach
                                                        </select>
                                                @endif

                                                {{-- End Brand Dropdown --}}
                                            </td>
                                        </tr>



                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Region') }} <span
                                                class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="region_div">

                                                @if (\Auth::user()->type == 'super admin' ||
                                                        \Auth::user()->type == 'Project Director' ||
                                                        \Auth::user()->type == 'Project Manager' ||
                                                        \Auth::user()->type == 'company' ||
                                                        \Auth::user()->type == 'Region Manager' ||
                                                        \Auth::user()->can('level 1') ||
                                                        \Auth::user()->can('level 2' ||
                                                        \Auth::user()->can('level 3')))

                                                            {!! Form::select('region_id', $regions, null, [
                                                                'class' => 'form-control select2',
                                                                'id' => 'region_id',
                                                            ]) !!}

                                                @else
                                                     <input type="hidden" name="region_id" value="{{ \Auth::user()->region_id }}">
                                                        {!! Form::select('region_id', $regions, \Auth::user()->region_id, [
                                                            'class' => 'form-control select2',
                                                            'disabled' => 'disabled',
                                                            'id' => 'region_id',
                                                        ]) !!}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Branch') }} <span
                                                class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="branch_div">

                                                @if (\Auth::user()->type == 'super admin' ||
                                                        \Auth::user()->type == 'Project Director' ||
                                                        \Auth::user()->type == 'Project Manager' ||
                                                        \Auth::user()->type == 'company' ||
                                                        \Auth::user()->type == 'Region Manager' ||
                                                        \Auth::user()->type == 'Branch Manager' ||
                                                        \Auth::user()->can('level 1') ||
                                                        \Auth::user()->can('level 2') ||
                                                        \Auth::user()->can('level 3') ||
                                                        \Auth::user()->can('level 4'))
                                                            <select name="lead_branch" id="branch_id" class="form-control select2 branch_id"
                                                                onchange="Change(this)">
                                                                    @foreach($branches as $key => $branch)
                                                                        <option value="{{$key}}">{{$branch}}</option>
                                                                    @endforeach
                                                            </select>
                                                @else
                                                         <input type="hidden" name="lead_branch" value="{{ \Auth::user()->branch_id }}">
                                                            <select name="branch_id" id="branch_id" class="form-control select2 branch_id"
                                                                onchange="Change(this)">
                                                                    @foreach($branches as $key => $branch)
                                                                        <option value="{{$key}}" {{ \Auth::user()->branch_id == $key ? 'selected' : '' }}>{{$branch}}</option>
                                                                    @endforeach
                                                            </select>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 110px; font-size: 13px;">
                                                {{ __('User Responsible') }} <span
                                                class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="assign_to_divs">
                                                <select class="form-control select2" id="choice-222" name="lead_assigned_user">
                                                    @foreach($employees as $key => $employee)
                                                    <option value="{{$key}}">{{$employee}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                       

 
                                    </tbody>
                                </table>
                            </div>

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
<script type="text/javascript">
    

    $(".brand_id").on("change", function(){

        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-regions') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.html);
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


    $(document).on("change", ".region_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branches') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#branch_div').html('');
                    $("#branch_div").html(data.html);
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

    $(document).on("change", ".branch_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branch-users') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#assign_to_divs').html('');
                    $("#assign_to_divs").html(data.html);
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


    //////////////////Submitting Form
    // new lead form submitting...
    $("#lead-creating-form").on("submit", function(e) {

        e.preventDefault();
        var formData = $(this).serialize();

        $(".new-lead-btn").val('Processing...');
        // $('.new-lead-btn').attr('disabled', 'disabled');

        $(".new-lead-btn").prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ route('leads.store') }}",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    $('.leads-list-tbody').prepend(data.html);
                    openSidebar('/get-lead-detail?lead_id=' + data.lead_id);
                    // openNav(data.lead.id);
                    return false;
                } else {
                    if(data.htmlead !== ''){
                        Swal.fire({
                        title: data.message,
                        html: data.htmlead,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    })

                    }else{
                        show_toastr('error', data.message, 'error');
                    }
                    $(".new-lead-btn").val('Create');
                    $('.new-lead-btn').removeAttr('disabled');
                }
            }
        });
    });
    $(document).on("click", "#leadLink", function(){
        Swal.close();
        $('#commonModal').modal('hide');
    });

</script>
