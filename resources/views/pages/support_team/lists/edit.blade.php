<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id, $year]) }}" method="post">
    @method('put')
    @csrf
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
                <td>{{ $mk->user->name ?? 'unknonw' }} </td>
                <td>{{ $mk->user->student_record->adm_no ?? 'unknown' }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>


</form>
