<form class="ajax-update"
>
    <table class="table datatable-button-html5-columns" id="marks-table">
        <div>
            @if(Qs::userIsTeamSA())
            <label for="scale">Course Scale</label>
            <input input title="Scale" id="scale" min="1" max="20" class="text-center" type="number">
            @endif
        </div>

        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>ADM_NO</th>
                @if($m->subject->clinical ==1)
                 <th>Final (100)</th>
                <th>Scale</th>
                <th>Total</th>
                <th>Point</th>
                @else
                <th>CA (20)</th>
                <th>Mid-Term (30)</th>
                <th>Final (50)</th>
                <th>Scale</th>
                <th>Total</th>
                <th>Point</th>
                @endif
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
                @if($m->subject->clinical ==1)
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                <td>
                @else
                <td>{{ $mk->t1 }}</td>
                <td>{{ $mk->t2 }}</td>
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                @endif
                @break


                @case('PCP')
                @if($m->subject->clinical ==1)
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                @else
                <td>{{ $mk->t1 }}</td>
                <td>{{ $mk->t2 }}</td>
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                @endif
                @break


                @case('LS')
                <td>{{ $mk->t1 }}</td>
            @if(Qs::userIsTeacher())
                @if($m->subject->clinical ==1)
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>
                @else
                <td>{{ $mk->t2 }}</td>
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
               
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>
                @endif
            @else
                @if($m->subject->clinical ==1)
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                 <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>
                @else
                <td>{{ $mk->t2 }}</td>
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>
                @endif

            @endif
            @break



                @Case('NA')
            @if($m->subject->clinical ==1)
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                 <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>
            @else 
                <td>{{ $mk->t1 }}</td>
                @if(Qs::userIsTeacher())
                <td>{{ $mk->t2 }}</td>
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>

                @else
                @if($m->subject->clinical ==1)
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                @else
                <td>{{ $mk->t2 }}</td>
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                @endif
               
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>
                @endif
        @endif
            @break

                @default
                @if($m->subject->clinical ==1)
                <td>{{ $mk->exm }}</td>
                <td>{{ $mk->s1 }}</td>
                <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                @else
                    <td>{{ $mk->t1 }}</td>
                    @if(Qs::userIsTeacher())
                    <td>{{ $mk->t2 }}</td>
                    <td>{{ $mk->exm }}</td>
                    @else
                    <td>{{ $mk->t2 }}</td>
                    <td>{{ $mk->exm }}</td>
                    <td>{{ $mk->s1 }}</td>
                    <td>{{ $mk->tex1 ?? $mk->tex2 ?? '' }}</td>
                @endif
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->name }}
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                    @endif
                @endswitch
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <div class="text-center mt-2">
        <button type="button" class="btn btn-success" onclick="exportexcell">Export to Excel</button>
        <button type="submit" class="btn btn-primary">Update Marks<i class="icon-paperplane ml-2"></i></button>
    </div> --}}
</form>
<h1>Course Statistics</h1>

<table>
    <thead>
        <tr>
            <th>Grade</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($count_out as $grade)
        <tr>
            <td>{{ $grade['grade'] }} </td>
            <td> {{ $grade['count'] }} </td>

        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $("input#scale").keyup(function(){
  $("input#scale1").val($(this).val());
});
</script>
