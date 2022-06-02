@extends('layouts.myapp')
@section('title','Edit')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
@section('content')

<div class="main-card mb-3 card">
    <div class='card-header'>
        update Field Staff <br>
        @if (session('message'))
          <p class="text-success">{{session('message')}}</p>
        @endif
    </div>
    <div class="card-body">
        <form method="post" action="{{route('staffs.update',$user->id)}}" enctype="multipart/form-data" onsubmit="return checkForm(this);">
        @csrf
        @method('put')
            <div class="row">
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid  @enderror" name="name" id="name" 
                    value="{{ old('name',$user->name) }}" placeholder="eg. Alina Mclourd">
                    @error('name')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="phone_number">Contact Number</label>
                    <input type="number" class="form-control @error('phone_number') is-invalid  @enderror" name="phone_number" 
                    value="{{ old('phone_number',$user->phone_number) }}" id="phone_number">
                    @error('phone_number')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="address">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid  @enderror" placeholder="eg. Jwagal, Kupondole, Lalitpur" 
                    value="{{ old('address',$user->address) }}" name="address" id="address">
                    @error('address')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <label for="profile_image">Profile Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="profile_image" 
                        id="profile_image">
                        <label class="custom-file-label" for="profile_image">
                            <span class="d-inline-block text-truncate">Choose an image...</span>
                        </label>
                        @error('profile_image')<a class="text-danger">{{$message}}</a>@enderror
                    </div>
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-12'>
                    @if($user->profile_image==''||$user->profile_image==NULL)
                        <img src="{{asset('images/user.png')}}" id="profile-img-tag" class="img-fluid">
                    @endif
                    @if($user->profile_image!=NULL)
                        <img src="{{asset('storage/images/profiles/'.$user->profile_image)}}" id="profile-img-tag" class="img-fluid">
                    @endif
                </div>

            </div>
            <div class='row'>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid  @enderror" name="email" id="email" 
                    value="{{ old('email',$user->email) }}" placeholder="eg. alinamclourd@lemon.com">
                     @error('email')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                {{-- <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="password">Set Password</label>
                    <input type="password" class="form-control" name="password" id="password" 
                    value="{{old('password')}}" placeholder="Set password for staff login">
                     @error('password')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                    value="{{old('confirm_password')}}" placeholder="Set password for staff login">
                     @error('confirm_password')<a class="text-danger">{{$message}}</a>@enderror
                </div> --}}
            </div>
            <div class='row'>
                <div class='form-group col-12 mb-0'>
                    <button name ="myButton" type="submit" class="btn btn-sm btn-success mr-3">Submit</button>
                    @if(Auth::user()->hasRole('Super Admin'))
                    <button type="reset" class="btn btn-sm btn-secondary">
                        <a style="color:white;text-decoration:none" href="{{route('staffs.index')}}">Cancel</a>
                    </button>
                    @endif
                    @if(Auth::user()->hasRole(['Admin','Regional Admin']))
                    <button type="reset" class="btn btn-sm btn-secondary">
                        <a style="color:white;text-decoration:none" href="/mystaffs">Cancel</a>
                    </button>
                    @endif
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
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection

