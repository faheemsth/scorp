@extends('layouts.auth')
@php
    use App\Models\Utility;
      //  $logo=asset(Storage::url('uploads/logo/'));
           $logo=\App\Models\Utility::get_file('uploads/logo');

        $company_logo=Utility::getValByName('company_logo');
        $settings = Utility::settings();

@endphp
@push('custom-scripts')
    @if(env('RECAPTCHA_MODULE') == 'on')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush
@section('page-title')
    {{__('Login')}}
@endsection

@section('auth-topbar')
    <li class="nav-item ">
        <select class="  btn btn-primary px-3 " style="border: none;box-shadow: none;background-color: #1F2635;color: white;" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" id="language">
            @foreach(Utility::languages() as $language)
                <option class="" @if($lang == $language) selected @endif value="{{ route('login',$language) }}">{{Str::upper($language)}}</option>
            @endforeach
        </select>
    </li>
@endsection
@section('content')
{{Form::open(array('class'=>'mainlogindiv','route'=>'login','method'=>'post','id'=>'loginForm' ))}}
@csrf
    <div class="text-center">
        <i class="fa-solid fa-circle-user mb-4 " style=" font-size:90px ; color: #A6A6A6; "></i>
    </div>
    <div class="input-group mb-3 border-0">
        <span class="input-group-text bg-white ps-2 pe-0" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
        <input type="email" class="form-control border-0 @error('email') is-invalid @enderror" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">
         {{-- @error('email')
            <div class="invalid-feedback" role="alert" style="display:block !important">{{ $message }}</div>
            @enderror --}}
            @if (session('error'))
                <div class="invalid-feedback" role="alert" style="display:block !important">
                    {{ session('error') }}
                </div>
            @endif
    </div>
    @if(env('APP_ENV') == 'local')
      <div class="input-group mb-3 border-0">
        <span class="input-group-text bg-white ps-2 pe-0" id="basic-addon1"><i class="fa-solid fa-key"></i></span>
        <input type="text" class="form-control border-0 @error('password') is-invalid @enderror" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
        @error('password')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
    @endif
    @if(env('RECAPTCHA_MODULE') == 'on')
    <div class="form-group mb-3">
        {!! NoCaptcha::display() !!}
        @error('g-recaptcha-response')
        <span class="small text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
        @enderror
    </div>
@endif
    <div class="mb-3 form-check d-flex justify-content-between align-items-baseline">
     <div>
        <input type="checkbox" class="form-check-input">
        <label class="form-check-label rempass " for="exampleCheck1" style="color: #A6A6A6;">Remember me</label>

     </div>
     @if(env('APP_ENV') == 'local')
     @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" style="text-decoration: none; color:#A6A6A6;" class="rempass">Forgotpassword?</a>
     @endif
     @endif
    </div>
    <div class="text-center">
        <button type="submit" id="login_button" class=" w-50 p-2  mt-3 "
            style="background-color: #000000; color:#FFFFFF ;">Login</button>
    </div>
    {{Form::close()}}
@endsection

<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#form_data").submit(function (e) {
            $("#login_button").attr("disabled", true);
            return true;
        });
    });
</script>

