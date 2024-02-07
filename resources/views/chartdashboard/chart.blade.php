@extends('layouts.admin')

@push('css-page')
<style>
    #chartdiv {
        width: 100%;
        height: 250px;
    }

    #dounotchart {
        width: 100%;
        height: 60%;
        margin: 0;
        padding: 0;
    }

    #dounotchart2 {
        width: 100%;
        height: 60%;
        margin: 0;
        padding: 0;
    }
    .canvasjs-chart-credit{
        display:none;
    }
    .anychart-credits-text{
        display:none;
    }
    .anychart-credits-logo{
        display:none;
    }
</style>




@endpush

@push('script-page')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script src="//cdn.amcharts.com/lib/5/index.js"></script>
<script src="//cdn.amcharts.com/lib/5/map.js"></script>
<script src="//cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="//cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.anychart.com/releases/8.11.1/js/anychart-core.min.js"></script>
<script src="https://cdn.anychart.com/releases/8.11.1/js/anychart-pie.min.js"></script>





<script>

    // line with barr

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 2,
                borderColor: '#000'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    const ctx2 = document.getElementById('myChart2');

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 2,
                borderColor: '#000'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

</script>


<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            // title: {
            //     text: "Weekly Revenue Analysis for First Quarter"
            // },
            axisY: [{
                title: "Admission",
                lineColor: "#C24642",
                tickColor: "#C24642",
                labelFontColor: "#C24642",
                titleFontColor: "#C24642",
                includeZero: true,
                suffix: "k"
            },
            {
                // title: "Footfall",
                // lineColor: "#369EAD",
                // tickColor: "#369EAD",
                // labelFontColor: "#369EAD",
                // titleFontColor: "#369EAD",
                // includeZero: true,
                // suffix: "k"
            }],
            axisY2: {
                title: "",
                lineColor: "#7F6084",
                tickColor: "#7F6084",
                labelFontColor: "#7F6084",
                titleFontColor: "#7F6084",
                includeZero: true,
                prefix: "$",
                suffix: "k"
            },
            toolTip: {
                shared: true
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: [{
                type: "line",
                name: "Footfall",
                color: "#000",
                showInLegend: true,
                axisYIndex: 1,
                // dataPoints: [
                //     { x: new Date(2017, 00, 7), y: 85.4 },
                //     { x: new Date(2017, 00, 14), y: 92.7 },
                //     { x: new Date(2017, 00, 21), y: 64.9 },
                //     { x: new Date(2017, 00, 28), y: 58.0 },
                //     { x: new Date(2017, 01, 4), y: 63.4 },
                //     { x: new Date(2017, 01, 11), y: 69.9 },
                //     { x: new Date(2017, 01, 18), y: 88.9 },
                //     { x: new Date(2017, 01, 25), y: 66.3 },
                //     { x: new Date(2017, 02, 4), y: 82.7 },
                //     { x: new Date(2017, 02, 11), y: 60.2 },
                //     { x: new Date(2017, 02, 18), y: 87.3 },
                //     { x: new Date(2017, 02, 25), y: 98.5 }
                // ]
            },
            {
                type: "line",
                name: "",
                color: "#000",
                axisYIndex: 0,
                showInLegend: true,
                dataPoints: [
                    { x: new Date(2017, 00, 7), y: 32.3 },
                    { x: new Date(2017, 00, 14), y: 33.9 },
                    { x: new Date(2017, 00, 21), y: 26.0 },
                    { x: new Date(2017, 00, 28), y: 15.8 },
                    { x: new Date(2017, 01, 4), y: 18.6 },
                    { x: new Date(2017, 01, 11), y: 34.6 },
                    { x: new Date(2017, 01, 18), y: 37.7 },
                    { x: new Date(2017, 01, 25), y: 24.7 },
                    { x: new Date(2017, 02, 4), y: 35.9 },
                    { x: new Date(2017, 02, 11), y: 12.8 },
                    { x: new Date(2017, 02, 18), y: 38.1 },
                    { x: new Date(2017, 02, 25), y: 42.4 }
                ]
            },
            {
                type: "line",
                name: "",
                color: "#7F6084",
                axisYType: "secondary",
                showInLegend: true,
                dataPoints: [
                    { x: new Date(2017, 00, 7), y: 42.5 },
                    { x: new Date(2017, 00, 14), y: 44.3 },
                    { x: new Date(2017, 00, 21), y: 28.7 },
                    { x: new Date(2017, 00, 28), y: 22.5 },
                    { x: new Date(2017, 01, 4), y: 25.6 },
                    { x: new Date(2017, 01, 11), y: 45.7 },
                    { x: new Date(2017, 01, 18), y: 54.6 },
                    { x: new Date(2017, 01, 25), y: 32.0 },
                    { x: new Date(2017, 02, 4), y: 43.9 },
                    { x: new Date(2017, 02, 11), y: 26.4 },
                    { x: new Date(2017, 02, 18), y: 40.3 },
                    { x: new Date(2017, 02, 25), y: 54.2 }
                ]
            }]
        });
        chart.render();

        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }

    }
</script>

<script>
    // map chart

    var root = am5.Root.new("chartdiv");
    root.setThemes([
        am5themes_Animated.new(root)
    ]);

    var chart = root.container.children.push(
        am5map.MapChart.new(root, {
            panX: "rotateX",
            projection: am5map.geoNaturalEarth1()
        })
    );


    var polygonSeries = chart.series.push(
        am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_worldLow,
            exclude: ["AQ"]
        })
    );

    polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}",
        interactive: true
    });

    polygonSeries.mapPolygons.template.states.create("hover", {
        fill: am5.color(0x677935)
    });
</script>

{{-- dounut chart --}}
<script>
    anychart.onDocumentReady(function () {

        var data = anychart.data.set([
            ["Wimbledon", 8],
            ["Australian Open", 6],
            ["U.S. Open", 5],
            ["French Open", 1]
        ]);

        var palette = anychart.palettes.distinctColors();


        var chart = anychart
            .pie(data)

            .innerRadius("60%");

        chart.container("dounotchart");

        chart.draw();
    });
</script>
<script>
    anychart.onDocumentReady(function () {

        var data = anychart.data.set([
            ["Wimbledon", 8],
            ["Australian Open", 6],
            ["U.S. Open", 5],
            ["French Open", 1]
        ]);

        var palette = anychart.palettes.distinctColors();


        var chart = anychart
            .pie(data)

            .innerRadius("60%");

        chart.container("dounotchart2");

        chart.draw();
    });
</script>




@endpush

@section('content')
<div class="main-content py-5" >

    <div class="row">
        <div class="dropdown col-6 col-lg-2 col-md-4 my-2 ">
            <a class="btn bg-white text-dark dropdown-toggle w-100 py-2 fw-bold fs-5" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Brands
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Active Vision</a></li>
                <li><a class="dropdown-item" href="#">Bright Routes</a></li>
                <li><a class="dropdown-item" href="#">Career Advisers</a></li>
                <li><a class="dropdown-item" href="#">Ibex Study</a></li>
            </ul>

        </div>
        <div class="dropdown col-6 col-lg-2 col-md-4 my-2">
            <a class="btn bg-white text-dark dropdown-toggle w-100 py-2 fw-bold fs-5" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Location
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Lahore</a></li>
                <li><a class="dropdown-item" href="#">Karachi</a></li>
                <li><a class="dropdown-item" href="#">Islamabad</a></li>
                <li><a class="dropdown-item" href="#">Peshawar</a></li>
            </ul>
        </div>
        <div class="dropdown col-6 col-lg-2 col-md-4 my-2">
            <a class="btn bg-white text-dark dropdown-toggle w-100 py-2 fw-bold fs-5" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Institute
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Abertay University</a></li>
                <li><a class="dropdown-item" href="#">University of Lahore</a></li>
                <li><a class="dropdown-item" href="#">Chicago University</a></li>
            </ul>
        </div>
        <div class="dropdown col-6 col-lg-2 col-md-4 my-2">
            <a class="btn bg-white text-dark dropdown-toggle w-100 py-2 fw-bold fs-5" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Intakes
            </a>
            <ul class="dropdown-menu ">
                <li><a class="dropdown-item" href="#">January</a></li>
                <li><a class="dropdown-item" href="#">February</a></li>
                <li><a class="dropdown-item" href="#">March</a></li>
                <li><a class="dropdown-item" href="#">April</a></li>
            </ul>
        </div>
        <div class="dropdown  col-lg-4 col-md-6 my-2">
            <a class="btn bg-white text-dark btn-lg dropdown-toggle w-100 py-2 fw-bold fs-5" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                April 15,2020-April 21,2020
            </a>

            <ul class="dropdown-menu w-100 ">
                <li><a class="dropdown-item" href="#">May 15,2021-May 21,2021</a></li>
                <li><a class="dropdown-item" href="#">June</a></li>
                <li><a class="dropdown-item" href="#">July</a></li>
                <li><a class="dropdown-item" href="#">August</a></li>
            </ul>
        </div>
    </div>
    {{-- charts --}}


    <div class="row">
        <div class="col-12 col-md-12 col-lg-8 my-2  ">


            <div class="card h-100">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <h6 class="card-title  fw-bold ">Admission-Application Chart </h6>
                    <select class="form-select form-select-sm w-25" aria-label="Small select example">
                        <option selected>Select</option>
                        <option value="1">Admission-Application</option>
                        <option value="2">Application-Deposit</option>
                        <option value="3">Admission-Deposit</option>
                        <option value="4">Deposit-Visa</option>
                    </select>
                </div>

                <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                <div class="card-body">
                    <!-- <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This content is a little bit longer.</p>
                    <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p> -->
                </div>
            </div>

        </div>

        <div class="col-12 col-md-12 col-lg-4 my-2 ">


            <div class="card h-100 ">
                <div class="p-4 d-flex justify-content-between align-items-center p-4">
                    <h6 class="card-title  fw-bold">Stages Shares</h6>
                    <select class="form-select form-select-sm w-50 float-right " aria-label="Small select example">
                        <option selected>Select </option>
                        <option value="1">Amar Suhail</option>
                        <option value="2">Dr Kashif Shahzad</option>
                        <option value="3">Muhammad Asif</option>
                        <option value="4">Muhammad Shahid</option>
                    </select>
                </div>
                <div id="dounotchart2"></div>
                <div class="card-body">

                    <div class="mb-4 w-100 text-center ">
                        <canvas id="myChart"class="d-block mx-md-auto mx-sm-auto"></canvas>
                    </div>
                </div>

            </div>




        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-3 g-4">
        <div class="col">
            <div class="card h-100 ">
                <h6 class="card-title p-4 fw-bold ">Language Breakdown</h6>
                <canvas id="myChart2" class="px-2 " style="width: 100%; height:220px;"></canvas>
                <div class="card-body">

                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" class="table-active">First</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td >Larry </td>
                                <td>Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <h6 class="card-title  fw-bold ">Country Breakdown </h6>
                    <select class="form-select form-select-sm w-50" aria-label="Small select example">
                        <option selected>Select Country</option>
                        <option value="1">Pakistan</option>
                        <option value="2">USA</option>
                        <option value="3">Australia</option>
                        <option value="4">United Kingdom</option>
                    </select>
                </div>
                <span id="chartdiv" class=""> </span>
                <div class="card-body">
                    <table class="table">
                        
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td colspan="2">Larry the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <div class="p-4 text-center">
                    <h6 class="card-title  fw-bold">Project Directors Shares in SCORP</h6>
                    <select class="form-select form-select-sm w-50 float-right" aria-label="Small select example">
                        <option selected>Select Option</option>
                        <option value="1">Amar Suhail</option>
                        <option value="2">Dr Kashif Shahzad</option>
                        <option value="3">Muhammad Asif</option>
                        <option value="4">Muhammad Shahid</option>
                    </select>
                </div>

                <div id="dounotchart"></div>
                <div class="card-body">
                    <table class="table mb-2">
                       
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td colspan="2">Larry the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>
{{-- --}}



@endsection