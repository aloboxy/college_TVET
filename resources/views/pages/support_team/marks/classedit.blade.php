<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $course_id]) }}" method="post">
    @csrf @method('put')
    <table class="table datatable-button-html5-columns">
        <thead>
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>ADM_NO</th>
        </tr>
        </thead>
        <tbody>
        @foreach($marks->sortBy('user.name') as $mk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mk->user->name }} </td>
                <td>{{ $mk->user->student_record->adm_no }}</td>

{{--                CA AND EXAMS --}}
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</form>
