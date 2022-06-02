@extends('layouts.myapp')
@section('title','Region')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-map-o"></i>
            </div>
            <div>
                Add New Region 
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        Add New Region <br>
        @if(session('message'))
            <div class="alert autoremove alert-success">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span class="text-success">{{session('message')}}</span>
            </div>
        @endif
    </div>
    <div class="card-body">
        <form method="post" action="{{route('regions.update',$region->id)}}" enctype="multipart/form-data" onsubmit="return checkForm(this);">
        @csrf
        @method('put')
            <div class="row">
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="name">Region Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" 
                    value="{{ old('name',$region->name)}}" placeholder="eg. Alina Mclourd">
                    @error('name')<a class="text-danger">{{$message}}</a>@enderror
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-12 mb-0'>
                     <button name="myButton" type="submit" class="btn btn-sm btn-success mr-3">Submit</button>
                     <button class="btn btn-sm btn-secondary"><a style="color:white;text-decoration:none" href="{{route('regions.index')}}">Cancel</a></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection

