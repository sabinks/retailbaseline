@extends('layouts.myapp')
@section('title','Regions')
@section('content')

<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
           View, edit or delete the Region Listed
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            @role('Super Admin')
                <button type="button" class="btn btn-sm btn-success">
                    <a id="link_page" href="{{route('regions.create')}}">Add New Region</a>
                </button>
            @endrole
        </div>
    </div>
    <div class="card-body">
        @if(session('notice'))
            <div class="alert autoremove alert-danger">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span class="text-danger">{{session('notice')}}</span>
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
                        <th style="width:50%">Region Name</th>
                        @can('viewAssociate')
                            <th style="width: 40%">Client Associated</th>
                        @endcan
                        <th style="width:10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($regions as $region)
                        <tr>
                            <td>{{ $region->name }}</td>
                            @can('viewAssociate')
                                <td>
                                    <div class="badge-group">
                                        @foreach ($region->users as $user)
                                            @if ($user->hasRole('Admin'))
                                                <span title="Client" class="badge badge-pill badge-primary">
                                                    {{ $user->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            @endcan
                            <td style="text-align:center">
                            @can('manageAllStaffs')
                                <div class='btn-group'>
                                    <a style="margin:2%" title="Edit" href="{{route('regions.edit',$region->id)}}" class='btn btn-secondary btn-sm'>
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form style="margin:2%" action="{{route('regions.destroy',$region->id)}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button title="Delete" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fa fa-trash-o"></i></button>
                                    </form>
                                </div>
                            @endcan
                            @can('hireStaff')
                                <a title="Select Region" href="{{'selectRegion/'.$region->id }}" class='btn btn-success btn-sm' 
                                onclick="this.addEventListener('click', clickStopper, false);">
                                    <i class="fa fa-check"></i>
                                </a>
                            @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function clickStopper(e)
    {
    e.preventDefault(); // equivalent to 'return false'
    }
</script>
@endsection