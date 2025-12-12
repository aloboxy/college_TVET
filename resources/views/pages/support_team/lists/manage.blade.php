@extends('layouts.master')
@section('page_title', 'Course list')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold">Course List</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div>
            <form method="post" action="{{ route('courselist.selector') }}">
                @csrf
            <div class="card-body">
                <livewire:mark-selector/>
            </div>
            </form>
        </div>
    </div>


    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col-md-3"><h6 class="card-title"><strong>Course: </strong> {{ $m->subject->name }}</h6></div>
                <div class="col-md-4"><h6 class="card-title"><strong>Session: </strong> {{ $m->session }}</h6></div>
                <div class="col-md-2"><h6 class="card-title"><strong>Year: </strong> {{ $year }}</h6></div>
                <div class="col-md-2"><h6 class="card-title"><strong>Level: </strong> {{ $my_class->name }}</h6></div>
                {{-- <div class="col-md-4"><h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->name.' '.$m->section->name }}</h6></div> --}}
                {{-- <div class="col-md-4"><h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name.' / '.$m->year }}</h6></div> --}}
            </div>
        </div>

        <div class="card-body">
            @include('pages.support_team.lists.edit')
            {{--@include('pages.support_team.marks.random')--}}
        </div>
    </div>

    {{--Marks Manage End--}}

@endsection
