<?php //dd($companies_pipeline_data);
?>
@extends('layouts.admin')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@push('script-page')
    <script src="{{ asset('js/apex_chart.js') }}"></script>
    <script src="{{ asset('js/umd_chart.js') }}"></script>
    <!-- Top 3 Brands of SCORP Chart -->

    <script>
        var data = [];

        <?php foreach ($spline_chart as $series) { ?>
        var seriesData = [];
        <?php foreach ($series['data'] as $month => $count) { ?>
        seriesData.push({
            x: '<?= $month ?>',
            y: <?= $count ?>
        });
        <?php } ?>
        data.push({
            name: '<?= $series['name'] ?>',
            data: seriesData
        });
        <?php } ?>

        var categories = <?= json_encode(array_keys(current($spline_chart)['data'])) ?>;

        var options = {
            series: data,
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'category',
                categories: categories,
            },
        };

        var chart = new ApexCharts(document.querySelector("#areachart"), options);
        chart.render();
    </script>


    <script>
        var labels = [];
        var totals = [];
        <?php foreach ($area_chart as $data) { ?>
        labels.push('<?= $data->name ?>');
        totals.push(<?= $data->total ?>);
        <?php } ?>

        var data = {
            labels: labels,
            datasets: [{
                // Remove or set label to null if you want to hide the legend label

                backgroundColor: ["rgba(255,99,132)", "rgba(54, 162, 235)", "rgba(255, 206, 86)"],
                //borderColor: ["rgba(255,99,132,1)", "rgba(54, 162, 235, 1)", "rgba(255, 206, 86, 1)"],
                // borderWidth: 2,
                // hoverBackgroundColor: ["rgba(255,99,132,0.4)", "rgba(54, 162, 235, 0.4)", "rgba(255, 206, 86, 0.4)"],
                //hoverBorderColor: ["rgba(255,99,132,1)", "rgba(54, 162, 235, 1)", "rgba(255, 206, 86, 1)"],
                data: totals,
            }]
        };

        var options = {
            maintainAspectRatio: false,
            scales: {
                y: {
                    stacked: true,
                    grid: {
                        display: true,
                        color: "rgba(255,99,132,0.2)"
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };

        new Chart('chart', {
            type: 'bar',
            options: options,
            data: data
        });
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('CRM Dashboard') }}</li>
@endsection

<style>
    .card-animate:hover {
        transform: translateY(calc(-1.5rem / 5));
        box-shadow: 0 5px 10px #1e20251f;
    }

    .card-animate {
        transition: all .4s;
    }

    .chart-container {
        position: relative;
        margin: auto;
        height: 60%;

    }
</style>


@section('content')
    <div class="row">
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card" style="height: 220px;  max-height: 100%;">
                <div class="card-body pl-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/user.png') }}" alt="" style="width: 52px;">
                            </p>
                        </div>
                        <div class="flex-shrink-0">

                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <div>
                            <h1 class=""> <span class="counter-value"
                                    data-target="730000">{{ $total_admissions }}</span>
                            </h1>
                            <a href="" class="text-decoration-none text-muted">Total Number of Admissions</a>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card" style="height: 220px;  max-height: 100%;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/applications.png') }}" alt=""
                                    style="width: 52px;">
                            </p>
                        </div>
                        <div class="flex-shrink-0">

                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <div>
                            <h1><span class="counter-value" data-target="0">{{ $total_app }}</span></h1>
                            <a href="" class="text-decoration-none text-muted">Total Number of Applications</a>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card" style="height: 220px;  max-height: 100%;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/deposite.png') }}" alt="" style="width: 52px;">
                            </p>
                        </div>
                    </div>
                    <div class="mt-1">
                        <h1><span class="counter-value" data-target="1">{{ $total_deposits }}</span>
                        </h1>
                        <a href="" class="text-decoration-none text-muted">Total Number of Deposits</a>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card" style="height: 220px;  max-height: 100%;">
                <div class="card-body ">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/visa.png') }}" alt="" style="width: 52px;margin-left: -5px;">
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <div>

                            <h1><span class="counter-value" data-target="0">{{ $total_visas }}</span>
                        </h1>
                            <a href="" class="text-decoration-none text-muted">Total Number of Visas</a>

                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card" style="height: 220px;  max-height: 100%;">
                <div class="card-body ">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/visa.png') }}" alt="" style="width: 52px;margin-left: -5px;">
                            </p>
                        </div>
                    </div>
                    <div class="mt-1">

                        <h1><span class="counter-value" data-target="0">{{ $Assigned }}</span>
                        </h1>

                        <a href="" class="text-decoration-none text-muted">Total Number of Assigned Leads</a>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card" style="height: 220px;  max-height: 100%;">
                <div class="card-body ">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/visa.png') }}" alt="" style="width: 52px;margin-left: -5px;">
                            </p>
                        </div>
                    </div>
                    <div class="mt-1">
                        <h1><span class="counter-value" data-target="0">{{ $Unassigned }}</span>
                        </h1>
                        <a href="" class="text-decoration-none text-muted">Total Number of Unassigned Leads</a>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card" style="height: 220px;  max-height: 100%;">
                <div class="card-body ">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/visa.png') }}" alt="" style="width: 52px;margin-left: -5px;">
                            </p>
                        </div>
                    </div>
                    <div class="mt-1">
                        <h1><span class="counter-value" data-target="0">{{ $Qualified }}</span>
                        </h1>
                        <a href="" class="text-decoration-none text-muted">Total Number of Qualified Leads</a>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->



        <div class="col-xl-2 col-md-6">
            <!-- card -->
            <div class="card card-animate my-card " style="height: 220px;  max-height: 100%;">
                <div class="card-body ">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                <img src="{{ asset('assets/images/visa.png') }}" alt="" style="width: 52px;margin-left: -5px;">
                            </p>
                        </div>
                    </div>
                    <div class="mt-1">
                        <h1><span class="counter-value" data-target="0">{{ $Unqualified }}</span>
                        </h1>
                        <a href="" class="text-decoration-none text-muted">Total Number of Unqualified Leads</a>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>

    <div class="row justify-content-between">
        <div class="col-12 col-md-12 col-lg-9">
            <div class="card my-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="fs-6 fw-bold mb-0" style="color: #000000; ">

                            @php
                                $title = '';

                                if (isset($_GET['status']) && $_GET['status'] == 'Admission-Application') {
                                    $title = 'Admission - Application Chart';
                                } elseif (isset($_GET['status']) && $_GET['status'] == 'Application-Deposit') {
                                    $title = '>Application - Deposit Chart';
                                } elseif (isset($_GET['status']) && $_GET['status'] == 'Admission-Deposit') {
                                    $title = 'Admission - Deposit Chart';
                                } elseif (isset($_GET['status']) && $_GET['status'] == 'Deposit-visas') {
                                    $title = 'Deposit - Visas Chart';
                                } else {
                                    $title = 'Admission - Application Chart';
                                }

                                echo $title;
                            @endphp
                        </p>
                        <form action="" class="" id="status_form">
                            <select class="form-select form-select-sm" id="status" name="status">
                                <option selected>Select</option>
                                <option value="Admission-Application"
                                    <?= isset($_GET['status']) && $_GET['status'] == 'Admission-Application' ? 'selected' : '' ?>>
                                    Admission - Application</option>
                                <option value="Application-Deposit"
                                    <?= isset($_GET['status']) && $_GET['status'] == 'Application-Deposit' ? 'selected' : '' ?>>
                                    Application - Deposit</option>
                                <option value="Admission-Deposit"
                                    <?= isset($_GET['status']) && $_GET['status'] == 'Admission-Deposit' ? 'selected' : '' ?>>
                                    Admission - Deposit</option>
                                <option value="Deposit-visas"
                                    <?= isset($_GET['status']) && $_GET['status'] == 'Deposit-visas' ? 'selected' : '' ?>>
                                    Deposit - Visas</option>
                            </select>
                        </form>
                    </div>
                    <div id="areachart"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-3">
            <div class="card my-card" style="min-height: 95% !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between mt-3 mb-2 align-items-center">
                        <p class="fs-6 fw-bold text-dark" style="color: #000000; ">Top 3 Brands of SCORP</p>
                    </div>
                    <div>
                        <form action="" id="top_brands_form">
                            <select class="form-select me-4 form-select-sm" name="top_brand_filter" id="top_brands">
                                <option value="">Select</option>
                                <option value="admissions"
                                    <?= isset($_GET['top_brand_filter']) && $_GET['top_brand_filter'] == 'admissions' ? 'selected' : '' ?>>
                                    Admission</option>
                                <option value="deposits"
                                    <?= isset($_GET['top_brand_filter']) && $_GET['top_brand_filter'] == 'deposits' ? 'selected' : '' ?>>
                                    Deposit</option>
                                <option value="visas"
                                    <?= isset($_GET['top_brand_filter']) && $_GET['top_brand_filter'] == 'visas' ? 'selected' : '' ?>>
                                    Visas</option>
                            </select>
                        </form>
                    </div>
                    <div class="chart-container">
                        <canvas id="chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {

            $("#status").on('change', function() {
                $('#status_form').submit();
            })

            $("#top_brands").on('change', function() {
                $('#top_brands_form').submit();
            })
        })
    </script>
@endpush
