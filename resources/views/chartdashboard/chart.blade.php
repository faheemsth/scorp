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
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>



{{-- minicharts --}}
{{-- <script>
    window.onload = function () {
    
    var chart = new CanvasJS.Chart("minichart", {
        animationEnabled: true,
        theme: "",
        title:{
            // text: "Simple Line Chart"
        },
        data: [{        
            type: "line",
              indexLabelFontSize: 16,
            dataPoints: [
                { y: 450 },
                { y: 414},
                { y: 520, indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },
                { y: 460 },
                { y: 450 },
                
            ]
        }]
    });
    chart.render();
    
    }
</script> --}}
<script>
    google.charts.load('current',{packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
    
    // Set Data
    const data = google.visualization.arrayToDataTable([
      ['Price', 'Size'],
      [50,7],[60,8],[70,8],[80,9],[90,9],
      [100,9],[110,10],[120,11],
      [130,14],[140,14],[150,15]
    ]);
    
    // Set Options
    const options = {
     
      
     
      legend: 'none'
    };
    
    // Draw
    const chart = new google.visualization.LineChart(document.getElementById('minichart'));
    chart.draw(data, options);
    
    }
</script>
{{-- 2 --}}
<script>
    google.charts.load('current',{packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
    
    // Set Data
    const data = google.visualization.arrayToDataTable([
      ['Price', 'Size'],
      [50,7],[60,8],[70,8],[80,9],[90,9],
      [100,9],[110,10],[120,11],
      [130,14],[140,14],[150,15]
    ]);
    
    // Set Options
    const options = {
     
      
     
      legend: 'none'
    };
    
    // Draw
    const chart = new google.visualization.LineChart(document.getElementById('minichart2'));
    chart.draw(data, options);
    
    }
</script>
{{-- 3 --}}
<script>
    google.charts.load('current',{packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
    
    // Set Data
    const data = google.visualization.arrayToDataTable([
      ['Price', 'Size'],
      [50,7],[60,8],[70,8],[80,9],[90,9],
      [100,9],[110,10],[120,11],
      [130,14],[140,14],[150,15]
    ]);
    
    // Set Options
    const options = {
     
      
     
      legend: 'none'
    };
    
    // Draw
    const chart = new google.visualization.LineChart(document.getElementById('minichart3'));
    chart.draw(data, options);
    
    }
</script>
{{-- 4 --}}
<script>
    google.charts.load('current',{packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
    
    // Set Data
    const data = google.visualization.arrayToDataTable([
      ['Price', 'Size'],
      [50,7],[60,8],[70,8],[80,9],[90,9],
      [100,9],[110,10],[120,11],
      [130,14],[140,14],[150,15]
    ]);
    
    // Set Options
    const options = {
     
      
     
      legend: 'none'
    };
    
    // Draw
    const chart = new google.visualization.LineChart(document.getElementById('minichart4'));
    chart.draw(data, options);
    
    }
</script>

<script>
    

    // line with barr

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['New Lead', 'Contacted', 'Documents Pending','Documents Received', 'Advised', 'Unqualified', 'Junk Lead'],
            datasets: [{
                label: 'Stages',
                data: [12, 19, 3, 5, 2, 3, 4],
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
            labels: ['AA Advisers', 'Active Visions', 'Better Uni'],
            datasets: [{
                label: 'Stages Data',
                data: [12, 19, 11],
                borderWidth: 1,
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
            ["New Lead", 8],
            ["Contacted", 6],
            ["Documents Pending", 5],
            ["Documents Received", 1],
            ["Advised", 1],
            ["Unqualified", 1],
            ["Junk Lead", 1],
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

@section('page-title')
{{ __('Dashboard') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a>
</li>
@endsection


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
                    <div class="row" >
                        <div class="col-3">
                            
                            <a href="#" class="mini-text">11 <br>This is the refrence text</a>
                        
                            {{-- <div id="minichart" style="height: 0px; width: 100%;"></div> --}}
                            <div id="minichart" style=" width:200px; height:150px;"></div>


                        </div>
                        <div class="col-3 px-0">
                            <p class="mini-text">11</p>
                            <small>this is the mini text</small>
                            <div id="minichart2" style=" width:200px; height:150px;"></div>
                        </div>
                        <div class="col-3">
                            <p class="mini-text">11</p>
                            <small>this is the mini text</small>
                            <div id="minichart3" style=" width:200px; height:150px;"></div>
                        </div>
                        <div class="col-3">
                            <p class="mini-text">11</p>
                            <small>this is the mini text</small>
                            <div id="minichart4" style=" width:200px; height:150px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <p class="mini-text">11</p>
                            <small>this is the mini text</small>

                        </div>
                        <div class="col-3">
                            <p class="mini-text">11</p>
                            <small>this is the mini text</small>

                        </div>
                        <div class="col-3">
                            <p class="mini-text">11</p>
                            <small>this is the mini text</small>

                        </div>
                        <div class="col-3">
                            <p class="mini-text">11</p>
                            <small>this is the mini text</small>

                        </div>
                    </div>
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
                        <option value="1">New Lead</option>
                        <option value="2">Contacted</option>
                        <option value="3">Documents Pending</option>
                        <option value="4">Documents Received</option>
                        <option value="">Advised</option>
                        <option value="">Unqualified</option>
                        <option value="">Qualified</option>
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
                                <th scope="col">Brands</th>
                                <th scope="col">Deposit</th>
                                <th scope="col">Visa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>AA Advisers</td>
                                <td>12</td>
                                <td>11</td>
                            </tr>
                            <tr>
                                <td>Active Visions</td>
                                <td>12</td>
                                <td>11</td>
                            </tr>
                            <tr>
                                <td>Better Uni</td>
                                <td>12</td>
                                <td>11</td>
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
                        <thead>
                            <th>Location</th>
                            <th>Leads</th>
                            <th>Deposit</th>
                            <th>Visa</th>
                        </thead>
                        
                        <tbody>
                            <tr>
                                <td>Pakistan</td>
                                <td>1200</td>
                                <td>400</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>India</td>
                                <td>1600</td>
                                <td>600</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>Pakistan</td>
                                <td>2994</td>
                                <td>1200</td>
                                <td>1600</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <div class="pl-4 py-4 d-flex">
                    <h6 class="card-title  fw-bold">Project Directors Shares</h6>
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
                       <thead>
                        <tr>
                            <th>Project Director</th>
                            <th>Deposit</th>
                            <th>Visa</th>
                        </tr>
                       </thead>
                        <tbody>
                            <tr>
                                <td>Amar Suhail</td>
                                <td>500</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>Dr Kashif Shahzad</td>
                                <td>500</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>Muhammad Asif</td>
                                <td>500</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>Muhammad Shahid</td>
                                <td>500</td>
                                <td>200</td>
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