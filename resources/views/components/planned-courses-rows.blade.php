@foreach($planned as $plan)
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
    @if(Qs::getSetting('planning_open') == 1)
    <td class="text-center">
        <button type="button" class="btn btn-danger drop-btn" data-course-id="{{ $plan->id }}">
            <span>Drop Course</span>
        </button>
    </td>
    @endif
</tr>
@endforeach