<style>
    .form-group {
        margin-bottom: 0px;
        padding-top: 0px;
    }

    /* .col-form-label {
        text-align: center;
    } */

    .space {
        padding: 3px 3px;
    }
</style>
{{ Form::model(\Auth::user(), ['route' => ['organization.tasks.update', $task->id], 'method' => 'POST', 'id' => 'update-task', 'style' => 'z-index: 9999999 !important;']) }}

<div class="modal-body  pt-0" style="min-height: 80vh">
    <div class="lead-content my-2" style="max-height: 79vh; overflow-y: scroll;">
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
                    <div class="accordion-body">

                        <div class="form-group row ">
                            <label for="organization-name" class="col-sm-3 col-form-label">
                                Task Name
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="task-name" value="{{ $task->name }}"
                                    placeholder="Task Name" name='task_name'>
                                <input type="hidden" class="task_id" value="{{ $task->id }}">
                            </div>
                        </div>

                        @if (
                            \Auth::user()->type == 'super admin' ||
                                \Auth::user()->type == 'project director' ||
                                \Auth::user()->type == 'project manager')
                            <div class="form-group row ">
                                <label for="branches" class="col-sm-3 col-form-label">Brands</label>
                                <div class="col-sm-6">
                                    <select class="form form-control select2 brand_id" id="brands"
                                        name="brand_id" {{ !\Auth::user()->can('edit brand task') ? 'disabled' : '' }}>
                                        <option value="">Select Brands</option>
                                        @foreach ($companies as $key => $brand)
                                            <option value="{{ $key }}" {{ $task->brand_id == $key ? 'selected' : ''  }}>{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                            <div class="form-group row ">
                                <label for="branches" class="col-sm-3 col-form-label">Brands</label>
                                <div class="form-group col-md-6" id="brand_div">
                                    <select class='form-control select2 brand_id' name="brands" id="brand_id">
                                        <option value="{{ Auth::id() }}" selected>{{ Auth::user()->name }}</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager')
                        <div class="form-group row ">
                            <label for="branches" class="col-sm-3 col-form-label">Region</label>
                            <div class="form-group col-md-6" id="region_div">
                                {!! Form::select('region_id', $Region, $task->region_id, [
                                    'class' => 'form-control select2',
                                    'id' => 'region_id'
                                ]) !!}
                            </div>
                        </div>
                        @endif

                        <div class="form-group row ">
                            <label for="branches" class="col-sm-3 col-form-label">
                                Branch

                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6" id="branch_div">
                                <select class="form form-control select2" {{ !\Auth::user()->can('edit branch task') ? 'disabled' : '' }} id="branch_id" name="branch_id">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $key => $branch)
                                        <option value="{{ $key }}"
                                            {{ App\Models\Branch::where('brands',$task->brand_id)->first()->id == $key ? 'selected' : '' }}>{{ $branch }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row ">
                            <label for="organization" class="col-sm-3 col-form-label">Assigned to <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-6" id="assign_to_div">
                                <select class="form form-control select2 assigned_to" id="choices-multiple4" name="assigned_to" {{ !\Auth::user()->can('edit assign to task') ? 'disabled' : '' }}>
                                    <option value="">Assign to</option>
                                    @foreach ($employees as $key => $user)
                                        <option value="{{ $key }}"
                                            {{ $key == $task->assigned_to ? 'selected' : '' }}>{{ $user }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="type" class="col-sm-3 col-form-label">Task Status</label>
                            <div class="col-sm-6">
                                <select class="form form-control select2" id="choices-multiple5" name="status" {{ !\Auth::user()->can('edit assign to task') ? 'disabled' : '' }}>
                                    <option value="">Select Status</option>
                                    <option value="0"
                                        {{ empty($task->status) || $task->status == '0' ? 'selected' : '' }}>On Going
                                    </option>
                                    <option value="1" {{ $task->status == '1' ? 'selected' : '' }}>Completed
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="organization" class="col-sm-3 col-form-label">Due Date</label>
                            <div class="col-sm-6">
                                <input type="date" class="form form-control" value="{{ $task->due_date }}"
                                    name="due_date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Organizaiton Contact Info --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="ture"
                        aria-controls="panelsStayOpen-collapseTwo">
                        ADDITIONAL INFORMATION
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseTwo show" class="accordion-collapse collapse show"
                    aria-labelledby="panelsStayOpen-headingTwo">
                    <div class="accordion-body">
                        <div class="form-group row ">
                            <label for="organization" class="col-sm-3 col-form-label">Start Date</label>
                            <div class="col-sm-6">
                                <input type="date" class="form form-control" value="{{ $task->start_date }}"
                                    name="start_date">
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">Remainder Date</label>
                            <div class="col-sm-6 d-flex">
                                <input type="date" class="form form-control" value="{{ $task->remainder_date }}"
                                    name="remainder_date">
                                <input type="time" class="form form-control" value="{{ $task->time }}"
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
                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse shoe"
                    aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">

                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">
                                Related Type

                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">

                                <select class="form form-control select2 related_type" disabled readonly
                                    id="choices-multiple6" name="related_type">
                                    <option value="">Select type</option>
                                    <option value="organization"
                                        {{ $task->related_type == 'organization' ? 'selected' : '' }}>Organization
                                    </option>
                                    <option value="lead" {{ $task->related_type == 'lead' ? 'selected' : '' }}>Lead
                                    </option>
                                    <option value="deal" {{ $task->related_type == 'deal' ? 'selected' : '' }}>Deal
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">Related To <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-6">

                                <select class="form form-control related_to" disabled id="choices-multiple7"
                                    name="related_to">
                                    <option value="">Related To</option>
                                    @foreach ($related_to as $key => $related)
                                        <option value="{{ $key }}"
                                            {{ $key == $task->related_to ? 'selected' : '' }}>{{ $related }}
                                        </option>
                                    @endforeach
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
                        <textarea name="description" id="" cols="30" rows="3" class="form form-control">{{ $task->description }}</textarea>
                    </div>
                </div>
            </div>


            {{-- Organizaiton Description --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                    <button class="accordion-button " type="button" data-bs-toggle="collapse"
                        data-bs-target="#panelsStayOpen-description" aria-expanded="ture"
                        aria-controls="panelsStayOpen-description">
                        PERMISSIONS
                    </button>
                </h2>
                <div id="panelsStayOpen-description" class="accordion-collapse collapse show"
                    aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">
                        <div class="form-group row">
                            <label for="website" class="col-sm-3 col-form-label">Task Visibility</label>
                            <div class="col-sm-6">
                                <select class="form form-control select2" id="choices-multiple8" name="visibility">
                                    <option value="">Select Visibility</option>
                                    <option value="public" {{ 'public' == $task->visibility ? 'selected' : '' }}>
                                        public</option>
                                    <option value="private" {{ 'private' == $task->visibility ? 'selected' : '' }}>
                                        private</option>
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
    <input type="submit" value="Update" class="btn  btn-dark px-2 update-task-btn">
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {
        select2();
        $(".assign_type").on("change", function() {


            var type = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('organization.assign_to', 1) }}',
                data: {
                    type
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        $("#assigned_to_div").html(data.html);
                        // $(".assigned_to").addClass('select2');
                        select2();
                    }
                }
            })

        })


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
                        $(".related_to").html(data.html);
                        $(".related_to").addClass('select2');
                        select2();
                    }
                }
            })

        })

        $(document).on("submit", "#update-task", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $('.task_id').val();

            $(".update-task-btn").val('Processing...');
            $('.update-task-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/organization/" + id + "/task-update",
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
                        $(".update-task-btn").val('Update');
                        $('.update-task-btn').removeAttr('disabled');
                    }
                }
            });
        });
    })
</script>
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

