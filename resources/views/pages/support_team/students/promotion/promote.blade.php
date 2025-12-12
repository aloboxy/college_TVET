<form method="post" action="{{ route('students.promote', [$fc, $fs, $tc, $ts, $year,$exam_id, $pass]) }}">
    @csrf
    <table class="table table-striped">
        @if($pass ==1)
        <h1 class="text-center" style="color: red">{{"FAILED STUDENTS FOR"}} - {{"Academic Year: "}}{{$year}}- {{$exam->name}}</h1>
        @else
        <h1 class="text-center" style="color: green">{{"PASSED STUDENTS FOR"}} - {{"Academic Year: "}}{{$year}}- {{$exam->name}}</h1>
        @endif


        <thead>
        <tr>
            <th>#</th>
            <th>Photo</th>
            <th>Name</th>
            <th>Admission#</th>
            <th>Current Session</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($students as $sr)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><img class="rounded-circle" style="height: 30px; width: 30px;" src="{{ $sr->photo }}" alt="img"></td>
                <td>{{ $sr->user->name ?? '' }}</td>
                <td>{{$sr->adm_no ?? ''}}</td>
                <td>{{ $sr->session ?? '' }}</td>
                <td>
                    <select class="form-control select">
                    @if($pass ==0)
                        <option value="">Promote</option>
                    @else
                        <option value="">Don't Promote</option>
                    @endif
                    </select>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center mt-3">
        <button class="btn btn-success"><i class="icon-stairs-up mr-2"></i> Promote Students</button>
    </div>
</form>
