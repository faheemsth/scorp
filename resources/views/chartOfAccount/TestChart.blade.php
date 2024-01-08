@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Leads') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leads') }}</li>
@endsection
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
</style>






@section('content')
    <div class="row">
        <div class="col-xl-12">

            <div class="container">
                <div class="row justify-content-center align-items-center my-4">
                    <div class="col-3">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-auto d-flex flex-column gap-2 justify-content-center align-items-center">
                                <div style="border: 2px solid black; border-radius: 50%;text-align: center;width: 25px;height: 25px;"
                                    class="p-1 mb-1 ">
                                    <i class="fa-solid fa-dollar-sign" style="color:black"></i>
                                </div>
                                <h6 class="fw-bold">Visas Analysis</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-auto d-flex flex-column gap-2 justify-content-center align-items-center">
                                <div style="border: 2px solid black; border-radius: 50%;text-align: center;width: 25px;height: 25px;"
                                    class="p-1 mb-1">
                                    <i class="fa-solid fa-dollar-sign" style="color:black"></i>
                                </div>
                                <h6 class="fw-bold">Deposits Analysis</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-auto d-flex flex-column gap-2 justify-content-center align-items-center">
                                <div style="border: 2px solid black; border-radius: 50%;text-align: center;width: 25px;height: 25px;"
                                    class="p-1 mb-1">
                                    <i class="fa-solid fa-dollar-sign" style="color:black"></i>
                                </div>
                                <h6 class="fw-bold">Application Analysis</h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="d-flex justify-content-center align-items-center py-2"
                style="background-color: #1F2735;color: white">
                <div class="col-2 text-center">
                    <p class="mb-0">Record Count</p>
                    <h4 class="text-white">6,224</h4>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn recoredtext dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span> Brands</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn recoredtext dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span>Locations</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn recoredtext dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span> Instition</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn recoredtext dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span>Intakes</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn recoredtext dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span> Deposit Date</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body table-border-style" style="padding: 25px 3px;">
                    <div class="row align-items-center justify-content-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-12">
                            <canvas id="FirstChart" style="width:100%;max-width:600px"></canvas>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body table-border-style" style="padding: 25px 3px;">
                    <div class="row align-items-center justify-content-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-12">
                            <canvas id="SecondChart" style="width:100%;max-width:600px"></canvas>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
<script>
    const chartConfig = {
        type: "bar",
        data: {
            labels: [],
            datasets: [{
                backgroundColor: [],
                data: []
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "World Wine Production 2018"
            }
        }
    };
    const myChart = new Chart("FirstChart", chartConfig);
    $.ajax({
        url: '/welcome2',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            myChart.data.labels = data.labels;
            myChart.data.datasets[0].data = data.values;
            myChart.data.datasets[0].backgroundColor = data.backgroundColor;
            myChart.update();
        },
        error: function (error) {
            console.error('Error fetching data:', error);
        }
    });
</script>













    <script>
        const xValues = ["Italy", "France", "Spain", "USA", "Argentina", "Argentina", "Argentina", "Argentina"];
        const yValues = [55, 49, 44, 24, 15, 25, 105, 35];
        const barColors = ["#1F2735", "#1F2735", "#1F2735", "#1F2735", "#1F2735", "#1F2735", "#1F2735", "#1F2735"];

        new Chart("SecondChart", {
            type: "horizontalBar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "World Wine Production 2018"
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                layout: {
                    padding: {
                        left: 50, // Adjust left padding to make space for bar colors
                        right: 50,
                        top: 0,
                        bottom: 0
                    }
                },
                plugins: {
                    datalabels: {
                        anchor: 'end', // Anchor labels at the end of bars
                        align: 'end',
                        color: 'black' // Color of the labels
                    }
                }
            }
        });
    </script>
@endpush
