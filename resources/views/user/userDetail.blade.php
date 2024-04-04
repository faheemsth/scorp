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
                        <p class="pb-0 mb-0 fw-normal">{{ __('Brand') }}</p>
                        <div class="d-flex align-items-baseline">
                            <h5 class="fw-bold">{{ $user->name }}</h5>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-1 me-3">
                    @can('edit user')
                    <a href="#!" data-size="lg" data-url="{{ route('user.edit', $user->id) }}" data-ajax-popup="true" class="btn px-2 py-2 btn-dark text-white" data-bs-original-title="{{__('Edit Brand')}}" data-bs-toggle="tooltip" title="{{ __('Edit Brand') }}">
                    <i class="ti ti-pencil"></i>
                      </a>
                    @endcan

                    @can('delete user')
                    {!! Form::open(['method' => 'DELETE','class'=>'mb-0' , 'route' => ['users.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]) !!}
                    <a href="#!" class="btn px-2 py-2 btn-danger text-white bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                        <i class="ti ti-archive"></i>
                        <!-- <span> @if($user->delete_status!=0){{__('Delete')}} @else {{__('Restore')}}@endif</span> -->
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
                                <button class="nav-link pills-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body px-2">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">

                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <!-- Open Accordion Item -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                                {{ __('BRAND INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-1" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Record ID') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $user->id }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Name') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $user->name }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Project Director') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                    {{ $userArr[$user->project_director_id] ?? '' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Project Manager') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                     {{ $userArr[$user->project_manager_id] ?? '' }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Domain Link') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                               <a href="{{ $user->domain_link }}" target="_blank" >{{ $user->domain_link }}</a> 
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Webiste Link') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                <?php
                                                                    $website_link = $user->website_link;
                                                                    if (strpos($website_link, 'https://') === false) {
                                                                        $website_link = 'https://' . $website_link;
                                                                    }
                                                                    ?>
                                                                    <a href="<?php echo $website_link; ?>" target="_blank"><?php echo $website_link; ?></a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Google Drive Link') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                <a href="{{ $user->drive_link }}" class="" target="_blank">{{ $user->drive_link }}</a>
                                                                </td>
                                                            </tr>

                                                            <tr>
    `                                                     <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Created By') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                   {{ $userArr[$user->created_by] }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Created at') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                                {{ $user->created_at }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="" style="width: 100px; font-size: 14px;">
                                                                    {{ __('Update at') }}
                                                                </td>
                                                                <td class="" style="padding-left: 10px; font-size: 14px;">
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
                        <!-- End of Open Accordion Item -->

                        <!-- Add More Accordion Items Here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
