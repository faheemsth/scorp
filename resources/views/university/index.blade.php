@extends('layouts.admin')
@section('page-title')
    {{ __('Manage university') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('University') }}</li>
@endsection

@section('content')
    <style>
        .red-cross {
            position: absolute;
            top: 5px;
            right: 5px;
            color: red;
        }

        .text-dark {
            color: #000;
            font-weight: 950;
            border: none !important;
            margin-top: 4%;

        }

        .boximg {
            margin-left: 22px;
        }
    </style>


    <div class="row">
        <div class="col-xl-12">
            <div class="card my-card">
                <div class="card-body table-border-style">
                    <?php $i = 0; ?>
                    <div class="row justify-evenly">
                        @forelse($statuses as $key => $status)
                            @php
                                $countryFound = false;
                            @endphp

                        @foreach (App\Models\University::all() as $university)
                            @if($university->country == $key && !$countryFound)
                            @if($i <= 4)
                            <?php $i++;?>
                                <div class="col-xl-2 col-md-6 mb-4">
                                    <div class="card shadow py-2" style="width: 90%; height: 90%;border-radius: 22px;">
                                        <div class="card-body">
                                            <span class="red-cross"><i class="fa-solid fa-circle-xmark"></i></span>
                                            <img src="{{ asset('assets/svg/country-'.$university->country_code.'.svg') }}" alt="{{ $key }}" width="80" height="60" class="boximg">

                                            <div class="row no-gutters text-center">
                                                <div class="col mr-2">
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                                </div>
                                            </div>
                                            <div class="row no-gutters text-center">
                                                <div class="col mt-2 mr-2">
                                                    <div class="h5 mb-0 text-gray-800">{{ $status }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            if ($i < 5) {
                                                echo '<div class="mt-5" style="border-left: 3px solid black; height: 80px; width: 1%;"></div>';
                                            }
                                            $countryFound = true;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach

                        @empty
                        @endforelse
                    </div>



                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-2">
                            <p class="mb-0 pb-0">Institutes</p>
                            <div class="dropdown">
                                <button class="All-leads" type="button">
                                    ALL Institutes
                                </button>
                            </div>
                        </div>

                        <div class="col-10 d-flex justify-content-end gap-2">
                            <div class="input-group w-25">
                                <button class="btn btn-sm list-global-search-btn">
                                    <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search"
                                    placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>

                            @can('create university')
                                <a href="#" data-size="lg" data-url="{{ route('university.create') }}"
                                    data-ajax-popup="true" data-bs-toggle="tooltip" data-size="lg"
                                    title="{{ __('Create University') }}" class="btn btn-sm btn-dark pt-2">
                                    <i class="ti ti-plus"></i>
                                </a>
                            @endcan

                        </div>
                    </div>


                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('#') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>

                                    <th scope="col">{{ __('Country') }}</th>

                                    <th scope="col">{{ __('City') }}</th>
                                    <th scope="col">{{ __('Phone') }}</th>
                                    <th scope="col">{{ __('Note') }}</th>

                                    @if (\Auth::user()->type == 'super admin')
                                        <th scope="col">{{ __('Created By') }}</th>
                                    @endif

                                    @if (\Auth::user()->type != 'super admin')
                                        <th scope="col">{{ __('Action') }}</th>
                                    @endif



                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($universities as $key => $university)
                                    <tr class="font-style">
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            @if (!empty($university->name))
                                                <span style="cursor:pointer" class="hyper-link"
                                                    onclick="openSidebar('/university/'+{{ $university->id }}+'/university_detail')">
                                                    {{ !empty($university->name) ? $university->name : '' }}
                                                </span>
                                            @endif

                                        </td>
                                        <td>{{ !empty($university->country) ? $university->country : '' }}</td>
                                        <td>{{ !empty($university->city) ? $university->city : '' }}</td>
                                        <td>{{ !empty($university->phone) ? $university->phone : '' }}</td>
                                        <td>{{ !empty($university->note) ? $university->note : '' }}</td>

                                        @if (\Auth::user()->type == 'super admin')
                                            <td>{{ isset($users[$university->created_by]) ? $users[$university->created_by] : '' }}
                                            </td>
                                        @endif

                                        @if (\Auth::user()->type != 'super admin')
                                            <td class="action ">
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
                                'num_results_on_page' => 10,
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
