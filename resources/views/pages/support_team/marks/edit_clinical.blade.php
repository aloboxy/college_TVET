@extends('layouts.master')
@section('page_title', 'Manage Clinical Grade')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Fill The Form To Manage Clinical Grades</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        {{-- @include('pages.support_team.marks.clinical') --}}
    </div>
    <form class="ajax-update"
        action="{{ route('clinical.update', [$exam_id, $my_class_id, $section_id, $subject_id, $year]) }}"
        method="post">
        @method('put')
        @csrf
        <table class="table table-striped" id="marks-table">
            <div>
                <h1>Course: {{ $m->subject->name }} {{'session:'.$m->session }}</h1>
            </div>

            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>ADM_NO</th>
                    <th>Clinical Score</th>
                </tr>

            </thead>
            <tbody>
                `
                @foreach($marks->sortBy('user.name') as $mk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mk->user->name ?? 'unknonw' }} </td>
                    <td>{{ $mk->user->student_record->adm_no ?? 'unknown' }}</td>

                    {{-- CA AND EXAMS --}}
                    @switch($class_type->code)
                    @case('CS')
                    <td><input title="CA" min="0" max="30" class="text-center" name="tex3_{{ $mk->id }}"
                            value="{{ $mk->tex3 }}" type="number"></td>
                    @break

                    @case('PCP')
                    <td><input title="CA" min="0" max="30" class="text-center" name="tex3_{{ $mk->id }}"
                            value="{{ $mk->tex3 }}" type="number"></td>
                    @break


                    @case('LS')
                    <td><input title="CA" min="0" max="30" class="text-center" name="tex3_{{ $mk->id }}"
                            value="{{ $mk->tex3 }}" type="number"></td>

                    @break

                    @Case('NA')
                    <td><input title="CA" min="0" max="30" class="text-center" name="tex3_{{ $mk->id }}"
                            value="{{ $mk->tex3 }}" type="number"></td>

                    @break

                    @default
                    <td><input title="CA" min="0" max="30" class="text-center" name="tex3_{{ $mk->id }}"
                            value="{{ $mk->tex3 }}" type="number"></td>
                    @endswitch
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center mt-2">
            {{-- <button type="button" class="btn btn-success" onclick="exportexcell">Export to Excel</button> --}}
            <button type="submit" class="btn btn-primary">Update Marks<i class="icon-paperplane ml-2"></i></button>
        </div>
    </form>
</div>
@endsection