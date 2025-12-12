@extends('layouts.master')
@section('page_title', 'Manage Resits Grades')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Manage Resits Grades</h5>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div  class="card-body">
        <form method="post" action="{{ route('resits.selector') }}">
        @csrf
        @include('pages.support_team.marks.selector_course')
        </form>
        </div>
    @endsection
