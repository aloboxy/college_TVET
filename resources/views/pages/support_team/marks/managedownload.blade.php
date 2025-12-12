@extends('layouts.master')
@section('page_title', 'Download '.$m->subject->name.' Session '. $m->session .' Grade Roster'. ' Academic year '. $m->year)
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Fill The Form To Download</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('marks.selectordownload') }}">
            @csrf
        @include('pages.support_team.marks.selector_course')
        </form>
    </div>
</div>



<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col-md-3">
                <h6 class="card-title"><strong>Course: </strong> {{ $m->subject->name }}</h6>
            </div>
            <div class="col-md-4">
                <h6 class="card-title"><strong>Session: </strong> {{ $m->session }}</h6>
            </div>
            <div class="col-md-2">
                <h6 class="card-title"><strong>Year: </strong> {{ $m->year }}</h6>
            </div>
           
            <!-- @if($m->subject->clinical ==1)
            <h2 style="color: red">Please note that this is a clinical Course. Total Grade should
                not
                exceed 70</h2>
            <br>
            <a
                    {{-- href="{{ route('clinical.get',[$exam_id,$section_id,$my_class_id,$subject_id,$year]) }}" --}}
                    style="color:white;"><button type="button" class="btb btn-secondary" style="margin: 4px; background-color:green">Enter Clinical Grades
            </button>
            </a>
            @else

            @endif -->
        </div>
    </div>

    <div class="card-body">
        @include('pages.support_team.marks.downloadroaster')
        {{--@include('pages.support_team.marks.random')--}}
    </div>
</div>



{{--Marks Manage End--}}

@endsection
