@extends('layouts.admin')
@section('page-title')
    {{__('Manage Indicator')}}
@endsection
@push('css-page')
    <style>
        @import url({{ asset('css/font-awesome.css') }});
    </style>
@endpush
@push('script-page')
    <script src="{{ asset('js/bootstrap-toggle.js') }}"></script>
    <script>

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
            $("fieldset[id^='demo'] .stars").click(function () {
                $(this).attr("checked");
            });
        });


        $(document).ready(function () {
            var d_id = $('#department_id').val();
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department]', function () {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {
            $.ajax({
                url: '{{route('employee.json')}}',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">{{__('Select Designation')}}</option>');
                    $.each(data, function (key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Indicator')}}</li>
@endsection
<style>
    .action-btn {
        display: inline-grid !important;
    }

    .dataTable-bottom,
    .dataTable-top {
        display: none;
    }

    .accordion-button {
        border-bottom-left-radius: 0px !important;
        border-bottoms-right-radius: 0px !important;
    }

    .card {
        box-shadow: none !important;
    }

    .hover-text-color {
        color: #1F2735 !important;
    }


    .lead-info-cell {
        max-width: 110px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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

<style>
    .form-controls,
    .form-btn {
        padding: 4px 1rem !important;
    }

    .action-btn {
        display: inline-grid !important;
    }

    .dataTable-bottom,
    .dataTable-top {
        display: none;
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
</style>
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body table-border-style">
                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-4 d-flex">
                        <span>
                            <p class="mb-0 pb-0 ps-1">Leads</p>
                            <div class="dropdown">
                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    ALL Trainer
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @if (sizeof($saved_filters) > 0)
                                        @foreach ($saved_filters as $filter)
                                            <li class="d-flex align-items-center justify-content-between ps-2">
                                                <div class="col-10">
                                                    <a href="{{ $filter->url }}"
                                                        class="text-capitalize fw-bold text-dark">{{ $filter->filter_name }}</a>
                                                    <span class="text-dark"> ({{ $filter->count }})</span>
                                                </div>
                                                <ul class="w-25" style="list-style: none;">
                                                    <li class="fil fw-bolder">
                                                        <i class=" fa-solid fa-ellipsis-vertical"
                                                            style="color: #000000;"></i>
                                                        <ul class="submenu"
                                                            style="border: 1px solid #e9e9e9;
                                                                    box-shadow: 0px 0px 1px #e9e9e9;">
                                                            <li><a class="dropdown-item" href="#"
                                                                    onClick="editFilter('<?= $filter->filter_name ?>', <?= $filter->id ?>)">Rename</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    onclick="deleteFilter('{{ $filter->id }}')"
                                                                    href="#">Delete</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>

                                            </li>
                                        @endforeach
                                    @else
                                        <li class="d-flex align-items-center justify-content-center ps-2">
                                            No Filter Found
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </span>


                        <span class="ml-3">
                            <p class="mb-0 pb-0 ps-1">Limit</p>
                            <form action="{{ url('leads/list') }}" method="GET" id="paginationForm">
                                <input type="hidden" name="num_results_on_page" id="num_results_on_page"
                                    value="{{ $_GET['num_results_on_page'] ?? '' }}">
                                {{-- <input type="hidden" name="page" id="page" value="{{ $_GET['page'] ?? 1 }}"> --}}
                                <input type="hidden" name="page" id="page" value="1">
                                <select name="perPage" onchange="submitForm()"
                                    style="width: 100px; margin-right: 1rem;border: 1px solid lightgray;border-radius: 1px;padding: 2.5px 5px;">
                                    <option value="50"
                                        {{ Request::get('perPage') == 50 || Request::get('num_results_on_page') == 50 ? 'selected' : '' }}>
                                        50</option>
                                    <option value="100"
                                        {{ Request::get('perPage') == 100 || Request::get('num_results_on_page') == 100 ? 'selected' : '' }}>
                                        100</option>
                                    <option value="150"
                                        {{ Request::get('perPage') == 150 || Request::get('num_results_on_page') == 150 ? 'selected' : '' }}>
                                        150</option>
                                    <option value="200"
                                        {{ Request::get('perPage') == 200 || Request::get('num_results_on_page') == 200 ? 'selected' : '' }}>
                                        200</option>
                                </select>
                            </form>

                            <script>
                                function submitForm() {
                                    var selectValue = document.querySelector('select[name="perPage"]').value;
                                    document.getElementById("num_results_on_page").value = selectValue;
                                    // document.getElementById("page").value = {{ $_GET['page'] ?? 1 }};
                                    document.getElementById("page").value = 1;
                                    document.getElementById("paginationForm").submit();
                                }
                            </script>
                        </span>
                    </div>

                    <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                        <div class="input-group w-25 rounded" style= "width:36px; height: 36px; margin-top:10px;">
                            <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search"
                                class="form-control border-0 bg-transparent p-0 pb-2 list-global-search"
                                placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>



                        <button id="filter-btn-show" class="btn filter-btn-show p-2 btn-dark" type="button"
                            data-bs-toggle="tooltip" title="{{ __('Filter') }}" aria-expanded="false"
                            style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>


                        @can('create indicator')
                        <a href="#" data-size="lg" data-url="{{ route('indicator.create') }}"
                            data-ajax-popup="true"
                            data-bs-toggle="tooltip" title="{{__('Create')}}" style="color:white; width:36px; height: 36px; margin-top:10px;" data-title="{{__('Create New Indicator')}}"
                            class="btn px-2 btn-dark">
                            <i class="ti ti-plus"></i>
                         </a>
                         @endcan
                    </div>
                </div>
                @include('training.list_filter')

                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Branch')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Overall Rating')}}</th>
                                <th>{{__('Added By')}}</th>
                                <th>{{__('Created At')}}</th>
                                @if( Gate::check('edit indicator') ||Gate::check('delete indicator') ||Gate::check('show indicator'))
                                    <th width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">


                            @foreach ($indicators as $indicator)

                                @php
                                    if(!empty($indicator->rating)){
                                        $rating = json_decode($indicator->rating,true);
                                        if(!empty($rating)){
                                            $starsum = array_sum($rating);
                                            $overallrating = $starsum/count($rating);
                                        }else{
                                                $overallrating = 0;
                                        }

                                    }
                                    else{
                                        $overallrating = 0;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ !empty($indicator->branches)?$indicator->branches->name:'' }}</td>
                                    <td>{{ !empty($indicator->departments)?$indicator->departments->name:'' }}</td>
                                    <td>{{ !empty($indicator->designations)?$indicator->designations->name:'' }}</td>
                                    <td>

                                        @for($i=1; $i<=5; $i++)
                                            @if($overallrating < $i)
                                                @if(is_float($overallrating) && (round($overallrating) == $i))
                                                    <i class="text-warning fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="fas fa-star"></i>
                                                @endif
                                            @else
                                                <i class="text-warning fas fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="theme-text-color">({{number_format($overallrating,1)}})</span>
                                    </td>


                                    <td>{{ !empty($indicator->user)?$indicator->user->name:'' }}</td>
                                    <td>{{ \Auth::user()->dateFormat($indicator->created_at) }}</td>
                                    @if( Gate::check('edit indicator') ||Gate::check('delete indicator') || Gate::check('show indicator'))
                                        <td>
                                            @can('show indicator')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-url="{{ route('indicator.show',$indicator->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Indicator Detail')}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('View')}}" data-original-title="{{__('View Detail')}}">
                                                    <i class="ti ti-eye text-white"></i></a>
                                            </div>
                                            @endcan
                                            @can('edit indicator')
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" data-url="{{ route('indicator.edit',$indicator->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Indicator')}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i></a>
                                            </div>
                                                @endcan
                                            @can('delete indicator')
                                            <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['indicator.destroy', $indicator->id],'id'=>'delete-form-'.$indicator->id]) !!}

                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$indicator->id}}').submit();">
                                                <i class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                                </div>
                                            @endcan
                                        </td>
                                    @endif
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
