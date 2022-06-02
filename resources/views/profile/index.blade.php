@extends('layouts.myapp')
@section('title','Update Profile')
{{-- @section('css')
<style>
    button.setting{
        float:right;
    }
    #demo{
        clear:both;
        float:right;
        background:#6c757d;
    }
    a.btn:hover{
        opacity:0.7;
    }
</style>
@endsection --}}
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
        <div class="alert autoremove alert-success" >
            <button type="button" class="close" data-dismiss="alert">x</button>
            <span class="text-success">{{session('message')}}</span>
        </div>
        @endif
        <div class="row d-flex justify-content-center">
            <h4>Personal Information</h4>
        </div>
        <div class="row">
        <div class="col-md-4">
            <img  class="rounded" style="width:200px;height:auto" src="{{asset('storage/images/profiles/'.$user->profile_image)}}">
        </div>
        <div class="col-md-4">
            Name:{{ $user->name }}
            <h5><i class="fa fa-envelope"></i>{{ $user->email }}</h5>
            <h5><i class="fa fa-phone"></i>{{ $user->phone_number }}</h5>
            <h5><i class="fa fa-map-marker"></i>{{ $user->address }}</h5>
        </div>
        <div class="col-md-4">
            {{-- <button type="button" class="btn btn-sm setting" data-toggle="collapse" data-target="#demo"><i class="fa fa-ellipsis-v"></i></button>
            <div id="demo" class="collapse">
                    <a class="btn btn-sm" style="color:white;text-decoration:none" href="{{route('profile.edit',$user->id)}}">Update Profile</a>
                <br>
                    <a class="btn btn-sm" style="color:white;text-decoration:none" href="/editPassword">Change Password</a>
                  
            </div> --}}
        </div>
    </div>
</div>
@endsection