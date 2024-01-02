<style>
    .editable:hover {
        border: 1px solid rgb(136, 136, 136);
    }
    #dellhover{
        opacity: 0;
    }
    #lihover:hover #dellhover{
        opacity: 1;
    }

    .task-details table tr td {
        font-size: 14px;
    }

    .task-details table tr td {
        font-size: 14px;
    }

    .card-body {
        padding: 25px 15px !important;
    }

    .edit-input-field-div {
        background-color: #ffffff;
        border: 0px solid rgb(224, 224, 224);
        max-width: max-content;
        max-height: 30px;
    }


    .edit-input-field-div .input-group {
        min-width: 70px;
        min-height: 30px;
        border: none !important;
    }

    .edit-input-field-div .input-group input {
        border: none !important;
    }

    .edit-input-field {
        border: 0px;
        box-shadow: none;
        padding: 4px !important;

    }

    .edit-input-field-div .edit-btn-div {
        display: none;
    }

    .edit-input-field-div:hover {
        /* border: 1px solid rgb(224, 224, 224); */
    }

    .edit-input-field-div:hover .edit-btn-div {
        display: block;
    }

    .edit-input {
        padding: 7px;
    }

    .block-items {
        overflow: auto;
        padding-right: 7px;
        padding-bottom: 5px;
        padding-top: 1px;
        padding-left: 1px;
        width: 100%;
        display: flex;
    }


    .block-item {
        display: inline-block;
        vertical-align: top;
        padding: 10px;
        text-align: left;
        white-space: nowrap;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08);
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08);
        border-radius: 2px;
        margin-right: 10px;
        line-height: initial;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .top-label {
        text-transform: uppercase;
        white-space: nowrap;
        width: 100%;
        color: #757575;
        font-size: 11px;
        line-height: 12px;
        font-weight: normal;
        padding-bottom: 4px;
        display: block;
    }


    .block-item-count-total {
        font-weight: bold;
        font-size: 14px;
        text-align: left;
    }
</style>
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
                        <p class="pb-0 mb-0 fw-normal">{{ __('Tasks') }}</p>
                        <div class="d-flex align-items-baseline ">
                            <h5 class="fw-bold">{{ $task->name }}</h5>
                        </div>
                    </div>

                </div>

                {{-- @if (\Auth::user()->type == 'super admin') --}}
                <div class="d-flex justify-content-end gap-1 me-3">
                    @if ($task->status == 0)
                    @can('edit status task')
                    <a href="javascript:void(0)" onclick="ChangeTaskStatus({{ $task->id }})"
                        title="{{ __('Edit Status') }}" class="btn px-2 btn-dark text-white">
                        <i class="fa-solid fa-check" style="color: #ffffff;"></i>
                    </a>
                    @endcan
                    @endif

                    @can('edit task')
                    <a href="#" data-size="lg" data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                        data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                        class="btn px-2 btn-dark text-white">
                        <i class="ti ti-pencil"></i>
                    </a>
                    @endcan

                    @can('delete task')
                    <a href="/organization/{{ $task->id }}/taskDeleted" class="btn px-2 btn-danger text-white">
                        <i class="ti ti-trash "></i>
                    </a>
                    @endcan
                </div>
                {{-- @endif --}}
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small style="margin-bottom: 4px;">{{ __('Date Due') }}</small>
                    <!-- <span class="px-3 text-white " style="border-radius: 6px;
                    background: #22A9E3; padding-top: 2px; padding-bottom: 4px"> -->
                    @php
                            $due_date = strtotime($task->due_date);
                            $current_date = strtotime(date('Y-m-d'));
                            $status = strtolower($task->status);
                            $color_code = '';

                            if ($due_date > $current_date && $status === '0') {
                                // Ongoing feture time
                                $color_code = '#B3CDE1;';
                            } elseif ($due_date === $current_date && $status === '0') {
                                // Today date time
                                $color_code = '#E89D25';
                            } elseif ($due_date < $current_date && $status === '0') {
                                // Past date time
                                $color_code = 'red';
                            } elseif ($status === '1') {
                                // Completed task
                                $color_code = 'green';
                            }
                            $message=Carbon\Carbon::parse($due_date)->diffForHumans();
                    @endphp
                    <span class="px-3 text-white" style="border-radius: 6px;background-color:{{ $color_code }};
                            padding-top: 4px; padding-bottom: 8px">
                        <span
                            class="">
                            {{ $message }}
                        </span>

                    </span>
                </div>
                <div class="">
                    <small style="margin-bottom: 4px;">{{ __('Priority') }}</small>
                    <span>{{ __('Medium') }}</span>
                </div>
                <div class="">
                    <small style="margin-bottom: 4px;">{{ __('Status') }}</small>
                    <span>{{ $task->status == 1 ? 'Completed' : 'On Going' }}</span>
                </div>
                <div class="">
                    <small style="margin-bottom: 4px;">{{ __('Progress') }}</small>
                    <span>{{ strtolower($task->status) == '0' ? '0' : '100' }}</span>
                </div>
                <div class="">
                    <small style="margin-bottom: 4px;">{{ __('Assigned To') }}</small>
                    <span class="text-info">{{ \App\Models\User::findOrFail($task->assigned_to)->name }}</span>
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
                             {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link" id="text" id="pills-related-tab" data-bs-toggle="pill" data-bs-target="#pills-related" type="button" role="tab" aria-controls="pills-related" aria-selected="false">{{ __('Related') }}</button>
                            </li> --}}
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link fw-bold" id="text" id="pills-timeline-tab" data-bs-toggle="pill" data-bs-target="#pills-timeline" type="button" role="tab" aria-controls="pills-timeline" aria-selected="false">{{ __('Timeline') }}</button>
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
                                                {{ __('Task Details') }}
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
                                                                    {{ __('Task Name') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{--
                                                                    <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 name">
                                                                            {{ $task->name }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="name"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $task->name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px;  font-size: 14px;">
                                                                    {{ __('Office') }}
                                                                </td>
                                                                <td class="branch_id-td"
                                                                    style="padding-left: 20px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 branch_id">
                                                                            {{ isset($task->branch_id) && isset($branches[$task->branch_id]) ? $branches[$task->branch_id] : '' }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="branch_id"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ isset($task->branch_id) && isset($branches[$task->branch_id]) ? $branches[$task->branch_id] : '' }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px;  font-size: 14px;">
                                                                    {{ __('Agency') }}
                                                                </td>
                                                                <td class="organization_id-td"
                                                                    style="padding-left: 20px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div
                                                                            class="input-group border-0 organization_id">
                                                                            {{ isset($users[$task->organization_id]) ? $users[$task->organization_id] : '' }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="organization_id"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ isset($users[$task->organization_id]) ? $users[$task->organization_id] : '' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px;  font-size: 14px;">
                                                                    {{ __('Assigned To') }}
                                                                </td>
                                                                <td class="td"
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{ $users[$task->assigned_to] }}
                                                                </td>
                                                            </tr>

                                                            {{-- <tr>
                                                                <td class="" style="  font-size: 14px;">
                                                                    {{ __('Category') }}
                                                                </td>
                                                                <td class="type-td" style="padding-left: 20px; font-size: 14px;">

                                                                    <span class="badge bg-success text-white"> {{ isset($stages[$task->deal_stage_id]) ? $stages[$task->deal_stage_id] : '' }}</span>
                                                                </td>
                                                            </tr> --}}

                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Date Due') }}
                                                                </td>
                                                                <td class="due_date-td"
                                                                    style="padding-left: 20px; font-size: 14px;">

                                                                    {{-- <div
                                                                        class="d-flex align-items-baseline edit-input-field-div">
                                                                        <div class="input-group border-0 due_date">
                                                                            {{ $task->due_date }}
                                                                        </div>
                                                                        <div class="edit-btn-div">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input"
                                                                                name="due_date"><i
                                                                                    class="ti ti-pencil"></i></button>
                                                                        </div>
                                                                    </div> --}}
                                                                    {{ $task->due_date }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" class="task-id" value="{{ $task->id }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeytwo">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytwo">
                                                {{ __('ADDITIONAL INFORMATION') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeytwo"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeytwo">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Start Date') }}
                                                                </td>
                                                                <td class="phone-td"
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{ $task->start_date }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Remainder Date') }}
                                                                </td>
                                                                <td class="email-td"
                                                                    style="padding-left: 20px; font-size: 14px;">

                                                                    {{ $task->remainder }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Updated at') }}
                                                                </td>
                                                                <td class="website-td"
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{ $task->updated_at }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class="website-td"
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{ $task->created_at }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeythree">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeythree">
                                                {{ __('RELATED TO') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeythree"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeythree">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>

                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Related Type') }}
                                                                </td>
                                                                <td class="td"
                                                                    style="padding-left: 20px; font-size: 14px;">
                                                                    {{ $task->related_type == 'deal' ? 'Admission' : $task->related_type }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Related To') }}
                                                                </td>
                                                                <td class="td"
                                                                    style="padding-left: 20px; font-size: 14px;">

                                                                    @php

                                                                        if ($task->related_type == 'organization') {
                                                                            echo \App\Models\User::where('id', $task->related_to)->first()->name;
                                                                        } elseif ($task->related_type == 'lead') {
                                                                            echo \App\Models\Lead::where('id', $task->related_to)->first()->name;
                                                                        } elseif ($task->related_type == 'deal') {
                                                                            echo \App\Models\Deal::findOrFail($task->related_to)->name;
                                                                        }
                                                                    @endphp
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeydesc">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeydesc">
                                                {{ __('TASK DESCRIPTION') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeydesc"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeydesc">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style=" width: 100px; font-size: 14px;">
                                                                    {{ __('Description') }}
                                                                </td>
                                                                <td class="description-td"
                                                                    style="padding-left:15px; width: 550px; text-align: justify; font-size: 14px;">
                                                                    {{ $task->description }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingdisc">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsedisc">
                                                {{ __('TASK COMMENTS') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsedisc" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingdisc">
                                            <div class="accordion-body">
                                                <div class="">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <textarea name="" id="" cols="95" class="form-control textareaClass" readonly
                                                                style="cursor: pointer"></textarea>
                                                            <span id="textareaID" style="display: none;">
                                                                <div class="card-header px-0 pt-0"
                                                                    style="padding-bottom: 18px;">
                                                                    {{ Form::model($task, ['route' => ['tasks.discussion.store', $task->id], 'method' => 'POST', 'id' => 'taskDiscussion']) }}
                                                                    {{ Form::textarea('comment', null, ['class' => 'form-control', 'style' => 'height: 120px', 'id' => 'taskDiscussionInput']) }}
                                                                    <input type="hidden" id="id"
                                                                        name="id">
                                                                    <div class="d-flex justify-content-end mt-2">

                                                                        <button type="submit" class="btn btn-secondary btn-sm mx-1" id="cancelDiscussion">Cancel</button>
                                                                        <button type="submit" class="btn btn-secondary btn-sm d-none" id="SaveDiscussion">Save</button>
                                                                    </div>
                                                                    {{ Form::close() }}
                                                                </div>
                                                            </span>
                                                            <div class="card-body px-0">
                                                                <ul class="list-group list-group-flush mt-2">
                                                                    @if($discussions != null)
                                                                        @foreach ($discussions as $discussion)
                                                                            <li class="list-group-item px-3"
                                                                                id="lihover">
                                                                                <div
                                                                                    class="d-block d-sm-flex align-items-start">
                                                                                    <img src="{{ asset('assets/images/user/avatar.png') }}"
                                                                                        class="img-fluid wid-40 me-3 mb-2 mb-sm-0"
                                                                                        alt="image">
                                                                                    <div class="w-100">
                                                                                        <div
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <div class="mb-3 mb-sm-0">
                                                                                                <h5 class="mb-0">
                                                                                                    {{ $discussion['comment'] }}
                                                                                                </h5>
                                                                                                <span
                                                                                                    class="text-muted text-sm">{{ $discussion['name'] }}</span>
                                                                                            </div>
                                                                                            <div
                                                                                                class=" form-switch form-switch-right ">
                                                                                                {{ $discussion['created_at'] }}
                                                                                            </div>
                                                                                            <div class="d-flex gap-3"
                                                                                                id="dellhover">
                                                                                                <i class="ti ti-pencil textareaClassedit"
                                                                                                    data-comment="{{ $discussion['comment'] }}"
                                                                                                    data-id="{{ $discussion['id'] }}"
                                                                                                    id="editable"
                                                                                                    style="font-size: 20px;"></i>
                                                                                                <script></script>
                                                                                                <i class="ti ti-trash"
                                                                                                    id="editable"
                                                                                                    style="font-size: 20px;"
                                                                                                    onclick="DeleteComment('{{ $discussion['id'] }}','{{ $task->id }}')"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @else
                                                                        <li class="list-group-item px-3" style="text-align:center">No comments found!</li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeytag">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeytag">
                                                {{ __('TAG LIST') }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapsekeytag"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeytag">
                                            <div class="accordion-body">
                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px;  font-size: 14px;">
                                                                    {{ __('PERMISSIONS') }}
                                                                </td>
                                                                <td class="" style="padding-left: 20px;">
                                                                    {{ $task->visibility }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- Details Pill End --}}
                            {{-- <div class="tab-pane fade show active" id="pills-related" role="tabpanel"
                                aria-labelledby="pills-related-tab">

                                <div class="block-items">

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Contacts</div>
                                        <div class="block-item-count">0</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Opportunities</div>
                                        <div class="block-item-count">0</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Projects</div>
                                        <div class="block-item-count">0</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>

                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Organizations</div>
                                        <div class="block-item-count">0</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>


                                    <div class="block-item large-block" id="con-stats" title="1 Linked Contacts"
                                        data-bs-target="#contacts-grid-container">
                                        <div class="top-label">Leads</div>
                                        <div class="block-item-count">0</div>
                                        <div class="fp-product-count-holder">
                                            <div class="fp-product-count-total"></div>
                                            <div class="fp-product-count-percent" style="width: 0px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="tab-pane fade" id="pills-timeline" role="tabpanel"
                                aria-labelledby="pills-timeline-tab">

                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingactive">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseactive">
                                                {{ __('Timeline') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseactive"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingactive">
                                            <div class="accordion-body">
                                                <!-- Accordion Content -->


                                                <div class="mt-1">
                                                    <div class="timeline-wrapper">
                                                        <ul class="StepProgress">
                                                            @foreach ($log_activities as $activity)
                                                                @php
                                                                    $remark = json_decode($activity->note);
                                                                @endphp

                                                                <li class="StepProgress-item is-done">
                                                                    <div class="bold time">{{ $activity->created_at }}</div>
                                                                    <div class="bold" style="text-align: left; margin-left: 80px;">
                                                                            <p class="bold" style="margin-bottom: 0rem; color: #000000;">{{ $remark->title }}</p>
                                                                            <p class="m-0">{{ $remark->message }}</p>
                                                                            <span class="text-muted text-sm" style="cursor: pointer;" @can('show employee') onclick="openSidebar('/user/employee/{{ isset($activity->created_by) ? $activity->created_by : '' }}/show')"  @endcan ><i class="step__icon fa fa-user me-2" aria-hidden="true"></i>{{ isset($users[$activity->created_by]) ? $users[$activity->created_by] : '' }}</span>
                                                                    </div>
                                                                </li>

                                                            @endforeach

                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- End of Accordion Content -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Open Accordion Item -->

                                    <!-- Add More Accordion Items Here -->

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
                text: 'You are about to update the task status.',
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
                            text: 'The task status has been changed successfully.',
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
