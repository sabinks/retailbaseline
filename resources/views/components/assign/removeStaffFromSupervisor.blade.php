@extends('layouts.myapp')
@section('title','Grant Staff')
@section('select2')
<style>
    input,select{
        width:100%;
    }
    optgroup{
        background:grey !important;
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
                <strong>Remove Staff(s) from Admin</strong>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        <strong> Grant Field Staffs to {{$user->name}} </strong>
    </div>
    @if($message = session('error'))
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{{$message}}</strong>
        </div>
    @endif
    <div class="card-body">
        <form method="post" action="{{asset('remove/staff/from/supervisor/'.$user->id)}}">
            <h5>Available Field Staffs Names || select one or more</h5>
            @csrf
            <div class="form-group">
                <label for="sel2">Only staffs ,those are granted by you, can be remove.</label>
                <div class="@error('staffs') required @enderror ">
                    <select name="staffs[]" multiple id="select2" data-placeholder="Select staff(s) to remove" tabindex="-1" aria-hidden="true">
                        @foreach ($fieldstaffs as $staff)
                            <option class="level_1" value="{{$staff->id}}"}}>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    @error('staffs')
                    <p class="text-danger">{{ $errors->first('staffs') }}</p>
                    @enderror
                    <button type="submit" class="btn btn-sm btn-primary">Remove</button>
                    <a  href="{{asset('supervisors')}}" class="btn btn-sm btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<script>
$("#select2").select2({
    
});
</script>
@endsection