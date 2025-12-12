@extends('layouts.master')
@section('page_title', 'Student Gradesheet')
@section('content')


<div class="card">
    <div class="card-header text-center">
        <h4 class="card-title font-weight-bold">Student Gradesheet for => {{ $sr->user->name }} </h4>
    </div>
</div>

{{-- Loop for exams --}}
@foreach($exams as $ex)
{{-- loop  for exam recors--}}
    @foreach($exam_records->where('exam_id', $ex->id) as $exr)

{{-- Only auth , student, and administrator if check --}}
    @if(Qs::userIsAcademic())
            <div class="card">
            @if($sr->my_class_id == null)
                <div class="card-header header-elements-inline">
                    <h6>{{ strtoupper($ggh->real_department->name) ?? ''}} {{ '   Major: '. $ggh->major ?? '' }}</h6>
                    <h6 class="font-weight-bold">{{ strtoupper($ex->exam_name.' - '.$ex->year)}}</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
            @else
                    <div class="card-header header-elements-inline">
                        <h6>{{ strtoupper($my_class) ?? ''}} {{ ' -'. $my_coho ?? '' }}</h6>
                        <h6 class="font-weight-bold">{{ strtoupper($ex->exam_name.' - '.$ex->year)}}</h6>
                        {!! Qs::getPanelOptions() !!}
                    </div>
            @endif
                <div class="card-body collapse">
            @if(Qs::userIsTeamSA())
                    @include('pages.support_team.marks.show.sheet')
                    <div class="text-center mt-3">
                        <a target="_blank" href="{{ route('marks.print', [Qs::hash($student_id), $ex->id, $year]) }}"
                           class="btn btn-secondary btn-lg">Print Gradesheet <i class="icon-printer ml-2"></i></a>
                    </div>
             @elseif(Qs::published_grades($ex->id) == 1)
                        @if(Qs::student_fees($student_id, $year, $ex->id)['status'] == 1 || Qs::student_fees($student_id, $year, $ex->id)['status'] == -1)
                            <h1 style="color: red; text-align:center">Please Visit the Business Office</h1>
                        @else
                            {{-- Include the grade sheet here if balance is cleared --}}
                            @include('pages.support_team.marks.show.sheet')

                            @if(Qs::userIsTeamSA())
                                <div class="text-center mt-3">
                                    <a target="_blank" href="{{ route('marks.print', [Qs::hash($student_id), $ex->id, $year]) }}"
                                    class="btn btn-secondary btn-lg">Print Gradesheet <i class="icon-printer ml-2"></i></a>
                                </div>
                            @endif
                        @endif

                </div>
            @else
                <h1  style="text-align:center">Grades Pending Approval</h1>
            @endif
            </div>
            {{-- end of auth, user admin check --}}
    @endif

    @endforeach
@endforeach

{{-- EXAM COMMENTS --}}
{{-- @include('pages.support_team.marks.show.comments') --}}

{{-- SKILL RATING --}}
{{-- @include('pages.support_team.marks.show.skills') --}}

@endsection
