@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Toolkit') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item "><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Toolkit') }}</li>
@endsection

@section('content')
    <style>
        /* .red-cross {
                position: absolute;
                top: 5px;
                right: 5px;
                color: red;
            } */
        .boximg {
            margin: auto
        }
    </style>


    <div class="row">
        <div class="col-xl-12">
            <div class="card my-card">
                <div class="card-body table-border-style" style="">
                    <?php $i = 0; ?>
                    <div class="row justify-evenly">
                        @forelse($statuses as $key => $status)
                            @php
                                $countryFound = false;
                            @endphp

                            @foreach (App\Models\University::all() as $university)
                                @if ($university->country == $key && !$countryFound)
                                    @if ($i <= 4)
                                        <?php $i++; ?> <div class="col-xl-2 col-md-6 ">
                                            <div class="card shadow py-2"
                                                style="width: 100%; height: 70%;border-radius: 22px;">
                                                <div class="card-body"
                                                    style="display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;">
                                                    {{-- <span class="red-cross"><i class="fa-solid fa-circle-xmark"></i></span> --}}
                                                    <img src="{{ asset('assets/svg/country-' . $university->country_code . '.svg') }}"
                                                        alt="{{ $key }}" width="90" height="75"
                                                        class="boximg">


                                                    <div class="row  text-center">
                                                        <div class="col mt-2 ">
                                                            <div class="h5 mb-0 text-gray-800">{{ $status }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            if ($i < 5) {
                                                echo '<div style="border-left: 3px solid black; height: 80px; width: 5px;margin-top: 1.9rem;margin-left: 2rem"></div>';
                                            }
                                        $countryFound = true; @endphp
                                    @endif
                                @endif
                            @endforeach @empty
                        @endforelse
                    </div>

                    <style>
                        .form-control:focus {
                            border: 1px solid rgb(209, 209, 209) !important;
                        }
                    </style>

                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-2">
                            <p class="mb-0 pb-0 ps-1">Institutes</p>
                            <div class="dropdown">
                                <button class="All-leads" type="button">
                                    ALL Institutes
                                </button>
                            </div>
                        </div>

                        <div class="col-10 d-flex justify-content-end gap-2">
                            <div class="input-group w-25">
                                <button class="btn btn-sm list-global-search-btn">
                                    <span class="input-group-text bg-transparent border-0  px-0 py-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 15px"></i>
                                    </span>
                                </button>
                                <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search"
                                    placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>


                            <button class="btn filter-btn-show p-2 btn-dark" style="color:white;" type="button"
                            data-bs-toggle="tooltip" title="{{__('Filter')}}">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>

                            <button class="btn p-2 refresh-list btn-dark" data-bs-toggle="tooltip" title="{{__('Refresh')}}" onclick="RefreshList()"><i class="ti ti-refresh"
                                style="font-size: 18px"></i></button>
                            @can('create university')
                                <button data-size="lg" data-url="{{ route('university.create') }}"
                                    data-ajax-popup="true" data-bs-toggle="tooltip" data-size="lg"
                                    title="{{ __('Create University') }}" class="btn  btn-dark p-2">
                                    <i class="ti ti-plus"></i>
                                </button>
                            @endcan

                        </div>
                    </div>

                    {{-- Filters --}}
                    <div class="filter-data px-3" id="filter-show"
                        <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                        <form action="/university" method="GET" class="">
                            <div class="row my-3">
                                <div class="col-md-4 mt-2">
                                    <label for="">Name</label>
                                    <input type="text" class="form form-control" placeholder="Search Name" name="name"
                                        value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>"
                                        style="width: 95%; border-color:#aaa">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="">Country</label>
                                    <input type="text" class="form form-control" placeholder="Search Country"
                                        name="country" value="<?= isset($_GET['country']) ? $_GET['country'] : '' ?>"
                                        style="width: 95%; border-color:#aaa">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="">City</label>
                                    <input type="text" class="form form-control" placeholder="Search City" name="city"
                                        value="<?= isset($_GET['city']) ? $_GET['city'] : '' ?>"
                                        style="width: 95%; border-color:#aaa">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="">Phone</label>
                                    <input type="text" class="form form-control" placeholder="Search Phone"
                                        name="phone" value="<?= isset($_GET['phone']) ? $_GET['phone'] : '' ?>"
                                        style="width: 95%; border-color:#aaa">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="">Note</label>
                                    <input type="text" class="form form-control" placeholder="Search Note"
                                        name="note" value="<?= isset($_GET['note']) ? $_GET['note'] : '' ?>"
                                        style="width: 95%; border-color:#aaa">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="">Note</label>
                                    <select name="created_by" class="form form-control">
                                        @if (!empty($users))
                                            @foreach ($users as $key => $user)
                                                <option value="{{ $key }}"
                                                    <?= isset($_GET['created_by']) && $_GET['created_by'] == $key ? 'selected' : '' ?>>
                                                    {{ $user }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <br>
                                    <input type="submit" class="btn me-2 bg-dark" style=" color:white;">
                                    <a href="/university" class="btn bg-dark" style="color:white;">Reset</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="enries_per_page" style="max-width: 300px; display: flex;">

                                    <?php
                                    $all_params = isset($_GET) ? $_GET : '';
                                    if (isset($all_params['num_results_on_page'])) {
                                        unset($all_params['num_results_on_page']);
                                    }
                                    ?>
                                    <input type="hidden" value="<?= http_build_query($all_params) ?>"
                                        class="url_params">
                                    <select name="" id="" class="enteries_per_page form form-control"
                                        style="width: 100px; margin-right: 1rem;">
                                        <option
                                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 25 ? 'selected' : '' ?>
                                            value="25">25</option>
                                        <option
                                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 100 ? 'selected' : '' ?>
                                            value="100">100</option>
                                        <option
                                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 300 ? 'selected' : '' ?>
                                            value="300">300</option>
                                        <option
                                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 1000 ? 'selected' : '' ?>
                                            value="1000">1000</option>
                                        <option
                                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == $total_records ? 'selected' : '' ?>
                                            value="{{ $total_records }}">all</option>
                                    </select>

                                    <span style="margin-top: 5px;">entries per page</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('#') }}</th>
                                    <th scope="col">{{ __('Institutes') }}</th>

                                    <th scope="col">{{ __('Campuses') }}</th>
                                    <th scope="col">{{ __('Intake') }}</th>
                                    <th scope="col">{{ __('Territory') }}</th>
                                    <th scope="col">{{ __('Company') }}</th>
                                    <th scope="col">{{ __('Resources') }}</th>
                                    <th scope="col">{{ __('Application Method') }}</th>
                                    @if (\Auth::user()->type == 'super admin')
                                        <th scope="col">{{ __('Created By') }}</th>
                                    @endif

                                    @if (\Auth::user()->type != 'super admin')
                                        <th scope="col" class="d-none">{{ __('Action') }}</th>
                                    @endif



                                </tr>
                            </thead>
                            <tbody id="">
                                @foreach ($universities as $key => $university)
                                    <tr class="font-style">
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            @if (!empty($university->name))
                                                <span style="cursor:pointer" class="hyper-link"
                                                    @can('show university') onclick="openSidebar('/university/'+{{ $university->id }}+'/university_detail')" @endcan >
                                                    {{ !empty($university->name) ? $university->name : '' }}
                                                </span>
                                            @endif

                                        </td>
                                        {{-- <td>{{ !empty($university->Institutes) ? $university->Institutes: '' }}</td> --}}
                                        <td>{{ !empty($university->campuses) ? $university->campuses : '' }}</td>
                                        <td>{{ !empty($university->intake_months) ? $university->intake_months : '' }}</td>
                                        <td>{{ !empty($university->territory) ? $university->territory : '' }}</td>
                                        <td>{{   $users[$university->company_id]  ?? ''  }}</td>
                                        <td>
                                        <a href="{{ !empty($university->resource_drive_link) ? $university->resource_drive_link : '' }}">
                                        Click to view
                                        </a>
                                        </td>
                                        <td>
                                            <a href="{{ !empty($university->application_method_drive_link) ? $university->application_method_drive_link : '' }}">
                                                {{ !empty($university->name) ? $university->name : '' }}
                                            </a>
                                        </td>

                                        @if (\Auth::user()->type == 'super admin')
                                            <td>{{ isset($users[$university->created_by]) ? $users[$university->created_by] : '' }}
                                            </td>
                                        @endif

                                        @if (\Auth::user()->type != 'super admin')
                                            <td class="action d-none">
                                                @can('edit university')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-url="{{ route('university.edit', $university->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}"
                                                            data-title="{{ __('Edit University') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete university')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['university.destroy', $university->id]]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                class="ti ti-trash text-white"></i></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        @if ($total_records > 0)
                            @include('layouts.pagination', [
                                'total_pages' => $total_records,
                                'num_results_on_page' => 50,
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-page')
    <script>
        $('.filter-btn-show').click(function() {
            $("#filter-show").toggle();
        });

        function RefreshList() {
            var ajaxCall = 'true';
            $("#").html('Loading...');

            $.ajax({
                type: 'GET',
                url: "/university",
                data: {
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == 'success') {
                        $("#").html('');
                        $('#universtyDivs').prepend(data.html);
                    }
                },
            });
        }
    </script>
@endpush
