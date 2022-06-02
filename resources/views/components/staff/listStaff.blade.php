@extends('layouts.myapp')
@section('title','Staffs')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                <strong>{{ $message }}</strong>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
            @can('manageAllStaffs')
            View, edit or disable the Staffs Listed
            @endcan
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            <button type="button" class="btn btn-sm btn-success">
                <a  id="link_page" href="{{route('staffs.create')}}">Add New Staff</a>
            </button>
        </div>
    </div>
    <div class="card-body">
        @if ($message = session('message'))
        <div class="alert autoremove alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if ($message = session('danger'))
        <div class="alert autoremove alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if ($message = session('primary'))
        <div class="alert autoremove alert-primary alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if ($message = session('alert'))
        <div class="alert autoremove alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
        </div>
        @endif

        <div id="allStaff" class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Assocaited Region</th>
                        <th>En/Disabled</th>
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
                            {{-- @can('viewAssociate')
                                <td>
                                    <div class="badge-group">
                                    @foreach ($user->bosses as $boss )
                                    <!--Show the client's which create their staffs, hire staffs, and staff are assigned to them by admin-->
                                        @if($boss->pivot->staff_status==1 || $boss->pivot->staff_status==3)
                                            <!--Displaying the client that have field staffs -->
                                            <span class="badge badge-pill badge-primary">
                                                {{ $boss->name }}
                                            </span>
                                        @endif
                                        @if($boss->pivot->staff_status==4)
                                            <!--Displaying the client that had create this field staff -->
                                            <span class="badge badge-pill badge-success">
                                                {{ $boss->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-primary  btn-sm" href="{{'/admin_staff_assign/'.$user->id}}">
                                        Click me
                                    </a>
                                </td>
                            @endcan --}}
                            <td>
                                @foreach ($user->regions as $region)
                                     <span class="badge badge-pill badge-primary">{{$region->name}}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->deleted_at)
                                    <span class="badge badge-pill badge-danger">Disabled</span>
                                @else
                                    <span class="badge badge-pill badge-success">Enabled</span>
                                @endif
                            </td>
                            <td>
                            @can('manageAllStaffs')
                                <div class='btn-group'>
                                    <a style="margin:2%" title="view" href="{{ route('staffs.show',$user->id) }}" class='btn btn-primary btn-sm'>
                                        <i  class="fa fa-eye"></i>
                                    </a>
                                    <a style="margin:2%" title="Edit" href="{{ route('staffs.edit',$user->id) }}" class='btn btn-secondary btn-sm'>
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    {{-- @if(Auth::user()->hasRole('Super Admin'))
                                        <a title="Reset Password" href="{{asset('/resetPassword/'.$user->id)}}" class='btn btn-warning btn-sm'>
                                            <i class="fa fa-lock"></i>
                                        </a>
                                    @endif --}}
                                     <form style="margin:2%" id="staff-delete-form" action="/field-staff-enable-disable/{{$user->id}}"onsubmit="return checkForm(this);" method="POST">
                                        @csrf
                                        @if($user->deleted_at)
                                            <button name="myButton" title="Enable Staff" type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-plus"></i></button>
                                        @else
                                            <button name="myButton" title="Disable Staff" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-times"></i></button>
                                        @endif
                                        
                                    </form>
                                    <!-- <form style="margin:2%" id="staff-delete-form" action="{{ route('staffs.destroy',$user->id) }}"onsubmit="return checkForm(this);" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button name="myButton" title="Delete" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-trash-o"></i></button>
                                    </form> -->
                                </div>
                            @endcan

                            @can('hireStaff')
                                <a href="/staff/hire/{{$user->id}}" class='btn btn-primary btn-sm'>
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
    function checkForm(form){
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection