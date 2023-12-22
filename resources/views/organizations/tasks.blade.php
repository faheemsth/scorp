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


<div class="modal-body pt-0 ">
<div class="lead-content my-2" style="max-height: 455px; overflow-y: scroll;">
<div class="card-body px-2 py-0" >

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
                        @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
                        <div class="form-group row ">
                            <label for="branches" class="col-sm-3 col-form-label">Brands</label>
                            <div class="col-sm-6">
                                <select class="form form-control select2 brand_id" id="choices-multiple0" name="brand_id">
                                    <option value="">Select Brands</option>
                                    @foreach ($companies as $key => $brand)
                                        <option value="{{ $key }}">{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row ">
                            <label for="branches" class="col-sm-3 col-form-label">Office</label>
                            <div class="col-sm-6">
                                <select class="form form-control select2" id="choices-multiple1" name="branch_id">
                                    <option value="">Select Office</option>
                                    @foreach ($branches as $key => $branch)
                                        <option value="{{ $key }}">{{ $branch }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row d-none">
                            <label for="type" class="col-sm-3 col-form-label">Assign Type <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form form-control select2 assign_type" id="choices-multiple3"
                                    name="assign_type">
                                    {{-- <option value="">Select Assign type</option> --}}
                                    {{-- <option value="company">Company</option> --}}
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
                                    @foreach($employees as $key => $emp)
                                        <option value="{{ $key }}">{{ $emp }}</option>
                                    @endforeach
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseTwo">
                        ADDITIONAL INFORMATION
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                        aria-controls="panelsStayOpen-collapseThree">
                        RELATED TO
                    </button>
                </h2>


                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
                    aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">
                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">Related Type 
                                <span
                                    class="text-danger"></span></label>
                            <div class="col-sm-6">
                                @if (isset($type) && !empty($type))
                                    <select class="form form-control select2 related_type" disabled
                                        id="choices-multiple6" name="related_type">
                                        <option value="">Select type</option>
                                        <option value="organization" {{ $type == 'organization' ? 'selected' : '' }}>
                                            Organization</option>
                                        <option value="lead" {{ $type == 'lead' ? 'selected' : '' }}>Lead</option>
                                        <option value="deal" {{ $type == 'deal' ? 'selected' : '' }}>Admission
                                        </option>
                                    </select>
                                    <input type="hidden" value="{{ $type }}" name="related_type">
                                @else
                                    <select class="form form-control select2 related_type" id="choices-multiple6"
                                        name="related_type">
                                        <option value="">Select type</option>
                                        <option value="organization" {{ $type == 'organization' ? 'selected' : '' }}>
                                            Organization</option>
                                        <option value="lead" {{ $type == 'lead' ? 'selected' : '' }}>Lead</option>
                                        <option value="deal" {{ $type == 'deal' ? 'selected' : '' }}>Admission
                                        </option>
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">Related To 
                                <span
                                    class="text-danger"></span></label>
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-description" aria-expanded="false"
                        aria-controls="panelsStayOpen-description">
                        DESCRIPTION INFORMATION
                    </button>
                </h2>
                <div id="panelsStayOpen-description" class="accordion-collapse collapse"
                    aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">
                        <textarea name="description" id="" cols="30" rows="3" class="form form-control"></textarea>
                    </div>
                </div>
            </div>


            {{-- Organizaiton Description --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingfour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-description" aria-expanded="false"
                        aria-controls="panelsStayOpen-description">
                        PERMISSIONS
                    </button>
                </h2>
                <div id="panelsStayOpen-description" class="accordion-collapse collapse"
                    aria-labelledby="panelsStayOpen-headingfour">
                    <div class="accordion-body">
                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">Task Visibility</label>
                            <div class="col-sm-6">
                                <select class="form form-control select2" id="choices-multiple8" name="visibility">
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
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '{{ route('organization.assign_to', 1) }}',
            data: {
                type: ''
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    $("#assign_to_div").html(data.html);
                    select2();
                }
            }
        });

        $(".brand_id").on("change", function(){
            var id = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('deal_companyemployees') }}',
                data: {
                    id: id  // Add a key for the id parameter
                },
                success: function(data){
                    data = JSON.parse(data);

                    if (data.status === 'success') {
                        $("#assign_to_div").html(data.html);
                        select2(); // Assuming this is a function to initialize or update a select2 dropdown
                    } else {
                        console.error('Server returned an error:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        });


        // $(".assign_type").on("change", function() {
        //     var type = $(this).val();
        //     var current = $(this);
        //     $.ajax({
        //         type: 'GET',
        //         url: '{{ route('organization.assign_to', 1) }}',
        //         data: {
        //             type
        //         },
        //         success: function(data) {
        //             data = JSON.parse(data);
        //             if (data.status == 'success') {
        //                 $("#assign_to_div").html(data.html);
        //                 select2();
        //             }
        //         }
        //     })

        // })


        $(".related_type").on('change', function() {
            var type = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('organization.task.related_to', 1) }}',
                data: {
                    type
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        //console.log(data.html);
                        $("#related_to_div").html(data.html);
                        select2();
                    }
                }
            })

        })


        $(document).on("submit", "#create-task", function(e) {

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
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $(".modal-backdrop").removeClass("modal-backdrop");
                        $(".block-screen").css('display', 'none');
                        openSidebar('/get-task-detail?task_id=' + data.task_id);
                        return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".create-task-btn").val('Create');
                        $('.create-task-btn').removeAttr('disabled');
                    }
                }
            });
        });
    })
</script>
