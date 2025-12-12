<form class="ajax-update"
    action="{{ route('marks.update', [$exam_id, $department_id, $subject_id, $year]) }}" method="post">
    @method('put')
    @csrf
    <table class="table table-striped" id="marks-table">
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
                @if(Qs::userIsTeamSA())
                <th>Scale</th>
                @endif
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
                <td><input title="CA" min="0" max="20" class="text-center" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}"
                        type="number"></td>
                <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                        value="{{ $mk->t2 }}" type="number"></td>
                <td><input title="Final Exam" min="0" max="50" class="text-center" name="exm_{{ $mk->id }}"
                        value="{{ $mk->exm }}" type="number"></td>
                <td><input title="Final Exam" min="0" max="50" class="text-center" id="total" type="number"></td>
                @break


                @case('PCP')
                <td><input title="CA" min="0" max="20" class="text-center" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}"
                        type="number"></td>
                <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                        value="{{ $mk->t2 }}" type="number"></td>
                <td><input title="Final Exam" min="0" max="50" class="text-center" name="exm_{{ $mk->id }}"
                        value="{{ $mk->exm }}" type="number"></td>
                @break


                @case('LS')
                    @if($m->subject->clinical ==1)
                        <td><input title="Final Exam" min="0" max="100" class="text-center" name="exm_{{ $mk->id }}"
                                value="{{ $mk->exm }}" type="number" ></td>
                        <td><input title="scale" id="scale1" class="text-center" type="number" name="s1_{{ $mk->id }}"
                                value="{{ $mk->s1 }}" ></td>
                        <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                                id="total" readonly></td>
                        <td>
                        @foreach($grades as $grade)
                        @if($grade->id == $mk->grade_id)
                        {{ $grade->grade }} ({{ $grade->name }})
                        @else
                        {{ '' }}
                        @endif
                        @endforeach
                        </td>
                @else
                <td><input title="CA" min="0" max="20" class="text-center" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}"
                        type="number"></td>


                        @if(Qs::userIsTeacher())
                        <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                                value="{{ $mk->t2 }}" type="number" ></td>
                        <td><input title="Final Exam" min="0" max="50" class="text-center" name="exm_{{ $mk->id }}"
                                value="{{ $mk->exm }}" type="number" ></td>
                        <td><input title="scale" id="scale1" class="text-center" type="number" name="s1_{{ $mk->id }}"
                                value="{{ $mk->s1 }}" ></td>
                        <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                                id="total" readonly></td>
                        <td>
                        @foreach($grades as $grade)
                        @if($grade->id == $mk->grade_id)
                        {{ $grade->grade }} ({{ $grade->name }})
                        @else
                        {{ '' }}
                        @endif
                        @endforeach
                        </td>
                        @else
                        <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                                value="{{ $mk->t2 }}" type="number"></td>
                        <td><input title="Final Exam" min="0" max="80" class="text-center" name="exm_{{ $mk->id }}"
                                value="{{ $mk->exm }}" type="number"></td>
                        <td><input title="scale" id="scale1" class="text-center" type="number" name="s1_{{ $mk->id }}"
                                value="{{ $mk->s1 }}" readonly></td>
                        <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                                id="total" readonly></td>
                        <td>
                        @foreach($grades as $grade)
                        @if($grade->id == $mk->grade_id)
                        {{ $grade->grade }} ({{ $grade->name }})
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
                        <td><input title="Final Exam" min="0" max="100" class="text-center" name="exm_{{ $mk->id }}"
                                value="{{ $mk->exm }}" type="number" ></td>
                        <td><input title="scale" id="scale1" class="text-center" type="number" name="s1_{{ $mk->id }}"
                                value="{{ $mk->s1 }}" ></td>
                        <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                                id="total" readonly></td>
                        <td>
                        @foreach($grades as $grade)
                        @if($grade->id == $mk->grade_id)
                        {{ $grade->grade }} ({{ $grade->name }})
                        @else
                        {{ '' }}
                        @endif
                        @endforeach
                        </td>
                @else
                <td><input title="CA" min="0" max="20" class="text-center" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}"
                        type="number"></td>
                @if(Qs::userIsTeacher())
                <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                        value="{{ $mk->t2 }}" type="number"></td>
                <td><input title="Final Exam" min="0" max="50" class="text-center" name="exm_{{ $mk->id }}"
                        value="{{ $mk->exm }}" type="number"></td>
                <td><input title="scale" id="scale1" class="text-center" type="number" name="s1_{{ $mk->id }}"
                        value="{{ $mk->s1 }}" readonly></td>
                <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                        id="total" readonly></td>
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->grade }} ({{ $grade->name }})
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>

                @else
                <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                        value="{{ $mk->t2 }}" type="number"></td>
                <td><input title="Final Exam" min="0" max="80" class="text-center" name="exm_{{ $mk->id }}"
                        value="{{ $mk->exm }}" type="number"></td>
                <td><input title="scale" id="scale1" class="text-center" type="number" name="s1_{{ $mk->id }}"
                        value="{{ $mk->s1 }}" readonly></td>
                <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                        id="total" readonly></td>
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->grade }} ({{ $grade->name }})
                    @else
                    {{ '' }}
                    @endif
                    @endforeach
                </td>
                @endif
                @endif
                @break

                @default
                <td><input title="CA" min="0" max="20" class="text-center" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}"
                        type="number"></td>
                @if(Qs::userIsTeacher())
                <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                        value="{{ $mk->t2 }}" type="number" ></td>
                <td><input title="Final Exam" min="0" max="80" class="text-center" name="exm_{{ $mk->id }}"
                        value="{{ $mk->exm }}" type="number" ></td>
                        <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                        id="total" readonly></td>
                 <td>
                        @foreach($grades as $grade)
                        @if($grade->id == $mk->grade_id)
                        {{ $grade->grade }} ({{ $grade->name }})
                        @else
                        {{ '' }}
                        @endif
                        @endforeach
                        </td>
                @else
                <td><input title="mid-Term" min="0" max="30" class="text-center" name="t2_{{ $mk->id }}"
                        value="{{ $mk->t2 }}" type="number"></td>
                <td><input title="Final Exam" min="0" max="80" class="text-center" name="exm_{{ $mk->id }}"
                        value="{{ $mk->exm }}" type="number"></td>
                <td><input title="scale" id="scale1" class="text-center" type="number" name="s1_{{ $mk->id }}"
                        value="{{ $mk->s1 }}" readonly></td>
                <td><input title="total" class="text-center" type="number" value="{{ $mk->tex1 ?? $mk->tex2 ?? '' }}"
                        id="total" readonly></td>
                <td>
                    @foreach($grades as $grade)
                    @if($grade->id == $mk->grade_id)
                    {{ $grade->grade }} ({{ $grade->name }})
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
    <div class="text-center mt-2">
    @if(Qs::getSetting('Close_Grade_Entry')== 1)
     <button type="submit" class="btn btn-primary">Update Marks<i class="icon-paperplane ml-2"></i></button>
     @else
        {{-- <button type="button" class="btn btn-success" onclick="exportexcell">Export to Excel</button> --}}
        <button type="submit" class="btn btn-primary">Update Marks<i class="icon-paperplane ml-2"></i></button>
    @endif
    </div>
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
