@extends('layouts.myapp')
@section('title','Report Data')
@section('content')
    <div id="route" url="{{ config('app.url') }}"></div>
@endsection

@section('form_css')
    <link rel="stylesheet" href="{{ asset('css/dynamic_form.css') }}">
    <style>
       @media (min-width: 576px){
            .custom-dialog-css {
                max-width: 700px !important;
            }
       }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('js/route.js') }}"></script>
@endpush
