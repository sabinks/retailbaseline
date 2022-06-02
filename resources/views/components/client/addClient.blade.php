@extends('layouts.myapp')
@section('title','Add Client')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Add New Admin
            </div>
        </div>
    </div>
</div>
<form method="post" action="{{route('admins.store')}}" enctype="multipart/form-data" onsubmit="return checkForm(this);">
@csrf
    <div class="main-card mb-3 card">
        <div class='card-header'>
            Add Client Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="compName">Company Name</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="compName" name="company_name" 
                    value="{{old('company_name')}}" placeholder="eg. Lemon Pvt. Ltd.">
                    @error('company_name')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="comp">Contact Number</label>
                    <input type="number" class="form-control @error('company_phone_number') is-invalid @enderror" name="company_phone_number" 
                    value="{{old('company_phone_number')}}" id="contactNumber">
                    @error('company_phone_number')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="compAddress">Address</label>
                    <input type="text" class="form-control @error('company_address') is-invalid @enderror" name="company_address" 
                    value="{{old('company_address')}}" placeholder="eg. Jwagal, Kupondole, Lalitpur" id="compAddress">
                    @error('company_address')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="compWebsite">Website</label>
                    <input type="text" class="form-control @error('webaddress') is-invalid @enderror" name="webaddress" 
                    value="{{old('webaddress')}}" placeholder="eg. www.lemon.com.np" id="compWebsite">
                    @error('webaddress')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <label for="compLogo">Company Logo</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('company_logo') is-invalid @enderror" 
                        name="company_logo" id="company_logo" 
                        onchange="document.getElementById('logo-img-tag').src = window.URL.createObjectURL(this.files[0])">
                        <label class="custom-file-label" for="profileImage">
                            <span class="d-inline-block text-truncate">Choose an image...</span>
                        </label>
                        @error('company_logo')<a class="text-danger">{{$message}}</a>@enderror
                    </div>
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <img src="" id="logo-img-tag" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class='card-header'>
            Client System User (Admin)
        </div>
        <div class="card-body">
            <div class='row'>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="staffName">Admin Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="staffName" 
                    value="{{old('name')}}" placeholder="eg. John Doe">
                    @error('name')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="contactNumber">Contact Number</label>
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" 
                    value="{{old('phone_number')}}" id="contactNumber">
                    @error('phone_number')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="address">Client Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" 
                    value="{{old('address')}}" id="contactNumber">
                    @error('address')<p class="text-danger">{{$message}}</p>@enderror
                </div>

                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <label for="profileImage">Profile Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('profile_image') is-invalid @enderror" name="profile_image" id="profile_image">
                        <label class="custom-file-label" for="profileImage">
                            <span class="d-inline-block text-truncate">Choose an image...</span>
                        </label>
                        @error('profile_image')<a class="text-danger">{{$message}}</a>@enderror
                    </div>
                </div>

                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <img src="" id="profile-img-tag" class="img-fluid">
                </div>

            </div>
            <div class='row'>
                <div class='divider'></div>
                <div class='col-12'>
                    <div class="alert alert-secondary" role="alert">
                        <b>Note: </b> Email address and password you enter here will be the credential when the Client wishes to login in to the system. <br>Ask the Client to change the password from "Account Settings" once he/she logs in.
                    </div>
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="staffEmail">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="staffEmail" name="email" 
                    value="{{old('email')}}" placeholder="eg. alinamclourd@lemon.com">
                    @error('email')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="password">Set Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
                    placeholder="Set password for staff login">
                    @error('password')<p class="text-danger">{{$message}}</p>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password" 
                    placeholder="Set password for staff login">
                    @error('confirm_password')<p class="text-danger">{{$message}}</p>@enderror
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-12 mb-0'>
                    <button name="myButton" type="submit" class="btn btn-sm btn-success mr-3">Submit</button>
                    <a class="btn btn-sm btn-secondary" href="{{asset('/admins')}}">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#profile-img-tag').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#profile_image").change(function(){
        readURL(this);
    });
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection

