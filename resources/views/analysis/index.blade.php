@extends('layouts.admin')
@section('page-title')
    {{ __('Analysis Report') }}
@endsection
@php
    $label = '';

    if (isset($_GET['type']) && $_GET['type'] == 'visas') {
        $label = 'Visas';
    } elseif (isset($_GET['type']) && $_GET['type'] == 'deposite') {
        $label = 'Deposit';
    } elseif (isset($_GET['type']) && $_GET['type'] == 'applications') {
        $label = 'Applications';
    } else {
        $label = 'Admissions';
    }
@endphp


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Analysis report') }}</li>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

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

    .form-control:focus {
        border: 1px solid rgb(209, 209, 209) !important;
    }

    .recoredtext {
        background-color: white !important;
    }

    .recoredtext span {

        color: blue !important;
    }

    .dropdown-menu:focus {
        box-shadow: none;
        outline: none
    }

    .select2-container--default .select2-selection--single {
        background-color: black;
        color: white;
        /* Set text color to ensure visibility on the dark background */
    }

    .active {
        background-color: #B3CDE1;
        border-radius: 5%;
    }
</style>


@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!-- Tabs -->
            <div class="container">
                <div class="row justify-content-center align-items-center my-4">
                    <!-- Visas Analysis Tab -->
                    <a class="col-3 py-5 text-decoration-none{{ !isset($_GET['type']) || empty($_GET['type'])  || (isset($_GET['type']) && $_GET['type'] == 'visas') ? ' active' : '' }}"
                        href="/analysis?type=visas">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-auto d-flex flex-column gap-2 justify-content-center align-items-center">
                                <div style="border: 2px solid black; border-radius: 50%;text-align: center;width: 25px;height: 25px;"
                                    class="p-1 mb-1">
                                    <i class="fa-solid fa-dollar-sign" style="color:black"></i>
                                </div>
                                <h6 class="fw-bold">Visas Analysis</h6>
                            </div>
                        </div>
                    </a>

                    <!-- Deposits Analysis Tab -->
                    <a class="col-3 py-5 text-decoration-none{{ isset($_GET['type']) && $_GET['type'] == 'deposite' ? ' active' : '' }}"
                        href="/analysis?type=deposite">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-auto d-flex flex-column gap-2 justify-content-center align-items-center">
                                <div style="border: 2px solid black; border-radius: 50%;text-align: center;width: 25px;height: 25px;"
                                    class="p-1 mb-1">
                                    <i class="fa-solid fa-dollar-sign" style="color:black"></i>
                                </div>
                                <h6 class="fw-bold">Deposits Analysis</h6>
                            </div>
                        </div>
                    </a>

                    <!-- Application Analysis Tab -->
                    <a class="col-3 py-5 text-decoration-none{{ isset($_GET['type']) && $_GET['type'] == 'applications' ? ' active' : '' }}"
                        href="/analysis?type=applications">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-auto d-flex flex-column gap-2 justify-content-center align-items-center">
                                <div style="border: 2px solid black; border-radius: 50%;text-align: center;width: 25px;height: 25px;"
                                    class="p-1 mb-1">
                                    <i class="fa-solid fa-dollar-sign" style="color:black"></i>
                                </div>
                                <h6 class="fw-bold">Application Analysis</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>




            <!-- Filters line -->
            <form action="">
                <div class="d-flex justify-content-center align-items-center py-2" style="background-color: #1F2735;">
                    <div class="col-md-1 text-center text-white">
                        <p class="mb-0">Total {{ $label }}</p>
                        <h4 class="text-white">{{ number_format($top_sum, 2) }}</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('brands', 'Brand') !!}
                            {!! Form::select('brand_id', $filters['brands'], $_GET['brand_id'] ?? '', [
                                'id' => 'brand_id',
                                'class' => 'form form-control select2 text-dark',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group" id="region_div">
                            {!! Form::label('regions', 'Regions') !!}
                            {!! Form::select('region_id', $filters['regions'], $_GET['region_id'] ?? null, [
                                'id' => 'region_id',
                                'class' => 'form form-control select2 text-dark',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group" id="branch_div">
                            {!! Form::label('branch_id', 'Branches') !!}
                            {!! Form::select('branch_id', $filters['branches'], $_GET['branch_id'] ?? null, [
                                'id' => 'branch_id',
                                'class' => 'form form-control select2 text-dark',
                            ]) !!}
                        </div>
                    </div>



                    <div class="col-md-1">
                        <input type="submit" value="Submit" class="btn btn-lg btn-dark" style="margin-top: 10px;">
                        <input type="hidden" class="" name="type" value="{{ $_GET['type'] ?? '' }}">
                    </div>
                </div>
            </form>


            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-body table-border-style" style="padding: 25px 3px;">
                            <div class="row align-items-center justify-content-center ps-0 ms-0 pe-4 my-2">
                                <div class="col-12">
                                    <h5>{{ $label }} Analysis</h5>
                                    <canvas id="admissionAnaylysis" style="width:100%;max-width:100%"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 d-none">
                    <div class="card mt-3">
                        <div class="card-body table-border-style" style="padding: 25px 3px;">
                            <div class="row align-items-center justify-content-center ps-0 ms-0 pe-4 my-2">
                                <div class="col-12">
                                    <h5>Application Analysis</h5>
                                    <canvas id="GrantedByUniversty" style="width:100%;max-width:600px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-page')
    <script>
        //const brands = {!! json_encode(array_values($brands)) !!};
        const admissions = <?= $total_admissions ?>;
        var brands = [];
        var dealsData = []; // Rename to differentiate from the previous data variable
        var outer = 0;

        console.log(admissions);

        // Iterate over admissions.top if it's an array, else use Object.values(admissions.top)
        Object.values(admissions.top).forEach(function(item, index) {
            // if(item.total_deals)
            // continue;

            brands.push(item.name);
            dealsData.push(item.total_deals);
        });

        // Check if "other" category exists in admissions object
        if (admissions.other) {
            brands.push('Others');
            dealsData.push(admissions.other);
        }

        const chartData = {
            labels: brands,
            datasets: [{
                type: 'bar',
                label: '',
                data: dealsData, // Use the renamed variable here
                borderColor: '#B3CDE1',
                backgroundColor: '#B3CDE1'
            }]
        };

        var ctx = document.getElementById('admissionAnaylysis').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: chartData // Use the renamed variable here
        });


        ///////////////for university applications
        /*const data_university = {
            labels: institutes,
            datasets: [{
                type: 'bar',
                label: 'Applications',
                data: total_app,
                borderColor: '#B3CDE1',
                backgroundColor: '#B3CDE1'
            }]
        };

        var ctx = document.getElementById('GrantedByUniversty').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data_university
        });*/
    </script>


    <script>
        $(document).ready(function() {
            $(document).on("change", "#brand_id", function() {
                var id = $(this).val();
                var type = 'brand';

                $.ajax({
                    type: 'GET',
                    url: '{{ route('region_brands') }}',
                    data: {
                        id: id, // Add a key for the id parameter
                        type: type
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status === 'success') {
                            $('#region_div').html('');
                            $("#region_div").html(data.regions);
                            select2();
                        } else {
                            console.error('Server returned an error:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                    }
                });
            })

            //for region
            $(document).on("change", "#region_id", function() {
                var id = $(this).val();
                var type = 'region';
                $.ajax({
                    type: 'GET',
                    url: '{{ route('region_brands') }}',
                    data: {
                        id: id, // Add a key for the id parameter
                        type: type
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status === 'success') {
                            if (type == 'region') {
                                $('#branch_div').html('');
                                $("#branch_div").html(data.branches);
                                select2();
                            }
                        } else {
                            console.error('Server returned an error:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                    }
                });
            });



            //for university intake months
            $(document).on("change", "#institute_id", function() {
                var id = $(this).val();
                var type = 'institute';
                $.ajax({
                    type: 'GET',
                    url: '{{ route('region_brands') }}',
                    data: {
                        id: id, // Add a key for the id parameter
                        type: type
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status === 'success') {
                            if (type == 'institute') {
                                $('#intakemonth_div').html('');
                                $("#intakemonth_div").html(data.insitute);
                                select2();
                            }
                        } else {
                            console.error('Server returned an error:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', status, error);
                    }
                });
            })
        })
    </script>
@endpush
