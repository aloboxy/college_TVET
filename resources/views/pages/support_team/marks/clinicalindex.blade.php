@extends('layouts.master')
@section('page_title', 'Manage Clinical Grades')
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="icon-books mr-2"></i> Manage Clinical Grades</h5>
        {!! Qs::getPanelOptions() !!}
    </div>
    @if(Qs::userIsTeacher())
    @if($planning ==1)
    <div class="card-body">
        @include('pages.support_team.marks.clinical')
    </div>
    @else
    <h1 class="text-center"> Grade Entry Closed</h1>
    @endif
    @else
    <div class="card-body">
        @include('pages.support_team.marks.clinical')
    </div>
    @endif
</div>
@endsection
