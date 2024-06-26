@extends('layouts.admin')

@section('page-title')
    @if (\Auth::user()->type != 'super admin' && isset($pipeline))
        - {{ $pipeline->first()->name }}
    @else
        {{ isset($EmailM->name) ? $EmailM->name : __('Email Marketing') }}
    @endif
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Email Marketing') }}</li>
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
</style>

<style>
    .form-controls,
    .form-btn {
        padding: 4px 1rem !important;
    }

    /* Set custom width for specific table cells */
    .action-btn {
        display: inline-grid !important;
    }

    .dataTable-bottom,
    .dataTable-top {
        display: none;
    }
</style>

<style>
    /* .red-cross {
                position: absolute;
                top: 5px;
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

    .form-control:focus {
        border: none !important;
        outline: none !important;
    }

    .filbar .form-control:focus {
        border: 1px solid rgb(209, 209, 209) !important;
    }
</style>
{{-- comment --}}


{{-- comment  --}}
@push('script-page')
    <script>
        $('.filter-btn-show').click(function() {
            $("#filter-show").toggle();
        });
    </script>
@endpush




@section('content')
    @if ($pipeline)
        <div class="row">

            <div class="col-xl-12">
                <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
                    <div class="card-body table-border-style" style="padding: 25px 3px;">
                        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                            <div class="col-4 d-flex"></div>
                            <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                                <div class="input-group w-25 rounded" style= "width:36px; height: 36px; margin-top:10px;">
                                    <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                        <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                            <i class="ti ti-search" style="font-size: 18px"></i>
                                        </span>
                                    </button>
                                    <input type="Search"
                                        class="form-control border-0 bg-transparent p-0 pb-2 list-global-search"
                                        placeholder="Search this list..." aria-label="Username"
                                        aria-describedby="basic-addon1">
                                </div>

                                    <button data-size="lg" data-url="{{ route('email.marketing.inset') }}" data-ajax-popup="true"
                                        data-bs-toggle="tooltip" title="{{ __('Create New Email') }}" class="btn px-2 btn-dark"
                                        style="color:white; width:36px; height: 36px; margin-top:10px;">
                                        <i class="ti ti-plus" style="font-size:18px"></i>
                                    </button>


                                    <button data-size="lg" data-bs-toggle="tooltip" title="{{ __('Import Csv Emails') }}"
                                        class="btn px-2 btn-dark" id="import_csv_modal_btn" data-bs-toggle="modal"
                                        data-bs-target="#import_csv"
                                        style="color:white; width:36px; height: 36px; margin-top:10px;">
                                        <i class="fa fa-file-csv"></i>
                                    </button>




                            </div>
                        </div>




                        <div class="card-body table-responsive" style="padding: 25px 3px; width:auto;">
                            <table class="table " data-resizable-columns-id="lead-table" id="tfont">
                                <thead>
                                    <tr>
                                        <th style="width: 50px !important;">
                                            <input type="checkbox" class="main-check">
                                        </th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Tag') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="leads-list-tbody leads-list-div">
                                    @if (!empty($EmailMarketings) && count($EmailMarketings) > 0)
                                        @foreach ($EmailMarketings as $EmailM)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="leads[]" value="{{ $EmailM->id }}"
                                                        data-brand-id="{{ $EmailM->brand_id }}"
                                                        data-brand-name="{{ $filters['brands'][$EmailM->brand_id] ?? '' }}"
                                                        class="sub-check">
                                                </td>
                                                <td class="lead-info-cell">
                                                    <span style="cursor:pointer" class="lead-name hyper-link"
                                                        @can('view lead') onclick="openSidebar('/email/marketing/show?id=<?= $EmailM->id ?>')" @endcan
                                                        data-lead-id="{{ $EmailM->id }}">{{ $EmailM->name }}</span>
                                                </td>
                                                <td class="lead-info-cell">
                                                    <a href="{{ $users_with_roles[$EmailM->type] }}">{{ $users_with_roles[$EmailM->type] }}</a>
                                                </td>

                                                <td class="lead-info-cell">
                                                    {{ $users[$EmailM->created_by] }}
                                                </td>

                                                <td class="lead-info-cell">
                                                    {{ !empty($EmailM->tag) ? $EmailM->tag : '-' }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                    <tr class="font-style">
                                        <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
                                    </tr>
                                    @endif
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
                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection

@push('script-page')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script></script>
@endpush
