@extends('layouts.myapp')
@section('title','Add supervisor')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Add New Supervisor 
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        Add Field Supervisor's information <br>
    </div>
    <div class="card-body">
        <form method="post" action="{{route('supervisors.store')}}" enctype="multipart/form-data" onsubmit="return checkForm(this);">
        @csrf
            <div class="row">
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" 
                    value="{{old('name')}}" placeholder="eg. Alina Mclourd">
                    @error('name')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="phone_number">Contact Number</label>
                    <input type="number" class="form-control" name="phone_number" 
                    value="{{old('phone_number')}}" id="phone_number">
                    @error('phone_number')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="address">Address</label>
                    <input type="text" class="form-control" placeholder="eg. Jwagal, Kupondole, Lalitpur" 
                    value="{{old('address')}}" name="address" id="address">
                    @error('address')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <label for="profile_image">Profile Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="profile_image" id="profile_image">
                       
                        <label class="custom-file-label" for="profile_image">
                            <span class="d-inline-block text-truncate">Choose an image...</span>
                        </label>
                        @error('profile_image')<a class="text-danger">{{$message}}</a>@enderror
                    </div>
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <img class="img-fluid" src="" id="profile-img-tag"/>
                </div>

                <div class='col-12'>
                    <div class='divider'></div>
                    <div class="alert alert-secondary" role="alert">
                        <b>Note: </b> Select one region from the folowing list. This person will become the supervisor of this region 
                    </div>
                </div>
                <div class="form-group col-lg-6 col-sm-6 col-12 @error('region') required @enderror ">
                    <select  class="regionSelect form-control" 
                    name="region">
                        <option></option>
                        @foreach ($regions as $region)
                            <option value="{{$region->id}}" {{old('region') == $region->id ? 'selected' : ''}}>{{$region->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('region')
                <p class="text-danger">{{ $errors->first('region') }}</p>
                @enderror
            </div>
            <div class='row'>
                <div class='col-12'>
                    <div class='divider'></div>
                    <div class="alert alert-secondary" role="alert">
                        <b>Note: </b> Email address and password you enter here will be the credential when the staff wishes to login in to the system. <br>Ask the staff to change the passwrd from "Account Settings" once he/she logs in.
                    </div>
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" 
                    value="{{old('email')}}" placeholder="eg. alinamclourd@lemon.com">
                     @error('email')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="password">Set Password</label>
                    <input type="password" class="form-control" name="password" id="password" 
                    value="{{old('password')}}" placeholder="Set password for staff login">
                     @error('password')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                    placeholder="Set password for staff login">
                    @error('confirm_password')<a class="text-danger">{{$message}}</a>@enderror
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-12 mb-0'>
                     <button name="myButton" type="submit" class="btn btn-sm btn-success mr-3">Submit</button>
                     <a class="btn btn-sm btn-secondary" href="{{asset('/supervisors')}}">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
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
    $(".regionSelect").select2({
    placeholder: "Select Region",
    allowClear: true
    });
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection

