@extends('layouts.myapp')
@section('title','Report Form')
@section('content')
    <div id="route"></div>
@endsection

@section('form_css')
    <link rel="stylesheet" href="{{ asset('css/dynamic_form.css') }}">
@endsection

@push('scripts')
    <script src="{{ asset('js/route.js') }}"></script>
@endpush
