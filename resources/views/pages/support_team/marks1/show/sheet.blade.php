<table class="table table-bordered table-responsive text-center">
    <thead>
    <tr>
        <th rowspan="2">S/N</th>
        <th rowspan="2">SUBJECTS</th>
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
        <!-- <th rowspan="2">SUBJECT <br> POSITION</th> -->
        @if(Qs::userIsTeamSA())
        <th rowspan="2">REMARKS</th>
        @endif
    </tr>
    </thead>

    <tbody>
    @foreach($subjects as $sub)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $sub->name }}  {{$sub->slug}}</td>
            @foreach($marks->where('subject_id', $sub->id)->where('exam_id', $ex->id) as $mk)
              <!--   <td>{{ ($mk->t1) ?: '-' }}</td>
                <td>{{ ($mk->t2) ?: '-' }}</td>
                <td>{{ ($mk->exm) ?: '-' }}</td> -->
                <!-- <td> -->
                 <!--    @if($ex->term === 1) {{ ($mk->tex1) }}
                    @elseif ($ex->term === 2) {{ ($mk->tex2) }}
                    @else {{ '-' }}
                    @endif -->
                <!-- </td> -->

                {{--3rd Term--}}
                {{-- @if($ex->term == 2)
                    <!--  <td>{{ $mk->tex3 ?: '-' }}</td>
                     <td>{{ Mk::getSubTotalTerm($student_id, $sub->id, 1, $mk->my_class_id, $year) }}</td> -->
                     <!-- <td>{{ Mk::getSubTotalTerm($student_id, $sub->id, 2, $mk->my_class_id, $year) }}</td> -->
                     <!-- <td>{{ $mk->cum ?: '-' }}</td> -->
                     <!-- <td>{{ $mk->cum_ave ?: '-' }}</td> -->
                 @endif--}}

                {{--Grade, Subject Position & Remarks--}}
                <td>{{ ($mk->grade) ? $mk->grade->name : '-' }}</td>
                <!-- <td>{!! ($mk->grade) ? Mk::getSuffix($mk->sub_pos) : '-' !!}</td> -->
                @if(Qs::userIsTeamSA())
                <td>{{ ($mk->grade) ? $mk->grade->remark : '-' }}</td>
                @endif
            @endforeach
        </tr>
    @endforeach
    <tr>
        <!-- <td colspan="4"><strong>TOTAL SCORES OBTAINED: </strong> {{ $ex->total }}</td> -->
        <td colspan="3"><strong>CUMULATIVE GPA: </strong> {{ $ex->ave/25 }}</td>
        <!-- <td colspan="2"><strong>CLASS AVERAGE: </strong> {{ $ex->class_ave }}</td> -->
    </tr>
    </tbody>
</table>
