@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Leads') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leads') }}</li>
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

    .form-control:focus {
        border: 1px solid rgb(209, 209, 209) !important;
    }
</style>






<style>
    /*------------------------------
    Template name: Bootstrap 5 Center logo
    Author name: Md Enjamul Islam
    Author URI: https://github.com/mdenjamulislam
--------------------------------*/

body {
    font-family: cursive;
    font-size: 18px;
    font-weight: 400;
    line-height: 30px;
    color: #585858;
}
.navbar {
    padding: 15px 0;
}
.navbar-brand {

}
.navbar a {
    text-decoration: none;
}
h2.logo {
    font-size: 28px;
    font-weight: 400;
    color: #fff;
}
h2.logo strong {
    font-size: 35px;
    color: #EDF828;
    font-weight: 600;
}
h2.center-logo {
    margin: 0 25px;
}
#navbarSupportedContent {
    justify-content: center;
}
ul.navbar-nav {}
ul.navbar-nav li.nav-item {
    margin-right: 25px;
}
ul.navbar-nav li.nav-item:last-child {
    margin-right: 0;
}
ul.navbar-nav li.nav-item a.nav-link {
    color: #fff;
}
.navbar-light .navbar-nav .nav-link.active, .navbar-light .navbar-nav .show>.nav-link {
    color: #EDF828;
}
ul.navbar-nav li.nav-item a.nav-link:hover {
    color: #EDF828;
}
</style>
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
                <div class="card card-header">
                    <div id="header">
                        <div class="container">
                            <nav class="navbar navbar-expand-lg navbar-dark">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a class="nav-link">
                                               <div class="row d-column">
                                                <div class="col-md-6"><i class="ti ti-pencil"></i></div>
                                                <div class="col-md-6">Visa Analysis</div>
                                               </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link">
                                                <div class="row">
                                                    <div class="col-md-6"><i class="ti ti-pencil"></i></div>
                                                    <div class="col-md-6">Visa Analysis</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link">
                                                <div class="row">
                                                    <div class="col-md-6"><i class="ti ti-pencil"></i></div>
                                                    <div class="col-md-6">Visa Analysis</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style" style="padding: 25px 3px;">
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-4">
                            sds
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
@endpush
