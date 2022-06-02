@extends('layouts.myapp')
@section('title','Other Staffs')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
                Staffs Assigned by Super Admin
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        View, edit or delete the Staffs Listed
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
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Contact Number</th>
                        <th>Address</th>
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
                                <div class='btn-group'>
                                    {{-- <a href="{{ route('staffs.show',$user->id) }}" class='btn btn-primary btn-sm'>
                                        <i class="fa fa-eye"></i>
                                    </a> --}}
                                    <form id="#" action="#" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit"  class="btn btn-sm btn-danger"><i class="fa fa-remove"></i></button>
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