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

                    <input type="hidden" name="announcement-id" class="announcement-id" value="{{ $announcement->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Name') }}</p>
                        <div class="d-flex align-items-baseline ">
                            @if (strlen($announcement->Title) > 40)
                                <h6 class="fw-bold">{{ substr($announcement->title, 0, 40) }}...</h6 >
                            @else
                                <h6 class="fw-bold">{{ $announcement->title }}</h6 >
                            @endif

                        </div>
                    </div>

                </div>
                @if(Gate::check('edit announcement') || Gate::check('delete announcement'))

                <div class="d-flex justify-content-end gap-1 me-3">
                    @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit Announcement'))
                        <div class="d-flex justify-content-end gap-1 me-3">
                            <a href="#" data-size="lg" data-url="{{ route('announcement.edit', $announcement->id) }}"
                                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Update Announcement') }}"
                                class="btn p-2 btn-dark text-white">
                                <i class="ti ti-pencil"></i>
                            </a>
                        </div>
                    @endif

                      @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete Announcement'))

                             {!! Form::open(['method' => 'DELETE', 'route' => ['announcement.destroy', $announcement->id]]) !!}

                            <a href="#" data-bs-toggle="tooltip" title="{{__('Delete')}}"
                                class="btn px-2 py-2 text-white bs-pass-para bg-danger">
                                <i class="ti ti-trash" ></i>
                            </a>


                            {!! Form::close() !!}
                    @endif
                    </div>
                    @endif
            </div>






            <div class="content my-2">

                <div class="card">
                    <div class="card-header p-1 bg-white">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active"  id="pills-details-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>

                            <li class="nav-item d-none" role="presentation"  >
                                <button class="nav-link pills-link "  id="pills-related-tab" data-bs-toggle="pill" data-bs-target="#pills-related" type="button" role="tab" aria-controls="pills-related" aria-selected="false">{{ __('Related') }}</button>
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
                                                {{ __('Detail') }}
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
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $announcement->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $announcement->title }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                   {{ optional(App\Models\User::find(str_replace(['["', '"]'], '',  $announcement->brand_id)))->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Region') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                   {{ optional(App\Models\Region::find(str_replace(['["', '"]'], '',  $announcement->region_id)))->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Branch') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                 {{ optional(App\Models\Branch::find(str_replace(['["', '"]'], '',  $announcement->branch_id)))->name }}

                                                                </td>
                                                            </tr>

                                                        {{--  <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Territory') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $university->territory }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Brand') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $users[$university->company_id] ?? '' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Phone') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $university->phone }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Resource') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    <a href="{{ $university->resource_drive_link }}" class="" target="_blank">{{ $university->resource_drive_link }}</a>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Application Method') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    <a href="{{ $university->application_method_drive_link }}" class="" target="_blank">{{ $university->application_method_drive_link }}</a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Note') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $university->note }}
                                                                </td> --}}
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headingkeyone">
                                            <button class="accordion-button p-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapsekeyfour">
                                                {{ __('ADDITIONAL INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeyfour"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $announcement->created_at }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; font-size: 14px;">
                                                                    {{ __('Updated at') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $announcement->updated_at }}
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



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
