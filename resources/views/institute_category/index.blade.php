@extends('layouts.admin')
@section('page-title')
    {{ __('Manage university') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Institute Category') }}</li>
@endsection


@section('content')
    <div class="row">

        <div class="col-3">
            @include('layouts.crm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body table-border-style">

                    {{-- <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-2">
                            <p class="mb-0 pb-0">Institute Categories</p>
                            <div class="dropdown">
                                <button class="All-leads" type="button">
                                    ALL Institute Categories
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


                            <a href="#" data-size="md" data-url="{{ route('institute-category.create') }}"
                                data-ajax-popup="true" data-bs-toggle="tooltip" data-size="lg"
                                title="{{ __('Create Institute Category') }}" class="btn btn-sm btn-primary pt-2">
                                <i class="ti ti-plus"></i>
                            </a>

                        </div>
                    </div> --}}

                    <div class="card-header" style="display: flex; justify-content: space-between;">
                        <h3>ALL Institute Categories</h3>
                        @can('create institute category')
                        <div class="float-end">
                            <a href="#" data-size="md" data-url="{{ route('institute-category.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Sources')}}" class="btn btn-sm btn-dark">
                                <i class="ti ti-plus"></i>
                            </a>
                        </div>
                        @endcan

                    </div>
                    <div class="table-responsive my-3">
                        <table class="table">

                            <thead>
                                <tr>
                                    <th scope="col">{{ __('#') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($institute_categories as $key => $category)
                                    <tr class="font-style">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td class="action ">
                                            @can('edit institute category')
                                            <div class="action-btn  ms-2">
                                                <a href="#" class="btn btn-sm btn-dark mx-1 align-items-center "
                                                    data-url="{{ route('institute-category.edit', $category->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                    title="{{ __('Edit') }}"
                                                    data-title="{{ __('Edit Institute Category') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endcan

                                            @can('delete institute category')
                                            <div class="action-btn  ms-2">

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['institute-category.destroy', $category->id]]) !!}
                                                <a href="#" class="btn btn-sm btn-danger mx-1 align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                        class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}

                                            </div>
                                            @endcan
                                        </td>
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
