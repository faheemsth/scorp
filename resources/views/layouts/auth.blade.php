<!DOCTYPE html>
@php
    use App\Models\Utility;

    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');

    $company_logo = Utility::getValByName('company_logo_dark');
    $company_logos = Utility::getValByName('company_logo_light');
    $company_favicon = Utility::getValByName('company_favicon');
    $setting = \App\Models\Utility::colorset();
    $mode_setting = \App\Models\Utility::mode_layout();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    $company_logo = \App\Models\Utility::GetLogo();
    $SITE_RTL = isset($setting['SITE_RTL']) ? $setting['SITE_RTL'] : 'off';

@endphp



{{-- <html lang="en" dir="{{$SITE_RTL == 'on' ? 'rtl' : '' }}"> --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

<head>
    <title>
        {{ Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'ERPGO') }}
        - @yield('page-title')</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />

    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image/x-icon" />

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->

    @if ($setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif


    {{--    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .logosite {
            width: 25%;
        }

        .loginimg {
            width: 70%;
        }

        .textper {
            font-size: 1.6rem;
        }

        .textp {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .mainlogindiv {
            width: 70%;
        }

        .rempass {
            font-size: 15px;
        }

        .textdiv {
            width: 75%;
            text-align: left;
        }



        @media only screen and (max-width: 1024px) {
            .mainlogindiv {
                width: 85%;
            }

            .logosite {
                width: 40%;
            }
            .textper {
            font-size: 1.6rem;
        }
            .loginimg {
                width: 57%;
            }

            .textdiv {
                width: 85%;
            }
        }

        .form-control:focus {
            box-shadow: none !important;
            border: 0px !important;
            outline: none !important;
        }
        @media only screen and (max-width: 768px) {
            .mainlogindiv {
                width: 90%;
            }

            .logosite {
                width: 45%;
            }

            .loginimg {
                width: 90% !important;
            }

            .textdiv {
                width: 100% !important;
            }

            .textper {
                font-size: 1.4rem;
            }
        }

        @media only screen and (max-width: 425px) {
            .mainlogindiv {
                width: 80%;
            }

            .logosite {
                width: 75%;
            }

            .rempass {
                font-size: 14px;
            }
        }
body::-webkit-scrollbar {
  display: none;
}

body{
  -ms-overflow-style: none;
  scrollbar-width: none;
}
    </style>
</head>

<body class="{{ $color }}">
    <div class="container-fluid px-0 loginpagescroll">
        <nav class="navbar navbar-expand-md navbar-light " style="background-color: #B3CDE1;">
            <div class="container-fluid px-md-5 mx-lg-5">
                <a class="navbar-brand w-50" href="#">
                    <img src="{{ asset('assets/cs-theme/assets/images/Isolation_Mode.png') }}" alt="" srcset="" class="logosite">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Support</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Terms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Privacy</a>
                        </li>
                        {{-- <li class="nav-item">
                    <select class="  btn btn-primary px-3 " style="border: none;box-shadow: none;background-color: #1F2635;color: white;">
                        <option selected>EN</option>
                        <option value="3">AR</option>
                        <option value="1">DA</option>
                        <option value="2">DR</option>
                      </select>

                  </li> --}}
                        @yield('auth-topbar')
                    </ul>
                </div>
            </div>
        </nav>
        <div class="row px-0 mx-0 justify-content-end" style="min-height:calc( 100vh - 9.4vh);">
            <div
                class="col-lg-8 col-md-7  d-none d-md-flex justify-content-center align-items-center py-5 py-lg-0 mt-3 mt-lg-0">
                <div class="mainlogindiv text-center">

                    <img src="{{ asset('assets/cs-theme/assets/images/Frame.png') }}" alt="" class="loginimg">

                    <div class="textdiv mt-3 mx-auto">
                        <p class="textper mb-0 my-3" style="color: #1F2635;  font-weight: bold;">"Attention is the New
                            Currency"</p>
                        <p class="textp" style="color: #1F2635; "> The more effortless the writing looks, the more
                            effort the
                            writer actually put into the
                            process.</p>
                    </div>
                </div>
            </div>


            <!-- SECond section -->

            <div class="col-12 col-md-4  d-flex align-items-center justify-content-center py-5 py-lg-0"
                style="background-color: #1F2635;">
                @yield('content')
            </div>
        </div>
    </div>
        <div class="auth-footer d-none">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <p class="">
                            {{ Utility::getValByName('footer_text') ? Utility::getValByName('footer_text') : __('Copyright ERPGO') }}
                            {{ date('Y') }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

    <!-- [ auth-signup ] end -->

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>


    <script>
        feather.replace();
        var pctoggle = document.querySelector("#pct-toggler");
        if (pctoggle) {
            pctoggle.addEventListener("click", function() {
                if (
                    !document.querySelector(".pct-customizer").classList.contains("active")
                ) {
                    document.querySelector(".pct-customizer").classList.add("active");
                } else {
                    document.querySelector(".pct-customizer").classList.remove("active");
                }
            });
        }

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }



        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });

        var custdarklayout = document.querySelector("#cust-darklayout");
        custdarklayout.addEventListener("click", function() {
            if (custdarklayout.checked) {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src", "{{ asset('assets/images/logo.svg') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
            } else {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src", "{{ asset('assets/images/logo-dark.png') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "{{ asset('assets/css/style.css') }}");
            }
        });

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>
    @stack('custom-scripts')
</body>

</html>
