<style>
    .btn-sm {
        width: 35px;
        height: 35px;
    }
</style>
<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0">
    <div class="row">
        <div class="col-sm-12 pe-0">

            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between align-items-center p-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avator">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" alt="" class="">
                    </div>

                    <input type="hidden" name="application-id" class="application-id" value="{{ $application->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Application') }}</p>
                        <div class="d-flex align-items-baseline ">
                            @if (strlen($application->name) > 40)
                            <h5 class="fw-bold">{{ substr($application->name, 0, 40) }}...</h5>
                            @else
                            <h5 class="fw-bold">{{ $application->name }}</h5>
                            @endif

                        </div>
                    </div>

                </div>

                @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit application') || \Auth::user()->can('delete application'))
                <div class="d-flex justify-content-end gap-1 me-3">
                    @can('edit application')
                    <a href="#" data-size="lg" data-url="{{ route('deals.application.edit', $application->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-title="{{ __('Update Application') }}" class="btn text-white px-2 btn-dark" style="width: 36px; height: 36px;">
                        <i class="ti ti-pencil"></i>
                    </a>
                    @endcan

                    @can('delete application')
                    {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['deals.application.destroy', $application->id],
                    'id' => 'delete-form-' . $application->id,
                    ]) !!}
                    <a href="#" class="btn px-2 bg-danger  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete') }}" style="width: 36px; height: 36px;"><i class="ti ti-trash text-white"></i></a>

                    {!! Form::close() !!}
                    @endcan
                </div>
                @endif
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small>{{ __('Status') }}</small>
                    <span class="font-weight-bolder">
                        {{ isset($application->stage_id) && isset($stages[$application->stage_id]) ? $stages[$application->stage_id] : '' }}
                    </span>
                </div>
                <div class="">
                    <small>{{ __('Universtiy') }}</small>
                    <span>
                        {{ isset($application->university_id) && isset($universities[$application->university_id]) ? $universities[$application->university_id] : ''}}
                    </span>
                </div>
                <div class="">
                    <small>{{ __('Created at') }}</small>

                    <span>
                        {{ $application->created_at }}

                    </span>
                </div>

            </div>




            <div class="card content my-2 bg-white">
                <div class="stages mt-2 bg-white">
                    <h2 class="mb-3">Application STATUS: <span class="d-inline-block fw-light">{{ $stages[$application->stage_id] }}</span>
                    </h2>
                    <div class="wizard mb-2" style="background: #EFF3F7; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);">
                        <?php $done = true; ?>
                        @forelse ($stages as $key => $stage)
                        <?php
                        if ($application->stage_id == $key) {
                            $done = false;
                        }

                        $is_missed = false;

                        if (!empty($stage_histories) && !in_array($key, $stage_histories) && $key <= max($stage_histories)) {
                            $is_missed = true;
                        }

                        ?>
                        <style>
                            .missedup {
                                background-color: #e0e0e0 !important;
                                color: white !important;
                            }

                            .missedup::after {
                                border-left-color: #e0e0e0 !important;
                            }
                        </style>

                        <a type="button" data-application-id="{{ $application->id }}" data-stage-id="{{ $key }}" class="@can('edit stage application') application_stage @endcan {{ $is_missed == true ? 'missedup' : ($application->stage_id == $key ? 'current' : ($done == true ? 'done' : '')) }} " style="font-size:13px">{{ $stage }} @if($is_missed == true)<i class="fa fa-close text-danger"></i>@endif </a>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-related" type="button" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Related') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeyone">
                                                {{ __('Details') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyone" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $application->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Application Name') }}
                                                                </td>
                                                                <td class="application_key-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{$application->name}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Application Key') }}
                                                                </td>
                                                                <td class="application_key-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{$application->application_key}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('University') }}
                                                                </td>
                                                                <td class="university_name-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $universities[$application->university_id] ?? '' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Course') }}
                                                                </td>
                                                                <td class="course-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{ ($application->course) }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Intake') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{($application->intake) }}

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Student ID') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $application->deal_id}}

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ App\Models\User::find(App\Models\Deal::find($application->deal_id)->brand_id)->name ?? ''}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ App\Models\Branch::find(App\Models\Deal::find($application->deal_id)->branch_id)->name  ?? ''}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Assigned To') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">
                                                                    @if (App\Models\Deal::find($application->deal_id)->assigned_to)

                                                                    {{ App\Models\User::find(App\Models\Deal::find($application->deal_id)->assigned_to)->name }}
                                                                    @else
                                                                    {{__(" ")}}
                                                                    @endif

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Status') }}
                                                                </td>
                                                                <td class="status-td" style="padding-left: 10px; font-size: 14px;">

                                                                    {{ isset($application->stage_id) && isset($stages[$application->stage_id]) ? $stages[$application->stage_id] : '' }}


                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class="created_at-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $application->created_at }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 150px; font-size: 14px;">
                                                                    {{ __('Updated at') }}
                                                                </td>
                                                                <td class="updated_at-td" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $application->updated_at }}
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


                            <div class="tab-pane fade" id="pills-related" role="tabpanel" aria-labelledby="pills-details-tab">


                                <div class="row">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingkeydesc">
                                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeydesc">
                                            {{ __('Admissions') }}
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapsekeydesc" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingkeydesc">
                                        <div class="accordion-body">
                                            <div style="max-height: 400px; overflow-y: auto;">
                                                    @php
                                                    $admissions = \App\Models\DealApplication::query()
                                                                ->select('d.id','deal_applications.name', 'contact.passport_number as passport_number', 's.name as stage', \DB::raw("CONCAT(d.intake_month, d.intake_year) AS intake"), 'u.name as assigned_to')
                                                                ->join('deals as d', 'deal_applications.deal_id', '=', 'd.id')
                                                                ->join('users as u', 'u.id', '=', 'd.assigned_to')
                                                                ->join('stages as s', 's.id', '=', 'd.stage_id')
                                                                ->join('client_deals as cd', 'cd.deal_id', '=', 'd.id')
                                                                ->join('users as contact', 'contact.id', '=', 'cd.client_id')
                                                                ->where('deal_applications.id', 4)
                                                                ->get();
                                                                                                                @endphp
                                                    <table class="table table-hover">
                                                        <thead  style="background-color:rgba(0, 0, 0, .08); font-weight: bold;color:#000000">
                                                            <tr>
                                                                <td>{{ __('Name') }}</td>
                                                                <td>{{ __('Passport Number') }}</td>
                                                                <td>{{ __('Stage') }}</td>
                                                                <td>{{ __('Intake') }}</td>
                                                                <td>{{ __('Assigned To') }}</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody >
                                                            @forelse($admissions as $admission)
                                                                <tr>
                                                                    <td>

                                                                        <span style="cursor:pointer" class="deal-name hyper-link" @can('view deal') onclick="openSidebar('/get-deal-detail?deal_id='+{{ $admission->id }})" @endcan data-deal-id="{{ $admission->id }}">

                                                                            @if (strlen($admission->name) > 40)
                                                                            {{ substr($admission->name, 0, 40) }}...
                                                                            @else
                                                                            {{ $admission->name }}
                                                                            @endif
                                                                        </span>
                                                                    </td>
                                                                    <td>{{ $admission->passport_number }}</td>
                                                                    <td>{{ $admission->stage }}</td>
                                                                    <td>{{ $admission->intake }}</td>
                                                                    <td>{{ $admission->assigned_to }}</td>
                                                                </tr>
                                                            @empty

                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>


                                        </div>
                                    </div>

                                </div>


                                    @can('manage notes')
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingnote">
                                                <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsenote">
                                                    {{ __('Notes') }}
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapsenote" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingnote">
                                                <div class="accordion-body">


                                                    <style>
                                                        .indivbtn {
                                                            position: absolute;
                                                            bottom: 6px;
                                                            right: 10px;
                                                            z-index: 1000;
                                                        }
                                                    </style>

                                                    <div class="card position-relative">
                                                        {{ Form::model($application, array('route' => array('application.notes.store', $application->id), 'method' => 'POST', 'id' => 'create-notes' ,'style' => 'z-index: 9999999 !important;')) }}
                                                        <textarea class="form-control" style="height: 120px;" name="description" id="note_description"
                                                            placeholder="Click here add your Notes Comments..."></textarea>
                                                        <input type="hidden" id="application_id" value="{{ $application->id }}" name="id">
                                                        <input type="hidden" id="note_id"  name="note_id">
                                                        <div class="row justify-content-end indivbtn">
                                                            {{-- <div class="col-auto px-0">
                                                                <button class="btn  btn-outline-dark text-dark"
                                                                    id="cancelNote">Cancel</button>
                                                            </div> --}}
                                                            <div class="col-auto ">
                                                                <button class="btn btn-dark text-white create-notes-btn">Save</button>
                                                            </div>
                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                        <div class="card-body px-0 py-0">
                                                            <ul class="list-group list-group-flush mt-2 note-tbody">
                                                                @php
                                                                $notes = \App\Models\ApplicationNote::where('application_id', $application->id)
                                                                ->orderBy('created_at', 'DESC')
                                                                ->get();
                                                                @endphp

                                                                @foreach ($notes as $note)
                                                                <div style="border-top:1px solid black;border-bottom:1px solid black ">
                                                                    <div class="row my-2 justify-content-between px-4">
                                                                        <div class="col-8">
                                                                            <div class="row align-items-center">

                                                                                <div class="col-8">
                                                                                    <h4 class="mb-0">
                                                                                        {{ \App\Models\User::where('id', $note->created_by)->first()->name }}</h4>
                                                                                    <p class="mb-0">{{ \App\Models\User::where('id', $note->created_by)->first()->type }}</p>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4 text-end">
                                                                            <p>{{ $note->created_at }}</p>
                                                                        </div>
                                                                        <div class="col-12 my-2">
                                                                            <p>{{ $note->description }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex gap-1 justify-content-end pb-2 px-3" id="dellhover">
                                                                        <div class="btn btn-outline-dark text-dark textareaClassedit" data-note="{{ $note->description }}" data-note-id="{{ $note->id }}" id="editable" style="font-size: ;">Edit</div>

                                                                        <div class="delete-notes btn btn-dark  text-white" id="editable" style="font-size: ;" data-note-id="{{ $note->id }}">Delete</div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endcan



                                    @can('manage task')
                                    <div class="accordion" id="accordionTasks">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="accordionTasks-heading">
                                                <button class="accordion-button px-2 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#accordionTasks-collapse">
                                                    <span>{{ __('Tasks') }}</span>
                                                    @can('create task')
                                                    <a data-size="lg" data-url="/organiation/1/task?type=application&typeid={{$application->id}}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Add Task') }}" class="btn p-2 text-white" style="background-color: #313949; color: #fff !important; position: absolute; right: 0;">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                    @endcan
                                                </button>
                                            </h2>
                                    @php $tasks = App\Models\DealTask::where(['related_to' => $application->id, 'related_type' => 'application'])->orderBy('status')->get(); @endphp
                                            <div id="accordionTasks-collapse" class="accordion-collapse collapse show" aria-labelledby="accordionTasks-heading">
                                                <div class="accordion-body">
                                                    <ul class="list-group list-group-flush mt-2 notes-tbody">
                                                        @if (!empty($tasks) && $tasks->count( ) > 0)
                                                        <div class="">
                                                          <div class="col-12">
                                                              <div class="card">
                                                                  <div class="card-body px-0">
                                                                      <ul class="list-group list-group-flush mt-2 notes-tbody">
                                                                          @php
                                                                          $section=1;
                                                                          $section2=1;
                                                                      @endphp
                                                                          @foreach($tasks as $task)
                                                                          @if ($task->status == 1)
                                                                          <div class="ps-3 py-2 d-flex gap-2 align-items-baseline" style="border-bottom: 1px solid rgb(192, 192, 192);">
                                                                              <i class="fa-regular fa-square-check" style="color: #000000;"></i>
                                                                              <h6 class="fw-bold">
                                                                                  {{ $section == 1 ? 'Closed Activity': '' }}
                                                                              </h6>
                                                                          </div>
                                                                              <li class="list-group-item px-3"
                                                                                  id="lihover">
                                                                                  <div class="d-block d-sm-flex align-items-start">
                                                                                      <div class="w-100">
                                                                                          <div
                                                                                              class="d-flex align-items-center justify-content-between">
                                                                                              <div class="mb-3 mb-sm-0">
                                                                                                  <h5 class="mb-0">
                                                                                                      {{ $task->name }}

                                                                                                  </h5>
                                                                                                  <span
                                                                                                      class="text-muted text-sm">
                                                                                                      {{ $task->created_at }}
                                                                                                  </span><br>
                                                                                                  <span
                                                                                                      class="text-muted text-sm"><i class="step__icon fa fa-user" aria-hidden="true"></i>
                                                                                                      {{ \App\Models\User::where('id', $task->assigned_to)->first()->name }}

                                                                                                      <span class="d-flex">
                                                                                                          <div>Status</div>
                                                                                                          <div class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ml-5">
                                                                                                            {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                      </div>
                                                                                                      </span>
                                                                                                      {{--  --}}
                                                                                                  </span>
                                                                                              </div>

                                                                                              {{-- <style>
                                                                                                  #editable {
                                                                                                      display: none;
                                                                                                  }

                                                                                                  #lihover:hover #editable {
                                                                                                      display: flex;
                                                                                                  }
                                                                                              </style> --}}
                                                                                          <div class="d-flex gap-3" id="dellhover">

                                                                                                  <a data-size="lg"
                                                                                                  data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                  data-ajax-popup="true"
                                                                                                  data-bs-toggle="tooltip"
                                                                                                  title="{{ __('Update Task') }}"
                                                                                                  id="editable"
                                                                                                  class="btn textareaClassedit">
                                                                                                  <i
                                                                                                      class="ti ti-pencil" style="font-size: 20px;margin-right: -30px;color: white"></i>
                                                                                              </a>


                                                                                              <a href="javascript:void(0)"
                                                                                                  class="btn"
                                                                                                  id="editable"
                                                                                                  onclick="deleteTask({{ $task->id }}, {{ $application->id }}, 'lead');">
                                                                                                  <i class="ti ti-trash " style="font-size: 20px;color: white"></i>
                                                                                              </a>

                                                                                          </div>

                                                                                          </div>
                                                                                      </div>
                                                                                  </div>
                                                                              </li>
                                                                              @php
                                                                                          $section++;
                                                                                      @endphp
                                                                                  @elseif ($task->status == 0)

                                                                                  <div class="ps-3 py-2 d-flex gap-2 align-items-baseline" style="border-bottom: 1px solid rgb(192, 192, 192);">
                                                                                      <i class="fa-regular fa-square-check" style="color: #000000;"></i>
                                                                                      <h6 class="fw-bold">
                                                                                          {{ $section2 == 1 ? 'Open Activity': '' }}
                                                                                      </h6>
                                                                                  </div>
                                                                                      <li class="list-group-item px-3"
                                                                                          id="lihover">
                                                                                          <div class="d-block d-sm-flex align-items-start">
                                                                                              <div class="w-100">
                                                                                                  <div
                                                                                                      class="d-flex align-items-center justify-content-between">
                                                                                                      <div class="mb-3 mb-sm-0">
                                                                                                          <h5 class="mb-0">
                                                                                                              {{ $task->name }}

                                                                                                          </h5>
                                                                                                          <span
                                                                                                              class="text-muted text-sm">
                                                                                                              {{ $task->created_at }}
                                                                                                          </span><br>
                                                                                                          <span
                                                                                                              class="text-muted text-sm"><i class="step__icon fa fa-user" aria-hidden="true"></i>
                                                                                                              {{ \App\Models\User::where('id', $task->assigned_to)->first()->name }}

                                                                                                              <span class="d-flex">
                                                                                                                  <div>Status</div>
                                                                                                                  <div class="badge {{ $task->status == 1 ? 'bg-success-scorp' : 'bg-warning-scorp' }} ml-5">
                                                                                                                    {{ $task->status == 1 ? 'Completed' : 'On Going' }}
                                                                                                              </div>
                                                                                                              </span>
                                                                                                              {{--  --}}
                                                                                                          </span>
                                                                                                      </div>


                                                                                                  <div class="d-flex gap-3" id="dellhover">

                                                                                                          <a data-size="lg"
                                                                                                          data-url="{{ route('organiation.tasks.edit', $task->id) }}"
                                                                                                          data-ajax-popup="true"
                                                                                                          data-bs-toggle="tooltip"
                                                                                                          title="{{ __('Update Task') }}"
                                                                                                          id="editable"
                                                                                                          class="btn textareaClassedit">
                                                                                                          <i
                                                                                                              class="ti ti-pencil" style="font-size: 20px;margin-right: -30px;"></i>
                                                                                                      </a>


                                                                                                      <a href="javascript:void(0)"
                                                                                                          class="btn"
                                                                                                          id="editable"
                                                                                                          onclick="deleteTask({{ $task->id }}, {{ $application->id }}, 'lead');">
                                                                                                          <i class="ti ti-trash " style="font-size: 20px;"></i>
                                                                                                      </a>

                                                                                                  </div>

                                                                                                  </div>
                                                                                              </div>
                                                                                          </div>
                                                                                      </li>
                                                                                      @php
                                                                                          $section2++;
                                                                                      @endphp
                                                                                  @endif
                                                                          @endforeach

                                                                          </ul>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                         </div>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endcan

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

            //Fall2201075
            $('.textareaClassedit').click(function() {
                var dataId = $(this).data('note-id');
                var dataNote = $(this).data('note');
                $('textarea[name="description"]').val(dataNote);
                $('#note_id').val(dataId);
            });


            ////////////////////////////Form Submission
            //saving notes
            // Bind event handler directly to the button element
            $(".create-notes-btn").on("click", function(e) {
                e.preventDefault();

                // Serialize form data
                var formData = $("#create-notes").serialize();
                var id = $('#application_id').val();

                $(".create-notes-btn").val('Processing...');
                $('.create-notes-btn').attr('disabled', 'disabled');

                $.ajax({
                    type: "POST",
                    url: "/application/" + id + "/notes",
                    data: formData,
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status == 'success') {
                            show_toastr('success', data.message, 'success');
                            $('#note_description').val('');
                            $('.create-notes-btn').removeAttr('disabled');
                            $('#commonModal').modal('hide');
                            $('.note-tbody').html(data.html);
                            $('#description').val('');
                            $('#note_id').val('');
                        } else {
                            show_toastr('error', data.message, 'error');
                            $(".create-notes-btn").val('Create');
                            $('.create-notes-btn').removeAttr('disabled');
                        }
                    }
                });
            });


            //delete-notes
            $(document).on("click", '.delete-notes', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-note-id');
                var application_id = $('#application_id').val();
                var currentBtn = '';

                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to delete this note. This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User confirmed, proceed with deletion
                        $.ajax({
                            type: "GET",
                            url: "/application/" + id + "/notes-delete",
                            data: {
                                id: id,
                                application_id: application_id
                            },
                            success: function(data) {
                                data = JSON.parse(data);

                                if (data.status == 'success') {
                                    show_toastr('success', data.message, 'success');
                                    $('.note-tbody').html(data.html);
                                } else {
                                    show_toastr('error', data.message, 'error');
                                }
                            }
                        });
                    }
                });
            });



        });
    </script>
