@extends('layouts.myapp')
@section('title','Entity Group')
@section('content')
<!-- <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Entity Group
            </div>
        </div>
    </div>
</div> -->
<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
            Entity Group
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            @role('Admin|Regional Admin|Super Admin')
                <button type="button" class="btn btn-sm btn-success">
                    <a id="link_page" href="{{route('group-entites.create')}}">New Group</a>
                </button>
            @endrole
        </div>
    </div>
    <div class="card-body">
        @role('Super Admin | Admin | Regional Admin')
            <div>
                <button type="button" class="btn btn-sm btn-success">
                    <a style="color:white;text-decoration:none" href="{{route('group-entites.create')}}">New Group</a>
                </button>
            </div>
        @endrole
       
        @if ($message = session('message'))
        <div class="alert autoremove alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong class="text-success">{{ $message }}</strong>
        </div>
        @endif
        <div  class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th width="30%">Group Name</th>
                        <th>Entities</th>
                        <th width="15%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entities as $entity)
                        <tr>
                            <td>{{ $entity[0] }}</td>
                            <td>
                                @foreach($entity[1] as $name)
                                    <span title="Entity Name" class="badge badge-pill badge-info">{{$name}}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class='btn-group'>
                                    <a style="margin:2%" title="Edit" href="{{ route('group-entites.edit',$entity[2]) }}" class='btn btn-secondary btn-sm'>
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form style="margin:2%" id="staff-delete-form" action="{{ route('group-entites.destroy',$entity[2]) }}"onsubmit="return checkForm(this);" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button name="myButton" title="Delete" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-trash-o"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection