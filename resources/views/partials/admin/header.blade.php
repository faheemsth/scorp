@push('css-page')
    <link rel="stylesheet" href="assets/css/customizer.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@php
    $users = \Auth::user();
    $logo_dark = \App\Models\Utility::getValByName('company_logo_dark');
    $notifications = \App\Models\Notification::all();

    //$profile=asset(Storage::url('uploads/avatar/'));
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $languages = \App\Models\Utility::languages();
    $lang = isset($users->lang) ? $users->lang : 'en';
    $setting = \App\Models\Utility::colorset();
    $mode_setting = \App\Models\Utility::mode_layout();
    $adminOption = \App\Models\User::where('type', Session::get('onlyadmin'))->first();
    if (Session::get('is_company_login') == true) {
        $currentUserCompany = \App\Models\User::where('type', 'company')->find(Session::get('auth_type_created_by'));
    } else {
        $currentUserCompany = \App\Models\User::where('type', 'company')->find(\Auth()->user()->created_by);
    }
    // dd(Session::get('auth_type_created_by'));
    $com_permissions = [];
    if ($currentUserCompany != null) {
        $com_permissions = \App\Models\CompanyPermission::where('user_id', \Auth::user()->id)->get();
    }

    $all_companies = App\Models\User::where('type', 'company')
        ->pluck('name', 'id')
        ->toArray();

    $unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
        ->where('seen', 0)
        ->count();
@endphp

<nav class="navbar navbar-expand navbar-light topbar  static-top shadow" style="background-color: #B3CDE1;">
    <button id="sidebarToggleTop" class="btn d-md-none ">
        <i class="fa fa-bars"></i>
    </button>
    <div class="logo ms-md-2">
        <a href="#">
            <!-- <img src="{{ asset('assets/cs-theme/assets/images/scorp-logo.png') }}" alt=""> -->
            <img id="image" src="{{ asset('storage/uploads/logo').'/'.(isset($logo_dark) && !empty($logo_dark)?$logo_dark:'assets/cs-theme/assets/images/scorp-logo.png') }}"
                                                         class="big-logo">
        </a>
    </div>
    <!-- Sidebar Toggle (Topbar) -->


    <!-- Topbar Search -->
    <form action="{{ route('global-search') }}" method="GET" id="globalSearchForm"
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 d-none navbar-search me-0"
        style="margin:auto !important;">
        <div class="input-group " style="border: none;border-radius: 0px;">
            <input style="border: none;border-radius: 0px;" type="text" name="search" class="form-control bg-light m-0"
                placeholder="Search for..." value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}">
            <div class="input-group-append" style="border: none;border-radius: 0px;">
                <span class="input-group-text bg-light" id="global-search-btn" style="border: none;border-radius: 0px;">
                    <i class="ti ti-search" style="font-size: 18px"></i> <!-- Add your search icon here -->
                </span>
            </div>
        </div>

        <input type="hidden" class="" name="global_search" value="all">
    </form>

    @if (\Auth::user()->type == 'super admin')
        <select name="company" id="company" class="form form-select" style="width:15% !important"
            onChange="loginWithCompany();">
            <option value="">Select Companies</option>
            <option value="{{ Auth::id() }}" {{ Auth::id() == 1? 'selected':'' }}>{{ Auth::user()->name }}</option>
            @foreach ($all_companies as $key => $comp)
                <option value="{{ $key }}" >{{ $comp }}</option>
            @endforeach
        </select>
    @elseif(\Auth::user()->type == 'Project Manager' || \Auth::user()->type == 'Project Director')
        @if ($currentUserCompany != null)
            <select name="company" id="company" class="form form-select" style="width:15% !important"
                onChange="loginWithCompany();">
                <option value="">Select Companies</option>
                @foreach ($all_companies as $key => $comp)
                    @if ($key == $currentUserCompany->id)
                        <option value="{{ $key }}" selected><a
                                href="{{ url('logged_in_as_customer') . '/' . $key }}">{{ $comp }}</a></option>
                    @endif
                    @foreach ($com_permissions as $com_per)
                        @if ($com_per->permitted_company_id == $key)
                            <option value="{{ $key }}"><a
                                    href="{{ url('logged_in_as_customer') . '/' . $key }}">{{ $comp }}</a></option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        @endif
    @else
        @if (Session::get('is_company_login') == true && Session::get('auth_type') == 'super admin')
            <select name="company" id="company" class="form form-select" style="width:15% !important"
                onChange="loginWithCompany();">
                <option value="">Select Companies</option>
                @if (!empty($adminOption))
                    <option value="{{ $adminOption->id }}">{{ $adminOption->name }}</option>
                @endif
                @foreach ($all_companies as $key => $comp)
                    <option value="{{ $key }}" {{ Auth::id() == $key? 'selected':'' }}>{{ $comp }}</option>
                @endforeach
            </select>
        @elseif (Session::get('auth_type') == \Auth::user()->type ||
                Session::get('auth_type') == 'Project Director' ||
                Session::get('auth_type') == 'Project Manager')
            <select name="company" id="company" class="form form-select" style="width:15% !important"
                onChange="loginWithCompany();">
                <option value="">Select Companies</option>

                <option value="{{ Session::get('auth_type_id') }}">{{Session::get('auth_type')}}</option>

                @foreach ($all_companies as $key => $comp)
                    @foreach ($com_permissions as $com_per)
                        @if ($com_per->permitted_company_id == $key)
                            <option value="{{ $key }}" {{ Auth::id() == $key? 'selected':'' }}><a
                                    href="{{ url('logged_in_as_customer') . '/' . $key }}">{{ $comp }}</a>
                            </option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        @endif
    @endif

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in d-none"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in d-none"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-bell" style="font-size: 19px; color: #000;"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter"></span>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="width: 225px !important;">
                {{-- @foreach($notifications as $notification)
                    {!! $notification->data !!}
                @endforeach --}}
                <ul>
                    <li class="px-2">
                        <h6 class="mb-0">title</h6>
                        <p class="mb-1" style="color: gray">Discription</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#b2b2b2;font-size: 13px;">12-27-2023</span>
                            <a href="!#" class="text-decoration-none">
                                Clear
                            </a>
                        </div>
                        <hr style="color: #dddddd00;" class="my-1">
                    </li>
                    <li class="px-2">
                        <h6 class="mb-0">title</h6>
                        <p class="mb-1" style="color: gray">Discription</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#b2b2b2;font-size: 13px;">12-27-2023</span>
                            <a href="!#" class="text-decoration-none">
                                Clear
                            </a>
                        </div>
                        <hr style="color: #dddddd00;" class="my-1">
                    </li>
                </ul>
            </div>
        </li>
            <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-regular fa-circle-question" style="font-size: 19px; color: #000;"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter"></span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="messagesDropdown">


                <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span> -->
                @if(\Auth::user()->avatar == null || \Auth::user()->avatar == '')
                <img class="img-profile rounded-circle" src="{{ asset('assets/images/user/default.jpg') }}">
                @else
                <img class="img-profile rounded-circle" src="{{ asset('storage/uploads/avatar').'/'.Auth::user()->avatar }}">
                @endif
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>

                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    <i class="fa fa-sign-out fa-sm fa-fw mr-2 text-gray-400"></i>
                    {{ __('Logout') }}
                </a>
                <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
    </ul>




</nav>

@push('script-page')
    <script>
        $(document).ready(function() {

            $("#global-search-btn").on("click", function() {
                $("#globalSearchForm").submit();
            });

            $('#global-search-bt').keydown(function(event) {
                if (event.keyCode === 13) {
                    $('#globalSearchForm').submit();
                }
            });


            // $("#globalSearchDropdown").on("change", function() {
            //     $("#globalSearchForm").submit();
            // })
        })

        function loginWithCompany() {
            let value = $('#company').val();
            if(value !== '')
            {
                window.location.href = "{{ url('logged_in_as_company') }}/" + value;
            }
        }

        function LoginBack(value) {
            window.location.href = "{{ url('logged_in_as_user') }}/" + value;
        }
    </script>
@endpush
