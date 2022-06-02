@extends('layouts.myapp')
@section('title','Home')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-tachometer"></i>
            </div>
            <div>
                Welcome {{Auth::user()->name}}<br>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
    
    </div>
    <div class="card-body">
       @if($message = session('message'))
            <div class="alert autoremove alert-success">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <p>{{ $message }}</p>
            </div>
       @endif
       @if($message = session('alert'))
            <div class="alert autoremove alert-warning">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <p>{{ $message }}</p>
            </div>
       @endif
    </div>
</div>
@endsection

