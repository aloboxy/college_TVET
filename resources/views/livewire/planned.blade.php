<div class="card">
    <script>
     var timeout = 300;
     $('.alert').delay(timeout).fadeOut(300);
    </script>

@if (session()->has('message'))
    <div id="session" class="alert alert-warning" role="alert">
        {{ session('message') }}
    </div>
@endif

@php
     $s = Auth::user()->id;
     $sd = DB::table('student_records')->where('user_id',$s)->first();
     $rd = DB::table('sections')->where('id', $sd->section_id)->first();
@endphp

    <div class="col-md-15">
    <div class="text-center">
        <h1>Planned Courses</h1>
     </div>
         <div class="table-responsive">
             <table class="table datatable-button-html5-columns">
                         <thead>
                         <tr>
                             <th>#</th>
                             <th>Course Name</th>
                             <th>Teacher</th>
                             <th>Semester</th>
                             <th>Session</th>
                             <th width="10%">Room</th>
                             <th>Day</th>
                             <th>Time From</th>
                             <th>Time To</th>
                             @if(Qs::getSetting('planning_open') == 1)
                             <th>Action</th>
                             @endif
                         </tr>
                         </thead>
         @if(!is_null($courses))
                         <tbody>
                             @foreach($courses as $s)
                             <tr wire:key="{{$s->id}}">
                                 <td>{{ $loop->iteration }}</td>
                                 <td>{{ $s->subject->name ?? 'unknown' }}</td>
                                 <td>{{ $s->teacher->name ?? 'unknown' }}</td>
                                 <td>
                                     @if($s->term_id == 1)
                                     {{'First Semester'}}
                                     @else
                                     {{ 'Second Semester' }}
                                     @endif
                                 </td>
                                 <td>{{ $s->session ?? 'TBA'}}</td>
                                 <td width="10%">{{ $s->room ?? 'TBA'}}</td>
                                 <td>{{ $s->day ?? 'Unknown'}}</td>
                                 <td>{{Carbon\Carbon::parse($s->time_from)->format('g:i A') ?? 'unknown' }}</td>
                                 <td>{{Carbon\Carbon::parse($s->time_to)->format('g:i A') ?? 'unknown'}}</td>
                                 @if(Qs::getSetting('planning_open') == 1)
                                 <td class="text-center">
                                     <button type="button" wire:click="delete({{ $s->id }})"
                                        wire:confirm="You are dropping the Course Type Delete to confirm|Delete" class="btn btn-danger" wire:loading.attr="disabled"><span wire:loading.remove>Drop Course</span>
                                         <span wire:loading>Dropping..</span>
                                     </button>
                                 </td>
                                 @endif
                             </tr>
                             @endforeach
                         </tbody>
                         @endif
                     </table>
                 </div>
    </div>
</div>
