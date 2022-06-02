@extends('layouts.myapp')
@section('select2')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <style>
        form{
            width:55%;
            margin:0 auto;
        }
        label{
            margin-bottom: 5% !important;
        }
        .required{
            border:1px red !important;
            width:100%;
            box-shadow: 1px 1px 10px red;
        }
    </style>
@endsection
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                <strong>Assign Admin to Staff/ Assign Staff to Admin</strong>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
       <strong> Assign Admins to {{$user->name}} </strong>
    </div>
    <div class="card-body">
        <form method="post" action="{{asset('admin_staff')}}">
        <h5>Available Admins Name || select one or more</h5>
        @csrf
            <div class="form-group">
                <label for="sel2">Mutiple select list (hold shift to select more than one):</label>
                <input type="hidden" value="{{ $user->id }}" name="staff"/>
                <div class="@error('admins') required @enderror ">
                    <select multiple class="adminSelect form-control" 
                    name="admins[]" placeholder="Select/Search admin(s)">
                        @foreach ($admins as $admin)
                            <option id="adminName" value="{{$admin->id}}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('admins')
                <p class="text-danger">{{ $errors->first('admins') }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            <a  href="{{URL::previous() }}" class="btn btn-sm btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(".adminSelect").select2({
    placeholder: "Select admin(s)",
    allowClear: true
    });
</script>
@endsection