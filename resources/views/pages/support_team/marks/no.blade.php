@extends('layouts.master')
@section('page_title', 'Manage Grades for')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold">Fill The Form To Manage Grades</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <form method="post" action="{{ route('resits.selector') }}">
            @csrf
        <div class="card-body">
          @include('pages.support_team.marks.selector')
        </div>
        <div class="text-center">
            <h1>Planning still ongoing for this Cohort Please select Different Cohort</h1>
        </div>
        </form>

    </div>

    {{--Marks Manage End--}}

@endsection
