@extends('layouts.myapp')
@section('title','Accept/Deny Entity')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-map"></i>
            </div>
            <div>
                Location Form Data List
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
       Accept or Deny the New Entity
       <div class="btn-wrapper btn-wrapper-multiple">
            <button id="allentity" class="btn btn-sm btn-primary float-right">All Entity</button>
            <button id="accept" class="btn btn-sm btn-success float-right">Accepted</button>
            <button id="reject" class="btn btn-sm btn-warning float-right">Rejected</button>
        </div>
        
    </div>
    @if(session('accept'))
       <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <span class="text-success">{{session('accept')}}</span>
       </div>
    @endif
    @if(session('reject'))
       <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <span class="text-warning">{{session('reject')}}</span>
       </div>
    @endif
    @if(session('remove'))
       <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <span class="text-info">{{session('remove')}}</span>
       </div>
    @endif
    <div class="card-body">
        <div class="row">
            <div class="col-12" id="entityList">
                <table class="table table-striped table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>Vendor Name</th>
                            <th>Latitdue</th>
                            <th>Longitude</th>
                            <th width="175">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entitiesFormData as $entitiesFormDatum)
                            <tr>
                                <td>{{ $entitiesFormDatum->name}}</td>
                                <td>{{ $entitiesFormDatum->latitude}}</td>
                                <td>{{ $entitiesFormDatum->longitude}}</td>
                                <td>
                                    <a title="View" class="btn btn-info btn-sm mr-1" href="{{asset('/entity-data-view/'.$entitiesFormDatum->id)}}">
                                        <li class="fa fa-eye"></li>
                                    </a>
                                    <a title="Accept" class="btn btn-success btn-sm mr-1" onclick="return confirm('Are you sure?');" href="{{asset('/supervisor/entities-form-datum/accept/'.$entitiesFormDatum->id)}}">
                                        <li class="fa fa-check"></li>
                                    </a>
                                    <a title="Reject" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');" href="{{asset('/supervisor/entities-form-datum/reject/'.$entitiesFormDatum->id)}}">
                                        <li class="fa fa-close"></li>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="users col-12 " id="acceptedEntityList">
                <table  class="table table-striped table-bordered dataTableAEL">
                    <thead>
                        <tr>
                           <th>Vendor Name</th>
                            <th>Latitdue</th>
                            <th>Longitude</th>
                            <th width="175">Action</th>
                        </tr>
                        <tbody id="acceptedData">
                        </tbody>  
                    </thead>
                </table>
            </div>
            <div class="users col-12" id="rejectedEntityList">
                <table  class="table table-striped table-bordered dataTableREL">
                    <thead>
                        <tr>
                            <th>Vendor Name</th>
                            <th>Latitdue</th>
                            <th>Longitude</th>
                            <th width="175">Action</th>
                        </tr>
                        <tbody id="rejectedData">
                        </tbody>  
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$("#allentity").on('click', function() {
    $("#entityList").show();
    $("#acceptedEntityList,#rejectedEntityList").hide();
});
$("#accept").on('click', function() {
    $("#acceptedEntityList").show();
    $("#entityList,#rejectedEntityList").hide();
    var url;
    $.ajax({
        url: "/supervisor/entities-form-data/acceptedList",
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
                acceptedData+="<td>"+ row.name + "</td><td>"+row.latitude+"</td><td>"+row.longitude+"</td>"
                + "<td><a title='View' class='btn btn-info btn-sm mr-1' href='/entity-data-view/"+row.id+"'><li class='fa fa-eye'></li></a>"
                + "<a title='Reject' class='btn btn-danger btn-sm' href='/supervisor/entities-form-datum/reject/"+row.id+"'><li class='fa fa-close'></li></a></td>";
                acceptedData+="</tr>";
                i++;
            })
            $("#acceptedData").html(acceptedData);
            $(".dataTableAEL").DataTable();
        }
    });
});
$("#reject").on('click', function() {
    $("#rejectedEntityList").show();
    $("#entityList,#acceptedEntityList").hide();
    var url;
    $.ajax({
        url: "/supervisor/entities-form-data/rejectedList",
        type: "get",
        data:{ 
            _token:'{{ csrf_token() }}'
        },
        cache:false,
        dataType: 'json',
        success: function(dataResult){
            var resultData = dataResult.data;
            var rejectedData = '';
            var i = 1;
            $.each(resultData,function(index,row){
                rejectedData+="<tr>"
                rejectedData+="<td>"+ row.name +"</td><td>"+row.latitude+"</td><td>"+row.longitude+"</td>"
                + "<td><a title='View' class='btn btn-info btn-sm mr-1' href='/entity-data-view/"+row.id+"'><li class='fa fa-eye'></li></a>"
                + "<a title='Accept' class='btn btn-success btn-sm' href='/supervisor/entities-form-datum/accept/"+row.id+"'><li class='fa fa-check'></li></a></td>";
                rejectedData+="</tr>";
                i++;
            })
            $("#rejectedData").html(rejectedData);
            $(".dataTableREL").DataTable();
        }
    });
});
</script>
@endsection