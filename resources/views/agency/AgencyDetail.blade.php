<style>
    .editable:hover {
        border: 1px solid rgb(136, 136, 136);
    }

    .lead-info small {
        font-weight: 700 !important;
    }

    .accordion-button:focus {
        box-shadow: none !important;
        outline: 0;
        border-radius: 0px !important;
    }

    /* table tr td {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
    } */

    .btn-effect-none:focus {
        box-shadow: none !important;
    }


    .edit-input-field-div {
        background-color: #ffffff;
        border: 0px solid rgb(224, 224, 224);
        max-width: max-content;
        max-height: 35px;
        align-items: center !important;
    }


    .edit-input-field-div .input-group {
        min-width: 70px;
        min-height: 30px;
        align-items: center !important;
    }

    .edit-input-field-div .input-group input {
        border: 0px !important;
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
        padding: 7px !important;
    }

    .btn-sm {
        width: 30px;
        height: 30px;
    }
</style>
<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0">
    <div class="row">
        <div class="col-sm-12">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between align-items-center p-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" class="">
                    </div>

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Agency') }}</p>
                        <div class="d-flex align-items-baseline">
                            <h5 class="fw-bold">{{ $org_query->username }}</h5>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">

                    <a href="#!" data-size="lg" data-url="{{ route('agency.edit', $org_query->id) }}"
                        data-ajax-popup="true" class="btn px-2 py-2 btn-dark text-white"
                        data-bs-original-title="{{ __('Edit Agency') }}" data-bs-toggle="tooltip"
                        title="{{ __('Edit Agency') }}">
                        <i class="ti ti-pencil"></i>
                    </a>


                    @can('delete user')
                        {!! Form::open([
                            'method' => 'DELETE',
                            'class' => 'mb-0',
                            'route' => ['agency.destroy', $org_query['id']],
                            'id' => 'delete-form-' . $org_query['id'],
                        ]) !!}
                        <a href="#!" class="btn px-2 py-2 btn-danger text-white bs-pass-para" data-bs-toggle="tooltip"
                            title="{{ __('Delete') }}">
                            <i class="ti ti-archive"></i>
                        </a>
                        {!! Form::close() !!}
                    @endcan
                </div>
            </div>





            <div class="lead-content my-2">

                <div class="card me-3">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link text-dark fw-bold" id="pills-related-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-related" type="button" role="tab"
                                    aria-controls="pills-related" aria-selected="false">{{ __('Related') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link text-dark fw-bold" id="pills-activity-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-activity" type="button" role="tab"
                                    aria-controls="pills-activity" aria-selected="false">{{ __('Timeline') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                aria-labelledby="pills-details-tab">

                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                                {{ __('AGENCY INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headinginfo">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Agency Name') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->username }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Brand Name') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $companies[$org_query->brand_id] ?? '' }}
                                                                </td>
                                                            </tr>

                                                            



                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Agency email') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->useremail }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Agency Phone') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->phone }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Contact Person Name') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->contactname }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Billing Country') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    @php
                                                                        $country_parts = explode(
                                                                            '-',
                                                                            $org_query->billing_country,
                                                                        );
                                                                        $country_code = $country_parts[0];

                                                                    @endphp
                                                                    {{ $country_code }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Billing City') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    @php
                                                                        $country_parts = explode(
                                                                            '-',
                                                                            isset($org_query->billing_country)
                                                                                ? $org_query->billing_country
                                                                                : '',
                                                                        );
                                                                        $cities = App\Models\City::where(
                                                                            'country_code',
                                                                            $country_parts[1] ?? '0',
                                                                        )
                                                                            ->where('name', $org_query->city)
                                                                            ->first();

                                                                    @endphp
                                                                    {{ $cities->name ?? '' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Complete Address') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->c_address }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->created_at }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Update at') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $org_query->updated_at }}
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

                            {{-- Related Pill Start --}}

                            <div class="tab-pane fade" id="pills-related" role="tabpanel"
                                aria-labelledby="pills-related-tab">
                                @can('manage notes')
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <!-- Open Accordion Item -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingnote">
                                                <button class="accordion-button p-2" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#panelsStayOpen-collapsenote">
                                                    {{ __('Notes') }}
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapsenote" class="accordion-collapse collapse show"
                                                aria-labelledby="panelsStayOpen-headingnote">
                                                <div class="accordion-body">


                                                    <div class="">

                                                        <div>

                                                            <input type="hidden" value="{{ $org_query->id }}"
                                                                class="lead-id">
                                                            <div class="card position-relative" id="leadsNoteForm">
                                                                {{ Form::model($org_query, ['route' => ['agency.notes.store', $org_query->id], 'method' => 'POST', 'id' => 'create-notes', 'style' => 'z-index: 9999999 !important;']) }}
                                                                <textarea class="form-control" style="height: 120px;" name="description" id="description"
                                                                    placeholder="Click here add your Notes Comments..."></textarea>
                                                                <input type="hidden" id="note_id" value=""
                                                                    name="note_id">
                                                                <div class="row justify-content-end indivbtn">
                                                                    <div class="col-auto ">
                                                                        <button class="btn btn-dark text-white"
                                                                            id="SaveDiscussion">Save</button>
                                                                    </div>
                                                                </div>
                                                                {{ Form::close() }}
                                                            </div>
                                                            <div class="card-body px-0 py-0">
                                                                @php
                                                                    $notesQuery = \App\Models\AgencyNote::where(
                                                                        'agency_id',
                                                                        $org_query->id,
                                                                    );

                                                                    $userType = \Auth::user()->type;
                                                                    if (
                                                                        in_array($userType, [
                                                                            'super admin',
                                                                            'Admin Team',
                                                                        ]) ||
                                                                        \Auth::user()->can('level 1')
                                                                    ) {
                                                                        // No additional filtering needed
                                                                    } elseif ($userType === 'company') {
                                                                        $notesQuery->whereIn(
                                                                            'created_by',
                                                                            getAllEmployees()->keys()->toArray(),
                                                                        );
                                                                    } elseif (
                                                                        in_array($userType, [
                                                                            'Project Director',
                                                                            'Project Manager',
                                                                        ]) ||
                                                                        \Auth::user()->can('level 2')
                                                                    ) {
                                                                        $notesQuery->whereIn(
                                                                            'created_by',
                                                                            getAllEmployees()->keys()->toArray(),
                                                                        );
                                                                    } elseif (
                                                                        ($userType === 'Region Manager' ||
                                                                            \Auth::user()->can('level 3')) &&
                                                                        !empty(\Auth::user()->region_id)
                                                                    ) {
                                                                        $notesQuery->whereIn(
                                                                            'created_by',
                                                                            getAllEmployees()->keys()->toArray(),
                                                                        );
                                                                    } elseif (
                                                                        $userType === 'Branch Manager' ||
                                                                        in_array($userType, [
                                                                            'Admissions Officer',
                                                                            'Admissions Manager',
                                                                            'Marketing Officer',
                                                                        ]) ||
                                                                        (\Auth::user()->can('level 4') &&
                                                                            !empty(\Auth::user()->branch_id))
                                                                    ) {
                                                                        $notesQuery->whereIn(
                                                                            'created_by',
                                                                            getAllEmployees()->keys()->toArray(),
                                                                        );
                                                                    } else {
                                                                        $notesQuery->where(
                                                                            'user_id',
                                                                            \Auth::user()->id,
                                                                        );
                                                                    }

                                                                    $notes = $notesQuery
                                                                        ->orderBy('created_at', 'DESC')
                                                                        ->get();
                                                                @endphp

                                                                <span class="list-group list-group-flush mt-2 note-tbody">

                                                                    @foreach ($notes as $note)
                                                                        <div
                                                                            style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                            <div
                                                                                class="row my-2 justify-content-between ps-4">
                                                                                <div class="col-12 my-2">
                                                                                    <p class="text-dark"
                                                                                        style="font-size: 18px;">
                                                                                        {!! $note->description !!}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-8">
                                                                                    <div class="row align-items-center">
                                                                                        {{-- <div class="col-2 text-center">

                                                                                        </div> --}}
                                                                                        <div class="col-8">
                                                                                            <p class="mb-0 text-secondary">
                                                                                                {{ \App\Models\User::where('id', $note->created_by)->first()->name }}
                                                                                            </p>
                                                                                            <p class="mb-0">
                                                                                                {{ optional(App\models\User::find($note->created_by))->type }}
                                                                                            </p>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 text-end px-1">
                                                                                    @php
                                                                                        $dateTime = new DateTime(
                                                                                            $note->created_at,
                                                                                        );
                                                                                    @endphp
                                                                                    <p>{{ $dateTime->format('Y-m-d H:i:s') }}
                                                                                    </p>
                                                                                </div>

                                                                            </div>
                                                                            <div class="d-flex gap-1 justify-content-end pb-2 px-3"
                                                                                id="dellhover">
                                                                                <div class="btn btn-outline-dark text-dark textareaClassedit"
                                                                                    data-note-id="{{ $note->id }}"
                                                                                    data-note="{{ $note->description }}"
                                                                                    id="editable" style="font-size: ;">
                                                                                    Edit
                                                                                </div>

                                                                                <div class="delete-notes btn btn-dark  text-white"
                                                                                    id="editable" style="font-size: ;"
                                                                                    data-note-id="{{ $note->id }}"
                                                                                    data-note="{{ $note->description }}">
                                                                                    Delete</div>
                                                                            </div>

                                                                        </div>
                                                                    @endforeach

                                                                </span>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                                @can('manage task')
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item">
                                            <h2 class="d-flex justify-between align-items-center accordion-header"
                                                id="panelsStayOpen-headingnote">
                                                <button class="accordion-button px-2 py-3 " type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#panelsStayOpen-collapsetasks">

                                                    <div style="position: absolute;right: 27px;z-index: 9999;">
                                                        @can('create task')
                                                            <a data-size="lg"
                                                                data-url="/organiation/1/task?type=agency&typeid={{ $org_query->id }}"
                                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                title="{{ __('Add Task') }}" class="btn p-2 text-white"
                                                                style="background-color: #313949; color: #fff !important;">
                                                                <i class="ti ti-plus"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                    <span>
                                                        {{ __('Tasks') }}
                                                    </span>
                                                </button>

                                            </h2>

                                            <div id="panelsStayOpen-collapsetasks"
                                                class="accordion-collapse collapse show"
                                                aria-labelledby="panelsStayOpen-headingnote">
                                                <div class="accordion-body">
                                                    @if (!empty($tasks) && $tasks->count() > 0)
                                                        @php
                                                            $section = 1;
                                                            $section2 = 1;
                                                        @endphp
                                                        @foreach ($tasks as $task1)
                                                            @if ($task1->status == 1)
                                                                <div class="accordion"
                                                                    id="accordionPanelsStayOpenExample">
                                                                    <div class="accordion-item">
                                                                        @if ($section == 1)
                                                                            <h2 class="accordion-header"
                                                                                id="panelsStayOpen-headingOnedds">
                                                                                <button class="accordion-button"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#panelsStayOpen-collapseOnedds"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="panelsStayOpen-collapseOnedds">
                                                                                    {{ $section == 1 ? 'Closed Activity' : '' }}
                                                                                </button>
                                                                            </h2>
                                                                            @foreach ($tasks as $task)
                                                                                @if ($task->status == 1)
                                                                                    <div id="panelsStayOpen-collapseOnedds"
                                                                                        class="accordion-collapse collapse"
                                                                                        aria-labelledby="panelsStayOpen-headingOnedds">
                                                                                        <div class="accordion-body">
                                                                                            {{--  --}}
                                                                                            <div
                                                                                                style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                                                <div
                                                                                                    class="row my-2 justify-content-between  ps-4">
                                                                                                    <div
                                                                                                        class="col-12 my-2">
                                                                                                        <p class="text-dark"
                                                                                                            style="font-size: 18px;">
                                                                                                            <span
                                                                                                                style="cursor:pointer"
                                                                                                                class="task-name hyper-link"
                                                                                                                @can('view task') onclick="openSidebar('/get-task-detail?task_id=<?= $task->id ?>')" @endcan
                                                                                                                data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                    <div class="col-8">
                                                                                                        <div
                                                                                                            class="row align-items-center">

                                                                                                            <div
                                                                                                                class="col-8">
                                                                                                                <p
                                                                                                                    class="mb-0 text-secondary">
                                                                                                                <p class="text-muted text-sm"
                                                                                                                    style="font-size: 18px;">
                                                                                                                    <i class="step__icon fa fa-user"
                                                                                                                        aria-hidden="true"></i>
                                                                                                                    {{ optional(\App\Models\User::where('id', $task->assigned_to)->first())->name }}
                                                                                                                </p>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="col-8">
                                                                                                                <span
                                                                                                                    class="d-flex mt-0">
                                                                                                                    <p>Status
                                                                                                                    </p>
                                                                                                                    <p class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ml-2"
                                                                                                                        style="font-size: 10px;">
                                                                                                                        {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                                    </p>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="col-4 text-end px-1">
                                                                                                        <p>{{ $task->created_at }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="d-flex gap-1 justify-content-end pb-2 px-3"
                                                                                                    id="dellhover">
                                                                                                    <button
                                                                                                        class="btn btn-outline-dark text-dark textareaClassedit"
                                                                                                        data-size="lg"
                                                                                                        data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                        data-ajax-popup="true"
                                                                                                        data-bs-toggle="tooltip"
                                                                                                        title="{{ __('Update Task') }}"
                                                                                                        id="editable"
                                                                                                        style="font-size: ;">Edit</button>

                                                                                                    <div class="btn btn-dark  text-white"
                                                                                                        id="editable"
                                                                                                        style="font-size: ;"
                                                                                                        onclick="deleteTask({{ $task->id }}, {{ $task->related_to }}, 'agency');">
                                                                                                        Delete
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{--  --}}
                                                                    </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            @php
                                                $section++;
                                            @endphp
                                        @elseif ($task1->status == 0)
                                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                                <div class="accordion-item">
                                                    @if ($section2 == 1)
                                                        <h2 class="accordion-header" id="panelsStayOpen-headingOneddsd">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapseOneddsd"
                                                                aria-expanded="true"
                                                                aria-controls="panelsStayOpen-collapseOneddsd">
                                                                {{ $section2 == 1 ? 'Open Activity' : '' }}
                                                            </button>
                                                        </h2>

                                                        @foreach ($tasks as $task)
                                                            @if ($task->status == 0)
                                                                <div id="panelsStayOpen-collapseOneddsd"
                                                                    class="accordion-collapse collapse show"
                                                                    aria-labelledby="panelsStayOpen-headingOneddsd">
                                                                    <div class="accordion-body">
                                                                        {{--  --}}
                                                                        <div
                                                                            style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                            <div
                                                                                class="row my-2 justify-content-between  ps-4">
                                                                                <div class="col-12 my-2">
                                                                                    <p class="text-dark"
                                                                                        style="font-size: 18px;">
                                                                                        <span style="cursor:pointer"
                                                                                            class="task-name hyper-link"
                                                                                            @can('view task') onclick="openSidebar('/get-task-detail?task_id=<?= $task->id ?>')" @endcan
                                                                                            data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-8">
                                                                                    <div class="row align-items-center">

                                                                                        <div class="col-8">
                                                                                            <p class="mb-0 text-secondary">
                                                                                            <p class="text-muted text-sm"
                                                                                                style="font-size: 18px;">
                                                                                                <i class="step__icon fa fa-user"
                                                                                                    aria-hidden="true"></i>
                                                                                                {{ optional(\App\Models\User::where('id', $task->assigned_to)->first())->name }}
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="col-8">
                                                                                            <span class="d-flex mb-0">
                                                                                                <p>Status
                                                                                                </p>
                                                                                                <p class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ml-2"
                                                                                                    style="font-size: 10px;">
                                                                                                    {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                </p>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 text-end px-1">
                                                                                    <p>{{ $task->created_at }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex gap-1 justify-content-end pb-2 px-3"
                                                                                id="dellhover">
                                                                                <button
                                                                                    class="btn btn-outline-dark text-dark textareaClassedit "
                                                                                    data-size="lg"
                                                                                    data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                    data-ajax-popup="true"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('Update Task') }}"
                                                                                    id="editable"
                                                                                    style="font-size: ;">Edit</button>

                                                                                <div class="btn btn-dark  text-white"
                                                                                    id="editable" style="font-size: ;"
                                                                                    onclick="deleteTask({{ $task->id }}, {{ $task->related_to }}, 'agency');">
                                                                                    Delete</div>
                                                                            </div>
                                                                        </div>
                                                                        {{--  --}}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </div>
                                            </div>

                                            @php
                                                $section2++;
                                            @endphp
                                            @endif
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endcan
                                <div class="accordion d-none" id="accordionPanelsStayOpenExample">
                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingdisc">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsedisc">
                                                {{ __('Discussion') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsedisc" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingdisc">
                                            <div class="accordion-body">


                                                <div class="">

                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-header ">
                                                                <div class="d-flex justify-content-end">
                                                                    <div class="float-end">
                                                                        <a data-size="lg"
                                                                            data-url="{{ route('leads.discussions.create', $org_query->id) }}"
                                                                            data-ajax-popup="true"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Add Message') }}"
                                                                            class="btn p-2 text-white"
                                                                            style="background-color: #313949;">
                                                                            <i class="ti ti-plus"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body px-0">
                                                                <ul class="list-group list-group-flush mt-2">
                                                                    @if (!empty($org_query->discussions))
                                                                        @foreach ($org_query->discussions as $discussion)
                                                                            <li class="list-group-item px-0"
                                                                                style="list-style: none;">
                                                                                <div
                                                                                    class="d-block d-sm-flex align-items-start">
                                                                                    <img src="@if ($discussion->user->avatar) {{ asset('/storage/uploads/avatar/' . $discussion->user->avatar) }} @else {{ asset('/storage/uploads/avatar/avatar.png') }} @endif"
                                                                                        class="img-fluid wid-40 me-3 mb-2 mb-sm-0"
                                                                                        alt="image">
                                                                                    <div class="w-100">
                                                                                        <div
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <div class="mb-3 mb-sm-0">
                                                                                                <h6 class="mb-0">
                                                                                                    {{ $discussion->comment }}
                                                                                                </h6>
                                                                                                <span
                                                                                                    class="text-muted text-sm">{{ $discussion->user->name }}</span>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-check form-switch form-switch-right mb-2">
                                                                                                {{ $discussion->created_at->diffForHumans() }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @else
                                                                        <li class="text-center">
                                                                            {{ __(' No Data Available.!') }}
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Related Pill End --}}



                        </div>
                        <!-- End of Open Accordion Item -->

                        <!-- Add More Accordion Items Here -->
                    </div>

                    {{-- Timeline Pill End --}}
                    <div class="tab-pane fade" id="pills-activity" role="tabpanel"
                        aria-labelledby="pills-activity-tab">

                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <!-- Open Accordion Item -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingactive">
                                    <button class="accordion-button p-2" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseactive">
                                        {{ __('Timeline') }}
                                    </button>
                                </h2>

                                <div id="panelsStayOpen-collapseactive" class="accordion-collapse collapse show"
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
                                                            <div class="bold time">{{ $activity->created_at }}
                                                            </div>
                                                            <div class="bold"
                                                                style="text-align: left; margin-left: 80px;">
                                                                <p class="bold"
                                                                    style="margin-bottom: 0rem; color: #000000;">
                                                                    {{ $remark->title }}</p>
                                                                <p class="m-0">{{ $remark->message }}</p>
                                                                <span class="text-muted text-sm"
                                                                    style="cursor: pointer;"
                                                                    @can('show employee') onclick="openSidebar('/user/employee/{{ isset($activity->created_by) ? $activity->created_by : '' }}/show')"  @endcan><i
                                                                        class="step__icon fa fa-user me-2"
                                                                        aria-hidden="true"></i>{{ isset($users[$activity->created_by]) ? $users[$activity->created_by] : '' }}</span>
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
                    {{-- Timeline Pill End --}}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<style>
    .indivbtn {
        position: absolute;
        bottom: 30px;
        right: 10px;
        z-index: 1000;
    }

    .note-toolbar>.btn-group {
        position: absolute;
        top: 101px;
        z-index: 1000;
    }

    .note-toolbar>.btn-group>.note-btn>.note-icon-link {
        font-size: 22px;
        position: relative;
        padding-right: 10px;
        padding-bottom: 6px;

    }

    .note-toolbar>.btn-group>.note-btn {
        width: fit-content;
    }



    .note-toolbar>.btn-group>.note-btn>.note-icon-link::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 0;
        width: 2px;
        height: 50%;
        background-color: darkgray;
        transform: translateY(-50%);
    }

    .note-btn::after {
        content: " Add a title";
        font-size: 15px;
        color: darkgray;
        margin-left: 5px;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            height: 150, // Set the height to 600 pixels
            focus: true,
            toolbar: [
                ['link', ['link']],
            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.textareaClass').click(function() {
            $('#textareaID, .textareaClass').toggle("slide");
        });

        $('#cancelNote').click(function() {
            $('textarea[name="description"]').val('');
            $('#note_id').val('');
            $('#textareaID, .textareaClass').toggle("slide");
        });
        $('.textareaClassedit').click(function() {
            var dataId = $(this).data('note-id');
            var dataNote = $(this).data('note');
            $('#description').text(dataNote);
            $('#note_id').val(dataId);
            $('#textareaID, #dellhover, .textareaClass').show();
            $('.textareaClass').toggle("slide");
        });
    });
</script>

