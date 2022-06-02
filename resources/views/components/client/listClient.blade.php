@extends('layouts.myapp')
@section('title','Clients')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Admin List
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
            View, edit or delete the Staffs Listed
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            <button type="button" class="btn btn-sm btn-success">
                <a id="link_page" href="{{route('admins.create')}}">Add New Admin</a>
            </button>
        </div>
    </div>
    <div class="card-body">
        @if(session('notice'))
        <div class="alert autoremove alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>	
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
                        <th>Company Name</th>
                        <th>Admin Name</th>
                        <th>Email Address</th>
                        <th>Contact Number</th>
                        <th style="width: 50%">Staffs Associated</th>
                        <th style="width: 13%">Assign Staff</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                {{$user->companies()->first()->company_name}}
                            </td>
                            <td style="text-align:center">
                                {{-- @if($user->profile_image==NULL||$user->profile_image=='')
                                <img style="width:100px;height:auto;" src="{{asset('/images/user.png')}}">
                                @endif
                                @if($user->profile_image!=NULL)
                                <img style="width:100px;height:auto;" src="{{asset('storage/images/profiles/'.$user->profile_image)}}">
                                @endif --}}
                                <br><p>{{ $user->name }}</p>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>
                            {{-- <a title="click to expand" href="#name_{{$user->id}}" class="btn btn-info btn-sm" data-toggle="collapse">
                                <i id="arrowDown" class="fa fa-angle-double-down">
                                <i id="arrowUp"  class="fa fa-angle-double-up"></i></i>
                            </a> --}}
                            <div id="name_{{$user->id}}">
                                <div class="badge-group">
                                    @foreach ($user->fieldstaffs as $staff)
                                        <!--Display all the staff those are associated with this client i.e 
                                        created by him and granted by Super Admin-->
                                        <span title="staffs granted by you" class="badge badge-pill badge-primary">{{ $staff->name }}</span>
                                    @endforeach
                                    @foreach ($user->users as $staff)
                                        <!--Display all the staff those are created with this client-->
                                        @if($staff->hasRole('Field Staff'))
                                            <span title="Staff created by {{$user->name}}" class="badge badge-pill badge-info">{{ $staff->name }}</span>
                                        @endif
                                        <!-- Also display fieldstaffs created by his Regional admins and supervisors -->
                                        @if($staff->hasRole('Regional Admin'))
                                            @foreach($staff->users as $fieldstaff)
                                                @if($fieldstaff->hasRole('Field Staff'))
                                                    <span title="Staff created by his lower manager" class="badge badge-pill badge-secondary">{{ $fieldstaff->name }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            </td>
                            <td>
                                <a title="Grant new staffs" class="btn btn-primary btn-sm" href="{{'/assign_staff/'.$user->id }}"><i class='fa fa-user-plus'></i></a>
                                <a title="Remove Staffs assigned by you only" class="btn btn-danger btn-sm" href="{{'/remove_staff/'.$user->id }}"><i class="fas fa-user-minus"></i></a>
                            </td>
                            <td>
                                <div class='btn-group'>
                                    <a style="margin:2%" title="View" href="{{route('admins.show',$user->id)}}" class='btn btn-primary btn-sm'>
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a style="margin:2%" title="Edit" href="{{route('admins.edit',$user->id)}}" class='btn btn-secondary btn-sm'>
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    {{-- <a title="Reset Password" href="{{asset('/resetPassword/'.$user->id)}}" class='btn btn-warning btn-sm'>
                                        <i class="fa fa-lock"></i>
                                    </a> --}}

                                    <form style="margin:2%" action="{{ route('admins.destroy',$user->id) }}" onsubmit="return checkForm(this);" method="POST">
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
<script>
function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection