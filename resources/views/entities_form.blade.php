@extends('layouts.myapp')
@section('title','Entities Tracking Form')
@section('content')
<!-- <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
               Entities Tracking Form
            </div>
        </div>
    </div>
</div> -->
<div id="route" url="{{ config('app.url') }}"></div>

@endsection

@section('form_css')
    <link rel="stylesheet" href="{{ asset('css/dynamic_form.css') }}">
@endsection

@push('scripts')
    <script src="{{asset('js/route.js')}}"></script>
@endpush
