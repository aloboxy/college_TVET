<table class="table table-bordered table-responsive text-center">
    <thead>
    <tr>
        <th rowspan="2">S/N</th>
        <th rowspan="2">COURSES</th>
        <th rowspan="2">COURSE DESCRIPTION</th>
    <!--     <th rowspan="2">CA<br>(20)</th>
        <th rowspan="2">Mid-Term<br>(20)</th>
        <th rowspan="2">Final<br>(60)</th>
        <th rowspan="2">TOTAL<br>(100)</th> -->

        {{--@if($ex->term == 2) --}}{{-- 3rd Term --}}{{--
        <!-- <th rowspan="2">TOTAL <br>(100%) 3<sup>RD</sup> Semester</th> -->
      <!--   <th rowspan="2">1<sup>ST</sup> <br> TERM</th>
        <th rowspan="2">2<sup>ND</sup> <br> TERM</th>
        <th rowspan="2">CUM (300%) <br> 1<sup>ST</sup> + 2<sup>ND</sup></th> -->
        <th rowspan="2">CUM AVE</th>
        @endif--}}
        <th rowspan="2">GRADE</th>
        <th rowspan="2">Credit/Hour</th>
        <!-- <th rowspan="2">SUBJECT <br> POSITION</th> -->
        <th rowspan="2">POINT</th>
        @if(Qs::userIsTeamSA())
        <th rowspan="2">Delete</th>
        @endif
    </tr>
    </thead>

    <tbody>
        <tr>
        @foreach($marks->where('exam_id', $ex->id) as $mk)
            <td width="1000px">{{ $loop->iteration }}</td>
            <td width="1500px">{{ $mk->course->subject->name ?? 'Please Contact Admin'}}</td>
            <td width="1500px">{{ $mk->course->subject->slug ?? 'Please Contact Admin'}}</td>
            <td width="1500px">{{ ($mk->grade) ? $mk->grade->name : '-' }}</td>
            <td width="1500px">{{ $mk->course->subject->credit ?? 'Please Contact Admin'}}</td>
            <td width="1500px">{{ number_format($mk->grade_get,1) ?: '0' }}</td>
            @if(Qs::userIsTeamSA())
            <td>
            <a id="{{$mk->id}}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
            <form method="post" id="item-delete-{{  $mk->id}}" action="{{ route('marks.destroy', $mk->id) }}" class="hidden">@csrf @method('delete')</form>
            </td>
                @endif
        </tr>
        @endforeach
        <tr>

            <td  colspan="4"></td>
            <td style="background-color: rgb(25, 128, 231); font-size: 25px"><strong>TOTAL CREDITS: </strong> {{  number_format($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit) ?? 0) }}</td>
        </tr>
        <tr>
            @if(!isset($remark) )
              <td colspan="4"></td>

            <td  style="background-color: rgb(25, 128, 231); font-size: 25px"><strong>TOTAL POINT: </strong> - </td>
            @else

            <td  colspan="5"></td>
            <td style="background-color: rgb(25, 128, 231); font-size: 25px"><strong>TOTAL POINT: </strong> {{  number_format($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get) ?? 0, 2) }}</td>
            @endif
        </tr>
        <tr>
            @if(isset($remark)== null)
            <td colspan="10" style="color: rgb(19, 19, 21); font-size: 25px"><strong>SEMESTER GRADE POINT AVERAGE: </strong> {{ "PROBATION" }} </td>
            @else
            <td colspan="10" style="color: rgb(19, 19, 21); font-size: 25px"><strong>SEMESTER GRADE POINT AVERAGE: </strong> {{ number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)) ?? 0,3)}}</td>
            @endif
        </tr>
    <tr>
       
        <!-- <td colspan="10"><strong>CUMULATIVE AVERAGE: </strong> {{ $exr->class_ave }} -->

@if(Qs::department_code($student_id) == 'LS' || Qs::department_code($student_id) == 'NA')
 <td colspan="10" style="background-color: rgb(103, 127, 247)"><strong>AVERAGE PERFORMANCE: </strong>
        @if($remark =='Resit')
                <strong style="background-color: rgb(228, 17, 17); font-size: 25px;">
                {{ 'Resit' }}
               </strong>
            @else
            <strong>
                @if(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)) ?? 0,3) >= 3)
                {{"Excellent"}}
                @elseif(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)) ?? 0,3)  > 2.0)
                {{ "Good" }}
                @elseif(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)) ?? 0,3)  == 2.0)
                {{ "Pass" }}
                @else
                <strong style="background-color: rgb(228, 17, 17); font-size: 25px;">
                 {{ "Redo" }}
               </strong>
                @endif
            @endif
            </strong>
    </td>
@else
    <td colspan="10" style="background-color: rgb(103, 127, 247)"><strong>AVERAGE PERFORMANCE: </strong>
            <strong>
                @if(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)) ?? 0,3) >= 3)
                <strong style="font-size: 25px;">
                {{"Honor Roll"}}
                </strong>
                @elseif(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)) ?? 0,3)  > 2.0)
                <strong style="font-size: 25px;">
                {{ "Good" }}
                </strong>
                @elseif(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)) ?? 0,3)  == 2.0)
                <strong style="font-size: 25px;">
                {{ "Pass" }}
                </strong>
                @else
                <strong style="background-color: rgb(228, 17, 17); font-size: 25px;">
                 {{ "Probation" }}
               </strong>
                @endif
            </strong>
    </td>
    
@endif
    </tr>
    </tbody>
</table>


        
