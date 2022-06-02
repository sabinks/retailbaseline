@extends('layouts.myapp')
@section('title','Reset Password')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                <strong>Reset User Password</strong>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class="card-body">
        @if(session('error'))
        <div class="alert autoremove alert-warning">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <span class="text-danger">{{session('error')}}</span>
        </div>
        @endif
        <form method="post" action="{{asset('/resetPassword/')}}" onsubmit="return checkForm(this);">
        @csrf
        @method('put')
        <div class="row">
            <div class='col-12'>
                <div class='divider'></div>
                <div class="alert alert-secondary" role="alert">
                    <b>Note: </b>Please Enter the user's Email that you wishes to reset password
                </div>
                <div class='divider'></div>
            </div>
            <div class='form-group col-lg-12 col-sm-12 col-12'>
                <label for="email">User Email:</label>
                <input type="email" class="form-control @error('email') border border-danger @enderror" 
                value="{{old('email')}}" name="email" id="email" placeholder="Enter Registered email">
                @error('email')<p class="text-danger">{{$message}}</p>@enderror
            </div>
            <div class='col-12'>
                <div class='divider'></div>
                <div class="alert alert-secondary" role="alert">
                    <b>Note: </b> Enter the new password for the user. You can then ask him/her to modify their password once they login.
                </div>
                <div class='divider'></div>
            </div>
            <div class='form-group col-lg-6 col-sm-6 col-12'>
                <label for="pwd">New Password:</label>
                <input type="password" class="form-control @error('new_password') border border-danger @enderror" 
                name="new_password" id="new_password" placeholder="Enter new password" onpaste="return false" oncopy="return false">
                @error('new_password')<p class="text-danger">{{$message}}</p>@enderror
            </div>

            <div class='form-group col-lg-6 col-sm-6 col-12'>
                <label for="pwd">Confirm New Password:</label>
                <input type="password" class="form-control @error('confirm_password') border border-danger @enderror" 
                name="confirm_password" placeholder="confirm new password" onpaste="return false" oncopy="return false">
                @error('confirm_password')<p class="text-danger">{{$message}}</p>@enderror
            </div>
        </div>
            <button name="myButton" type="submit" class="btn btn-sm  btn-primary">Submit</button>
            <a href="{{'/home'}}"  class="btn btn-sm btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script>
function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection