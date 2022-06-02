@extends('layouts.myapp')
@section('title','Regional Admins')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Regional Admin List
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
           View, edit or delete the Regional Admins Listed
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            <button type="button" class="btn btn-sm btn-success">
                <a id="link_page" href="{{route('regionalAdmins.create')}}">Add New Regional Admin</a>
            </button>
        </div>
    </div>
    <div class="card-body">
        @if(session('notice'))
            <div class="alert autoremove alert-danger">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span class="text-danger">{{session('notice')}}</span>
            </div>
        @endif
        @if(session('alert'))
            <div class="alert autoremove alert-warning">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span class="text-warning">{{session('alert')}}</span>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th style="width: 30%">Region</th>
                        <!-- <th>En/Disabled</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->address }}</td>
                            <td>
                                <div class="badge-group">
                                    @foreach ($user->regions as $region)
                                        <span title="Region  {{$region->name}}" class="badge badge-pill badge-primary">{{$region->name}}</span>
                                    @endforeach
                                </div>
                            </td>
                            <!-- <td>
                                @if($user->deleted_at)
                                    <span class="badge badge-pill badge-danger">Disabled</span>
                                @else
                                    <span class="badge badge-pill badge-success">Enabled</span>
                                @endif
                            </td> -->
                            <td>
                                <div class='btn-group'>
                                    <a style="margin:2%" title="View" href="{{ route('regionalAdmins.show',$user->id) }}" class='btn btn-primary btn-sm'>
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a style="margin:2%" title="Edit" href="{{ route('regionalAdmins.edit',$user->id) }}" class='btn btn-secondary btn-sm'>
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="margin:2%" title="Reset Password" href="{{route('subordinate.resetPassword',$user->id)}}" class='btn btn-warning btn-sm'>
                                        <i class="fa fa-gears"></i>
                                    </a>
                                    <!-- <form style="margin:2%" id="staff-delete-form" action="/regional-admin-enable-disable/{{$user->id}}"onsubmit="return checkForm(this);" method="POST">
                                        @csrf
                                        @if($user->deleted_at)
                                            <button name="myButton" title="Enable Regional Admin" type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-plus"></i></button>
                                        @else
                                            <button name="myButton" title="Disable Regional Admin" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-times"></i></button>
                                        @endif
                                        
                                    </form> -->
                                    <!-- <form style="margin:2%" id="staff-delete-form" action="{{ route('regionalAdmins.destroy',$user->id) }}" method="POST"
                                        onsubmit="return checkForm(this);">
                                        @csrf
                                        @method('delete')
                                        <button name="myButton" title="Delete" type="submit" class="btn btn-danger btn-sm" onclick=" return confirm('are you sure?');"><i class="fa fa-trash-o"></i></button>
                                    </form> -->
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection