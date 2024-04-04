@extends('layouts.admin') @push('css-page')
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

    .canvasjs-chart-credit {
        display: none;
    }

    .anychart-credits-text {
        display: none;
    }

    .anychart-credits-logo {
        display: none;
    }
</style>
<style>
    @media screen and (max-width: 576px) {
        .scrollable-table {
            overflow-x: auto;
            width: 100%;
            display: block;
            white-space: nowrap;

        }
    }
</style>
<style>
    @media only screen and (min-width: 1201px) and (max-width: 2600px) {
        .respons-chart {
            display: none !important;
        }

        .line-main-select {
            width: 170px !important;
        }

    }

    @media only screen and (max-width: 767px) {
        .respons-chart {
            display: none !important;
        }

        .line-main-select {
            width: 170px !important;
        }
    }

    @media only screen and (min-width: 761px) and (max-width:1200px) {
        .second-card {
            display: none !important;
        }

        .end-card {
            display: none !important;
        }

        .line-main-select {
            width: 170px !important;
        }
    }

    @media only screen and (max-width:500px) {
        .line-main-select {
            width: 120px !important;
        }
    }
</style>

@endpush @push('script-page')

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









<script>
    // map chart
    var root = am5.Root.new("chartdiv");
    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(
        am5map.MapChart.new(root, {
            panX: "rotateX",
            projection: am5map.geoNaturalEarth1(),
        })
    );

    var polygonSeries = chart.series.push(
        am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_worldLow,
            exclude: ["AQ"],
        })
    );

    polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}",
        interactive: true,
        fill: am5.color(0xcccccc),
    });

    polygonSeries.mapPolygons.template.states.create("hover", {
        fill: am5.color(0x677935),
    });

    function selectCountry(countryName) {
        var selectedPolygon = polygonSeries.getPolygonById(countryName);
        if (selectedPolygon) {
            selectedPolygon.mapPolygon.fill = am5.color(0x677935); // Set fill color for selected country
            chart.zoomToMapObject(selectedPolygon);
        }
    }

    // Example: Selecting a country named "Canada"
    selectCountry("Canada");
</script>





@endpush @section('page-title')
{{ __("Dashboard") }}
@endsection @section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('crm.dashboard') }}">{{ __("Dashboard") }}</a>
</li>
@endsection @section('content')
<div class="main-content pb-5">

    <form action="">
        <div class="row">
            <div class="col-md-3 my-2">
                <select name="brand_id" id="mybrand-filter" class="form form-select select2">
                    @forelse($filter_data['brands'] as $key => $brand)
                    <option value="{{ $key }}" {{ isset($_GET['brand_id']) && $_GET['brand_id'] == $key ? 'selected' : '' }}>{{ $brand }}</option>
                    @empty
                    @endforelse
                </select>
            </div>

            <div class="col-md-3 my-2">
                <select name="region_id" id="myregion-filter" class="form form-select">
                    @forelse($filter_data['regions'] as $key => $region)
                    <option value="{{ $key }}" {{ isset($_GET['region_id']) && $_GET['region_id'] == $key ? 'selected' : '' }}>{{ $region }}</option>
                    @empty
                    @endforelse
                </select>
            </div>

            <div class="col-md-3 my-2">
                <select name="branch_id" id="mybranch-filter" class="form form-select">
                    @forelse($filter_data['branches'] as $key => $branch)
                    <option value="{{ $key }}" {{ isset($_GET['branch_id']) && $_GET['branch_id'] == $key ? 'selected' : '' }}>{{ $branch }}</option>
                    @empty
                    @endforelse
                </select>
            </div>

            @if(isset($_GET['datatype']) && !empty($_GET['datatype']))
            <input type="hidden" name="datatype" value="{{ $_GET['datatype'] }}">
            @endif

            <div class="col-md-3 my-2">
                <input type="submit" value="Submit" class="btn btn-dark mt-1">
            </div>
        </div>
    </form>

    {{-- charts --}}

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12 col-xl-8 my-2">
            <div class="card h-100">
                <div class=" main-line-btn d-flex justify-content-between align-items-center p-4 gap-2">
                    <h6 class="card-title fw-bold">
                        @if(isset($_GET['datatype']) && !empty($_GET['datatype']))
                            {{ $_GET['datatype'] }} Chart
                        @else
                            Admission-Application Chart
                        @endif 
                    </h6>
                    <form action="" class="data-type-form">
                        @if(isset($_GET['brand_id']) && !empty($_GET['brand_id']))
                        <input type="hidden" name="brand_id" value="{{ $_GET['brand_id'] }}">
                        @endif

                        @if(isset($_GET['region_id']) && !empty($_GET['region_id']))
                        <input type="hidden" name="region_id" value="{{ $_GET['region_id'] }}">
                        @endif

                        @if(isset($_GET['branch_id']) && !empty($_GET['branch_id']))
                        <input type="hidden" name="branch_id" value="{{ $_GET['branch_id'] }}">
                        @endif

                        <select name="datatype" class="form-select form-select-sm line-main-select data-type-select" aria-label="Small select example">
                            <option selected>Select type</option>
                            <option value="Admission-Application" {{ isset($_GET['datatype']) && $_GET['datatype'] == 'Admission-Application' ? 'selected' : '' }}>Admission-Application</option>
                            <option value="Application-Deposit" {{ isset($_GET['datatype']) && $_GET['datatype'] == 'Application-Deposit' ? 'selected' : '' }}>Application-Deposit</option>
                            <option value="Admission-Deposit" {{ isset($_GET['datatype']) && $_GET['datatype'] == 'Admission-Deposit' ? 'selected' : '' }}>Admission-Deposit</option>
                            <option value="Deposit-Visa" {{ isset($_GET['datatype']) && $_GET['datatype'] == 'Deposit-Visa' ? 'selected' : '' }}>Deposit-Visa</option>
                        </select>
                    </form>
                </div>

                <div id="chartContainer" style="height: 250px; width: 100%"></div>


                <div class="card-body pe-0">
                    <div class="row mx-auto px-2">
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_visas)->total }}</a></h4>
                            <a href="#" class="text-dark">Visas</a>
                            <div id="subchartvisas" style="width: 150px; height: 70px"></div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_deposit)->total }} </a></h4>
                            <a href="#" class="text-dark">Deposit</a>
                            <div id="subchartdeposit" style="width: 150px; height: 70px"></div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_applications)->total }}</a></h4>
                            <a href="#" class="text-dark">Applications</a>
                            <div id="subchartapplications" style="width: 150px; height: 70px"></div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_admissions)->total }}</a></h4>
                            <a href="#" class="text-dark">Admissions</a>
                            <div id="subchartadmissions" style="width: 150px; height: 70px"></div>
                        </div>
                    </div>
                    <div class="row mx-auto px-2">
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_assignedleads)->total }}</a></h4>
                            <a href="#" class="text-dark">Assigned Leads</a>
                            <div id="subchartassignedleads" style="width: 150px; height: 70px"></div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_unassignedleads)->total }}</a></h4>
                            <a href="#" class="text-dark">Unassigned Leads </a>
                            <div id="subchartunassignedleads" style="width: 150px; height: 70px"></div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_qualifiedleads)->total }}</a></h4>
                            <a href="#" class="text-dark">Qualified Leads</a>
                            <div id="subchartqualifiedleads" style="width: 150px; height: 70px"></div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                            <h4><a href="#" class="text-dark">{{ json_decode($sub_chart_unqualifiedleads)->total }}</a></h4>
                            <a href="#" class="text-dark">Unqualified Leads</a>
                            <div id="subchartunqualifiedleads" style="width: 150px; height: 70px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-6 col-xl-4 my-2 second-card">
            <div class="card h-100">
                <div class="d-flex justify-content-between align-items-center p-4 gap-2">
                    <h6 class="card-title fw-bold">Lead Stages Shares</h6>
                </div>
                <div id="stageshare_dounotchart" style="height: 300px; width:100%;"></div>
                <div class="card-body">
                    <canvas id="myChart" class="" style=" "></canvas>
                </div>
            </div>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-6 col-lg-6 col-xl-4 my-2">
            <div class="card h-100">
                <h6 class="card-title p-4 fw-bold">Language Breakdown</h6>
                <canvas id="topBrands" class="px-2 mb-1" style="width: 100%; height: 280px"></canvas>
                <div class="card-body px-0 scrollable-table overflow-auto">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Brands</th>
                                <th scope="col">Admissions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $top3brands = json_decode($top_brands);
                            @endphp

                            @foreach($top3brands->top_brands as $brand => $states)
                            <tr>
                                <td>{{ $states->brand_name }}</td>
                                <td>{{ $states->total_deals  }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <td>Other Brands</td>
                                <td>{{ $top3brands->totalOtherDeal }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class=" col-12 col-md-6 col-lg-6 col-xl-4 my-2">
            <div class="card h-100">
                <div class="d-flex justify-content-between align-items-center p-4 gap-3">
                    <h6 class="card-title fw-bold">Country Breakdown</h6>
                </div>
                <span id="chartdiv" class="" style="width: 100%; height: 280px">
                </span>
                <div class="card-body px-0 scrollable-table overflow-auto">
                    <table class="table">
                        <thead>
                            <th>Location</th>
                            <th>Admissions</th>
                        </thead>

                        <tbody>

                        @foreach($top_countries['top_countries'] as $country)
                            <tr>
                                <td>{{$country['country']}}</td>
                                <td>{{$country['total_deals']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6 col-xl-4 my-2 end-card">
            <div class="card h-100">
                <div class="d-flex justify-content-between align-items-center p-4 gap-2">
                    <h6 class="card-title fw-bold">Admission Stages Shares</h6>
                </div>
                <div id="admissionstageshare_dounotchart" style="height: 300px; width:100%;"></div>
                <div class="card-body">
                    <canvas id="admissionStagesShare" class="" style=" "></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- --}}

@endsection
@push('script-page')
<script>
    $("#mybrand-filter").on("change", function() {
        var brand_id = $(this).val();
        $.ajax({
            url: '/filter-regions?brand_id=' + brand_id,
            type: 'GET',
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    // $("#myregion-filter").empty();
                    $("#myregion-filter").html(data.html);
                    // $("#myregion-filter").addClass('select2');
                    //select2();
                }
            }
        });

    });


    $("#myregion-filter").on("change", function() {
        var region_id = $(this).val();
        $.ajax({
            url: '/filter-branches?region_id=' + region_id,
            type: 'GET',
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    // $("#myregion-filter").empty();
                    $("#mybranch-filter").html(data.html);
                    // $("#myregion-filter").addClass('select2');
                    //select2();
                }
            }
        });
    });

    $(".data-type-select").on("change", function() {
        $(".data-type-form").submit();
    })
</script>

<script>
    function drawChart(data, options, chartType, elementId) {
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(function() {
            // Set Data
            const chartData = google.visualization.arrayToDataTable(data);

            // Draw
            const chart = new google.visualization[chartType](
                document.getElementById(elementId)
            );
            chart.draw(chartData, options);
        });
    }

    function prepareChartData(chartData, data) {
        const datapoints = [
            ["Month", chartData.name]
        ]; // Column headers

        let monthIndex = 0;
        for (const month in data) {
            let newData = [monthIndex, data[month]];
            datapoints.push(newData);
            monthIndex++;
        }

        return datapoints;
    }

    function drawSubChart(chartConfig) {
        const {
            chartData,
            chartType,
            elementId
        } = chartConfig;
        const datapoints = prepareChartData(chartData, chartData.data);
        const options = {
            chartType: chartType,
            colors: ["black"],
            legend: "none", // Disable the legend
            hAxis: {
                gridlines: {
                    color: "transparent"
                },
                textPosition: "none",
                ticks: [],
                baselineColor: "transparent"
            },
            vAxis: {
                gridlines: {
                    color: "transparent"
                },
                textPosition: "none",
                baselineColor: "transparent"
            }
        };

        drawChart(datapoints, options, chartType, elementId);
    }

    const subCharts = [{
            chartData: <?= $sub_chart_visas ?>,
            chartType: "LineChart",
            elementId: "subchartvisas"
        },
        {
            chartData: <?= $sub_chart_deposit ?>,
            chartType: "ComboChart",
            elementId: "subchartdeposit"
        },
        {
            chartData: <?= $sub_chart_applications ?>,
            chartType: "ComboChart",
            elementId: "subchartapplications"
        },
        {
            chartData: <?= $sub_chart_admissions ?>,
            chartType: "ComboChart",
            elementId: "subchartadmissions"
        },
        {
            chartData: <?= $sub_chart_assignedleads ?>,
            chartType: "ComboChart",
            elementId: "subchartassignedleads"
        },
        {
            chartData: <?= $sub_chart_unassignedleads ?>,
            chartType: "ComboChart",
            elementId: "subchartunassignedleads"
        },
        {
            chartData: <?= $sub_chart_qualifiedleads ?>,
            chartType: "ComboChart",
            elementId: "subchartqualifiedleads"
        },
        {
            chartData: <?= $sub_chart_unqualifiedleads ?>,
            chartType: "ComboChart",
            elementId: "subchartunqualifiedleads"
        }
        // Add more sub-chart configurations here as needed
    ];

    subCharts.forEach(drawSubChart);
</script>

<script>
    var chart1 = <?= $chart_data1_json ?>

    datapoint1 = [];
    datapoint2 = [];
    datapoint1_label = '';
    datapoint2_label = '';

    var outer = 0;

    chart1.forEach(function(item, index) {
        outer++;


        for (const month in item.data) {
            if (item.data.hasOwnProperty(month)) {
                if (item.data.hasOwnProperty(month)) {
                    const dataPoint = {
                        label: month,
                        y: item.data[month]
                    };

                    if (outer == 1) {
                        datapoint1_label = item.name;
                        datapoint1.push(dataPoint);
                    } else {
                        datapoint2_label = item.name;
                        datapoint2.push(dataPoint);
                    }
                }
            }
        }
    });
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            title: {
                text: ""
            },
            axisY: {
                title: datapoint1_label + '-' + datapoint2_label
            },
            toolTip: {
                shared: true
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: [{
                    type: "spline",
                    name: datapoint1_label,
                    showInLegend: true,
                    dataPoints: datapoint1
                },
                {
                    type: "spline",
                    name: datapoint2_label,
                    showInLegend: true,
                    dataPoints: datapoint2
                }
            ]
        });

        chart.render();

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }

    }
</script>

<script>
    // line with barr
    var stagesdata = <?= $stage_share_data ?>;
    var stages = [];
    var stages_data = [];
    var donuts = [];

    for (const stage in stagesdata) {
        stages.push(stage);
        stages_data.push(stagesdata[stage]);
        var new_item = [];
        new_item.push(stage, stagesdata[stage]);
        donuts.push(new_item);
    }


    const ctx = document.getElementById("myChart");

    new Chart(ctx, {
        type: "line",
        data: {
            labels: stages,
            datasets: [{
                label: "Stages",
                data: stages_data,
                borderWidth: 2,
                borderColor: "#000",
            }, ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    anychart.onDocumentReady(function() {
        var data = anychart.data.set(donuts);
        var palette = anychart.palettes.distinctColors();

        var chart = anychart
            .pie(data)

            .innerRadius("60%");

        chart.container("stageshare_dounotchart");

        chart.draw();
    });
</script>

<script>
    var topbrands = <?= $top_brands ?>;
    var brands = [];
    var brand_stats = [];

    for (const brand in topbrands) {
        if (brand === 'top_brands') {
            topbrands[brand].forEach(function(item, index) {
                brands.push(item.brand_name);
                brand_stats.push(item.total_deals);
            });
        } else {
            brands.push('Other Brands');
            brand_stats.push(topbrands[brand].toString());
        }
    }

    const ctx2 = document.getElementById("topBrands");
    if (ctx2) { // Check if canvas element exists
        new Chart(ctx2, {
            type: "bar",
            data: {
                labels: brands,
                datasets: [{
                    label: "Admissions",
                    data: brand_stats,
                    borderWidth: 1,
                    borderColor: "#000",
                }, ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    } else {
        console.error("Canvas element with ID 'topBrands' not found.");
    }
</script>


<script>
    // line with barr
    var stagesdata = <?= $deals_stage_share_data ?>;
    var stages = [];
    var stages_data = [];
    var admissions_donuts = [];

    for (const stage in stagesdata) {
        stages.push(stage);
        stages_data.push(stagesdata[stage]);
        var new_item = [];
        new_item.push(stage, stagesdata[stage]);
        admissions_donuts.push(new_item);
    }


    const ctx_2 = document.getElementById("admissionStagesShare");

    new Chart(ctx_2, {
        type: "line",
        data: {
            labels: stages,
            datasets: [{
                label: "Stages",
                data: stages_data,
                borderWidth: 2,
                borderColor: "#000",
            }, ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    anychart.onDocumentReady(function() {
        var data = anychart.data.set(admissions_donuts);
        var palette = anychart.palettes.distinctColors();

        var chart = anychart
            .pie(data)

            .innerRadius("60%");

        chart.container("admissionstageshare_dounotchart");

        chart.draw();
    });
</script>


@endpush