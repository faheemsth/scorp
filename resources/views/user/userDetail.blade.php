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

                    <input type="hidden" name="user-id" class="user-id" value="{{ $user->id }}">

                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Contact') }}</p>
                        <div class="d-flex align-items-baseline ">
                            @if (strlen($user->name) > 40)
                                <h4>{{ substr($user->name, 0, 40) }}...</h4>
                            @else
                                <h4>{{ $user->name }}</h4>
                            @endif

                        </div>
                    </div>

                </div>

                @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit user'))
                    <div class="d-flex justify-content-end gap-1 me-3">
                        <a href="#" data-size="lg" data-url="{{ route('users.edit', $user->id) }}"
                            data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-title="{{ __('Update User') }}"
                            class="btn p-2 btn-dark">
                            <i class="ti ti-pencil"></i>
                        </a>
                    </div>
                @endif
            </div>


            <div class="lead-info d-flex justify-content-between p-3 text-center">
                <div class="">
                    <small>{{ __('Organization') }}</small>
                    <span class="font-weight-bolder">

                    </span>
                </div>
                <div class="">
                    <small>{{ __('Email') }}</small>
                    <span>
                        {{ $user->email }}
                    </span>
                </div>

                <div class="">
                    <small>{{ __('Owner') }}</small>
                    <span>
                        {{ $userArr[$user->created_by] }}
                    </span>
                </div>

            </div>



            <div class="content my-2">

                <div class="card">
                    <div class="card-header p-1">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-details" type="button" role="tab"
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
                                                {{ __('NAME AND OCCUPATION') }}
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
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $user->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class="name-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $user->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Organizaiton') }}
                                                                </td>
                                                                <td class="university_id-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Contact Type') }}
                                                                </td>
                                                                <td class="status-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                    {{ $user->type }}

                                                                </td>
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
                                                data-bs-target="#panelsStayOpen-collapsekeytwo">
                                                {{ __('CONTACT AND DETAIL') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapsekeytwo" class="accordion-collapse collapse show"
                                            aria-labelledby="panelsStayOpen-headingkeyone">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Email') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $user->email }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Phone') }}
                                                                </td>
                                                                <td class="phone-td"
                                                                    style="padding-left: 10px; font-size: 14px;">

                                                                </td>
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
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Contact Owner') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                     {{ $userArr[$user->created_by] }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('Status') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $user->is_active == 1 ? 'Active' : 'Inactive' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('User Created') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $user->created_at }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 150px; text-align: right; font-size: 14px;">
                                                                    {{ __('User Updated') }}
                                                                </td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $user->updated_at }}
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
