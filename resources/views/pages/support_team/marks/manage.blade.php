@extends('layouts.master')
@section('page_title', 'Manage Grades for')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Fill The Form To Manage Marks</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('marks.selector') }}">
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
             <div class="col-md-2">
                <h6 class="card-title"><strong>Credit Hour: </strong> {{ $m->subject->credit }}</h6>
            </div>
            @if($m->subject->clinical ==1)
            <h2 style="color: red">Please note that this is a clinical Course. Total Grade should be 100%</h2>
            <br>
            {{-- <a
                    href="{{ route('clinical.get',[$exam_id,$section_id,$my_class_id,$subject_id,$year]) }}"
                    style="color:white;"><button type="button" class="btb btn-secondary" style="margin: 4px; background-color:green">Enter Clinical Grades
            </button>
            </a> --}}
            @else

            @endif
        </div>
    </div>

    <div class="card-body">
        @include('pages.support_team.marks.edit')
        {{--@include('pages.support_team.marks.random')--}}
    </div>
</div>



{{--Marks Manage End--}}

@endsection
