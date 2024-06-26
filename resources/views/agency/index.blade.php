@extends('layouts.admin')
@section('page-title')
{{ __('Agency') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Agency') }}</li>
@endsection
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@push('script-page')
@endpush
<style>
    #objType {
        font-size: 11px;
        line-height: 11px;
        color: rgb(119, 119, 119);
        text-transform: uppercase;
    }

    .head-list .title {
        color: rgb(0, 0, 0);
    }

    .title {
        padding-right: 10px;
    }

    .btn-group {
        position: relative;
        display: inline-block;
        font-size: 0px;
        white-space-collapse: collapse;
        text-wrap: nowrap;
        vertical-align: middle;
    }


    .head-list #btn-list-title {
        font-weight: normal;
        font-size: 18px;
        opacity: 1;
        margin-left: -4px;
        cursor: default;
        max-width: 320px;
        white-space-collapse: collapse;
        text-wrap: nowrap;
        text-overflow: ellipsis;
        background-color: rgb(255, 255, 255) !important;
        background-image: none !important;
        border-color: rgb(255, 255, 255);
        padding: 4px;
        overflow: hidden;
    }


    .btn-group>.btn:first-child {
        margin-left: 0px;
        border-top-left-radius: 2px;
        border-bottom-left-radius: 2px;
    }

    label {
        font-weight: normal !important;
    }

    .filbar .form-control:focus{
                    border: 1px solid rgb(209, 209, 209) !important;
                }

</style>





@section('content')

<div class="row">



<style>
    /* .red-cross {
                position: absolute;
                top: 5px;http://127.0.0.1:8000/deals/get-user-tasks#
                right: 5px;
                color: red;
            } */
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
</style>

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row align-items-center ps-0 ms-0 pe-4 my-2 justify-content-between">
                    <div class="col-2">
                        <p class="mb-0 pb-0 ps-1">Agency</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL Agency
                            </button>
                            @php
                            $saved_filters = App\Models\SavedFilter::where('created_by', \Auth::user()->id)->where('module', 'agency')->get();
                            @endphp
                              @if(sizeof($saved_filters) > 0)
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
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
                                                        <li><a class="dropdown-item" href="#" onClick="editFilter('<?= $filter->filter_name ?>', <?= $filter->id ?>)">Rename</a></li>
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

                    <div class="col-8 d-flex justify-content-end gap-2">
                        <div class="input-group w-25 rounded" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <button class="btn  list-global-search-btn p-0 pb-5">
                                <span class="input-group-text bg-transparent border-0  px-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <!-- <button class="btn px-2 pb-2 pt-2 refresh-list bg-dark" style=" color:white;"><i class="ti ti-refresh" style="font-size: 18px"></i></button> -->

                        <button class="btn filter-btn-show px-2 btn-dark"  type="button" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>

                       
                        <a href="#" data-size="lg" data-url="{{ route('agency.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create New Agency') }}" class="btn p-2 btn-dark" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-plus"></i>
                        </a>

                        <button class="btn px-2 btn-dark d-none" id="actions_div" type="button" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-trash" style="font-size:18px"></i>
                        </button>
                    </div>
                </div>



                <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                    <form action="/agency/" method="GET" class="">
                        <div class="row my-3 align-items-end filbar">
                            <div class="col-md-3 mt-2">
                                <label for="">Agency Name</label>
                                <input type="text" class="form form-control" name="agencyname" value="<?= isset($_GET['agencyname']) ? $_GET['agencyname'] : '' ?>" style="width: 95%; border-color:#aaa">
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Agency email</label>
                                <input type="text" class="form form-control" name="agencyemail" value="<?= isset($_GET['agencyemail']) ? $_GET['agencyemail'] : '' ?>" style="width: 95%; border-color:#aaa">
                            </div>


                            <div class="col-md-3 mt-2">
                                <label for="">Agency Phone</label>
                                <input type="text" class="form form-control" name="agencyphone" value="<?= isset($_GET['agencyphone']) ? $_GET['agencyphone'] : '' ?>" style="width: 95%; border-color:#aaa">
                            </div>


                            <div class="col-md-3"> <label for="">Country</label>
                                <select name="country" id="countries" class="form form-control select2 " style="width: 95%;">
                                    <option value="">Select user</option>
                                    @foreach ($countries as $key => $country)
                                        <option value="{{ $country['name'] }}-{{ $country['code'] }}" <?= isset($_GET['country']) && $_GET['country'] == $country['name'].'-'.$country['code'] ? 'selected' : '' ?>>
                                                {{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-3"> <label for="">City</label>
                                <span id="Cities_divs">
                                    <select name="city" id="city" class="form form-select select2">
                                        <option value="">Select cities</option>
                                        @if(!empty($citiese))
                                           @foreach($citiese as $city)
                                              <option value="{{$city->name}}" {{ $city->name == $_GET['city']? 'selected':'' }}>{{$city->name}}</option>
                                           @endforeach
                                        @endif
                                    </select>
                                </span>
                            </div>


                            <div class="col-md-4 mt-2">
                                <br>
                                <input type="submit" class="btn form-btn bg-dark me-2" style=" color:white;">
                                <a type="button" id="save-filter-btn" onClick="saveFilter('agency',<?= sizeof($organizations) ?>)" class="btn form-btn me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
                                <a href="/agency/" class="btn form-btn bg-dark" style="color:white;">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class=" mt-3">
                    <table class="table">
                        <thead style="background: #ddd; color:rgb(0, 0, 0); font-size: 14px; font-weight: bold;">
                            <tr>
                                <th style="width: 50px !important;">
                                    <input type="checkbox" class="main-check">
                                </th>
                                <!-- <td style="border-left: 1px solid #fff;"></td> -->
                                <td style="border-left: 1px solid #fff;">Agency Name</td>
                                <td style="border-left: 1px solid #fff;">Agency email</td>
                                <td style="border-left: 1px solid #fff;">Agency Phone</td>
                                <td style="border-left: 1px solid #fff;">Contact Person Name</td>

                                <td style="border-left: 1px solid #fff;">Billing Country</td>
                                <td style="border-left: 1px solid #fff;">Billing City</td>

                                <td style="border-left: 1px solid #fff; display: none;">Action</td>
                            </tr>
                        </thead>
                        <tbody class="organization_tbody" style="; font-size: 14px;" class="new-organization-list-tbody">

                            @forelse($organizations as $org)
                            @php
                            $org_data = $org;

                            @endphp

                            <tr>
                                <td >
                                    <input type="checkbox" name="agency[]" value="{{ $org->id }}" class="sub-check">
                                </td>

                                <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                    <span style="cursor:pointer" class="lead-name hyper-link"
                                        onclick="openSidebar('/get-agency-detail?id=<?= $org->id ?>')"
                                        data-lead-id="{{ $org->id }}">{{ !empty($org->username)? $org->username : '--' }}
                                    </span>
                                </td>
                                <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ isset($org->useremail) ? $org->useremail : '' }}</td>

                                <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ isset($org_data->phone) ? $org_data->phone : '' }}</td>
                                <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ isset($org_data->contactname) ? $org_data->contactname : '' }}</td>
                                <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                    @php
                                       $country_parts = explode("-", isset($org_data->billing_country) ? $org_data->billing_country : '');
                                       $country_code = $country_parts[0];
                                    @endphp
                                    {{ $country_code }}
                                </td>
                                @php
                                    $country_parts = explode("-", isset($org_data->billing_country) ? $org_data->billing_country : '');
                                    $cities = App\Models\City::where('country_code', $country_parts[1])->where('name',$org_data->city)->first();
                                @endphp
                                <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $cities['name'] }}</td>
                                <td class="d-none">
                                    <div class="dropdown">
                                        <button class="btn bg-transparents" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                                <path d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                                                </path>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('edit organization'))
                                            <li>
                                                <a href="#" data-size="lg" data-url="{{ route('organization.edit', $org->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Edit') }}" class="dropdown-item">
                                                    Edit this Organization
                                                </a>
                                            </li>
                                            @endif

                                            <li>
                                                @if (\Auth::user()->type == 'super admin' || \Auth::user()->can('delete organization'))
                                                <a href="{{ route('organization.delete', $org->id) }}" class="dropdown-item">
                                                    Delete this Organization
                                                </a>
                                                @endif
                                            </li>
                                            @can('create task')
                                            <li>
                                                <a href="{{ route('organiation.tasks.create', $org->id) }}" data-bs-toggle="tooltip" title="{{ __('Add Message') }}" class="dropdown-item">
                                                    Add new task For Organization
                                                </a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="py-1 text-center">
                                    No Agency found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="pagination_div">
                        @if ($total_records > 0)
                            @include('layouts.pagination', [
                                'total_pages' => $total_records,
                            ])
                        @endif
                    </div>
                </div>


                <div id="mySidenav" style="z-index: 1065; padding-left:10px; box-shadow: -5px 0px 30px 0px #aaa;" class="sidenav <?= isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>" style="padding-left: 5px">
                </div>

            </div>

        </div>
    </div>
</div>
</div>



<!-- Add Organization -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg my-0" role="document">
        <div class="modal-content">
            <form id="organization-creating-form">
                @csrf
                <div class="modal-header pt-3">
                    <div class="float-left d-flex">
                        <div class="lead-avator">
                            <img src="https://d3rqem538l0q4a.cloudfront.net/img/placeholder-organization.png" alt="" class="" style="width: 40px; height: 40px;">
                        </div>

                        <div class="lead-basic-info my-auto" style="margin-left: 10px;">
                            <span style="font-size: 14px; line-height: 11px; color: rgb(119, 119, 119);">{{ __('Create Organizaiton') }}</span><br>
                            <h1 class="" style="font-weight: normal;
                font-size: 21px;
                line-height: 25px;
                display: inline;
                white-space-collapse: collapse;
                text-wrap: nowrap;
                margin: 0px;">
                                {{ __('Add New Organization') }}
                            </h1>
                        </div>

                    </div>
                </div>
                <style>
                    .form-group {
                        margin-bottom: 0px;
                        padding-top: 0px;
                    }

                    .space {
                        padding: 3px 3px;
                    }
                </style>
                <div class="modal-body pt-0 " style="height: 80vh;">
                    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
                        <div class="card-body px-2 py-0">
                            {{-- ACCORDION --}}
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                {{-- Organizaiton Basic Info --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            ORGANIZATION NAME
                                        </button>
                                    </h2>


                                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                        <div class="accordion-body">

                                            <div class="form-group row ">
                                                <label for="organization-name" class="col-sm-3 col-form-label">Organization
                                                    Name</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="organization-name" value="" placeholder="Organization Name" name='organization_name'>
                                                </div>
                                            </div>

                                            <div class="form-group row ">
                                                <label for="type-of-organization" class="col-sm-3 col-form-label">Type
                                                    Of
                                                    Organization</label>
                                                <div class="col-sm-6">
                                                    <select name="organization_type" class="form form-control select2" id="test-0001">
                                                        <option value="">Select Type</option>
                                                        @foreach ($org_types as $key => $type)
                                                        <option value="{{ $type }}">{{ $type }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- Organizaiton Contact Info --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                            ORGANIZATION CONTACT DETAILS
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
                                        <div class="accordion-body">
                                            <div class="form-group row">
                                                <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="phone" value="" name="organization_phone">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                                <div class="col-sm-6">
                                                    <input type="email" class="form-control" id="email" value="" name="organization_email">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="website" class="col-sm-3 col-form-label">Website</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="website" value="" name="organization_website">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="linkedin" class="col-sm-3 col-form-label">Linkedin</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="linkedin" value="" name="organization_linkedin">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="facebook" class="col-sm-3 col-form-label">Facebook</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="facebook" value="" name="organization_facebook">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="twitter" class="col-sm-3 col-form-label">Twitter</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="twitter" value="" name="organization_twitter">
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                {{-- Organizaiton Address Info --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                            ADDRESS INFORMATION
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingThree">
                                        <div class="accordion-body">

                                            <div class="form-group row">
                                                <label for="billing-addres" class="col-sm-3 col-form-label">Billing
                                                    Address <span style="color:red;">*</span> </label>
                                                <div class="col-sm-6">
                                                    <div class="col-12">
                                                        <textarea name="organization_billing_street" class="form form-control" id="" cols="30" rows="3"></textarea>
                                                    </div>

                                                    <div class="row mt-1 mx-0">
                                                        <div class="col-6 mt-1 space">
                                                            <input type="text" class="form-control" id="billing-city" placeholder="Billing City" value="" name="organization_billing_city">
                                                        </div>

                                                        <div class="col-6 mt-1 space">
                                                            <input type="text" class="form-control" id="billing-state" placeholder="Billing State" value="" name="organization_billing_state">
                                                        </div>

                                                        <div class="col-6 mt-1 space">
                                                            <input type="text" class="form-control" id="billing-postal-code" placeholder="Billing Postal Code" value="" name="organization_billing_postal_code">
                                                        </div>


                                                        <div class="col-6 mt-1 space">
                                                            <select name="organization_billing_country" id="" class="form form-select">
                                                                <option value="">Select country</option>
                                                                @foreach ($countries as $country)
                                                                <option value="{{ $country['name'] }}">
                                                                    {{ $country['name'] }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>




                                        </div>
                                    </div>
                                </div>


                                {{-- Organizaiton Description --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-description" aria-expanded="false" aria-controls="panelsStayOpen-description">
                                            DESCRIPTION INFORMATION
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-description" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingThree">
                                        <div class="accordion-body">
                                            <textarea name="organization_description" id="" cols="30" rows="3" class="form form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark new-organization-btn">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('script-page')
<script>
    $('.filter-btn-show').click(function() {
        $("#filter-show").toggle();
    });

    $(document).on("click", ".textareaClass", function() {
        $('#textareaID, .textareaClass').toggle("slide");
    })

    $(document).on("submit", '#create-notes', function(event) {
        event.preventDefault(); // Prevents the default form submission
        $('#textareaID, .textareaClass').toggle("slide");
    });

    $(document).on("change", "#countries", function(e) {
        var selectedOption = $(this).children('option:selected');
        var id = selectedOption.val().split('-').pop().trim(); // Removed extra parenthesis
        $.ajax({
            type: 'GET',
            url: '{{ url('getCitiesOnCode') }}',
            data: {
                code: id
            },
            success: function(data) {
                if (data.status === 'success') {
                    $('#Cities_divs').html(data.html);
                    $('#Cities_divs_create').html(data.html);
                    $('#Cities_divs_update').html(data.html);
                    select2();
                }
            }
        });
    });
    $(document).on("submit", "#create-notes", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var id = $('#org_id').val();

        $(".create-notes-btn").val('Processing...');
        $('.create-notes-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "/organization/" + id + "/notes",
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    $('.note-tbody').html(data.html);
                    $('#note_id').val('');
                    $('#description').val('');

                    // openNav(data.lead.id);
                    // return false;
                } else {
                    show_toastr('Error', data.message, 'error');
                    $(".create-notes-btn").val('Create');
                    $('.create-notes-btn').removeAttr('disabled');
                }
            }
        });
    })

    $(document).on("click", '#cancelNote', function() {
        $('textarea[name="description"]').val('');
        $('#note_id').val('');
        $('#textareaID, .textareaClass').toggle("slide");
    });


    $(document).on('click', '.textareaClassedit', function() {
        var dataId = $(this).data('note-id');
        var dataNote = $(this).data('note');
        $('textarea[name="description"]').val(dataNote);
        $('#note_id').val(dataId);
        $('#textareaID, #dellhover, .textareaClass').show();
        $('.textareaClass').toggle("slide");
    });



    $(document).on("submit", "#organization-creating-form", function(e) {

        e.preventDefault();
        var formData = $(this).serialize();

        $(".new-new-organization-btn").val('Processing...');
        $('.new-new-organization-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "{{ route('agency.store') }}",
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $("#commonModal").modal('hide');
                    // $("#commonModal").removeClass('show');
                    openSidebar('/get-agency-detail?id=' + data.org);
                    
                    return false;
                } else {
                    show_toastr('Error', data.message, 'error');
                    $(".new-organization-btn").val('Create');
                    $('.new-organization-btn').removeAttr('disabled');
                }
            }
        });
    });

    $(document).on("submit", "#organization-updating-form", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var id = $("#org_id").val();
        $(".update-lead-btn").val('Processing...');
        $('.update-lead-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "{{ route('agency.update', '') }}/" + id,
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $("#commonModal").modal('hide');
                    openSidebar('/get-agency-detail?id=' + data.org);
                    return false;
                } else {
                    show_toastr('Error', data.message, 'error');
                    $(".new-lead-btn").val('Create');
                    $('.new-lead-btn').removeAttr('disabled');
                }
            }
        });
    });


    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
    function openNav(org_id) {
        var ww = $(window).width()

        $.ajax({
            type: 'GET',
            url: "{{ route('get-organization-detail') }}",
            data: {
                org_id: org_id
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

        $("#modal-discussion-add").attr('data-org-id', org_id);
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

    ///////////////////////////////////////Field Edit

    // $(document).on("click", ".edit-input", function() {
    //     var value = $(this).val();
    //     var name = $(this).attr('name');
    //     var id = $(".org-id").val();
    //     //var org_did = $(".org_did").val();

    //     $.ajax({
    //         type: 'GET',
    //         url: "/organization/get-field/" + id,
    //         data: {
    //             name,
    //             id
    //         },
    //         success: function(data) {
    //             data = JSON.parse(data);
    //             if (data.status == 'success') {
    //                 $('.' + name + '-td').html(data.html);
    //             }
    //         }
    //     })

    // })


    // $(document).on("click", ".edit-btn-data", function() {
    //     var name = $(this).attr('data-name');
    //     var id = $(".org-id").val();
    //     var value = $('.' + name).val();


    //     $.ajax({
    //         type: 'GET',
    //         url: "organization/" + id + "/update-data",
    //         data: {
    //             value: value,
    //             name: name,
    //             id: id
    //         },
    //         success: function(data) {
    //             data = JSON.parse(data);

    //             if (data.status == 'success') {
    //                 show_toastr('Success', data.message, 'msg');
    //                 $('.' + name + '-td').html(data.html);
    //             }
    //         }
    //     })

    // });


    $(document).on("click", ".edit-lead-remove", function() {

        var name = $(this).attr('data-name');

        var value = '';
        if (name == 'organization_id') {
            value = $('.' + name).val();
            alert(value);
        } else if (name == 'sourcess') {
            value = $('.' + name).val();
        } else {
            value = $('.' + name).val();
        }




        var html = '<div class="d-flex align-items-baseline edit-input-field-div">' +
            '<div class="input-group border-0 d-flex align-items-baseline ' + name + '">' +
            value +
            '</div>' +
            '<div class="edit-btn-div">' +
            '<button class="btn btn-sm btn-secondary edit-input rounded-0 btn-effect-none ' + name +
            '" name="' + name + '"><i class="ti ti-pencil"></i></button>' +
            '</div>' +
            '</div>';

        $('.' + name + '-td').html(html);
    })




    $(document).on("click", ".edit-btn-address", function() {

        var id = $(".org-id").val();

        $.ajax({
            type: 'GET',
            url: "/organization/get-address/" + id,
            data: {
                id
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    $('.address-td').html(data.html);
                }
            }
        })

    })


    $(document).on("click", ".edit-btn-save-address", function() {

        var id = $(".org-id").val();
        var street = $(".billing_street").val();
        var city = $(".billing_city").val();
        var state = $(".billing_state").val();
        var postal_code = $(".billing_postal_code").val();
        var country = $(".billing_country").val();

        $.ajax({
            type: 'GET',
            url: "/organization/save-address/" + id,
            data: {
                id,
                street,
                city,
                state,
                postal_code,
                country
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('.address-td').html(data.html);
                }
            }
        })

    })

    $(document).ready(function() {
        let curr_url = window.location.href;

if(curr_url.includes('?')){
    $('#save-filter-btn').css('display','inline-block');
}
        // Attach an event listener to the input field
        $('.list-global-search').keypress(function(e) {
            // Check if the pressed key is Enter (key code 13)
            if (e.which === 13) {
                var search = $(".list-global-search").val();
                var ajaxCall = 'true';

                // if (search.trim() == '') {
                //     return false;
                // }


                $(".organization_tbody").html('Loading...');

                $.ajax({
                    type: 'GET',
                    url: "{{route('agency.index')}}",
                    data: {
                        search: search,
                        ajaxCall: ajaxCall
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status == 'success') {
                            console.log(data.html);
                            $(".organization_tbody").html(data.html);
                            $(".pagination_div").html(data.pagination_html);
                        }
                    }
                })
            }
        });
    });

    $(".refresh-list").on("click", function() {
        var ajaxCall = 'true';
        $(".organization_tbody").html('Loading...');

        $.ajax({
            type: 'GET',
            url: "{{ route('organization.index') }}",
            data: {
                ajaxCall: ajaxCall
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    $(".organization_tbody").html('');
                    $(".organization_tbody").html(data.html);
                    $(".pagination_div").html(data.pagination_html);
                }
            }
        });
    })

    $(document).on("change", ".assigned_to", function() {

        var val = $(this).val();
        var userType = <?= json_encode($user_type) ?>;

        if (userType[val] == 'company' || userType[val] == 'team') {
            $(".assigned_to_type").removeClass('d-none');
        } else {
            $(".assigned_to_type").addClass('d-none');
        }
    })
    // main check
    $(document).on('change', '.main-check', function() {
    $(".sub-check").prop('checked', $(this).prop('checked'));

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
    //    single check 
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
        let commaSeperated = selectedArr.join(",");
        $("#lead_ids").val(commaSeperated);

    });
    // by check delete bulk code

    $("#actions_div").on("click", function() {
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
                window.location.href = '/delete-bulk-agency?ids=' + selectedIds.join(',');
            }
        });
    });
</script>
@endpush
