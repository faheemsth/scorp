@extends('layouts.admin')
@section('page-title')
{{__('Manage Attendance List')}}
@endsection
@push('script-page')
<script>
    $('input[name="type"]:radio').on('change', function(e) {
        var type = $(this).val();

        if (type == 'monthly') {
            $('.month').addClass('d-block');
            $('.month').removeClass('d-none');
            $('.date').addClass('d-none');
            $('.date').removeClass('d-block');
        } else {
            $('.date').addClass('d-block');
            $('.date').removeClass('d-none');
            $('.month').addClass('d-none');
            $('.month').removeClass('d-block');
        }
    });

    $('input[name="type"]:radio:checked').trigger('change');
</script>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Attendance')}}</li>
@endsection

{{--@section('action-btn')--}}
{{-- <div class="float-end">--}}
{{-- <a class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" data-bs-toggle="tooltip" title="{{__('Filter')}}">--}}
{{-- <i class="ti ti-filter"></i>--}}
{{-- </a>--}}
{{-- </div>--}}
{{--@endsection--}}
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class=" mt-2 " id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(array('route' => array('attendanceemployee.index'),'method'=>'get','id'=>'attendanceemployee_filter')) }}
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row">

                                <div class="col-12">
                                    <label class="form-label">{{__('Type')}}</label> <br>

                                    <div class="form-check form-check-inline form-group">
                                        <input type="radio" id="monthly" value="monthly" name="type" class="form-check-input" {{isset($_GET['type']) && $_GET['type']=='monthly' ?'checked':'checked'}}>
                                        <label class="form-check-label" for="monthly">{{__('Monthly')}}</label>
                                    </div>
                                    <div class="form-check form-check-inline form-group">
                                        <input type="radio" id="daily" value="daily" name="type" class="form-check-input" {{isset($_GET['type']) && $_GET['type']=='daily' ?'checked':''}}>
                                        <label class="form-check-label" for="daily">{{__('Daily')}}</label>
                                    </div>

                                </div>

                                <div class="col-12 row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12  month">
                                        <div class="btn-box">
                                            {{Form::label('month',__('Month'),['class'=>'form-label'])}}
                                            {{Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'month-btn form-control month-btn'))}}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('Date'),['class'=>'form-label'])}}
                                            {{ Form::date('date',isset($_GET['date'])?$_GET['date']:'', array('class' => 'form-control month-btn')) }}
                                        </div>
                                    </div>
                                    @if(\Auth::user()->can('level 1'))
                                    @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                                    <div class="col-md-3">
                                        <label for="">Brand</label>
                                        <select name="brand" class="form form-control select2" id="filter_brand_id">
                                            @if (!empty($filters['brands']))
                                            @foreach ($filters['brands'] as $key => $Brand)
                                            <option value="{{ $key }}" {{ !empty($_GET['brand']) && $_GET['brand'] == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                            @endforeach
                                            @else
                                            <option value="" disabled>No brands available</option>
                                            @endif
                                        </select>
                                    </div>
                                    @endif



                                    @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
                                    <div class="col-md-3" id="region_filter_div">
                                        <label for="">Region</label>
                                        <select name="region_id" class="form form-control select2" id="filter_region_id">
                                            @if (!empty($filters['regions']))
                                            @foreach ($filters['regions'] as $key => $region)
                                            <option value="{{ $key }}" {{ !empty($_GET['region_id']) && $_GET['region_id'] == $key ? 'selected' : '' }}>{{ $region }}</option>
                                            @endforeach
                                            @else
                                            <option value="" disabled>No regions available</option>
                                            @endif
                                        </select>
                                    </div>
                                    @endif


                                    @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3') || \Auth::user()->can('level 4'))
                                    <div class="col-md-3" id="branch_filter_div">
                                        <label for="">Branch</label>
                                        <select name="branch_id" class="form form-control select2" id="filter_branch_id">
                                            @if (!empty($filters['branches']))
                                            @foreach ($filters['branches'] as $key => $branch)
                                            <option value="{{ $key }}" {{ !empty($_GET['branch_id']) && $_GET['branch_id'] == $key ? 'selected' : '' }}>{{ $branch }}</option>
                                            @endforeach
                                            @else
                                            <option value="" disabled>No regions available</option>
                                            @endif
                                        </select>
                                    </div>
                                    @endif
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="row">
                                <div class="col-auto" style="margin-top: 6rem;">

                                    <a href="#" class="btn btn-sm btn-dark" onclick="document.getElementById('attendanceemployee_filter').submit(); return false;" data-size="lg" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>

                                    <a href="{{route('attendanceemployee.index')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip" title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="float-end">
                    <a href="#" data-url="{{ route('attendanceemployee.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto btn btn-dark" data-ajax-popup="true" data-title="{{__('Create New Award')}}">
                        <i class="fa fa-plus"></i> {{__('Create')}}
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                @if(\Auth::user()->type!='Employee')
                                <th>{{__('Employee')}}</th>
                                @endif
                                <th>{{__('Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Clock In')}}</th>
                                <th>{{__('Clock Out')}}</th>
                                <th>{{__('Late')}}</th>
                                <th>{{__('Early Leaving')}}</th>
                                <th>{{__('Overtime')}}</th>
                                @if(Gate::check('edit attendance') || Gate::check('delete attendance'))
                                <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($attendanceEmployee as $attendance)
                            <tr>
                                @if(\Auth::user()->type!='Employee')
                                <td>{{!empty($attendance->employee)?$attendance->employee->name:'' }}</td>
                                @endif
                                <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                <td>{{ $attendance->status }}</td>
                                <td>{{ ($attendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_in):'00:00' }} </td>
                                <td>{{ ($attendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_out):'00:00' }}</td>
                                <td>{{ $attendance->late }}</td>
                                <td>{{ $attendance->early_leaving }}</td>
                                <td>{{ $attendance->overtime }}</td>
                                @if(Gate::check('edit attendance') || Gate::check('delete attendance'))
                                <td>
                                    @can('edit attendance')
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="#" data-url="{{ URL::to('attendanceemployee/'.$attendance->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Attendance')}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                            <i class="ti ti-pencil text-white"></i></a>
                                    </div>
                                    @endcan
                                    @can('delete attendance')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['attendanceemployee.destroy', $attendance->id],'id'=>'delete-form-'.$attendance->id]) !!}

                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$attendance->id}}').submit();">
                                            <i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
                                    </div>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        $('.daterangepicker').daterangepicker({
            format: 'yyyy-mm-dd',
            locale: {
                format: 'YYYY-MM-DD'
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