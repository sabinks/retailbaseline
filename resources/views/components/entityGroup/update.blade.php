@extends('layouts.myapp')
@section('title','Entity Group')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-map-marker"></i>
            </div>
            <div>
                Update group {{ $entity->group_name }}
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
                <form method="post" action="{{route('group-entites.update',$entity->id)}}" onsubmit="return checkForm(this);">
                <h5>Available Entities Name || select two or more</h5>
                @csrf
                @method('PUT')
                    <div class="form-group">
                        <label for="entities">Select Entites:</label>
                        <div class="@error('entities') required @enderror">
                            <select multiple class="EntitySelect form-control" 
                            name="entities[]" placeholder="Select/Search Enity(s)">
                            @foreach ($regions as $region)
                            <optgroup label="{{$region->name}}"></optgroup>
                            {{-- @foreach ($entities as $entity)
                            @if($region->id==$entity->region_id)   --}}
                                @foreach ($entity_name as $old_entity)
                                    @if($region->id==$old_entity->region_id)
                                        <option id="entityName" value="{{$old_entity->id}}"
                                        }} selected >{{ $old_entity->name }}</option>
                                    @endif
                                @endforeach
                                @foreach ($entitie_collect as $new_entity)
                                    @if($region->id==$new_entity->region_id)
                                    <option id="entityName" value="{{$new_entity->id}}"
                                    {{ (collect(old('entities'))->contains($new_entity->id)) ? 'selected':'' }}>{{ $new_entity->name }}</option>
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
                        value="{{ old('name',$entity->group_name) }}" placeholder="Enter entity group Name">
                        @error('group_name')
                        <p class="text-danger">{{ $errors->first('group_name') }}</p>
                        @enderror
                    </div>
                    <button id="myButton" type="submit" class="btn btn-sm  btn-primary">Submit</button>
                    <a  href="{{asset('/group-entites')}}" class="btn btn-sm  btn-secondary">Cancel</a>
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