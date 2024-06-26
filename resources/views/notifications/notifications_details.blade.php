
<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0 task-details">
    <div class="row">
        <div class="col-sm-12">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between align-items-center p-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" class="">
                    </div>

                    <input type="hidden" name="task_id" value="{{ $task->id }}">


                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Notifications') }}</p>
                        <div class="d-flex align-items-baseline ">
                            <h5 class="fw-bold">{{ $task->name }}</h5>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                    @if ($task->is_read == 0)

                    <a href="javascript:void(0)" onclick="ChangeNotificationStatus({{ $task->id }})"
                        title="{{ __('Edit Status') }}" class="btn px-2 btn-dark text-white" style="width:36px; height: 36px; margin-top:12px;">
                        <i class="fa-solid fa-check" style="color: #ffffff;"></i>
                    </a>

                    @endif


                    <a href="/delete-bulk-notifications?ids={{ $task->id }}" class="btn px-2 btn-danger text-white"  title="{{ __('Delete') }}" style="width:36px; height: 36px; margin-top:12px;">
                        <i class="ti ti-trash"></i>
                    </a>

                </div>

            </div>



            <div class="lead-content my-2">

                <div class="card ">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link fw-bold active" id="text" id="pills-details-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                aria-labelledby="pills-details-tab">

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeyone">
                                                {{ __('Notifications Details') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyone" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px;  font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{ $task->id }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px;  font-size: 14px;">
                                                                    {{ __('Username') }}
                                                                </td>
                                                                <td class="td"
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{ $task->Notifier->name }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Date Due') }}
                                                                </td>
                                                                <td class="due_date-td"
                                                                    style="padding-left: 20px; font-size: 14px;">

                                                                    <?php
                                                                    $date = new DateTime($task->created_at);
                                                                    $formattedDate = $date->format('Y-m-d');
                                                                    echo $formattedDate;
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" class="task-id" value="{{ $task->id }}">

                                </div>
                            </div>


                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('.textareaClass').click(function() {
                $('textarea[name="comment"]').val('');
                $('#id').val('');
                $('#textareaID, .textareaClass').toggle("slide");
            });


            $('.textareaClassedit').click(function() {
                var dataId = $(this).data('id');
                var dataComment = $(this).data('comment');
                $('textarea[name="comment"]').val(dataComment);
                $('#id').val(dataId);
                $('#textareaID, #dellhover, .textareaClass').show();
                $('.textareaClass').toggle("slide");

            });


            $('#taskDiscussion').submit(function(event) {
                event.preventDefault(); // Prevents the default form submission
                $('#textareaID, .textareaClass').toggle("slide");
            });

            $('#cancelDiscussion').click(function(event) {
                event.preventDefault(); // Prevents the default form submission
                $('textarea[name="comment"]').val('');
                $('#id').val('');
                $('#textareaID, .textareaClass').toggle("slide");
            });

        });

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        function ChangeTaskStatus(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to update the Notifications status.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('task.status.change') }}",
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
                            text: 'The Notifications status has been changed successfully.',
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
    <script>
$(document).ready(function() {
    $('#taskDiscussionInput').keyup(function(event) {
        var commentText = $('textarea[name="comment"]').val();
        if (commentText.length > 0) {
            $('#SaveDiscussion').removeClass("d-none");
        } else {
            $('#SaveDiscussion').addClass("d-none");
        }
    });
});

    </script>
