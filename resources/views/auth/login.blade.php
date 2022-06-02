@extends('layouts.app')
@section('title','Login')
@section('loginCss')
<link rel="stylesheet" href="{{URL::asset('css/loginCss.css')}}">
<Style>
body{
     background: -webkit-gradient(linear, left top, left bottom, from(#5e3384), to(#4d959c)) fixed;
}
.popup {
    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
.col-xl, .col-xl-auto, .col-xl-12, .col-xl-11, .col-xl-10, .col-xl-9, .col-xl-8, .col-xl-7, .col-xl-6, .col-xl-5, .col-xl-4, .col-xl-3, .col-xl-2, .col-xl-1, .col-lg, .col-lg-auto, .col-lg-12, .col-lg-11, .col-lg-10, .col-lg-9, .col-lg-8, .col-lg-7, .col-lg-6, .col-lg-5, .col-lg-4, .col-lg-3, .col-lg-2, .col-lg-1, .col-md, .col-md-auto, .col-md-12, .col-md-11, .col-md-10, .col-md-9, .col-md-8, .col-md-7, .col-md-6, .col-md-5, .col-md-4, .col-md-3, .col-md-2, .col-md-1, .col-sm, .col-sm-auto, .col-sm-12, .col-sm-11, .col-sm-10, .col-sm-9, .col-sm-8, .col-sm-7, .col-sm-6, .col-sm-5, .col-sm-4, .col-sm-3, .col-sm-2, .col-sm-1, .col, .col-auto, .col-12, .col-11, .col-10, .col-9, .col-8, .col-7, .col-6, .col-5, .col-4, .col-3, .col-2, .col-1 {
    position: relative;
    width:auto; 
    padding-right: 15px;
    padding-left: 15px;
}
.login_form{
  position:relative;
  background: rgba(0, 0, 0, 0.5);
  color: #f1f1f1;
  width: 100%;
  padding: 20px;
}
.card-header{
    color:white !important;
}
input[type="email"],input[type="password"]{
    background:none !important;
    border:none;
    color:white !important;
}
img{
    width: 30%;
    height: auto;
    margin-top: -20%;
    margin-left: 33%;
}
</style>
@endsection
@section('content')
<div class="popup container">
    <div class="row justify-content-center">
        <div class="col-md-6 login_form">
        {{-- <img  src="{{asset('images/user.png')}}"> --}}
            <div>
                <div class="card-header">{{ __('Login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label text-md-right">
                                <i class="metismenu-icon fa fa-user-o"></i>
                            </label>

                            <div class="col-sm-8">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-2 col-form-label text-md-right">
                                <i class="metismenu-icon fa fa-unlock"></i>
                            </label>

                            <div class="col-sm-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-sm-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-sm-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-sm btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     
</div>
<div class="container">
    <div class="row d-flex justify-content-around fixed-bottom">
        <span>Note: <a href="/apk-app-download" class="text-decoration-none text-warning">Download Apk File</a></span>
    </div> 
</div>
@endsection
