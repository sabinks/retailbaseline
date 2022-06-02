@extends('layouts.myapp')
@section('title','Item Balance Sheet')
@section('content')
    <div id="route"></div>
@endsection

@push('scripts')
    <script src="{{ asset('js/route.js') }}"></script>
@endpush
