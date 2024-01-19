@extends('layouts.admin')

@section('page-title')
    {{__('Manage Announcement')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Announcement')}}</li>
@endsection




@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body ">
                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-4">
                        <p class="mb-0 pb-0 ps-1">Announcement</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL announcement
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                {{-- <li><a class="dropdown-item assigned_to" href="javascript:void(0)">Assigned to</a></li>
                                <li><a class="dropdown-item update-status-modal" href="javascript:void(0)">Update Status</a></li>
                                <li><a class="dropdown-item" href="#">Brand Change</a></li>--}}
                                <li><a class="dropdown-item delete-bulk-tasks" href="javascript:void(0)">Delete</a></li>
                                {{-- <li id="actions_div" style="display:none"><a class="dropdown-item assigned_to" onClick="massUpdate()">Mass Update</a></li> --}}
                            </ul>
                        </div>
                    </div>


                    <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                        <div class="input-group w-25">
                            <button class="btn btn-sm list-global-search-btn">
                                <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <button data-bs-toggle="tooltip" title="{{__('Refresh')}}" class="btn px-2 pb-2 pt-2 refresh-list btn-dark" ><i class="ti ti-refresh" style="font-size: 18px"></i></button>

                        <button class="btn filter-btn-show p-2 btn-dark d-none"  type="button" data-bs-toggle="tooltip" title="{{__('Filter')}}">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>

                        @can('create announcement')
                        <button data-size="lg" data-url="{{ route('announcement.create') }}" data-ajax-popup="true" data-title="{{__('Create New Announcement')}}" data-bs-toggle="tooltip" title="{{__('Create Announcement')}}" class="btn px-2 btn-dark">
                            <i class="ti ti-plus" style="font-size:18px"></i>
                        </button>
                        @endcan

                        <a class="btn p-2 btn-dark  text-white assigned_to" id="actions_div" style="display:none;font-weight: 500;" onClick="massUpdate()">Mass Update</a>
                    </div>
                </div>
                    <div class="table-responsive mt-3">
                    <table class="table ">
                            <thead>
                            <tr>
                                <th>{{__('Title')}}</th>
                                <th class="d-none">{{__('Brand')}}</th>
                                <th class="d-none">{{__('Region')}}</th>
                                <th class="d-none">{{__('Branch')}}</th>
                                <th>{{__('description')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('End Date')}}</th>
                                @if(Gate::check('edit announcement') || Gate::check('delete announcement'))
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($announcements as $announcement)
                                <tr>
                                    <td>{{ $announcement->title }}</td>
                                    <td class="d-none">
                                        {{ optional(App\Models\User::find(str_replace(['["', '"]'], '',  $announcement->brand_id)))->name }}
                                    </td>
                                    <td class="d-none">
                                        {{ optional(App\Models\Region::find(str_replace(['["', '"]'], '',  $announcement->region_id)))->name }}
                                    </td>
                                    <td class="d-none">
                                        {{ optional(App\Models\Branch::find(str_replace(['["', '"]'], '',  $announcement->branch_id)))->name }}
                                    </td>

                                    <td>{{ strlen($announcement->description) > 20 ? substr($announcement->description, 0, 20) . '...' : $announcement->description }}</td>
                                    <td>{{  \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                    <td>{{  \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                    @if(Gate::check('edit announcement') || Gate::check('delete announcement'))
                                        <td>

                                            @can('edit announcement')
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" data-url="{{ URL::to('announcement/'.$announcement->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Announcement')}}" class="x-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>

                                                </div>
                                            @endcan

                                            @can('delete announcement')
                                                <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['announcement.destroy', $announcement->id],'id'=>'delete-form-'.$announcement->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$announcement->id}}').submit();">
                                                            <i class="ti ti-trash text-white text-white"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan

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

        //Branch Wise Deapartment Get
        $(document).ready(function () {
            var b_id = $('#branch_id').val();
            getDepartment(b_id);
        });

        $(document).on('change', 'select[name=branch_id]', function () {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(bid) {

            $.ajax({
                url: '{{route('announcement.getdepartment')}}',
                type: 'POST',
                data: {
                    "branch_id": bid, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#department_id').empty();
                    $('#department_id').append('<option value="">{{__('Select Department')}}</option>');

                    $('#department_id').append('<option value="0"> {{__('All Department')}} </option>');
                    $.each(data, function (key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

        $(document).on('change', '#department_id', function () {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        function getEmployee(did) {

            $.ajax({
                url: '{{route('announcement.getemployee')}}',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {

                    $('#employee_id').empty();
                    $('#employee_id').append('<option value="">{{__('Select Employee')}}</option>');
                    $('#employee_id').append('<option value="0"> {{__('All Employee')}} </option>');

                    $.each(data, function (key, value) {
                        $('#employee_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
