@extends('layouts.myapp')
@section('title',$user->name)
@section('select2')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Regional Admin Info
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        View users Information
    </div>
    <div class="card-body">
    <a href="{{URL::previous()}}"><i class="material-icons">arrow_back</i></a><h5 style="text-align:center">Information of Regional Admin</h5>
        <div class="row" style="text-align:center">
            <div class="col-md-12">
                @if($user->profile_image==''||$user->profile_image==NULL)
                    <img style="width:200px;height:auto" src="{{asset('images/user.png')}}">
                @endif
                @if($user->profile_image!=NULL)
                    <img style="width:200px;height:auto" src="{{asset('storage/images/profiles/'.$user->profile_image)}}">
                @endif
            </div>
            <div class="row" style="width:100%;margin:2%">
                <div class="col-md-12" style="text-align:center">
                    <h5>{{ $user->name }}</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"> 
               <a title="mail" href="mailto:{{ $user->email }}">
                    <i class="fa fa-envelope"></i>{{ $user->email }}</a>
            </div>
            <div class="col-md-3"> 
                <a title="Phone" href="tel:{{ $user->phone_number }}">
                    <i class="fa fa fa-phone"></i>{{ $user->phone_number }}</a>
            </div>
            <div class="col-md-3"> 
                <i title="Adress" class="fa fa-map-marker"></i>{{ $user->address }}
            </div>
            <div class="col-md-3">
                @foreach ( $user->creators as $creator ) 
                     Created by:-{{ $creator->name }}<br>
                @endforeach
            </div>    
        </div>

        {{-- <div class="row">
            <div class="col-md-12">
                <h4>Regional Admin Record's History</h4>
            </div>
        </div> --}}
    </div>
</div>
@endsection