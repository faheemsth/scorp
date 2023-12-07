@extends('layouts.admin')
<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}

@section('page-title')
    TEST
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}" id="main-style-link">
@endpush
@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Lead') }}</li>
@endsection

@section('content')
    <div class="convert-leads border border-2 mx-2 my-1 card p-3">

        <div class="main-title">
            <div class="leads-title px-2 py-1">
                <h3>Convert Leads</h3>
            </div>
            <hr class="m-0">
            <div class="title-button d-flex gap-2 m-2">
                <a href="{{ route('leads.list') }}" class="btn px-4 py-1 border rounded-0 bg-light"><i
                        class=" fa fa-solid fa-arrow-left"></i></a>
                <a href="javascript:void(0)" class="btn px-4 py-1 border rounded-0 text-light save-convert-to-deal"  id="save-convert-to-deal" style="background: #B5282F">Save</a>
                <a href="{{ route('leads.list') }}" class="btn px-4 py-1 border rounded-0 bg-light">Discard</a>
            </div>
        </div>

        <hr>

        <form action="{{ route('leads.updated.convert.deal', $lead->id) }}" method="POST" class="px-4 mb-5" id="convert-to-deal">
            @csrf
            <div class="">
                <div class="lead-form-title">
                    <span class="fw-bold" style="font-size: 11px; line-height:14px;">CONVERTED LEAD DETAILS</span>
                </div>


                <div class="row justify-content-center">
                    <div class="col-md-10">

                        <div class="form-group row py-0">

                            <div class="col-md-2">
                                <label class="text-end" style="color: #aaa; font-size:12px; padding:4px 10px 0 0;">
                                    Name <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-md-6">
                                <input type="text" class="form form-control name" value="{{ $lead->name }}" name="name">
                                <span class="invalid-error text-danger d-none opportunity-name-error">Please enter opportunity Name</span>
                            </div>
                        </div>

                        <div class="form-group row py-0">

                            <div class="col-md-2">
                                <label class="text-end" style="color: #aaa; font-size:12px; padding:4px 10px 0 0;">
                                    Passport Number <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-md-6">
                                <input type="text" class="form form-control passport-number" value="" name="passport_number">
                                <span class="invalid-error text-danger d-none passport-number-error">Please enter passport number</span>
                            </div>
                        </div>



                        <div class="form-group row py-0">

                            <div class="col-md-2">
                                <label class="text-end" style="color: #aaa; font-size:12px; padding:4px 10px 0 0">User
                                    Responsible <span class="text-danger"> * </span></label>
                            </div>


                            <div class="col-md-6">
                                <select name="assigned_to" id="users" class="form form-control select2 assigned_to">
                                    <option value=""></option>
                                    <optgroup label="{{ __('Employee') }}">
                                        @foreach ($employees as $key => $emp)
                                            <option data-type="employee" value="{{ $key }}">{{ $emp }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <span class="invalid-error text-danger d-none assigned-to-error">Please select responsible user.</span>
                            </div>


                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="send_email">
                                    <label class="form-check-label" style="font-size: 12px; color:#999;">Send
                                        Email to Responsible User</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row py-0">
                            <div class="col-md-2">
                                <label class="text-end"
                                    style="color: #aaa; font-size:12px; padding:4px 10px 0 0">Organization
                                    <span class="text-danger"> * </span>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <select name="organization_id" id="organization" class="form form-control select2 organization">
                                    <option value=""></option>
                                    @foreach ($organizations as $key => $org)
                                        <option value="{{ $key }}">{{ $org }}</option>
                                    @endforeach
                                    <option>
                                </select>
                                <span class="invalid-error text-danger d-none organization-error">Please select organization.</span>
                            </div>
                        </div>


                        <div class="form-group row py-0">
                            <div class="col-md-2">
                                <label class="text-end"
                                    style="color: #aaa; font-size:12px; padding:4px 10px 0 0">University
                                    <span class="text-danger"> * </span>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <select name="university_id" id="university" class="form form-control select2 university">
                                    <option value=""></option>
                                    @foreach ($universities as $key => $uni)
                                        <option value="{{ $key }}">{{ $uni }}</option>
                                    @endforeach
                                    <option>
                                </select>
                                <span class="invalid-error text-danger d-none university-error">Please select university.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="form" style="margin-top: 30px">
                <div class="lead-form-title">
                    <span class="fw-bold" style="font-size: 11px; line-height:14px;">ADD FOLLOW UP TASK</span>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-10">

                        <div class="form-group row py-0">
                            <div class="col-md-2">
                                <label class="text-end" style="color: #aaa; font-size:12px; padding:4px 10px 0 0">Task
                                    Name <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-md-6">
                                <input type="text" class="form form-control task-name" placeholder="" name="task_name">
                                <span class="invalid-error text-danger d-none task-name-error">Please enter task name.</span>

                            </div>
                        </div>

                        <div class="form-group row py-0">
                            <div class="col-md-2">
                                <label class="text-end" style="color: #aaa; font-size:12px; padding:4px 10px 0 0">Task
                                    Date Due <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-md-6">
                                <input type="date" class="form form-control date-due" name="date_due">
                                <span class="invalid-error text-danger d-none date-due-error">Please enter task due date.</span>
                            </div>
                        </div>

                        <div class="form-group row py-0">

                            <div class="col-md-2">
                                <label class="text-end" style="color: #aaa; font-size:12px; padding:4px 10px 0 0">
                                    Category <span class="text-danger"> * </span></label>
                            </div>

                            <input type="hidden" value="{{$lead->id}}" class="lead-hidden-id">

                            <div class="col-md-6">
                                <select name="lead_stage_id" id="lead_stage" class="form form-control select2 category">
                                    <option value="">Nothing Selected</option>
                                    @foreach ($categories as $key => $category)
                                        <option value="{{ $key }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-error text-danger d-none category-error">Please select category.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>














            <div class="form group" style="margin-top: 30px">
                <div class="lead-form-title">
                    <span class="fw-bold" style="font-size: 11px; line-height:14px;">FOLLOW UP TASK ADDITIONAL
                        INFORMATION</span>
                </div>


                <div class="row gap-2 mt-2">
                    <label class="col-3 text-end" style="color: #aaa; font-size:12px; padding:0 10px 0 0">Add Start
                        Date</label>
                    <a href="#" class="col-2 links start-date" style="font-size:12px;">Add Start Date</a>
                    <div class="start-date-none d-none col-4 d-flex">
                        <input type="text" class="form form-control" name="date_start">
                        <a href="javascript:void(0)" class="btn p-0 cls-1"><i class="fa-solid fa-xmark"></i></a>
                    </div>
                </div>

                <div class="row gap-2 mt-2">
                    <label class="col-3 text-end" style="color: #aaa; font-size:12px; padding:0 10px 0 0">Reminder</label>
                    <a href="javascript:void(0)" class="col-3 links reminder" style="font-size:12px;">Add Reminder</a>
                    <div class="reminder-none d-none col-4">
                        <input type="text" class="form form-control" name="date_reminder">
                        <a href="javascript:void(0)" class="btn p-0 cls-2"><i class="fa-solid fa-xmark"></i></a>
                    </div>
                </div>

                <div class="row gap-2 mt-2">
                    <label class="col-3 text-end" style="color: #aaa; font-size:12px; padding:0 10px 0 0">Progress</label>
                    <a href="javascript:void(0)" class="col-3 links percentage" style="font-size:12px;">0 %</a>
                </div>

                <div class="row gap-2 mt-2">
                    <label class="col-3 text-end"
                        style="color: #aaa; font-size:12px; padding:4px 10px 0 0">Priority </label>
                    <div class="col-3">
                        <input type="hidden" class="priority-input" value="" name="priority">
                        <a href="#" class="links-icon text-danger priority-btn"data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="High Priority"
                            style="font-size:15px; padding: 0 8px; border-radius:3px;" data-priority="3">!!!</a>
                        <a href="#" class="links-icon text-warning priority-btn"data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Normal Priority"
                            style="font-size:15px; padding: 0 8px; border-radius:3px;" data-priority="2">!!</a>
                        <a href="#" class="links-icon text-primary priority-btn"data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Low Priority"
                            style="font-size:15px; padding: 0 8px; border-radius:3px;" data-priority="1">!</a>
                    </div>
                </div>


                <div class="row gap-2 mt-2">
                    <label class="col-3 text-end" style="color: #aaa; font-size:12px; padding:4px 10px 0 0">Status</label>
                    <div class="col-3">
                        <input type="hidden" class="task-status" name="task_status">
                        <a href="#" class="links-icon text-dark task-status-btn" data-status="On Going" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="In Progress"
                            style="font-size:15px; padding: 0 8px; border-radius:3px;"><i
                                class="fa-solid fa-play"></i></a>
                        <a href="#" class="links-icon text-dark task-status-btn" data-status="Comleted" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Compelete"
                            style="font-size:15px; padding: 0 8px; border-radius:3px;"><i
                                class="fa-solid fa-check"></i></a>
                    </div>
                </div>



            </div>
        </form>
    </div>
@endsection


@push('script-page')

<script>
    $(document).ready(function() {
        $(".start-date").click(function() {
            $('.start-date-none').removeClass("d-none");
            $('.start-date').addClass("d-none");
        });

        $(".cls-1").click(function() {
            $('.start-date-none').addClass("d-none");
            $('.start-date').removeClass("d-none");
        });

        $(".reminder").click(function() {
            $('.reminder-none').removeClass("d-none");
            $('.reminder').addClass("d-none");
        });

        $(".cls-2").click(function() {
            $('.reminder-none').addClass("d-none");
            $('.reminder').removeClass("d-none");
        });


        $('.priority-btn').on("click", function(){
            $(".priority-input").val($(this).attr('data-priority'));
        })


        $(".task-status-btn").on("click", function(){
            $(".task-status").val($(this).attr('data-status'));
        })

        $(document).on("click", "#save-convert-to-deal" ,function() {
            var error_found = false;
            var lead_id = $(".lead-hidden-id").val();

            if($('.name').val() == ''){
                $('.opportunity-name-error').removeClass('d-none');
                error_found = true;
            }


            if($('.passport-number').val() == ''){
                $('.passport-number-error').removeClass('d-none');
                error_found = true;
            }

            if($('.assigned-to').val() == ''){
                $('.assigned-to-error').removeClass('d-none');
                error_found = true;
            }

            if($('.organization').val() == ''){
                $('.organization-error').removeClass('d-none');
                error_found = true;
            }

            if($('.university').val() == ''){
                $('.university-error').removeClass('d-none');
                error_found = true;
            }


            if($('.task-name').val() == ''){
                $('.task-name-error').removeClass('d-none');
                error_found = true;
            }


            if($('.date-due').val() == ''){
                $('.date-due-error').removeClass('d-none');
                error_found = true;
            }

            if($('.category').val() == ''){
                $('.category-error').removeClass('d-none');
                error_found = true;
            }

            if(error_found){
                return false;
            }


            $.ajax({
                type: 'POST',
                url: '/leads/'+lead_id+'/update-convert-lead',
                headers: {
                    __csrf_token: $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#convert-to-deal").serialize(),
                success: function(data){
                    data = JSON.parse(data);
                    if(data.status == 'success'){
                        show_toastr('Success', data.message, 'msg');
                        window.location.href = '/deals/list';
                    }else{
                        show_toastr('Error', data.message, 'msg');
                        return false;
                    }
                }
            })
        });

    });
</script>

@endpush
