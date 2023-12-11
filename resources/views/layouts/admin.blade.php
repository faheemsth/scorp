@php
use App\Models\Utility;

//$logo=asset(Storage::url('uploads/logo/'));
$logo=\App\Models\Utility::get_file('uploads/logo');

$company_favicon=Utility::getValByName('company_favicon');
$setting = \App\Models\Utility::colorset();
$company_logo = \App\Models\Utility::GetLogo();
$mode_setting = \App\Models\Utility::mode_layout();
$color = (!empty($setting['color'])) ? $setting['color'] : 'theme-3';
$SITE_RTL = Utility::getValByName('SITE_RTL');
$lang=Utility::getValByName('default_language');


@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$SITE_RTL == 'on' ? 'rtl' : '' }}">


<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<head>
    <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'ERPGO')}} - @yield('page-title')</title>
    <!-- <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script> -->

    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="url" content="{{ url('').'/'.config('chatify.path') }}" data-user="{{ Auth::user()->id }}">
    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">

    <!-- Favicon icon -->
    {{-- <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon"/>--}}
    <!-- Calendar-->
    @stack('css-page')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">



    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!--bootstrap switch-->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

    <!-- vendor css -->
    @if ($SITE_RTL == 'on')
    <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
    <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" id="main-style-link">

    @stack('css-page')

    <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}">

    <link href="{{ asset('cs-theme/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .All-leads {
            border: 0px;
            background-color: transparent;
            font-size: 16px;
            padding: 4px;
            line-height: 20px;
        }

        .All-leads:hover {
            border: 1px solid rgb(223, 220, 220);
        }

        .input-group {
            border: 1px solid rgb(102, 102, 102)
        }

        input:focus {
            box-shadow: none !important;
        }

        .btn-primary {
            background-color: #b5282f !important;
            border-color: #b5282f !important;
        }


        .hover-text-color {
            color: blue;
        }
    </style>





    <style>
        .stages h2 {
            font-size: 16px;
            line-height: 14px;
            display: inline-block;
            white-space: nowrap;
            font-weight: bold;
            margin-top: 10px;
        }

        .wizard {
            font-size: 8px;
        }

        .wizard a {
            padding: 8px 8px 8px 20px;
            background: #efefef;
            position: relative;
            display: inline-block;
        }

        .wizard a:before {
            width: 0;
            height: 0;
            border-top: 20px inset transparent;
            border-bottom: 20px inset transparent;
            border-left: 20px solid #fff;
            position: absolute;
            content: "";
            top: 0;
            left: 0;
        }

        .wizard a:after {
            width: 0;
            height: 0;
            border-top: 20px inset transparent;
            border-bottom: 20px inset transparent;
            border-left: 20px solid #efefef;
            position: absolute;
            content: "";
            top: 0;
            right: -20px;
            z-index: 2;
        }

        .wizard a:first-child:before,
        .wizard a:last-child:after {
            border: none;
        }

        .wizard a:first-child {
            -webkit-border-radius: 4px 0 0 4px;
            -moz-border-radius: 4px 0 0 4px;
            border-radius: 4px 0 0 4px;
        }

        .wizard a:last-child {
            -webkit-border-radius: 0 4px 4px 0;
            -moz-border-radius: 0 4px 4px 0;
            border-radius: 0 4px 4px 0;
        }

        .wizard .badge {
            margin: 0 5px 0 18px;
            position: relative;
            top: -1px;
        }

        .wizard a:first-child .badge {
            margin-left: 0;
        }

        .wizard .current {
            background: #b5282f;
            color: #fff;
        }

        .wizard .current:after {
            border-left-color: #b5282f;
        }

        .wizard .done {
            background: #4fee0e;
            color: #fff;
        }

        .wizard .done:after {
            border-left-color: #4fee0e;
        }


        .lead-topbar {
            border-bottom: 1px solid rgb(230, 230, 230);
            background-color: #e9ecef;
        }

        .lead-avator img {
            width: 50px;
            height: 50px;
            /* border-radius: 50px; */
        }

        .lead-basic-info {
            margin: 10px 0px -10px 10px;
        }

        .lead-basic-info span {
            font-size: 10px;
        }

        .lead-basic-info p {
            font-weight: bold;
        }

        .lead-info {
            border-bottom: 1px solid rgb(230, 230, 230);
        }

        .lead-info small {
            font-size: 13px;
            line-height: 14px;
            display: block;
        }

        .lead-info span {
            font-size: 14px;
            line-height: 18px;
            width: calc(100% - 10px);
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .action-btn {
            display: inline-grid !important;
        }

        .accordion-button {
            font-size: 13px !important;
            justify-content: flex-end;
            flex-direction: row-reverse;
            margin-right: 10px;
            letter-spacing: 0.02rem;
            font-weight: 700;
        }

        .accordion-button::after {
            margin-left: 0px !important;
            margin-right: 10px;
        }

        .accordion-button:focus {
            box-shadow: none;
            background: #e9ecef !important;
        }

        .accordion-button.collapsed::after {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='220px' height='220px'%3E%3Cpath d='M12.1717 12.0005L9.34326 9.17203L10.7575 7.75781L15.0001 12.0005L10.7575 16.2431L9.34326 14.8289L12.1717 12.0005Z'%3E%3C/path%3E%3C/svg%3E") !important;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'  width='32' height='32' %3E%3Cpath d='M12.1717 12.0005L9.34326 9.17203L10.7575 7.75781L15.0001 12.0005L10.7575 16.2431L9.34326 14.8289L12.1717 12.0005Z'%3E%3C/path%3E%3C/svg%3E") !important;
            transform: rotate(90deg) !important;
        }

        #tfont {
            font-size: 12px;
        }

        .links:hover {
            text-decoration: underline;
        }

        .links-icon:hover {
            background-color: #eee;
        }

        @media screen and (max-width: 480px) {
            .dash-header {
                left: 0 !important;
            }
        }

        @media screen and (max-width: 768px) {
            .dash-header {
                left: 0 !important;
            }
        }

        @media screen and (max-width: 1024px) {
            .dash-header {
                left: 0 !important;
            }
        }
    </style>

</head>

<body class="{{ $color }}" id="body">

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- [ Header ] start -->
    @include('partials.admin.header')

    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('partials.admin.menu')
        <!-- [ navigation menu ] end -->

        <div id="mySidenav" style="z-index: 1065; padding-left:5px; box-shadow: -5px 0px 30px 0px #aaa;" class="sidenav <?= isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>" style="padding-left: 5px"></div>


        <!-- Modal -->
        <div class="modal notification-modal fade" id="notification-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h6 class="mt-2">
                            <i data-feather="monitor" class="me-2"></i>Desktop settings
                        </h6>
                        <hr />
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="pcsetting1" checked />
                            <label class="form-check-label f-w-600 pl-1" for="pcsetting1">Allow desktop notification</label>
                        </div>
                        <p class="text-muted ms-5">
                            you get lettest content at a time when data will updated
                        </p>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="pcsetting2" />
                            <label class="form-check-label f-w-600 pl-1" for="pcsetting2">Store Cookie</label>
                        </div>
                        <h6 class="mb-0 mt-5">
                            <i data-feather="save" class="me-2"></i>Application settings
                        </h6>
                        <hr />
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="pcsetting3" />
                            <label class="form-check-label f-w-600 pl-1" for="pcsetting3">Backup Storage</label>
                        </div>
                        <p class="text-muted mb-4 ms-5">
                            Automaticaly take backup as par schedule
                        </p>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="pcsetting4" />
                            <label class="form-check-label f-w-600 pl-1" for="pcsetting4">Allow guest to print file</label>
                        </div>
                        <h6 class="mb-0 mt-5">
                            <i data-feather="cpu" class="me-2"></i>System settings
                        </h6>
                        <hr />
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="pcsetting5" checked />
                            <label class="form-check-label f-w-600 pl-1" for="pcsetting5">View other user chat</label>
                        </div>
                        <p class="text-muted ms-5">Allow to show public user message</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger btn-sm" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-light-primary btn-sm">
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Header ] end -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <div class="container-fluid">
                    <div class="my-3 ">
                        <h4 style="color: #000;"><strong>@yield('page-title')</strong></h4>

                        <ul class="breadcrumb">
                            @yield('breadcrumb')
                        </ul>
                    </div>



                    @yield('content')
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
        <div id="liveToast" class="toast text-white fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    @include('partials.admin.footer')

    @include('Chatify::layouts.footerLinks')
    @push('script-page')

    <!-- Resize Table Column -->
    <script src="{{ asset('assets/js/drag-resize-columns/dist/jquery-3.6.1.slim.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/drag-resize-columns/dist/store.min.js') }}"></script> -->
    <script src="{{ asset('assets/js/drag-resize-columns/dist/jquery.resizableColumns.min.js') }}"></script>
    <script>
        $(function() {
            $("table").resizableColumns({
                store: window.store
            });
        });
    </script>
    @endpush

    <!-- CS Theme -->
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('cs-theme/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('cs-theme/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('cs-theme/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('cs-theme/js/sb-admin-2.min.js') }}"></script>
</body>
<div class="block-screen"></div>

</html>