@extends('layouts.admin')

@section('page-title')
    {{__('Manage Announcement')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Announcement')}}</li>
@endsection

<style>
    .search-bar-input{
        width:30% !important;
    }
    @media only screen and (max-width: 768px){
        .search-bar-input{
        width:50% !important;
    }
    }
    @media only screen and (max-width: 425px){
        .search-bar-input{
        width:100% !important;
    }
    }
</style>



@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body ">
                <div class="row align-items-center ">
                    <div class="col-12 col-md-4 pb-2 ">
                        <p class="mb-0 pb-0 ps-1">Announcement</p>
                        <div class="dropdown " >
                            <button class="dropdown-toggle All-leads " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" >
                                ALL ANNOUNCEMENT
                            </button>
                            <!-- <ul class="dropdown-menu d-none" aria-labelledby="dropdownMenuButton1">
                                {{-- <li><a class="dropdown-item assigned_to" href="javascript:void(0)">Assigned to</a></li>
                                <li><a class="dropdown-item update-status-modal" href="javascript:void(0)">Update Status</a></li>
                                <li><a class="dropdown-item" href="#">Brand Change</a></li>--}}
                                <li><a class="dropdown-item delete-bulk-tasks" href="javascript:void(0)">Delete</a></li>
                                {{-- <li id="actions_div" style="display:none"><a class="dropdown-item assigned_to" onClick="massUpdate()">Mass Update</a></li> --}}
                            </ul> -->
                        </div>
                    </div>


                    <div class="col-12 col-md-8 d-flex justify-content-end gap-2 pe-0 me-0 align-items-center pb-1">
                        <div class="input-group  rounded search-bar-input  " style="height:36px;">
                            <button class="btn btn-sm list-global-search-btn px-0 ">
                                <span class="input-group-text bg-transparent border-0 px-1 pt-0" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent px-0  pb-2 list-global-search text-truncate" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <!-- <button data-bs-toggle="tooltip" title="{{__('Refresh')}}" class="btn px-2 pb-2 pt-2 refresh-list btn-dark" ><i class="ti ti-refresh" style="font-size: 18px"></i></button> -->

                        <!-- <button class="btn filter-btn-show p-2 btn-dark "  type="button" data-bs-toggle="tooltip" title="{{__('Filter')}}">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button> -->

                        @can('create announcement')
                        <button data-size="lg" data-url="{{ route('announcement.create') }}" data-ajax-popup="true" data-title="{{__('Create New Announcement')}}" data-bs-toggle="tooltip" title="{{__('Create Announcement')}}" class="btn px-2 btn-dark  " style="width:36px; height: 36px; ">
                            <i class="ti ti-plus" style="font-size:18px"></i>
                        </button>
                        @endcan
<!-- 
                        <a class="btn p-2 btn-dark  text-white assigned_to" id="actions_div" style="display:none;font-weight: 500;" onClick="massUpdate()">Mass Update</a> -->
                    </div>
                </div>
                    <div class="table overflow-auto">
                    <table class="table ">
                            <thead>
                            <tr>
                                <th>{{__('Title')}}</th>
                                <!-- <th class="d-none">{{__('Brand')}}</th>
                                <th class="d-none">{{__('Region')}}</th>
                                <th class="d-none">{{__('Branch')}}</th> -->
                                <th>{{__('description')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('End Date')}}</th>

                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($announcements as $announcement)
                                <tr>
                                    <td>

                                        <span style="cursor:pointer" class="task-name hyper-link" onclick="openSidebar('/announcement_details?id='+{{ $announcement->id }})" data-task-id="{{ $announcement->id }}">{{ $announcement->title }}</span>

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
        function openNav(announcement_id) {
        var ww = $(window).width()

        $.ajax({
            type: 'GET',
            url: "{{ route('announcement-detail') }}",
            data: {
                announcement_id: announcement_id
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    $("#mySidenav").html(data.html);
                    $(".block-screen").css('display', 'none');
                }
            }
        });


        if (ww < 500) {
            $("#mySidenav").css('width', ww + 'px');
            $("#main").css('margin-right', ww + 'px');
        } else {
            $("#mySidenav").css('width', '850px');;
            $("#main").css('margin-right', "850px");
        }

        $("#modal-discussion-add").attr('data-org-id', announcement_id);
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'block');
        $("#body").css('overflow', 'hidden');

        // var csrf_token = $('meta[name="csrf-token"]').attr('content');

        // $.ajax({
        //     url: "/leads/getDiscussions",
        //     data: {
        //         lead_id,
        //         _token: csrf_token,
        //     },
        //     type: "POST",
        //     cache: false,
        //     success: function(data) {
        //         data = JSON.parse(data);
        //         //console.log(data);

        //         if (data.status) {
        //             $(".discussion-list-group").html(data.content);
        //             $(".lead_id").val(lead_id);


        //         }
        //     }
        // });

    }
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
