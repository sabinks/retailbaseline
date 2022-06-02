@extends('layouts.myapp')
@section('title','Entity Group')
@section('content')
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
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-map-marker"></i>
            </div>
            <div>
                Create New Group
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        Entities List
    </div>
    <div class="card-body">
        @if ($message = session('error'))
        <div class="alert autoremove alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong class="text-danger">{{ $message }}</strong>
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <form method="post" action="{{route('group-entites.store')}}" onsubmit="return checkForm(this);">
                <h5>Available Entities Name || select two or more</h5>
                @csrf
                    <div class="form-group">
                        <label for="entities">Select Entites:</label>
                        <div class="@error('entities') required @enderror">
                            <select multiple class="EntitySelect form-control" 
                            name="entities[]" multiple>
                                <option></option> 
                                @foreach ($regions as $region)
                                    <optgroup label="{{$region->name}}"></optgroup>
                                    @foreach ($entities as $entity)
                                        @if($region->id==$entity->region_id)  
                                            <option id="entityName" value="{{$entity->id}}"
                                            {{ (collect(old('entities'))->contains($entity->id)) ? 'selected':'' }}>{{ $entity->name }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        @error('entities')
                        <p class="text-danger">{{ $errors->first('entities') }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="group_name">Set Group Name:</label>
                        <input type="text" name="group_name" class="form-control @error('entities') required @enderror"
                        value="{{old('group_name')}}" placeholder="Enter entity group Name">
                        @error('group_name')
                        <p class="text-danger">{{ $errors->first('group_name') }}</p>
                        @enderror
                    </div>
                    <button id="myButton" type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <a  href="{{asset('/group-entites')}}" class="btn btn-sm btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".EntitySelect").select2({
    placeholder: "Select two or more Entities",
    allowClear: true
    });
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection