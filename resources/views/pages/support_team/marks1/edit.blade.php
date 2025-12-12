<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id]) }}" method="post">
    @csrf @method('put')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>ADM_NO</th>
            <th>CA (20)</th>
            <th>Mid-Term (30)</th>
            <th>Final (50)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($marks->sortBy('user.name') as $mk)
            <tr>
            @if($mk->user->status == 1)
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mk->user->name }} </td>
                <td>{{ $mk->user->student_record->adm_no }}</td>

{{--                CA AND EXAMS --}}
                <td><input title="1ST CA" min="1" max="20" class="text-center" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}" type="number"></td>
                <td><input title="2ND CA" min="1" max="30" class="text-center" name="t2_{{ $mk->id }}" value="{{ $mk->t2 }}" type="number"></td>
                <td><input title="EXAM" min="1" max="50" class="text-center" name="exm_{{ $mk->id }}" value="{{ $mk->exm }}" type="number"></td>

            </tr>
             @endif
        @endforeach
        </tbody>
    </table>

    <div class="text-center mt-2">
        <button type="submit" class="btn btn-primary">Update Marks <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>
