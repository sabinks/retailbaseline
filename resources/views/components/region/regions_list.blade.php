@extends('layouts.myapp')
@section('title','Regions')
@section('content')

<div class="main-card mb-3 card">
    <div class='card-header'>
        <div class='card-title'>
           View Regions
        </div>
        <div class="btn-wrapper btn-wrapper-multiple">
            <button type="button" class="btn btn-sm btn-success">
                <a id="link_page" href="{{asset('createMyRegion')}}">Add New Region</a>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Region Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($regions as $index => $region)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $region->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
</script>
@endsection