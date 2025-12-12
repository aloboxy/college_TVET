@extends('layouts.master')
@section('page_title', 'Student Information)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Students List By Course</h6>
            {!! Qs::getPanelOptions() !!}
        </div>


@endsection
