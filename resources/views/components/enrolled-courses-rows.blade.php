<table class="table datatable-button-html5-columns table-sm table-hover" id="courses-table">
            <thead>
                <tr>
                    <th>#</th><th>Course Name</th>
                    <th>Teacher</th>
                    <th>Semester</th>
                    <th>Session</th>
                    <th>Room</th>
                    <th>Day</th>
                    <th>Time From</th>
                    <th>Time To</th>
                    <th>Capacity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="course-body">
            @foreach($enrolled_courselist as $plan)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $plan->subject->name ?? 'unknown' }}</td>
    <td>{{ $plan->teacher->name ?? 'unknown' }}</td>
    <td>
        @if($plan->term_id == 1)
        {{'First Semester'}}
        @else
        {{ 'Second Semester' }}
        @endif
    </td>
    <td>{{ $plan->session ?? 'TBA'}}</td>
    <td width="10%">{{ $plan->room ?? 'TBA'}}</td>
    <td>{{ $plan->day ?? 'Unknown'}}</td>
    <td>{{Carbon\Carbon::parse($plan->time_from)->format('g:i A') ?? 'unknown' }}</td>
    <td>{{Carbon\Carbon::parse($plan->time_to)->format('g:i A') ?? 'unknown'}}</td>
    <td>{{ $plan->total}}{{ '/' }}{{ $plan->capacity }}</td>
    @if(Qs::getSetting('planning_open') == 1)
    <td class="text-center">
        <button type="button" class="btn btn-success add-btn" data-course-id="{{ $plan->id }}">
            <span>Add Course</span>
        </button>
    </td>
    @endif
</tr>
@endforeach
    </tbody>
</table>