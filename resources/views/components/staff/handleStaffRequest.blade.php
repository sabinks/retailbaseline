@extends('layouts.myapp')
@section('title','Request')
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
@if($message = session('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if($message = session('error'))
            <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if($message = session('success'))
            <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Staff Requested</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @foreach ($user->fieldstaffs as $staff)
                            <tr>
                                <td><a href="{{route('admins.show',$user->id)}}">{{ $user->name }}</a></td>
                                <td><a href="{{route('staffs.show',$staff->id)}}">{{ $staff->name }}</a></td>
                                <td>
                                    <a class="btn btn-sm btn-success" href="{{ '/grant/' .$user->id.'/'.$staff->id }}"> Grant</a>
                                    <a class="btn btn-sm btn-danger" href="{{ '/reject/' .$user->id.'/'.$staff->id }}"> Reject</a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
