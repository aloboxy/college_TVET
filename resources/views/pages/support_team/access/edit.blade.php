<form class="" action="{{ route('access.update', [$exam_id, $my_class_id, $section_id, $year]) }}" method="post">
    @csrf @method('put')
    <table class='table datatable-button-html5-columns'>
        <thead>
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>ADM_NO</th>
            <th>Access</th>
        </tr>
        </thead>
        <tbody>

        @foreach($access as $mk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mk->user->name ?? 'Unknown' }} </td>
                <td>{{ $mk->student->adm_no ?? 'unknown' }}</td>
                <td>
                    <select class="form-control select" name="p-{{$mk->id}}">
                        <option value=""></option>
                        <option {{ ($mk->access  == 1 ? 'selected' : '') }} value=1 >Access</option>
                        <option  {{ ($mk->access  == 0 ? 'selected' : '') }} value=0>No Access</option>
                    </select>
            </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-center mt-2">
        <button type="submit" class="btn btn-primary">Update Access<i class="icon-paperplane ml-2"></i></button>
    </div>
</form>
