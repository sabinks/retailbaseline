@extends('layouts.myapp')
@section('title','Regions')
@section('content')
<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
           View Regions
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            <button type="button" class="btn btn-sm btn-success">
                <a id="link_page" href="{{asset('createMyRegion')}}">Add New Region</a>
            </button>
        </div>
    </div>
    <div class="card-body">
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
        @if(session('success'))
            <script>
                swal({
                    icon:"success",
                    title: "Edit Scuessfull ",
                    text: "{{session('success')}}",
                    type: "success"
                })
            </script>
        @endif
        @if(session('notice'))
            <div class="alert autoremove alert-danger">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span class="text-danger">{{session('notice')}}</span>
            </div>       
        @endif
        @if(session('error'))
            <div class="alert autoremove alert-warning">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span class="text-warning">{{session('error')}}</span>
            </div>       
        @endif
        @if(session('message'))
            <div class="alert autoremove alert-success">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span class="text-success">{{session('message')}}</span>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Main Region</th>
                        <th>Region Name</th>
                        <th style="width: 20%;text-align:center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($region_group as $region)
                        <tr>
                            <td>{{$region}}</td>
                            <td>
                                @foreach ($regions as $reg)
                                    @if ($reg->pivot->region_name==$region)
                                        <span class="badge badge-primary">{{$reg->name}}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td style="text-align:center">
                                <a title="Edit Group" href="{{'/edit-group/'.$region}}" class="btn btn-primary btn-sm" >
                                    <i class="fa fa-pencil"></i>
                                </a>    
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($single_regions as $single)
                        <tr>
                            <td>-</td>
                            <td><span class="badge badge-secondary">{{$single[0]}}</span></td>
                            <td style="text-align:center">
                                {{-- <a title="Remove region" href="{{asset('/removeRegion/'.$single[1])}}" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure?');">
                                    <i class="fa fa-remove"></i>
                                </a>     --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
/*function clickStopper(e)
    {
    e.preventDefault();
    }*/
</script>
@endsection