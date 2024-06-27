<style>
    .form-group {
        margin-bottom: 0px;
        padding-top: 0px;
    }



    .space {
        padding: 3px 3px;
    }
</style>
{{ Form::model(\Auth::user(), ['route' => ['organization.tasks.store', \Auth::user()->id], 'method' => 'POST', 'id' => 'create-task', 'style' => 'z-index: 9999999 !important;']) }}


<div class="modal-body pt-0 " style="height: 80vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-2 py-0">

            {{-- ACCORDION --}}
            <div class="accordion" id="accordionPanelsStayOpenExample">
                {{-- Organizaiton Basic Info --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseOne">
                            TASK DETAILS
                        </button>
                    </h2>


                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body ">

                            <div class="form-group row ">
                                <label for="organization-name" class="col-sm-3 col-form-label">
                                    Task Name
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="task-name" value=""
                                        placeholder="Task Name" name='task_name'>
                                </div>
                            </div>
                            @if (
                                \Auth::user()->type == 'super admin' ||
                                    \Auth::user()->type == 'Project Director' ||
                                    \Auth::user()->type == 'Project Manager' ||
                                    \Auth::user()->can('level 1') ||
                                    \Auth::user()->can('level 2'))
                                <div class="form-group row ">
                                    <label for="branches" class="col-sm-3 col-form-label">Brands<span
                                        class="text-danger">*</span></label>
                                    <div class="form-group col-md-6" id="brand_div">
                                        {!! Form::select('brand_id', $companies, 0, [
                                            'class' => 'form-control select2 brand_id',
                                            'id' => 'brands',
                                        ]) !!}
                                    </div>
                                </div>
                            @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                            <div class="form-group row ">
                                <label for="branches" class="col-sm-3 col-form-label">Brands<span
                                    class="text-danger">*</span></label>
                                <div class="form-group col-md-6" id="brand_div">
                                    {{-- <input type="hidden" name="brand_id" value="{{\Auth::user()->id}}"> --}}
                                    <select class='form-control select2 brand_id' id="brands" name="brand_id">
                                        @foreach($companies as $key => $comp)
                                         <option value="{{$key}}" {{ $key == \Auth::user()->id ? 'selected' : ''}}>{{$comp}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @else
                            <div class="form-group row ">
                                <label for="branches" class="col-sm-3 col-form-label">Brands<span
                                    class="text-danger">*</span></label>
                                <div class="form-group col-md-6" id="brand_div">
                                    {{-- <input type="hidden" name="brand_id" value="{{\Auth::user()->brand_id}}"> --}}
                                    <select class='form-control select2 brand_id' id="brands" name="brand_id">
                                        @foreach($companies as $key => $comp)
                                         <option value="{{$key}}" {{ $key == \Auth::user()->brand_id ? 'selected' : ''}}>{{$comp}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif




                            @if (\Auth::user()->type == 'super admin' ||
                                    \Auth::user()->type == 'Project Director' ||
                                    \Auth::user()->type == 'Project Manager' ||
                                    \Auth::user()->type == 'company' ||
                                    \Auth::user()->type == 'Region Manager' ||
                                    \Auth::user()->can('level 1') ||
                                    \Auth::user()->can('level 2') ||
                                    \Auth::user()->can('level 3'))

                                <div class="form-group row ">
                                    <label for="branches" class="col-sm-3 col-form-label">Region<span
                                        class="text-danger">*</span></label>
                                    <div class="form-group col-md-6" id="region_div">
                                        {!! Form::select('region_id', $Region, null, [
                                            'class' => 'form-control select2',
                                            'id' => 'region_id',
                                        ]) !!}
                                    </div>
                                </div>

                            @else
                                <div class="form-group row ">
                                    <label for="branches" class="col-sm-3 col-form-label">Region<span
                                        class="text-danger">*</span></label>
                                    <div class="form-group col-md-6" id="region_div">
                                        <input type="hidden" name="region_id" value="{{ \Auth::user()->region_id }}">
                                        {!! Form::select('region_id', $Region, \Auth::user()->region_id, [
                                            'class' => 'form-control select2',
                                            'disabled' => 'disabled',
                                            'id' => 'region_id',
                                        ]) !!}
                                    </div>
                                </div>
                            @endif

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

                                        <div class="form-group row ">
                                            <label for="branches" class="col-sm-3 col-form-label">Branch<span
                                                class="text-danger">*</span></label>
                                            <div class="form-group col-md-6" id="branch_div">
                                                <select name="branch_id" id="branch_id" class="form-control select2 branch_id"
                                                    onchange="Change(this)">
                                                        @foreach($branches as $key => $branch)
                                                            <option value="{{$key}}">{{$branch}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                            @else
                                <div class="form-group row ">
                                    <label for="branches" class="col-sm-3 col-form-label">Branch<span
                                        class="text-danger">*</span></label>
                                    <div class="form-group col-md-6" id="branch_div">
                                        <input type="hidden" name="branch_id" value="{{ \Auth::user()->branch_id }}">
                                        <select name="branch_id" id="branch_id" class="form-control select2 branch_id"
                                            onchange="Change(this)">
                                                @foreach($branches as $key => $branch)
                                                    <option value="{{$key}}" {{ \Auth::user()->branch_id == $key ? 'selected' : '' }}>{{$branch}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif


                            <div class="form-group row d-none">
                                <label for="type" class="col-sm-3 col-form-label">Assign Type <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <select class="form form-control select2 assign_type" id="choices-multiple3"
                                        name="assign_type">
                                        <option value="individual">individual</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row ">
                                <label for="organization" class="col-sm-3 col-form-label">Assigned to <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-6 " id="assign_to_divs">
                                    <select class="form form-control assigned_to select2" id="choices-multiple4" name="assigned_to">
                                        @foreach($employees as $key => $employee)
                                        <option value="{{$key}}">{{$employee}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="type" class="col-sm-3 col-form-label">Task Status<span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <select class="form form-control select2" id="choices-multiple5" name="status" {{ !\Auth::user()->can('edit assign to task') ? 'disabled' : '' }}>
                                        <option value="">Select Status</option>
                                        <option value="0" selected>On Going</option>
                                        <option value="1">Completed
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row d-none">
                                <label for="organization" class="col-sm-3 col-form-label">Agency
                                </label>
                                <div class="col-sm-6">
                                    <select class="form form-control select2 organization_id" id="choices-multiple2"
                                        name="organization_id">
                                        <option value="">Select Agency</option>
                                        @foreach ($orgs as $key => $org)
                                            <option value="{{ $key }}">{{ $org }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row d-none">
                                <label for="organization" class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-6">
                                    <select class="form form-control select2" id="choices-multiple5" name="stage_id">
                                        <option value="">Select Category</option>
                                        @foreach ($stages as $key => $stage)
                                            <option value="{{ $key }}">{{ $stage }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="organization" class="col-sm-3 col-form-label">Due Date <span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <input type="date" class="form form-control" value="" name="due_date">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- Organizaiton Contact Info --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="ture"
                            aria-controls="panelsStayOpen-collapseTwo">
                            ADDITIONAL INFORMATION
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            <div class="form-group row ">
                                <label for="organization" class="col-sm-3 col-form-label">Start Date<span
                                    class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <input type="date" class="form form-control" value="{{ date('Y-m-d') }}"
                                        name="start_date">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="website" class="col-sm-3 col-form-label">Remainder Date</label>
                                <div class="col-sm-6 d-flex">
                                    <input type="date" class="form form-control" value=""
                                        name="remainder_date">
                                    <input type="time" class="form form-control" value=""
                                        name="remainder_time">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Organizaiton Address Info --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="ture"
                            aria-controls="panelsStayOpen-collapseThree">
                            RELATED TO
                        </button>
                    </h2>


                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            <div class="form-group row">
                                <label for="website" class="col-sm-3 col-form-label">Related Type
                                    </label>
                                <div class="col-sm-6">
                                    @if (isset($type) && !empty($type))
                                        <select class="form form-control select2 related_type" disabled
                                            id="choices-multiple6" name="related_type">
                                            <option value="">Select type</option>
                                            <option value="organization"
                                            {{ $type == 'organization' ? 'selected' : '' }}>
                                                Organization</option>
                                            <option value="lead" {{ $type == 'lead' ? 'selected' : '' }}>Lead
                                            </option>
                                            <option value="deal" {{ $type == 'deal' ? 'selected' : '' }}>Admission
                                            </option>
                                            <option value="application" {{ $type == 'application' ? 'selected' : '' }}>Application
                                            </option>
                                            <option value="toolkit" {{ $type == 'toolkit' ? 'selected' : '' }}>Toolkit
                                            </option>
                                            <option value="agency" {{ $type == 'agency' ? 'selected' : '' }}>Agency
                                            </option>
                                        </select>
                                        <input type="hidden" value="{{ $type }}" name="related_type">
                                    @else
                                        <select class="form form-control select2 related_type" id="choices-multiple6"
                                            name="related_type">
                                            <option value="">Select type</option>
                                             <option value="organization"  {{ $type == 'organi' ? 'selected' : '' }} >
                                                Organization</option>
                                            <option value="lead" {{ $type == 'lead' ? 'selected' : '' }}>Lead
                                            </option>
                                            <option value="deal" {{ $type == 'deal' ? 'selected' : '' }}>Admission
                                            </option>
                                            <option value="application" {{ $type == 'application' ? 'selected' : '' }}>Application
                                            </option>
                                            <option value="toolkit" {{ $type == 'toolkit' ? 'selected' : '' }}>Toolkit
                                            </option>
                                            <option value="agency" {{ $type == 'agency' ? 'selected' : '' }}>Agency
                                            </option>
                                        </select>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="website" class="col-sm-3 col-form-label">Related To
                                   </label>
                                <div class="col-sm-6" id="related_to_div">
                                    <select class="form form-control related_to select2"
                                            id="choices-multiple7" name="related_to" readonly>
                                            <option value="">Related To</option>
                                            @if(!empty($organization))
                                                <option value="{{$organization->id}}" selected>{{$organization->name}}</option>
                                            @endif

                                            @if(!empty($lead))
                                                <option value="{{$lead->id}}" selected>{{$lead->name}}</option>
                                            @endif

                                            @if(!empty($deal))
                                                <option value="{{$deal->id}}" selected>{{$deal->name}}</option>
                                            @endif

                                            @if(!empty($application))
                                                <option value="{{$application->id}}" selected>{{$application->application_key}}</option>
                                            @endif
                                            @if(!empty($University))
                                                <option value="{{$University->id}}" selected>{{$University->name}}</option>
                                            @endif
                                            @if(!empty($Agency))
                                                <option value="{{$Agency->id}}" selected>{{$Agency->username}}</option>
                                            @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- Organizaiton Description --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-description" aria-expanded="ture"
                            aria-controls="panelsStayOpen-description">
                            DESCRIPTION INFORMATION
                        </button>
                    </h2>
                    <div id="panelsStayOpen-description" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            <textarea name="description" id="" cols="30" rows="3" class="form form-control"></textarea>
                        </div>
                    </div>
                </div>


                {{-- Organizaiton Description --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingfour">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-description" aria-expanded="ture"
                            aria-controls="panelsStayOpen-description">
                            PERMISSIONS
                        </button>
                    </h2>
                    <div id="panelsStayOpen-description" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingfour">
                        <div class="accordion-body">
                            <div class="form-group row">
                                <label for="website" class="col-sm-3 col-form-label">Task Visibility </label>
                                <div class="col-sm-6">
                                    <select class="form form-control select2" id="choices-multiple8"
                                        name="visibility">
                                        <option value="">Select Visibility</option>
                                        <option value="public" selected>public</option>
                                        <option value="private">private</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn px-2 btn-light" data-bs-dismiss="modal">
    <input type="submit" value="Create" class="btn  btn-dark px-2 create-task-btn">
</div>

{{ Form::close() }}


<script>
var BranchId = '';
    $("#choices-multiple6").on("change", function() {
        // var type = $(this).val();
        // Id = BranchId

        var id = $("#branch_id").val();
        var type = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('GetBranchByType') }}',
            data: {
                id: id,
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);
                    if (data.status === 'success') {
                         $('#related_to_div').html('');
                        if(data.University === 'success')
                        {
                           $("#related_to_div").html(data.Universites);
                           select2();
                        }else{
                           $("#related_to_div").html(data.branches);
                           select2();
                        }

                    }
                }

        });
    });

    // change branch for assign
    function Change(selectedBranch) {
        var id = selectedBranch.value;
        var type = 'branch';

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands_task') }}',
            data: {
                id: id,
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    console.log(data.employees);
                    $("#assign_to_divs").html(data.employees);
                    select2();
                }
            }
        });
    }


    // change brand for region
    $("#brands").on("change", function() {
        var id = $(this).val();
        var type = 'brand';

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands_task') }}',
            data: {
                id: id,
                type: type
            },
            success: function(data) {
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
    // change region for branch
    $(document).on("change", "#region_id", function() {
        var id = $(this).val();
        var type = 'region';
        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands_task') }}',
            data: {
                id: id,
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    if (type == 'region') {
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

    // create task
    $("#create-task").on("submit", function(e) {

        e.preventDefault();
        var formData = $(this).serialize();
        var id = $('.org-id').val();

        $(".create-task-btn").val('Processing...');
        $('.create-task-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "/organization/" + id + "/task",
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    $(".modal-backdrop").removeClass("modal-backdrop");
                    $(".block-screen").css('display', 'none');
                    openSidebar('/get-task-detail?task_id=' + data.task_id);
                    return false;
                } else {
                    toastr.error(data.message);
                    $(".create-task-btn").val('Create');
                    $('.create-task-btn').removeAttr('disabled');
                }
            }
        });
    });
</script>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
