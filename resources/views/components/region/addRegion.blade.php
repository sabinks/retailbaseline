@extends('layouts.myapp')
@section('title','Regions')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
@section('content')

<div class="main-card mb-3 card">
    <div class='card-header'>
        Add New Region <br>
        @if (session('message'))
        <div class="alert autoremove alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong class="text-danger">{{session('message')}}</strong>
        </div>
        @endif
    </div>
    <div class="card-body">
        <form method="post" action="{{route('regions.store')}}" enctype="multipart/form-data" onsubmit="return checkForm(this);">
        @csrf
            <div class="row">
                <div class='form-group col-lg-6 col-sm-6 col-12'>
                    <label for="name">Region Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" 
                    value="{{old('name')}}" placeholder="eg. Alina Mclourd">
                    @error('name')<a class="text-danger">{{$message}}</a>@enderror
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-12 mb-0'>
                     <button name="myButton" type="submit" class="btn btn-sm btn-success mr-3">Submit</button>
                     <button class="btn btn-sm btn-secondary"><a id="link_page" href="{{route('regions.index')}}">Cancel</a></button>
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

