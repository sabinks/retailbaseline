@extends('layouts.myapp')
@section('title','Update Password')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Change Password
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class="card-body">
        @if(session('password_notice'))
        <div class="alert autoremove alert-warning">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <span class="text-danger">{{session('password_notice')}}</span>
        </div>
        @endif
        <form method="post" action="/editPassword">
        @csrf
        @method('put')
            <div class="form-group">
            <label for="email">Old Password:</label>
                <input type="password" class="form-control @error('old_password') border border-danger @enderror" 
                name="old_password" placeholder="Enter old password" onpaste="return false" oncopy="return false">
                @error('old_password')<p class="text-danger">{{$message}}</p>@enderror
            </div>
            <div class="form-group">
                <label for="pwd">New Password:</label>
                <input type="password" class="form-control @error('new_password') border border-danger @enderror" 
                name="new_password" id="new_password" placeholder="Enter new password" onpaste="return false" oncopy="return false">
                @error('new_password')<p class="text-danger">{{$message}}</p>@enderror
            </div>
            <div class="form-group">
                <label for="pwd">Confirm New Password:</label>
                <input type="password" class="form-control @error('confirm_password') border border-danger @enderror" 
                name="confirm_password" placeholder="confirm new password" onpaste="return false" oncopy="return false">
                @error('confirm_password')<p class="text-danger">{{$message}}</p>@enderror
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            <a href="{{'/home'}}"  class="btn btn-sm btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection