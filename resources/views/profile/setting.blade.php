@extends('layouts.myapp')
@section('title','Update Profile')
@section('css')
<style>
.setting{
     background: -webkit-gradient(linear, left top, left bottom, from(#5e3384), to(#4d959c)) fixed;
     padding:1.5 rem;
     color:white;
     height:25vw;   
}
.card-body {
    padding: 0px !important;
}
.setting__update_info{
    width:100%;
    text-align:center;
    padding:4%;
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
                Setting
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="row setting">
            <div class="setting__update_info">
                <a class="url" style="color:white;text-decoration:none" 
                href="{{route('profile.edit',Auth::user()->id)}}">Profile Update</a>
            </div>
            
            @if(Auth::user()->hasRole(['Super Admin','Admin']))
                <div class="setting__update_info">
                    <a class="url" style="color:white;text-decoration:none" 
                    href="{{'updatecompany/'}}">Update Company Info</a>
                    </div>
                </a>
            @endif 

            <div class="setting__update_info">
                <a class="url" style="color:white;text-decoration:none" href="/editPassword">Password Change</a>
            </div>
        </div>
        {{-- <div>
            <button type="button" class="btn btn-sm btn-warning"><a style="color:white;text-decoration:none" href="{{route('profile.edit',$user->id)}}">Update Profile</a></button>
            <button type="button" class="btn btn-sm btn-warning"><a style="color:white;text-decoration:none" href="/editPassword">Change Password</a></button>
        </div> --}}
       
    </div>
</div>
@endsection