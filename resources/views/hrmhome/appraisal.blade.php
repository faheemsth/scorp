@extends('layouts.admin')
@section('page-title')
    {{ __('Appraisal') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Appraisal') }}</li>
@endsection
@section('content')
    <style>
        .table td,
        .table th {
            font-size: 14px;
        }
    </style>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .list-group-item.active {
            background-color: #007bff;
            border-color: #007bff;
        }

        .list-group-item:hover {
            background-color: #f1f1f1;
        }

        .sticky-top {
            top: 30px;
        }
    </style>
    <div class="row">
        <div class="col-3">
            @include('hrmhome.hrm_setup_routes')
        </div>
        <div class="col-6">


            <div class="card me-3">
                <div class="card-header d-flex justify-content-between align-items-baseline">
                    <h4>Appraisal Information</h4>
                </div>
                <div class="card-body px-2">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                            aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <!-- Open Accordion Item -->
                                <div class="card me-3">
                                    <div class="card-body px-2">
                                        <div class="tab-content" id="pills-tabContent">
                                            {{-- Details Pill Start --}}
                                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                                aria-labelledby="pills-details-tab">

                                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                                    <!-- Open Accordion Item -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                                            <button class="accordion-button p-2" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#panelsStayOpen-collapseinfo">
                                                                {{ __('INDICATOR INFORMATION') }}
                                                            </button>
                                                        </h2>

                                                        <div id="panelsStayOpen-collapseinfo"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="panelsStayOpen-headinginfo">
                                                            <div class="accordion-body">

                                                                <div class="accordion-body">

                                                                    <div class="table-responsive mt-1"
                                                                        style="margin-left: 10px;">

                                                                        <table>
                                                                            <tbody>

                                                                                <tr>
                                                                                    <td class=""
                                                                                        style="width: 150px; font-size: 14px;">
                                                                                        {{ __('Brand') }}
                                                                                    </td>
                                                                                    <td class=""
                                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                                        {{ $appraisal->brand }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class=""
                                                                                        style="width: 150px; font-size: 14px;">
                                                                                        {{ __('Region') }}
                                                                                    </td>
                                                                                    <td class=""
                                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                                        {{ $appraisal->region }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class=""
                                                                                        style="width: 150px; font-size: 14px;">
                                                                                        {{ __('Branch') }}
                                                                                    </td>
                                                                                    <td class=""
                                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                                        {{ $appraisal->branch }}
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class=""
                                                                                        style="width: 150px; font-size: 14px;">
                                                                                        {{ __('Assign To') }}
                                                                                    </td>
                                                                                    <td class=""
                                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                                        {{ $appraisal->created_user }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class=""
                                                                                        style="width: 150px; font-size: 14px;">
                                                                                        {{ __('Appraisal Date') }}
                                                                                    </td>
                                                                                    <td class=""
                                                                                        style="padding-left: 10px; font-size: 14px;">
                                                                                        {{ $appraisal->appraisal_date }}
                                                                                    </td>
                                                                                </tr>


                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="table-responsive mt-1"
                                                                    style="margin-left: 10px;">

                                                                    <table>
                                                                        <tbody>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-5  text-end"
                                                                                        style="margin-left: 51px;">
                                                                                        <h5>{{ __('Indicator') }}
                                                                                        </h5>
                                                                                    </div>
                                                                                    <div class="col-4  text-end">
                                                                                        <h5>{{ __('Appraisal') }}
                                                                                        </h5>
                                                                                    </div>
                                                                                    @foreach ($performance_types as $performance_type)
                                                                                        <div class="col-md-12 mt-3">
                                                                                            <h6>{{ $performance_type->name }}
                                                                                            </h6>
                                                                                            <hr class="mt-0">
                                                                                        </div>

                                                                                        @foreach ($performance_type->types as $types)
                                                                                            <div class="col-4">
                                                                                                {{ $types->name }}
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <fieldset id='demo'
                                                                                                    class="rating">
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-5*-{{ $types->id }}"
                                                                                                        name="ratings[{{ $types->id }}]"
                                                                                                        value="5"
                                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 5 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-5*-{{ $types->id }}"
                                                                                                        title="Awesome - 5 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-4*-{{ $types->id }}"
                                                                                                        name="ratings[{{ $types->id }}]"
                                                                                                        value="4"
                                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 4 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-4*-{{ $types->id }}"
                                                                                                        title="Pretty good - 4 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-3*-{{ $types->id }}"
                                                                                                        name="ratings[{{ $types->id }}]"
                                                                                                        value="3"
                                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 3 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-3*-{{ $types->id }}"
                                                                                                        title="Meh - 3 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-2*-{{ $types->id }}"
                                                                                                        name="ratings[{{ $types->id }}]"
                                                                                                        value="2"
                                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 2 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-2*-{{ $types->id }}"
                                                                                                        title="Kinda bad - 2 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-1*-{{ $types->id }}"
                                                                                                        name="ratings[{{ $types->id }}]"
                                                                                                        value="1"
                                                                                                        {{ isset($ratings[$types->id]) && $ratings[$types->id] == 1 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-1*-{{ $types->id }}"
                                                                                                        title="Sucks big time - 1 star"></label>
                                                                                                </fieldset>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <fieldset id='demo1'
                                                                                                    class="rating">
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-5-{{ $types->id }}"
                                                                                                        name="rating[{{ $types->id }}]"
                                                                                                        value="5"
                                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 5 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-5-{{ $types->id }}"
                                                                                                        title="Awesome - 5 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-4-{{ $types->id }}"
                                                                                                        name="rating[{{ $types->id }}]"
                                                                                                        value="4"
                                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 4 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-4-{{ $types->id }}"
                                                                                                        title="Pretty good - 4 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-3-{{ $types->id }}"
                                                                                                        name="rating[{{ $types->id }}]"
                                                                                                        value="3"
                                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 3 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-3-{{ $types->id }}"
                                                                                                        title="Meh - 3 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-2-{{ $types->id }}"
                                                                                                        name="rating[{{ $types->id }}]"
                                                                                                        value="2"
                                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 2 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-2-{{ $types->id }}"
                                                                                                        title="Kinda bad - 2 stars"></label>
                                                                                                    <input class="stars"
                                                                                                        type="radio"
                                                                                                        id="technical-1-{{ $types->id }}"
                                                                                                        name="rating[{{ $types->id }}]"
                                                                                                        value="1"
                                                                                                        {{ isset($rating[$types->id]) && $rating[$types->id] == 1 ? 'checked' : '' }}
                                                                                                        disabled>
                                                                                                    <label class="full"
                                                                                                        for="technical-1-{{ $types->id }}"
                                                                                                        title="Sucks big time - 1 star"></label>
                                                                                                </fieldset>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @endforeach
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <hr>
                                                                                        <h6>{{ __('Remark') }}
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-md-12 mt-3">
                                                                                        <p class="text-sm">
                                                                                            {{ $appraisal->remark }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
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
            </div>
        </div>
        <div class="col-3">
            @include('hrmhome.hrm_setup_activity')
        </div>
    </div>
@endsection
