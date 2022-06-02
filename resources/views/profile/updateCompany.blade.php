@extends('layouts.myapp')
@section('title','Update Company')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
@section('content')

<div class="main-card mb-3 card">
    <div class='card-header'>
        Update Field Staff <br>
        @if (session('message'))
          <p class="text-success">{{session('message')}}</p>
        @endif
    </div>
    <div class="card-body">
        <form method="post" action="{{asset('updatecompany/'.$company->id)}}" enctype="multipart/form-data">
        @csrf
        @method('put')
            <div class="row">
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="company_name" id="name" 
                    value="{{ old('company_name',$company->company_name) }}" placeholder="eg. Alina Mclourd">
                    @error('company_name')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="phone_number">Contact Number</label>
                    <input type="number" class="form-control" name="company_phone_number" 
                    value="{{ old('company_phone_number',$company->company_phone_number) }}" id="phone_number">
                    @error('company_phone_number')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="address">Company Address</label>
                    <input type="text" class="form-control" placeholder="eg. Jwagal, Kupondole, Lalitpur" 
                    value="{{ old('company_address',$company->company_address) }}" name="company_address" id="address">
                    @error('company_address')<a class="text-danger">{{$message}}</a>@enderror
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-6'>
                    <label for="company_logo">Profile Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="company_logo" 
                        id="company_logo">
                        <label class="custom-file-label" for="company_logo">
                            <span class="d-inline-block text-truncate">Choose an image...</span>
                        </label>
                        @error('company_logo')<a class="text-danger">{{$message}}</a>@enderror
                    </div>
                </div>
                <div class='form-group col-lg-3 col-sm-3 col-12'>
                    @if($company->company_logo==NULL)
                        <img style="width:30%;height:auto" src="{{asset('/images/lemon.png')}}" id="profile-img-tag" class="img-fluid">
                    @else
                    <img src="{{asset('storage/images/logos/'.$company->company_logo)}}" id="profile-img-tag" class="img-fluid">
                    @endif
                </div>

            </div>
            <div class='row'>
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="email">Web Address</label>
                    <input type="text" class="form-control" name="webaddress" id="email" 
                    value="{{ old('webaddress',$company->webaddress) }}" placeholder="eg. alinamclourd@lemon.com">
                     @error('webaddress')<a class="text-danger">{{$message}}</a>@enderror
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-12 mb-0'>
                    <button type="submit" class="btn btn-sm btn-success mr-3">Submit</button>
                    <button type="reset" class="btn  btn-sm btn-secondary"><a style="color:white;text-decoration:none" 
                    href="{{asset('home')}}"> Cancel<a></button>
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
    $("#company_logo").change(function(){
        readURL(this);
    });
</script>
@endsection

