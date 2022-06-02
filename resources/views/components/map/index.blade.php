@extends('layouts.myapp')
@section('title','Map Location')
@section('content')
<div class="row">
    <div class="col-12">
        <div id="route" value="{{ env('GOOGLE_API_KEY') }}"></div>
    </div>
</div>
@endsection

@section('form_css')
    
@endsection

@push('scripts')
    <script src="{{asset('js/route.js')}}"></script>
@endpush
