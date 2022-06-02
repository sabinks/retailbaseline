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
                <strong>Grant Staff to Admin</strong>
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
        <form method="post" action="{{asset('assign_staff')}}">
            <h5>Available Field Staffs Names || select one or more</h5>
            @csrf
            <div class="form-group">
                <label for="sel2">Mutiple select list (hold shift to select more than one):</label>
                <input type="hidden" value="{{ $user->id }}" name="client"/>
                <div class="@error('staffs') required @enderror ">
                    <select name="staffs[]" multiple id="select2" data-placeholder="Select staff(s)" tabindex="-1" aria-hidden="true">
                    <option></option>
                        @foreach ($regions as $region)
                            <optgroup label="{{$region->name}}"></optgroup>
                            @foreach ($region->superAdminStaffs as $staff)
                                    <option class="level_1" value="{{$staff->id}}"}}>{{ $staff->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('staffs')
                    <p class="text-danger">{{ $errors->first('staffs') }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                    <a  href="{{asset('admins')}}" class="btn btn-sm btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<script>
function formatState (state) {
if (!state.id) {
return state.text;
}
var $state = $(
'<span>'+ state.text + '</span>'
);
return $state;
};

$("#select2").select2({
templateResult: formatState
});
</script>
@endsection