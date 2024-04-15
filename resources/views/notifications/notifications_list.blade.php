@extends('layouts.admin')

<?php
$brands = FiltersBrands();
$setting = \App\Models\Utility::colorset();

?>





@section('page-title')
    {{ __('Notifications') }}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}" id="main-style-link">
@endpush


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Notifications') }}</li>
@endsection

@push('css-page')
    {{-- <link rel="stylesheet" href="{{ asset('assets/js/drag-resize-columns/dist/jquery.resizableColumns.css') }}"> --}}
@endpush


@section('content')
    <style>
        .form-control:focus {
            border: 1px solid rgb(209, 209, 209) !important;
        }
    </style>
    <style>
        .boximg {
            margin: auto;
        }

        .dropdown-togglefilter:hover .dropdown-menufil {
            display: block;
        }

        .choices__inner {
            border: 1px solid #ccc !important;
            min-height: auto;
            padding: 4px !important;
        }

        .fil:hover .submenu {
            display: block;
        }

        .fil .submenu {
            display: none;
            position: absolute;
            top: 3%;
            left: 154px;
            width: 100%;
            background-color: #fafafa;
            font-weight: 600;
            list-style-type: none;

        }

        .dropdown-item:hover {
            background-color: white !important;
        }

        .form-control:focus {
            border: none !important;
            outline: none !important;
        }

        .filbar .form-control:focus {
            border: 1px solid rgb(209, 209, 209) !important;
        }

        .search-bar-input {

            width: 30% !important;
        }

        @media only screen and (max-width: 768px) {
            .search-bar-input {
                width: 50% !important;
            }
        }

        @media only screen and (max-width: 425px) {
            .search-bar-input {
                width: 100% !important;
            }
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card my-card">
                <div class="card-body">
                    <div class="row align-items-center ">
                        <div class="col-12 col-md-4 pb-2">
                            <p class="mb-0 pb-0 ps-1">Notifications</p>
                            <div class="dropdown ">
                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    ALL Notifications
                                </button>
                                @if(sizeof($saved_filters) > 0)
                                <ul class="dropdown-menu " aria-labelledby="dropdownMenuButton1">

                                    @foreach($saved_filters as $filter)
                                    <li class="d-flex align-items-center justify-content-between ps-2">
                                        <div class="col-10">
                                            <a href="{{$filter->url}}" class="text-capitalize fw-bold text-dark">{{$filter->filter_name}}</a>
                                            <span class="text-dark"> ({{$filter->count}})</span>
                                        </div>
                                        <ul class="w-25" style="list-style: none;">
                                            <li class="fil fw-bolder">
                                                <i class=" fa-solid fa-ellipsis-vertical" style="color: #000000;"></i>
                                                <ul class="submenu" style="border: 1px solid #e9e9e9;
                                                box-shadow: 0px 0px 1px #e9e9e9;">
                                                    <li><a class="dropdown-item" href="#" onClick="editFilter('<?= $filter->filter_name?>', <?= $filter->id ?>)">Rename</a></li>
                                                    <li><a class="dropdown-item" onclick="deleteFilter('{{$filter->id}}')" href="#">Delete</a></li>
                                                </ul>
                                            </li>
                                        </ul>

                                    </li>
                                    @endforeach

                                </ul>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-8 d-flex justify-content-end gap-2 pe-0 me-0 align-items-end">
                            <div class="input-group  rounded search-bar-input " style="height: 36px;">
                                <button class="btn btn-sm list-global-search-btn  px-0 ">
                                    <span class="input-group-text bg-transparent border-0  px-1 pt-0" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search"
                                    class="form-control border-0 bg-transparent p-0 pb-2 list-global-search text-truncate"
                                    placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>

                            <button data-bs-toggle="tooltip" title="{{ __('Refresh') }}"
                                class="btn px-2 pb-2 pt-2 refresh-list btn-dark d-none d-flex justify-content-center align-items-center">
                                <i class="ti ti-refresh" style="font-size: 18px"></i>
                            </button>
                            <button class="btn filter-btn-show p-2 btn-dark d-flex justify-content-center align-items-center" type="button" data-bs-toggle="tooltip" title="{{__('Filter')}}" style="width:36px; height: 36px;">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>

                            @if (auth()->user()->type == 'super admin' || auth()->user()->can('delete task'))
                                <a href="javascript:void(0)" id="actions_div" data-bs-toggle="tooltip"
                                    title="{{ __('Delete Notifications') }}"
                                    class="btn delete-bulk text-white btn-dark justify-content-center align-items-center px-0 d-none"
                                    style="width:36px; height: 36px;">
                                    <i class="ti ti-trash"></i>
                                </a>
                            @endif

                        </div>
                    </div>

                    <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                        <form action="/notifications" method="GET" class="">


                            <div class="row my-3 ">
                                <div class="col-md-3 mt-2"> <label for="">Assigned To</label>
                                    <div class="" id="assign_to_div">
                                        <select name="notification_user" id="choices-multiple333"
                                            class="form form-control select2" style="width: 95%;">
                                            @foreach ($users as  $user)
                                              <option value="{{ $user->user_id }}"
                                              <?= isset($_GET['notification_user']) && $user->user_id == $_GET['notification_user'] ? 'selected' : '' ?>
                                              class="">{{ $user->Notifier->name }}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4 mt-4 pt-2 d-flex align-items-end">
                                    <input type="submit" data-bs-toggle="tooltip" title="{{__('Submit')}}" class="btn form-btn me-2 btn-dark px-2 py-2" >
                                    <a href="notifications" data-bs-toggle="tooltip" title="{{__('Reset')}}" class="btn form-btn mr-2 px-2 py-2 btn-dark" style="color:white;">Reset</a>
                                    <a type="button" id="save-filter-btn" onClick="saveFilter('notifications',<?= sizeof($Notifications) ?>)" class="btn form-btn mr-2 px-2 py-2 btn-dark" style=" color:white;display:none;">Save Filter</a>

                                </div>
                            </div>


                        </form>
                    </div>

                    <div class="table-responsive mt-1">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px !important;">
                                        <input type="checkbox" class="main-check">
                                    </th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Username') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Data') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Update Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="Notification_tbody">
                                @forelse($Notifications as $key => $task)
                                    @php

                                        $due_date = strtotime($task->created_at);
                                        $current_date = strtotime(date('Y-m-d'));
                                        $color_code = '';

                                        if ($due_date > $current_date) {
                                            $color_code = 'green';
                                        } elseif ($due_date === $current_date) {
                                            $color_code = '#E89D25';
                                        } elseif ($due_date < $current_date) {
                                            $color_code = 'red';
                                        } elseif ($status === '1') {
                                            $color_code = 'green';
                                        }

                                    @endphp
                                    <tr class="<?php echo (!empty($_GET['id']) && $_GET['id'] == $task->id) ? 'bg-info' : ''; ?>">
                                        <td>
                                            <input type="checkbox" name="tasks[]" value="{{ $task->id }}"
                                                class="sub-check">
                                        </td>
                                        <td
                                            style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                            <span class="badge text-white" style="background-color:{{ $color_code }}">
                                                <?php
                                                $date = new DateTime($task->created_at);
                                                $formattedDate = $date->format('Y-m-d');
                                                echo $formattedDate;
                                                ?>
                                            </span>
                                        </td>

                                        <td
                                            style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                            <span style="cursor:pointer" class="task-name hyper-link"
                                               onclick="openSidebar('/get-notifications-detail?notification_id=<?= $task->id ?>')"
                                                data-task-id="{{ $task->id }}">{{ $task->Notifier->name }}</span>
                                        </td>
                                        <td
                                            style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">

                                            <span class="hyper-link">
                                                {{ $task->type }}
                                            </span>

                                        </td>

                                        <td
                                            style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">


                                            <span class="hyper-link">
                                                {{ $task->data }}
                                            </span>




                                        </td>



                                        <td
                                            style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                            @if ($task->is_read == 0)
                                                <span class="badge  text-white"
                                                    style="background-color:#cd9835">{{ __('Unseen') }}</span>
                                            @else
                                                <span class="badge text-white"
                                                    style="background: green; ">{{ __('Seen') }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($task->status == 0)
                                                <button class="btn btn-sm btn-dark position-relative"
                                                    @can('edit status task') onclick="ChangeNotificationStatus({{ $task->id }})" @endcan
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Change Notification Status">
                                                    <i class="fa-solid fa-check d-flex justify-content-center align-items-center"
                                                        style="font-size: 18px;"></i>
                                                </button>
                                            @else
                                                <span class="badge text-white"
                                                    style="background: green; ">{{ __('Completed') }}</span>
                                            @endif
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td class="7">No Record Found!!!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination_div">
                        @if ($total_records > 0)
                            @include('layouts.pagination', [
                                'total_pages' => $total_records,
                                'num_results_on_page' => 50,
                            ])
                        @endif
                    </div>


                    <div id="mySidenav" style="z-index: 1065; padding-left:10px; box-shadow: -5px 0px 30px 0px #aaa;"
                        class="sidenav <?= isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>"
                        style="padding-left: 5px">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="update-status-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('update-bulk-task-status') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <select name="status" id="bulk_status" class="form form-control">
                            <option value="">Select Status</option>
                            <option value="0">On Going</option>
                            <option value="1">Completed</option>
                        </select>

                        <input type="hidden" class="task_ids" value="" name="task_ids">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Update Status">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="mass-update-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg my-0" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mass Update</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('update-bulk-task') }}" method="POST">
                    @csrf
                    <div class="modal-body" style="min-height: 40vh;">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="bulk_field" id="bulk_field" class="form form-control">
                                    <option value="">Select Field</option>
                                    <option value="tm">Task Name</option>
                                    <option value="ofc">Office</option>
                                    <!-- <option value="ast">Assign Type</option> -->
                                    <option value="asto">Assigned To</option>
                                    <option value="ts">Task Status</option>
                                    <option value="dd">Due Date</option>
                                    <option value="sd">Start Date</option>
                                    <option value="rd">Reminder Date</option>
                                    <!-- <option value="rt">Related Type</option>
                                    <option value="rto">Related To</option> -->
                                    <option value="des">Description</option>
                                    <option value="per">Permissions</option>
                                </select>
                            </div>
                            <input name='tasks_ids' id="tasks_ids" hidden>
                            <div class="col-md-6" id="field_to_update">

                            </div>
                        </div>

                    </div>
                    <br>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-dark px-2" value="Update">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection







@push('script-page')
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> --}}

    <script>
        let selectedArr = [];
        $(document).ready(function() {
            $('.filter-btn-show').click(function() {
                $("#filter-show").toggle();
            });
        });

        $(document).ready(function() {
            let curr_url = window.location.href;

            if (curr_url.includes('?')) {
                $('#save-filter-btn').css('display', 'inline-block');
            }
        });

        $(document).on("click", ".list-global-search-btn", function() {
            var search = $(".list-global-search").val();
            var ajaxCall = 'true';
            $(".Notification_tbody").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "/notifications",
                data: {
                    search: search,
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        console.log(data.html);
                        $(".Notification_tbody").html(data.html);
                    }
                }
            })
        })

        $(document).ready(function() {
            // Attach an event listener to the input field
            $('.list-global-search').keypress(function(e) {
                // Check if the pressed key is Enter (key code 13)
                if (e.which === 13) {
                    var search = $(".list-global-search").val();
                    var ajaxCall = 'true';
                    $(".Notification_tbody").html('Loading...');

                    $.ajax({
                        type: 'GET',
                        url: "/notifications",
                        data: {
                            search: search,
                            ajaxCall: ajaxCall
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            if (data.status == 'success') {
                                console.log(data.html);
                                $(".Notification_tbody").html(data.html);
                            }
                        }
                    })
                }
            });
        });

        $(".refresh-list").on("click", function() {
            var ajaxCall = 'true';
            $(".Notification_tbody").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "/notifications",
                data: {
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        $(".Notification_tbody").html(data.html);
                    }
                }
            });
        })


        /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
        function openNav(task_id) {
            var ww = $(window).width()

            $.ajax({
                type: 'GET',
                url: "{{ route('get-task-detail') }}",
                data: {
                    task_id: task_id
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

            $("#modal-discussion-add").attr('data-org-id', task_id);
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

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
        function closeNav() {
            $("#mySidenav").css("width", '0');
            $("#main").css("margin-right", '0');
            $("#modal-discussion-add").removeAttr('data-org-id');
            $('.modal-discussion-add-span').removeClass('ti-minus');
            $('.modal-discussion-add-span').addClass('ti-plus');
            $(".add-discussion-div").addClass('d-none');
            $(".block-screen").css('display', 'none');
            $("#body").css('overflow', 'visible');
        }

        $(document).on("click", ".edit-input", function() {
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = $(".task-id").val();
            //var org_did = $(".org_did").val();

            $.ajax({
                type: 'GET',
                url: "/tasks/get-field/" + id,
                data: {
                    name,
                    id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        $('.' + name + '-td').html(data.html);
                    }
                }
            })

        })


        $(document).on("click", ".edit-btn-data", function() {
            var name = $(this).attr('data-name');
            var id = $(".task-id").val();
            var value = $('.' + name).val();


            $.ajax({
                type: 'GET',
                url: "/tasks/" + id + "/update-data",
                data: {
                    value: value,
                    name: name,
                    id: id
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'msg');
                        $('.' + name + '-td').html(data.html);
                    }
                }
            })

        });

        function DeleteComment(id, taskID) {
            $('#dellhover').show();
            $.ajax({
                type: "GET",
                url: "{{ url('delete/task/comment') }}" + '/' + id + '/' + taskID,
                success: function(data) {
                    data = JSON.parse(data);

                    console.log(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.list-group-flush').html(data.html);
                        // openNav(data.lead.id);
                        // return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".create-discussion-btn").val('Create');
                        $('.create-discussion-btn').removeAttr('disabled');
                    }
                }
            });
        }

        $(document).on("submit", "#taskDiscussion", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $('.task-id').val();

            $(".create-discussion-btn").val('Processing...');
            $('.create-discussion-btn').attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: "/tasks/" + id + "/discussions",
                data: formData,
                success: function(data) {
                    data = JSON.parse(data);

                    console.log(data);

                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('#commonModal').modal('hide');
                        $('.list-group-flush').html(data.html);
                        // openNav(data.lead.id);
                        // return false;
                    } else {
                        show_toastr('Error', data.message, 'error');
                        $(".create-discussion-btn").val('Create');
                        $('.create-discussion-btn').removeAttr('disabled');
                    }
                }
            });
        })


        $(document).on("change", ".assigned_to", function() {

            var val = $(this).val();
            var userType = 1;

            if (userType[val] == 'company' || userType[val] == 'team') {
                $(".assigned_to_type").removeClass('d-none');
            } else {
                $(".assigned_to_type").addClass('d-none');
            }
        });

        $('#bulk_field').on('change', function() {

            if (this.value != '') {
                $('#field_to_update').html('');

                if (this.value == 'tm') {

                    let field =
                        '<input type="text" class="form-control" id="task-name" value="" placeholder="Task Name" name="task_name" required>';
                    $('#field_to_update').html(field);

                } else if (this.value == 'ofc') {

                    var branches = 1;
                    console.log(branches)
                    let options = '';
                    for (let i = 0; i < branches.length; i++) {
                        options += '<option value="' + branches[i].id + '">' + branches[i].name + '</option>';
                    }

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="branch_id" required>
                                <option value="">Select Office</option>
                                ` + options + `
                            </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'ast') {

                } else if (this.value == 'asto') {
                    var assign_users = 1;
                    // console.log(branches)
                    let options = '';
                    for (let i = 0; i < assign_users.length; i++) {
                        options += '<option value="' + assign_users[i].id + '">' + assign_users[i].name +
                            '</option>';
                    }

                    let field = `<select class="form form-control select2" id="choices-multiple1" name="assigned_to" required>
                                <option value="">Select person</option>
                                ` + options + `
                            </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'ts') {

                    let field = `<select class="form form-control select2" id="choices-multiple5" name="status" required>
                                <option value="">Select Status</option>
                                <option value="0">On Going</option>
                                <option value="1">Completed</option>
                            </select>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'dd') {

                    let field = `<input type="date" class="form form-control"
                                    name="due_date" required>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'sd') {

                    let field = `<input type="date" class="form form-control"
                                    name="start_date" required>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'rd') {

                    let field = `<div class="col-sm-6 d-flex"><input type="date" class="form form-control"
                                    name="remainder_date" required>
                                <input type="time" class="form form-control"
                                    name="remainder_time" required></div>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'des') {

                    let field =
                        `<textarea name="description" id="" cols="30" rows="3" class="form form-control" required></textarea>`;
                    $('#field_to_update').html(field);

                } else if (this.value == 'per') {

                    let field = `<select class="form form-control select2" id="choices-multiple8" name="visibility" required>
                                <option value="">Select Visibility</option>
                                <option value="public" >public</option>
                                <option value="private">private</option>
                            </select>`;
                    $('#field_to_update').html(field);

                }
            }

        });

        $(document).on('change', '.main-check', function() {
            $(".sub-check").prop('checked', $(this).prop('checked'));

            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            // console.log(selectedIds.length)

            if (selectedIds.length > 0) {
                selectedArr = selectedIds;
                $("#actions_div").removeClass('d-none');
            } else {
                selectedArr = selectedIds;

                $("#actions_div").addClass('d-none');
            }
        });


        $(document).on('change', '.sub-check', function() {
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            if (selectedIds.length > 0) {
                selectedArr = selectedIds;
                $("#actions_div").removeClass('d-none');
            } else {
                selectedArr = selectedIds;

                $("#actions_div").addClass('d-none');
            }
        });

        function massUpdate() {
            if (selectedArr.length > 0) {
                $('#mass-update-modal').modal('show')
            } else {
                alert('Please choose Tasks!')
            }
        }

        $(document).on("click", ".update-status-modal", function() {
            // Get an array of selected checkbox IDs
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            // Set the value of the 'task_ids' input field
            $('.task_ids').val(selectedIds.join(','));

            // Optionally, you can also get the selected status
            var selectedStatus = $('#bulk_status').val();
            $("#update-status-modal").modal('show');
        });

        $(document).on("click", '.delete-bulk', function() {
            var task_ids = $(".sub-check:checked");
            var selectedIds = $('.sub-check:checked').map(function() {
                return this.value;
            }).get();

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/delete-bulk-notifications?ids=' + selectedIds.join(',');
                }
            });
        })


        ////////////////////Filters Javascript
        $("#filter-show #filter_brand_id").on("change", function() {
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


        $(document).on("change", "#filter-show #filter_region_id, #filter-show #region_id", function() {
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
                        getTasks();
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

        $(document).on("change", "#filter-show #filter_branch_id, #filter-show #branch_id", function() {
            getTasks();

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

        function getTasks() {
            var brand_id = $("#filter_brand_id").val();
            var region_id = $("#region_id").val();
            var branch_id = $("#branch_id").val();

            var type = 'tasks';

            $.ajax({
                type: 'GET',
                url: '{{ route('filterData') }}',
                data: {
                    brand_id,
                    region_id,
                    branch_id,
                    type
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status === 'success') {
                        $('#filter-names').html('');
                        $("#filter-names").html(data.html);
                        select2();
                    } else {
                        console.error('Server returned an error:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        }


        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        function ChangeNotificationStatus(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to update the Notification status.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('NotificationStatusChange') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'The task Notification has been changed successfully.',
                            }).then(function() {
                                // Reload the page after the user closes the SweetAlert dialog
                                window.location.href = window.location.href;
                            });
                        },

                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                } else {
                    console.log("Task status update canceled.");
                }
            });
        }
    </script>
@endpush
