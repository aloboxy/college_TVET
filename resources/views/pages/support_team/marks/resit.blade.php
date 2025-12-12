@extends('layouts.master')
@section('page_title', 'Resits Grades for')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold">Fill The Form To Manage Resits Grades</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div>
            <form method="post" action="{{ route('resits.selector') }}">
                @csrf
            <div class="card-body">
             @include('pages.support_team.marks.selector_course')
            </div>
            </form>
        </div>
    </div>

    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col-md-4"><h6 class="card-title"><strong>Course: </strong> {{ $m->subject->name }}</h6></div>
                <div class="col-md-4"><h6 class="card-title"><strong>Session: </strong> {{ $m->session }}</h6></div>
                {{-- <div class="col-md-4"><h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->name.' Cohort'.''. $m->section->name }}</h6></div> --}}
                {{-- <div class="col-md-4"><h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name.' / '.$m->year }}</h6></div> --}}
            </div>
        </div>

        <div class="card-body">
            @include('pages.support_team.marks.resit_edit')
            {{--@include('pages.support_team.marks.random')--}}
        </div>
    </div>

    {{--Marks Manage End--}}

@endsection
