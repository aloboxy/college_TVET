@extends('layouts.master')
@section('page_title', 'See Active and Planned Students')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Fill The Form to see Students that Planned Courses</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @include('pages.support_team.planning.index')
    </div>
</div>
@endSection