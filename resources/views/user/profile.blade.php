@extends('layouts.admin')
@php
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
    {{__('Profile Account')}}
@endsection
@push('script-page')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function(){
            $('.list-group-item').filter(function(){
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
    </script>
    <style>
        h5::after{
            background-color: #313949!important
        }
        .form-control:focus{
            border: 1px solid gray !important;
        }
    </style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Profile')}}</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-3">
            <div class="card sticky-top" style="top:30px">
                <div class="list-group list-group-flush" id="useradd-sidenav">

                    <a href="#personal_info" class="list-group-item list-group-item-action border-0">{{__('Personal Info')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                    <a href="#bank_info" class="list-group-item list-group-item-action border-0">{{__('Bank Info')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                    <a href="#change_password" class="list-group-item list-group-item-action border-0">{{__('Change Password')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                </div>
            </div>
        </div>
        <div class="col-xl-9">

            <div id="personal_info" class="card" h5::after="" >
                <div class="card-header">
                    <h5>{{__('Personal Info')}}</h5>
                </div>
                <div class="card-body">
                    {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'post', 'enctype' => "multipart/form-data"))}}
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label text-dark">{{__('Name')}}</label>
                                    <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="name" placeholder="{{ __('Enter Your Name') }}" value="{{ $userDetail->name }}" required autocomplete="name">
                                    @error('name')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label for="email" class="col-form-label text-dark">{{__('Email')}}</label>
                                    <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $userDetail->email }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label text-dark">{{__('Phone')}}</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" name="phone" type="text" id="phone" placeholder="{{ __('Enter Phone Number') }}" value="{{ $userDetail->phone }}" required autocomplete="phone">
                                    @error('phone')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label text-dark">{{__('Date Of Birth')}}</label>
                                    <input class="form-control @error('dob') is-invalid @enderror" name="dob" type="date" id="dob" placeholder="{{ __('Enter Your Date Of Birth') }}" value="{{ $userDetail->date_of_birth }}" required autocomplete="dob">
                                    @error('dob')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label text-dark">{{__('Address')}}</label>
                                    <input class="form-control @error('address') is-invalid @enderror" name="address" type="text" id="address" placeholder="{{ __('Enter Your Address') }}" value="{{ $userDetail->address }}" required autocomplete="address">
                                    @error('address')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label text-dark">{{__('Passport Number')}}</label>
                                    <input class="form-control @error('passport_number') is-invalid @enderror" name="passport_number" type="text" id="passport_number" placeholder="{{ __('Enter Your Passport Number') }}" value="{{ $userDetail->passport_number }}" required autocomplete="passport_number">
                                    @error('passport_number')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 mt-3">
                                <div class="form-group">
                                    <div class="choose-files">
                                        <label for="avatar" class="w-100">
                                            <div class=" profile_update" style="background-color: #313949 !important;border-color:#313949!important;"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                            <input type="file" class="form-control file" name="profile" id="avatar" data-filename="profile_update">
                                        </label>
                                    </div>
                                    <span class=" text-muted">{{ __('Please upload a valid image file. Size of image should not be more than 2MB.')}}</span>
                                    @error('avatar')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror

                                </div>

                            </div>
                            <div class="col-lg-12 text-end">
                                <input type="submit" style="background-color: #313949 !important;border-color:#313949!important;" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </form>

                </div>

            </div>
            <div id="bank_info" class="card" >
                <div class="card-header">
                    <h5>{{__('Bank Info')}}</h5>
                </div>
                <div class="card-body">
                    {{Form::model($userDetail,array('route' => array('update.bankinfo'), 'method' => 'post', 'enctype' => "multipart/form-data"))}}
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label text-dark">{{__('Bank Name')}}</label>
                                    <input class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" type="text" id="bank_name" placeholder="{{ __('Enter Your Bank Name') }}" value="" required autocomplete="bank_name">
                                    @error('bank_name')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label  class="col-form-label text-dark">{{__('Account Holder Name')}}</label>
                                    <input class="form-control @error('account_holder_name') is-invalid @enderror" name="account_holder_name" type="text" id="account_holder_name" placeholder="{{ __('Enter Your Account Holder Name') }}" value="" required autocomplete="account_holder_name">
                                    @error('account_holder_name')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label  class="col-form-label text-dark">{{__('Account Number')}}</label>
                                    <input class="form-control @error('account_number') is-invalid @enderror" name="Account Number" type="text" id="account_number" placeholder="{{ __('Enter Your Account Number') }}" value="" required autocomplete="account_number">
                                    @error('account_number')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label  class="col-form-label text-dark">{{__('Branch Location')}}</label>
                                    <input class="form-control @error('branch_location') is-invalid @enderror" name="branch_location" type="text" id="branch_location" placeholder="{{ __('Enter Your Branch Location') }}" value="" required autocomplete="branch_location">
                                    @error('branch_location')
                                    <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12 text-end">
                                <input type="submit" style="background-color: #313949 !important;border-color:#313949!important;" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </form>

                </div>

            </div>
            <div id="change_password" class="card">
                <div class="card-header">
                    <h5>{{__('Change Password')}}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('update.password')}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 form-group">
                                <label for="old_password" class="col-form-label text-dark">{{ __('Old Password') }}</label>
                                <input class="form-control @error('old_password') is-invalid @enderror" name="old_password" type="password" id="old_password" required autocomplete="old_password" placeholder="{{ __('Enter Old Password') }}">
                                @error('old_password')
                                <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-sm-6 form-group">
                                <label for="password" class="col-form-label text-dark">{{ __('New Password') }}</label>
                                <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                                @error('password')
                                <span class="invalid-feedback text-danger " role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-6 form-group">
                                <label for="password_confirmation" class="col-form-label text-dark">{{ __('New Confirm Password') }}</label>
                                <input class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" type="password" required autocomplete="new-password" id="password_confirmation" placeholder="{{ __('Enter Your Password') }}">
                            </div>
                            <div class="col-lg-12 text-end">
                                <input type="submit" style="background-color: #313949 !important;border-color:#313949!important;" value="{{__('Change Password')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
@endsection
