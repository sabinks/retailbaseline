@extends('layouts.myapp')
@section('title','Update Profile')
@section('css')
<style>
.card-body{
     background: -webkit-gradient(linear, left top, left bottom, from(#5e3384), to(#4d959c)) fixed;
     padding:1.5 rem;
     color:white;
}
</style>
@endsection
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                User Profile
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class="card-body">
        @if(session('message'))
        <div class="alert alert-success" >
            <button type="button" class="close" data-dismiss="alert">x</button>
            <span class="text-success">{{session('message')}}</span>
        </div>
        @endif
        <div class="row d-flex justify-content-center">
            <img style="width:30%;height:auto" src="{{asset('storage/images/logos/'.$company->company_logo)}}">
        </div>
        <div class="row d-flex justify-content-center">
            <div>
            <h4>Name:{{ $company->company_name }}</h4>
            <h4>Web:{{ $company->webaddress }}</h4>
            <h4>Phone:{{ $company->company_phone_number }}</h4>
            <h4>Address:{{ $company->company_address }}</h4>
            </div>
        </div>
        @if(Auth::user()->hasRole(['Super Admin','Admin']))
            <div class="setting__update_info">
                <a class="url" style="color:white;text-decoration:none" 
                href="{{'updatecompany/'}}">Update Company Info</a>
                </div>
            </a>
        @endif
       
    </div>
</div>
@endsection