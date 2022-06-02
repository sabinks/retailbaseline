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
                <p>Staffs List</p>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
            @role('Admin|Regional Admin')
                    View, edit or disable the Staffs Listed
            @endrole
            @role('Supervisor')
                List of Staff(s)
            @endrole
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            @role('Admin|Regional Admin')
                <button type="button" class="btn btn-sm btn-success">
                    <a id="link_page" href="{{route('staffs.create')}}">Add New Staffs</a>
                </button>
                <button type="button" id="myStaff" class="btn btn-sm btn-primary">My Staffs</button>
                <button type="button" id="assignedStaff" class="btn btn-sm btn-secondary">Assigned Staff</button>
            @endrole
            @role('Admin')
                <button type="button" id="staffByRegionalAdmin" class="btn btn-sm btn-info">Staffs Created by Regional Admin</button>
            @endrole
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

        <div id="myStaffList" class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        @can('viewAssociate')
                            <th style="width: 30%">Client Association</th>
                            <th style="width: 15%">Assign Client</th>
                        @endcan
                        @role('Admin')
                            <th>Region Associated</th>
                        @endrole
                        <th>En/Disabled</th>
                        @role(['Admin|Regional Admin'])
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
                            <td><span class="badge badge-pill badge-primary">{{ $user->regions()->first()->name }}</span></td>
                            @endrole
                            <td>
                                @if($user->deleted_at)
                                    <span class="badge badge-pill badge-danger">Disabled</span>
                                @else
                                    <span class="badge badge-pill badge-success">Enabled</span>
                                @endif
                            </td>
                            @role(['Admin|Regional Admin'])
                            <td style="text-align:center">
                                <div class='btn-group'>
                                    <a style="margin:2%" title="View" href="{{ route('staffs.show',$user->id) }}" class='btn btn-primary btn-sm'>
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a style="margin:2%" title="Edit" href="{{ route('staffs.edit',$user->id) }}" class='btn btn-secondary btn-sm'>
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a style="margin:2%" title="Reset Password" href="{{route('subordinate.resetPassword',$user->id)}}" class='btn btn-warning btn-sm'>
                                        <i class="fa fa-gears"></i>
                                    </a>
                                    <form style="margin:2%" id="staff-delete-form" action="/field-staff-enable-disable/{{$user->id}}"onsubmit="return checkForm(this);" method="POST">
                                        @csrf
                                        
                                        @if($user->deleted_at)
                                            <button name="myButton" title="Enable Staff" type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-plus"></i></button>
                                        @else
                                            <button name="myButton" title="Disable Staff" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i  class="fa fa-user-times"></i></button>
                                        @endif  
                                    </form>
                                    <!-- <form style="margin:2%" id="staff-delete-form" action="{{ route('staffs.destroy',$user->id) }}" method="POST" onsubmit="return checkForm(event,this);">
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
        <!--ALl accepted Staff list-->
        <div class="users" id="assignedStaffs">
            <table  class="table table-striped table-bordered dataTableas">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Staff Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <tbody id="acceptedData">
                    </tbody>  
                </thead>
            </table>
        </div>
        @role('Admin')
            <div class="users" id="staffByRegionalAdminList">
                <table  class="table table-striped table-bordered dataTableSBRA">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Staff Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        <tbody id="regionalAdminStaffData">
                        </tbody>  
                    </thead>
                </table>
            </div>
        @endrole
    </div>
</div>
<script>
    $("#assignedStaff").on('click', function() {
    $("#assignedStaffs").show();
    $("#myStaffList,#staffByRegionalAdminList").hide();
    var url;
        $.ajax({
            url: "/otherStaffs",
            type: "get",
            data:{ 
                _token:'{{ csrf_token() }}'
            },
            cache:false,
            dataType: 'json',
            success: function(dataResult){
             
                var resultData = dataResult.data;
                var acceptedData = '';
                var i = 1;
                $.each(resultData,function(index,row){
                    acceptedData+="<tr>"
                        acceptedData+="<td>"+ i +"</td>"
                        +"<td>" + row.name + "</td>"
                        +"<td>" + row.email + "</td>"
                        +"<td><a class='btn btn-primary btn-sm' href='staffs/" + row.id + "'><i class='fa fa-eye'></i></a></td>"
                    acceptedData+="</tr>";
                    i++;
                })
                $("#acceptedData").html(acceptedData);
                $(".dataTableas").DataTable();
            }
        });
    });
    $("#staffByRegionalAdmin").on('click', function() {
        $("#staffByRegionalAdminList").show();
        $("#assignedStaffs,#myStaffList").hide();
        var url;
        $.ajax({
            url: "/staffByRegionalAdmin",
            type: "get",
            data:{ 
                _token:'{{ csrf_token() }}'
            },
            cache:false,
            dataType: 'json',
            success: function(dataResult){
                var resultData = dataResult.data;
                var regionalAdminStaffData = '';
                var i = 1;
                var resultData = dataResult.data;
                $.each(resultData,function(index,row){
                    regionalAdminStaffData+="<tr>"
                    regionalAdminStaffData+="<td>"+ i +"</td>"
                    +"<td>" +row.name + "</td>"
                    +"<td>" + row.email + "</td>"
                    +"<td><a class='btn btn-primary btn-sm' href='staffs/" + row.id + "'><i class='fa fa-eye'></i></a>"
                    //+"<a class='m-1 btn btn-secondary btn-sm' href='staffs/"+row.id+"/edit '><i class='fa fa-pencil'></i></a>"
                    +"</td>"
                    regionalAdminStaffData+="</tr>";
                    i++;
                })
                $("#regionalAdminStaffData").html(regionalAdminStaffData);
                $(".dataTableSBRA").DataTable();
            }
        });
    });

    $("#myStaff").on('click', function() {
        $("#myStaffList").show();
        $("#assignedStaffs,#staffByRegionalAdminList").hide();
    });
    
    function checkForm(form) // Submit button clicked
    {
        form.myButton.disabled = true;
        return true;
    }
</script>
@endsection