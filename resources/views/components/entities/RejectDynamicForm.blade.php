@extends('layouts.myapp')
@section('title','Accept/Deny Entity')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-map"></i>
            </div>
            <div>
                Location Form List
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
       View Entity Data list
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" id="entityList">
                <table class="table table-striped table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>Form Creator Name</th>
                            <th>From Title</th>
                            <th width="75">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entitiesForms as $entitiesForm)
                            <tr>
                                <td>{{ $entitiesForm->formCreator->name}}</td>
                                <td>{{ $entitiesForm->form_title}}</td>
                                <td>
                                    <a title="View Location Form Data list" class="btn btn-success btn-sm" href="{{url('supervisor/entities-form/'.$entitiesForm->id.'/entities-form-data')}}">
                                        <li class="fa fa-plus"></li>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection