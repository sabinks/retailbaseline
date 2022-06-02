@extends('layouts.myapp')
@section('title','Regions')
@section('select2')
<style>
    form{
        width:50%;
        margin:0 auto;
    }
    .required{
        border:1px red !important;
        box-shadow: 1px 1px 10px red;
    }
</style>
@endsection
@section('content')
<div class="main-card mb-3 card">
    <div class='card-header'>
        Create your region by combinig two or more regions from the following list
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form method="post" action="{{asset('createMyRegion')}}" onsubmit="return checkForm(this);">
                <h5>Available Regions Name || select two or more</h5>
                @csrf
                    <div class="form-group">
                        <label for="regions">Select Regions:</label>
                        <div class="@error('regions') required @enderror">
                            <select multiple class="RegionSelect form-control" 
                            name="regions[]" placeholder="Select/Search region(s)">
                                @foreach ($regions as $region)
                                    <option id="regionName" value="{{$region->id}}"
                                    {{ (collect(old('regions'))->contains($region->id)) ? 'selected':'' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('regions')
                        <p class="text-danger">{{ $errors->first('regions') }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="region_name">Set Region Name:</label>
                        <input type="text" name="region_name" class="form-control @error('regions') required @enderror"
                        value="{{old('region_name')}}" placeholder="Enter Region Name" id="email">
                        @error('region_name')
                        <p class="text-danger">{{ $errors->first('region_name') }}</p>
                        @enderror
                    </div>
                    <button id="myButton" type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <a  href="{{asset('/myRegion')}}" class="btn btn-sm btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(".RegionSelect").select2({
    placeholder: "Select two or more regions",
    allowClear: true
    });
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection