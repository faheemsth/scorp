@extends('layouts.admin')

@section('page-title')
{{__('Manage Leave')}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Manage Leave')}}</li>
@endsection

@section('content')


<div class="row">
    <div class="col-xl-12">
        <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
            <div class="card-body table-border-style" style="padding: 25px 3px;">


                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-4">
                        <p class="mb-0 pb-0 ps-1">Set Leave</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL Leaves
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                            </ul>
                        </div>
                    </div>


                    <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                        <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                            <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        @can('create leave')
                        <a href="#" data-size="lg" data-url="{{ route('leave.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Leave')}}" class="btn filter-btn-show p-2 btn-dark" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-plus"></i>
                        </a>
                        @endcan

                    </div>


                    <div class="card-body table-responsive" style="padding: 25px 3px; width:auto;">
                        <table class="table " data-resizable-columns-id="lead-table" id="tfont">
                            <thead>
                                <tr>
                                    <th data-resizable-columns-id="employee">{{ __('EMPLOYEE') }}</th>
                                    <th data-resizable-columns-id="leavetype">{{ __('LEAVE TYPE') }}</th>
                                    <th data-resizable-columns-id="appliedon">{{ __('APPLIED ON') }}</th>
                                    <th data-resizable-columns-id="startdate">{{ __('START DATE') }}</th>
                                    <th data-resizable-columns-id="enddate">{{ __('END DATE') }}</th>
                                    <th data-resizable-columns-id="totaldays">{{ __('TOTAL DAYS') }}</th>
                                    <th data-resizable-columns-id="status">{{ __('STATUS') }}</th>
                                    <th data-resizable-columns-id="action">{{ __('ACTION') }}</th>
                                </tr>
                            </thead>


                            <tbody>
                                @forelse ($leaves as $leave)
                                <tr>
                                    @if(\Auth::user()->type!='employee')
                                    <td>{{ !empty(\Auth::user()->getEmployee($leave->employee_id))?\Auth::user()->getEmployee($leave->employee_id)->name:'' }}</td>
                                    @endif
                                    <td>{{ !empty(\Auth::user()->getLeaveType($leave->leave_type_id))?\Auth::user()->getLeaveType($leave->leave_type_id)->title:'' }}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->applied_on )}}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->start_date ) }}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->end_date )  }}</td>
                                    @php
                                    $startDate = new \DateTime($leave->start_date);
                                    $endDate = new \DateTime($leave->end_date);
                                    $total_leave_days = !empty($startDate->diff($endDate))?$startDate->diff($endDate)->days:0;
                                    @endphp
                                    <td>{{ $total_leave_days }}</td>
                                    <td>

                                        @if($leave->status=="Pending")
                                        <div class="status_badge badge bg-warning p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @elseif($leave->status=="Approved")
                                        <div class="status_badge badge bg-success p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @else($leave->status=="Reject")
                                        <div class="status_badge badge bg-danger p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(\Auth::user()->type == 'employee')
                                        @if($leave->status == "Pending")
                                        @can('edit leave')
                                        <div class="action-btn bg-primary ms-2">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Leave')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil text-white"></i></a>
                                        </div>
                                        @endcan
                                        @endif
                                        @else
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/action') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Leave Action')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Leave Action')}}" data-original-title="{{__('Leave Action')}}">
                                                <i class="ti ti-caret-right text-white"></i> </a>
                                        </div>
                                        @can('edit leave')
                                        <div class="action-btn bg-primary ms-2">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Leave')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i></a>
                                        </div>
                                        @endcan
                                        @endif
                                        @can('delete leave')
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['leave.destroy', $leave->id],'id'=>'delete-form-'.$leave->id]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$leave->id}}').submit();">
                                                <i class="ti ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty 
                                <tr>
                                    <td colspan="8">No Record Found!!!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>


                        <div class="pagination_div">
                            @if ($total_records > 0)
                            @include('layouts.pagination', [
                            'total_pages' => $total_records,
                            ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-page')
<script>
    $(document).on('change', '#employee_id', function() {
        var employee_id = $(this).val();

        $.ajax({
            url: '{{route('leave.jsoncount')}}',
            type: 'POST',
            data: {
                "employee_id": employee_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {

                $('#leave_type_id').empty();
                $('#leave_type_id').append('<option value="">{{__('Select Leave Type ')}}</option>');

                $.each(data, function(key, value) {

                    if (value.total_leave >= value.days) {
                        $('#leave_type_id').append('<option value="' + value.id + '" disabled>' + value.title + '&nbsp(' + value.total_leave + '/' + value.days + ')</option>');
                    } else {
                        $('#leave_type_id').append('<option value="' + value.id + '">' + value.title + '&nbsp(' + value.total_leave + '/' + value.days + ')</option>');
                    }
                });

            }
        });
    });
</script>
@endpush