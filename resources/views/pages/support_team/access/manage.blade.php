@extends('layouts.master')
@section('page_title', 'Planning Marks')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold">Fill The Form To Manage Planning</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @include('pages.support_team.access.selector')
        </div>
    </div>

    <div class="card">

        {{-- <div class="card-header">
            <div class="row">
                <div class="col-md-4"><h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->name.' '.$m->section->name }}</h6></div>
                <div class="col-md-4"><h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name.' - '.$m->year }}</h6></div>
            </div>
        </div> --}}

        <div class="card-body">
            @include('pages.support_team.access.edit')
        </div>
    </div>

    {{--Marks Manage End--}}

@endsection
