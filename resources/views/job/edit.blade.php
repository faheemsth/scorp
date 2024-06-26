@extends('layouts.admin')
@section('page-title')
    {{__('Edit Job')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('job.index')}}">{{__('Job')}}</a></li>
    <li class="breadcrumb-item">{{__('Job Edit')}}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
    <link href="{{asset('css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>

@endpush
@push('script-page')

    <script src="{{asset('js/bootstrap-tagsinput.min.js')}}"></script>

    <script>
        var e = $('[data-toggle="tags"]');
        e.length && e.each(function () {
            $(this).tagsinput({tagClass: "badge badge-primary"})
        });
    </script>
    <script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
@endpush
@section('content')

    {{Form::model($job,array('route' => array('job.update', $job->id), 'method' => 'PUT')) }}
    <div class="row mt-3">
        <div class="col-md-6 ">
            <div class="card card-fluid">
                <div class="card-body job-create ">
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('title', __('Job Title'),['class'=>'form-label']) !!}
                            {!! Form::text('title', null, ['class' => 'form-control','required' => 'required']) !!}
                        </div>

                        @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                        <div class="form-group col-md-6">
                            <label for="">Brand</label>
                            <select name="brand" class="form form-control select2" id="filter_brand_id">
                                @if (!empty($filters['brands']))
                                @foreach ($filters['brands'] as $key => $Brand)
                                <option value="{{ $key }}" {{ $job->brand_id == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                @endforeach
                                @else
                                <option value="" disabled>No brands available</option>
                                @endif
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="brand" value="{{\Auth::user()->brand_id}}">
                        @endif



                        @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
                        <div class="form-group col-md-6" id="region_filter_div">
                            <label for="">Region</label>
                            <select name="region_id" class="form form-control select2" id="filter_region_id">
                                @if (!empty($filters['regions']))
                                @foreach ($filters['regions'] as $key => $region)
                                <option value="{{ $key }}" {{ $job->region_id == $key ? 'selected' : '' }}>{{ $region }}</option>
                                @endforeach
                                @else
                                <option value="" disabled>No regions available</option>
                                @endif
                            </select>
                        </div>
                        @else
                         <input type="hidden" name="region_id" value="{{ \Auth::user()->region_id }}">
                        @endif


                        @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3') || \Auth::user()->can('level 4'))
                        <div class="form-group col-md-6" id="branch_filter_div">
                            <label for="">Branch</label>
                            <select name="branch_id" class="form form-control select2" id="filter_branch_id">
                                @if (!empty($filters['branches']))
                                @foreach ($filters['branches'] as $key => $branch)
                                <option value="{{ $key }}" {{ $job->branch == $key ? 'selected' : '' }}>{{ $branch }}</option>
                                @endforeach
                                @else
                                <option value="" disabled>No regions available</option>
                                @endif
                            </select>
                        </div>
                        @else
                           <input type="hidden" name="branch_id" value="{{ \Auth::user()->branch_id }}">
                        @endif


                        <div class="form-group col-md-6">
                            {!! Form::label('category', __('Job Category'),['class'=>'form-label']) !!}
                            {{ Form::select('category', $categories,null, array('class' => 'form-control select','required'=>'required')) }}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('position', __('Positions'),['class'=>'form-label']) !!}
                            {!! Form::text('position', null, ['class' => 'form-control','required' => 'required']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('status', __('Status'),['class'=>'form-label']) !!}
                            {{ Form::select('status', $status,null, array('class' => 'form-control select','required'=>'required')) }}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('start_date', __('Start Date'),['class'=>'form-label']) !!}
                            {!! Form::date('start_date', null, ['class' => 'form-control ']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('end_date', __('End Date'),['class'=>'form-label']) !!}
                            {!! Form::date('end_date', null, ['class' => 'form-control ']) !!}
                        </div>

                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" value="{{$job->skill}}" data-toggle="tags" name="skill" placeholder="Skill"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 ">
            <div class="card card-fluid">
                <div class="card-body job-create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h6>{{__('Need to ask ?')}}</h6>
                                <div class="my-4">
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="applicant[]" value="gender" id="check-gender" {{(in_array('gender',$job->applicant)?'checked':'')}}>
                                        <label class="form-check-label" for="check-gender">{{__('Gender')}} </label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="applicant[]" value="dob" id="check-dob" {{(in_array('dob',$job->applicant)?'checked':'')}}>
                                        <label class="form-check-label" for="check-dob">{{__('Date Of Birth')}}</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="applicant[]" value="country" id="check-country" {{(in_array('country',$job->applicant)?'checked':'')}}>
                                        <label class="form-check-label" for="check-country">{{__('Country')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h6>{{__('Need to show option ?')}}</h6>
                                <div class="my-4">
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="visibility[]" value="profile" id="check-profile" {{(in_array('profile',$job->visibility)?'checked':'')}}>
                                        <label class="form-check-label" for="check-profile">{{__('Profile Image')}} </label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="visibility[]" value="resume" id="check-resume" {{(in_array('resume',$job->visibility)?'checked':'')}}>
                                        <label class="form-check-label" for="check-resume">{{__('Resume')}}</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="visibility[]" value="letter" id="check-letter" {{(in_array('letter',$job->visibility)?'checked':'')}}>
                                        <label class="form-check-label" for="check-letter">{{__('Cover Letter')}}</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="visibility[]" value="terms" id="check-terms" {{(in_array('terms',$job->visibility)?'checked':'')}}>
                                        <label class="form-check-label" for="check-terms">{{__('Terms And Conditions')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <h6>{{__('Custom Question')}}</h6>
                            <div class="my-4">
                                @foreach($customQuestion as $question)
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" class="form-check-input" name="custom_question[]" value="{{$question->id}}" id="custom_question_{{$question->id}}" {{(in_array($question->id,$job->custom_question)?'checked':'')}}>
                                        <label class="form-check-label" for="custom_question_{{$question->id}}">{{$question->question}} </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-fluid">
                <div class="card-body ">
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('description', __('Job Description'),['class'=>'form-label']) !!}
                            <textarea class="form-control summernote-simple" name="description" id="exampleFormControlTextarea1" rows="15">{{$job->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-fluid">
                <div class="card-body ">
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('requirement', __('Job Requirement'),['class'=>'form-label']) !!}
                            <textarea class="form-control summernote-simple" name="requirement" id="exampleFormControlTextarea2" rows="8">{{$job->requirement}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-end">
            <div class="form-group">
                <input type="submit" value="{{__('Update')}}" class="btn btn-dark BulkSendButton">
            </div>
        </div>
        {{Form::close()}}
    </div>
@endsection


@push('script-page')
<script>
         $('form').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData($(this)[0]); // Create FormData object from the form
            $(".BulkSendButton").val('Processing...');
            $('.BulkSendButton').attr('disabled', 'disabled');
            $.ajax({
                url: $(this).attr('action'), // Get the form action URL
                type: $(this).attr('method'), // Get the form method (POST in this case)
                data: formData, // Set the form data
                contentType: false, // Don't set contentType, let jQuery handle it
                processData: false, // Don't process the data, let jQuery handle it
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (response.status == 'success') {
                        show_toastr('Success', response.message, 'success');
                        window.location.href = response.url;
                        return false;
                    } else {
                        show_toastr('Error', response.message, 'error');
                        $(".BulkSendButton").val('Create');
                        $('.BulkSendButton').removeAttr('disabled');
                    }
                },
            });
        });
    $("#filter_brand_id").on("change", function() {
        var id = $(this).val();
        var type = 'brand';
        var filter = true;

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id, // Add a key for the id parameter
                filter,
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_filter_div').html('');
                    $("#region_filter_div").html(data.regions);
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


    $(document).on("change", "#filter_region_id, #region_id", function() {
        var id = $(this).val();
        var filter = true;
        var type = 'region';
        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id, // Add a key for the id parameter
                filter,
                type: type
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#branch_filter_div').html('');
                    $("#branch_filter_div").html(data.branches);
                    getLeads();
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

    $(document).on("change", "#filter_branch_id, #branch_id", function() {

        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branch-users') }}',
            data: {
                id: id
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#assign_to_div').html('');
                    $("#assign_to_div").html(data.html);
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
</script>
@endpush
