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
                <i class="fa fa-flag"></i>
            </div>
            <div>
                <strong>Edit {{$group}}</strong>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        <strong> Add or Remove Region(s) from {{$group}} </strong>
    </div>
    @if(session('info'))
        <script>
            swal({
                icon:"warning",
                title: "Error Message",
                text: "{{session('info')}}",
                type: "success"
            })
        </script>
    @endif
    <div class="card-body">
        <form method="post" action="/edit-group/{{$group}}">
            @csrf
            <h5>Available Region Names || Add or Remove one or more</h5>
            <div class="form-group">
                <label for="sel2">Please select atlest two regions</label>
                <div class="@error('regions') required @enderror ">
                    <select name="regions[]" multiple id="select2">
                        @foreach ($old_regions as $region)
                            <option class="level_1" value="{{$region->id}}"}} selected>{{ $region->name }}</option>
                        @endforeach
                            @foreach ($all_regions as $new_region)
                                <option class="level_1" value="{{$new_region->id}}"}}>{{ $new_region->name }}</option>
                            @endforeach
                    </select>
                    @error('regions')
                    <p class="text-danger">{{ $errors->first('regions') }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    <a  href="{{asset('myRegion')}}" class="btn btn-sm btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<script>
$("#select2").select2({
    
});
</script>
@endsection