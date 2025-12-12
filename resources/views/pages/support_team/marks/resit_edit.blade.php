<form class="ajax-update" action="{{ route('resits.update', [$exam_id, $department_id, $subject_id, $year]) }}" method="post">
    @method('put')
    @csrf
    <table class="table table-striped">
        {{-- <div>
            @if(Qs::userIsTeamSA())
            <label for="scale">Course Scale (Please Add only 3)</label>
            <input input title="Scale" id="scale" min="1" max="3" class="text-center"  type="number">
            @endif
        </div> --}}

        <thead>
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>ADM_NO</th>
            <th>Grade (73)</th>
        </tr>

        </thead>
        <tbody>

        @foreach($marks->sortBy('user.name') as $mk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mk->user->name ?? 'unknonw' }} </td>
                <td>{{ $mk->user->student_record->adm_no ?? 'unknown' }}</td>
                 <td><input title="Final Exam" min="0" max="73" class="text-center" name="exm_{{ $mk->id }}" value="{{ $mk->exm }}" type="number"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
<script>
    $("input#scale").keyup(function(){
  $("input#scale1").val($(this).val());
});
</script>
    <div class="text-center mt-2">
        <button type="submit" class="btn btn-primary">Update Marks<i class="icon-paperplane ml-2"></i></button>
    </div>
</form>
