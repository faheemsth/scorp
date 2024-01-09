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
                                    \Auth::user()->type == 'Project Manager')
                                <div class="form-group row ">
                                    <label for="branches" class="col-sm-3 col-form-label">Brands</label>
                                    <div class="form-group col-md-6" id="brand_div">
                                        {!! Form::select('brand_id', $companies, null, [
                                            'class' => 'form-control select2 brand_id',
                                            'id' => 'brands',
                                        ]) !!}
                                    </div>
                                </div>
                            @endif

                            @if (
                                \Auth::user()->type == 'super admin' ||
                                    \Auth::user()->type == 'Project Director' ||
                                    \Auth::user()->type == 'Project Manager' ||
                                    \Auth::user()->type == 'company')
                                <div class="form-group row ">
                                    <label for="branches" class="col-sm-3 col-form-label">Region</label>
                                    <div class="form-group col-md-6" id="region_div">
                                        {!! Form::select('region_id', $Region, null, [
                                            'class' => 'form-control select2',
                                            'id' => 'region_id',
                                        ]) !!}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row ">
                                <label for="branches" class="col-sm-3 col-form-label">Branch</label>
                                <div class="form-group col-md-6" id="branch_div">
                                    <select name="branch_id" id="branchs" class="form-control select2 branchs"
                                        onchange="Change(this)">
                                        <option value="">Select Branch</option>
                                    </select>
                                </div>
                            </div>

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
                                <div class="col-sm-6 " id="assign_to_div">
                                    <select class="form form-control assigned_to select2" id="choices-multiple4"
                                        name="assigned_to">
                                        <option value="">Select Employee</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row d-none">
                                <label for="organization" class="col-sm-3 col-form-label">Agency

                                    <span class="text-danger">*</span>
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
                                <label for="organization" class="col-sm-3 col-form-label">Start Date</label>
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
                                    <span class="text-danger"></span></label>
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
                                        </select>
                                        <input type="hidden" value="{{ $type }}" name="related_type">
                                    @else
                                        <select class="form form-control select2 related_type" id="choices-multiple6"
                                            name="related_type">
                                            <option value="">Select type</option>
                                            <option value="organization"
                                                {{ $type == 'organization' ? 'selected' : '' }}>
                                                Organization</option>
                                            <option value="lead" {{ $type == 'lead' ? 'selected' : '' }}>Lead
                                            </option>
                                            <option value="deal" {{ $type == 'deal' ? 'selected' : '' }}>Admission
                                            </option>
                                        </select>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="website" class="col-sm-3 col-form-label">Related To
                                    <span class="text-danger"></span></label>
                                <div class="col-sm-6" id="related_to_div">

                                    @if (isset($typeId) && !empty($typeId))
                                        <select class="form form-control related_to select2" disabled
                                            id="choices-multiple7" name="related_to">
                                            <option value="">Related To</option>
                                            @forelse ($relateds as $key => $related)
                                                <option value="{{ $key }}"
                                                    {{ $key == $typeId ? 'selected' : '' }}>
                                                    {{ $related }}</option>
                                            @empty
                                            @endforelse
                                        </select>

                                        <input type="hidden" value="{{ $typeId }}" name="related_to">
                                    @else
                                        <select class="form form-control related_to select2" id="choices-multiple7"
                                            name="related_to">
                                            <option value="">Related To</option>
                                            @forelse ($relateds as $key => $related)
                                                <option value="{{ $key }}"
                                                    {{ $key == $typeId ? 'selected' : '' }}>
                                                    {{ $related }}</option>
                                            @empty
                                            @endforelse

                                        </select>
                                    @endif
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
                                <label for="website" class="col-sm-3 col-form-label">Task Visibility</label>
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
    // change branch for assign
    function Change(selectedBranch) {
        var id = selectedBranch.value;
        $.ajax({
            type: 'GET',
            url: '{{ route('lead_companyemployees') }}',
            data: {
                id: id
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $("#assign_to_div").html(data.employees);
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
</script>
