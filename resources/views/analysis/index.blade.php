@extends('layouts.admin')
@section('page-title')
    {{ __('Analysis Report') }}
@endsection



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
        color: white; /* Set text color to ensure visibility on the dark background */
    }
</style>


@section('content')
<div class="row">
    <div class="col-xl-12">
        <!-- Tabs -->
        <div class="container">
            <div class="row justify-content-center align-items-center my-4">
                <a class="col-3 py-5 text-decoration-none" href="/analysis?type=visas" style="background-color: #B3CDE1; border-radius: 5%;">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-auto d-flex flex-column gap-2 justify-content-center align-items-center">
                            <div style="border: 2px solid black; border-radius: 50%;text-align: center;width: 25px;height: 25px;"
                                class="p-1 mb-1 ">
                                <i class="fa-solid fa-dollar-sign" style="color:black"></i>
                            </div>
                            <h6 class="fw-bold">Visas Analysis</h6>
                        </div>
                    </div>
                </a>
                <a class="col-3 py-5 text-decoration-none" href="/analysis?type=deposite">
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

                <a class="col-3 py-5 text-decoration-none" href="/analysis?type=">
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
            <div class="d-flex justify-content-center align-items-center py-2"
                style="background-color: #1F2735;">
                <div class="col-md-1 text-center text-white">
                    <p class="mb-0">Total Admissions</p>
                    <h4 class="text-white">{{  str_pad(array_sum($total_admissions), 2, '0', STR_PAD_LEFT) }}</h4>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('brands', 'Brand') !!}
                        {!! Form::select('brand_id', $filter_companies, $_GET['brand_id'] ?? '', ['id' => 'brand_id', 'class' => 'form form-control select2 text-dark']) !!}
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group" id="region_div">
                        {!! Form::label('regions', 'Regions') !!}
                        {!! Form::select('region_id', [], null, ['id' => 'region_id', 'class' => 'form form-control select2 text-dark']) !!}
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group" id="branch_div">
                        {!! Form::label('branch_id', 'Branches') !!}
                        {!! Form::select('branch_id', [], null, ['id' => 'branch_id', 'class' => 'form form-control select2 text-dark']) !!}
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('Institutes', 'institutes') !!}
                    {!! Form::select('institute_id', $filter_institutes, null, ['id' => 'institute_id', 'class' => 'form form-control select2 text-dark']) !!}
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group" id="intakemonth_div">
                        {!! Form::label('Intake Month', 'Intake Month') !!}
                        {!! Form::select('intake_month', [], null, ['id' => 'intake_month', 'class' => 'form form-control select2 text-dark']) !!}
                    </div>
                </div>

                <div class="col-md-1">
                    <input type="submit" value="Submit" class="btn btn-dark">
                </div>
            </div>
        </form>


        <div class="row">
            <div class="col-6">
                <div class="card mt-3">
                    <div class="card-body table-border-style" style="padding: 25px 3px;">
                        <div class="row align-items-center justify-content-center ps-0 ms-0 pe-4 my-2">
                            <div class="col-12">
                                <h5>Admission Analysis</h5>
                                <canvas id="GrantedByCountry" style="width:100%;max-width:600px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
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
    const brands = {!! json_encode(array_values($brands)) !!};
    const admissions = {!! json_encode(array_values($total_admissions)) !!};

    const institutes = {!! json_encode(array_values($institutes)) !!};
    const total_app = {!! json_encode(array_values($total_app)) !!};
    

    const data = {
        labels: brands,
        datasets: [{
            type: 'bar',
            label: 'Admissions',
            data: admissions,
            borderColor: '#B3CDE1',
            backgroundColor: '#B3CDE1'
        }]
    };

    var ctx = document.getElementById('GrantedByCountry').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: data
    });

    ///////////////for university applications
    const data_university = {
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
    });
</script>


<script>
    $(document).ready(function(){
        $(document).on("change", "#brand_id", function(){
            var id = $(this).val();
            var type = 'brand';

            $.ajax({
                type: 'GET',
                url: '{{ route('region_brands') }}',
                data: {
                    id: id,  // Add a key for the id parameter
                    type: type
                },
                success: function(data){
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
        $(document).on("change", "#region_id" ,function(){
            var id = $(this).val();
            var type = 'region';
            $.ajax({
                type: 'GET',
                url: '{{ route('region_brands') }}',
                data: {
                    id: id,  // Add a key for the id parameter
                    type: type
                },
                success: function(data){
                    data = JSON.parse(data);

                    if (data.status === 'success') {
                        if(type == 'region'){
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
        $(document).on("change", "#institute_id", function(){
            var id = $(this).val();
            var type = 'institute';
            $.ajax({
                type: 'GET',
                url: '{{ route('region_brands') }}',
                data: {
                    id: id,  // Add a key for the id parameter
                    type: type
                },
                success: function(data){
                    data = JSON.parse(data);

                    if (data.status === 'success') {
                        if(type == 'institute'){
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