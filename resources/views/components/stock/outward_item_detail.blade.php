@extends('layouts.myapp')
@section('title','Outward Item Detail')
@section('content')
    <div id="route" url="{{ config('app.url') }}"></div>
@endsection

@push('scripts')
    <script src="{{ asset('js/route.js') }}"></script>
@endpush
