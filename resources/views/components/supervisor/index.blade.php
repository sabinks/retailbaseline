@extends('layouts.myapp')
@section('title','Supervisors List')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Supervisors List
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
     <div class='card-header'>
        <div class='card-title'>
           View, edit or disable the Supervisors
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            <button type="button" class="btn btn-sm btn-success">
                <a id="link_page" href="{{route('supervisors.create')}}">Add New Supervisor</a>
            </button>
            @role('Admin')
                <button type="button" id="mySupervisors" class="btn btn-sm btn-primary">Mine Supervisors</button>
                <button type="button" id="regionalAdminSupervisor" class="btn btn-sm btn-secondary">Supervisors of Regional Admin</button>
            @endrole
            @role('Regional Admin')
                <button type="button" id="mySupervisors" class="btn btn-sm btn-primary">Mine Supervisors</button>
                <button type="button" id="allSupervisors" class="btn btn-sm btn-secondary">All Supervisors</button>
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

        <div id="showMySupervisors" class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        @role('Admin')
                            <th>Associated Region</th>
                        @endrole
                        @role('Regional Admin')
                            <th>Associated Staffs</th>
                            <th>Assign Staffs</th>
                        @endrole
                        <th>En/Disabled</th>
                        @role('Admin')
                            <th>Action</th>
                        @endrole
                        @role('Regional Admin')
                            <th>Action</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->address }}</td>
                            @role('Admin')
                            <td>
                                <div class="badge-group">
                                    <span class="badge badge-pill badge-primary">{{$user->regions[0]->name}}</span>
                                </div>
                            </td>
                            @endrole
                            @role('Regional Admin')
                            <td>
                                @foreach ($user->assignStaffs as $staff)
                                     <span class="badge badge-pill badge-primary">{{$staff->name}}</span>
                                @endforeach
                            </td>
                                <td>
                                    <a title="Grant new staffs" class="btn btn-primary btn-sm" href="{{'/assign/staff/to/supervisor/'.$user->id }}">
                                        <i class='fa fa-user-plus'></i>
                                    </a>
                                    <a title="Remove Staffs assigned by you only" class="btn btn-danger btn-sm" href="{{'/remove/staff/from/supervisor/'.$user->id }}">
                                        <i class="fas fa-user-minus"></i>
                                    </a>

                                </td>   
                            @endrole
                            <td>
                                @if($user->deleted_at)
                                    <span class="badge badge-pill badge-danger">Disabled</span>
                                @else
                                    <span class="badge badge-pill badge-success">Enabled</span>
                                @endif
                            </td>
                            @role('Admin')
                                <td>
                                    <div class='btn-group'>
                                        <a  title="View" href="{{ route('supervisors.show',$user->id) }}" class='btn btn-primary btn-sm'>
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a  title="Edit" href="{{ route('supervisors.edit',$user->id) }}" class='btn btn-secondary btn-sm'>
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a  title="Reset Password" href="{{route('subordinate.resetPassword',$user->id)}}" class='btn btn-warning btn-sm'>
                                            <i class="fa fa-gears"></i>
                                        </a>
                                        <form  id="staff-delete-form" action="/supervisor-enable-disable/{{$user->id}}"onsubmit="return checkForm(this);" method="POST">
                                            @csrf
                                            @if($user->deleted_at)
                                                <button name="myButton" title="Enable Staff" type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-plus"></i></button>
                                            @else
                                                <button name="myButton" title="Disable Staff" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-times"></i></button>
                                            @endif
                                        </form>
                                        <!-- <form  id="staff-delete-form" action="{{ asset('supervisors/'.$user->id) }}" method="POST" onsubmit="return checkForm(this);">
                                            @csrf
                                            @method('delete')
                                            <button name="myButton" title="Delete" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fa fa-trash-o"></i></button>
                                        </form> -->
                                    </div>
                                </td>
                            @endrole
                            @role('Regional Admin')
                                <td>
                                    <div class='btn-group'>
                                        <a  title="View" href="{{ route('supervisors.show',$user->id) }}" class='btn btn-primary btn-sm'>
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a  title="Edit" href="{{ route('supervisors.edit',$user->id) }}" class='btn btn-secondary btn-sm'>
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a  title="Reset Password" href="{{route('subordinate.resetPassword',$user->id)}}" class='btn btn-warning btn-sm'>
                                            <i class="fa fa-gears"></i>
                                        </a>
                                        <form  id="staff-delete-form" action="/supervisor-enable-disable/{{$user->id}}"onsubmit="return checkForm(this);" method="POST">
                                            @csrf
                                            @if($user->deleted_at)
                                                <button name="myButton" title="Enable Staff" type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-plus"></i></button>
                                            @else
                                                <button name="myButton" title="Disable Staff" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-times"></i></button>
                                            @endif
                                        </form>
                                        <!-- <form style="margin:2%" id="staff-delete-form" action="{{ asset('supervisors/'.$user->id) }}" method="POST" onsubmit="return checkForm(this);">
                                            @csrf
                                            @method('delete')
                                            <button name="myButton" title="Delete" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fa fa-trash-o"></i></button>
                                        </form> -->
                                    </div>
                                </td>
                            @endrole
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @role('Regional Admin')
            <div class="users" id="showAllSupervisors">
                <table  class="table table-striped table-bordered dataTableARS">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Staff Name</th>
                            <th>email</th>
                            <th>Action</th>
                        </tr>
                        <tbody id="allSupervisorList">
                        </tbody>  
                    </thead>
                </table>
            </div>
        @endrole
        @role('Admin')
            <div class="users" id="regionalAdminSupervisorList">
                <table  class="table table-striped table-bordered dataTableRAS">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Staff Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                        <tbody id="supervisorByRa">
                        </tbody>  
                    </thead>
                </table>
            </div>
        @endrole
    </div>
</div>
<script>
$("#mySupervisors").on('click', function() {
    $("#showMySupervisors").show();
    $("#showAllSupervisors,#regionalAdminSupervisorList").hide();
});

$("#allSupervisors").on('click', function() {
    $("#showAllSupervisors").show();
    $("#showMySupervisors,#regionalAdminSupervisorList").hide();
    var url;
    $.ajax({
        url: "/allRegionalSupervisors",
        type: "get",
        data:{ 
            _token:'{{ csrf_token() }}'
        },
        cache:false,
        dataType: 'json',
        success: function(dataResult){
            var i = 1;
            var resultData = dataResult.data;
            console.log(resultData)
            var allSupervisorList = '';
            $.each(resultData,function(index,row){
                allSupervisorList+="<tr>"
                allSupervisorList+="<td>"+ i +"</td><td>"+row.name+"</td><td>"
                +row.email+"</td><td>"
                +"<a class='m-1 btn btn-primary btn-sm' href='supervisors/"+row.id+"'><i class='fa fa-eye'></i></a>"
                //+"<a class='m-1 btn btn-secondary btn-sm' href='supervisors/"+row.id+"/edit '><i class='fa fa-pencil'></i></a></td>"
                // +"<form style='margin:2%;display: inline;' id='staff-delete-form' action='supervisors/delete/'"+row.id+"' method='DELETE' onsubmit='return checkForm(this);'>"
                // +"<button name='myButton' title='Delete' type='submit' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure?')\">"
                // +"<i class='fa fa-trash-o'></i></button>"
                // +"</form></td>";
                allSupervisorList+="</tr>";
                i++;
            })
            $("#allSupervisorList").html(allSupervisorList);
            $(".dataTableARS").DataTable();
        }
    });
});

$("#regionalAdminSupervisor").on('click', function() {
    $("#regionalAdminSupervisorList").show();
    $("#showAllSupervisors ,#showMySupervisors").hide();
    var url;
    $.ajax({
        url: "/regionalAdminSupervisors",
        type: "get",
        data:{ 
            _token:'{{ csrf_token() }}'
        },
        cache:false,
        dataType: 'json',
        success: function(dataResult){   
            var i = 1;
            var supervisorByRa = '';
            var j = "Are you sure?";
            var resultData = dataResult.data;
            $.each(resultData,function(index,row){
                supervisorByRa+="<tr>"
                supervisorByRa+="<td>"+ i +"</td><td>"+row.name+"</td><td>"+row.email+"</td><td>"+row.address+"</td><td>"+row.phone_number+"</td>";
                supervisorByRa+="<td>"
                +"<a class='m-1 btn btn-primary btn-sm' href='supervisors/"+row.id+"'><i class='fa fa-eye'></i></a>"
                //+"<a class='m-1 btn btn-secondary btn-sm' href='supervisors/"+row.id+"/edit '><i class='fa fa-pencil'></i></a></td>"
                supervisorByRa+="</tr>";
                i++;
            })
            $("#supervisorByRa").html(supervisorByRa);
            $(".dataTableRAS").DataTable();
        }
    });
});
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
    function ConfirmDelete()
    {
       $("#theForm").ajaxForm({type: 'delete'});
    }
</script>
@endsection