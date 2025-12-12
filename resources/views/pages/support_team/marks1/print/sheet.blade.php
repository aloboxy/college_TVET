{{--<!--NAME , CLASS AND OTHER INFO -->--}}
<table style="width:100%; border-collapse:collapse; ">
    <tbody>
    <tr>
        <td><strong>NAME:</strong> {{ strtoupper($sr->user->name) }}</td>
        <td><strong>ADM NO:</strong> {{ $sr->adm_no }}</td>
        <!-- <td><strong>HOUSE:</strong> {{ strtoupper($sr->house) }}</td> -->
        <td><strong>CLASS:</strong> {{ strtoupper($my_class->name) }}</td>
    </tr>
    <tr>
        <td><strong>GRADE SHEET FOR</strong> {!! strtoupper(Mk::getSuffix($ex->term)) !!} Semester </td>
        <td><strong>ACADEMIC YEAR:</strong> {{ $ex->year }}</td>
        <td><strong>AGE:</strong> {{ $sr->age ?: ($sr->user->dob ? date_diff(date_create($sr->user->dob), date_create('now'))->y : '-') }}</td>
    </tr>

    </tbody>
</table>


{{--Exam Table--}}
<table style="width:100%; border-collapse:collapse; border: 1px solid #000; margin: 10px auto;" border="1">
    <thead>
    <tr>
        <th rowspan="2">SUBJECTS</th>
<!--         <th colspan="3">CONTINUOUS ASSESSMENT</th>
        <th rowspan="2">EXAM<br>(60)</th>
        <th rowspan="2">FINAL MARKS <br> (100%)</th> -->
        <th rowspan="2">GRADE</th>
        <!-- <th rowspan="2">SUBJECT <br> POSITION</th> -->


      {{--  @if($ex->term == 2) --}}{{-- 3rd Term --}}{{--
        <th rowspan="2">FINAL MARKS <br>(100%) 3<sup>RD</sup> TERM</th>
        <th rowspan="2">1<sup>ST</sup> <br> SEMESTER</th>
        <th rowspan="2">2<sup>ND</sup> <br> SEMESTER</th>
        <th rowspan="2">CUM (200%) <br> 1<sup>ST</sup> + 2<sup>ND</sup></th>
        <th rowspan="2">CUM AVE</th>
        <th rowspan="2">GRADE</th>
        @endif--}}

        <th rowspan="2">REMARKS</th>
    </tr>
    <tr>
<!--         <th>CA(20)</th>
        <th>Mid-Term(20)</th>
        <th>Final(40)</th> -->
    </tr>
    </thead>
    <tbody>
    @foreach($subjects as $sub)
        <tr>
            <td width="5000px" style="font-weight: bold">{{ $sub->name }} {{$sub->slug}}</td>
            @foreach($marks->where('subject_id', $sub->id)->where('exam_id', $ex->id) as $mk)
  <!--               <td>{{ $mk->t1 ?: '-' }}</td>
                <td>{{ $mk->t2 ?: '-' }}</td>
                <td>{{ $mk->tca ?: '-' }}</td>
                <td>{{ $mk->exm ?: '-' }}</td>

                <td>{{ $mk->$tex ?: '-'}}</td> -->
                <td width="2000px" >{{ $mk->grade ? $mk->grade->name : '-' }}</td>
                <!-- <td>{!! ($mk->grade) ? Mk::getSuffix($mk->sub_pos) : '-' !!}</td> -->
                <td width="200px">{{ $mk->grade ? $mk->grade->remark : '-' }}</td>

                {{--@if($ex->term == 2)
                    <td>{{ $mk->tex3 ?: '-' }}</td>
                    <td>{{ Mk::getSubTotalTerm($student_id, $sub->id, 1, $mk->my_class_id, $year) }}</td>
                    <td>{{ Mk::getSubTotalTerm($student_id, $sub->id, 2, $mk->my_class_id, $year) }}</td>
                    <td>{{ $mk->cum ?: '-' }}</td>
                    <td>{{ $mk->cum_ave ?: '-' }}</td>
                    <td>{{ $mk->grade ? $mk->grade->name : '-' }}</td>
                    <td>{{ $mk->grade ? $mk->grade->remark : '-' }}</td>
                @endif--}}

            @endforeach
        </tr>
    @endforeach
    <tr>
        <td colspan="10"><strong>CUMULATIVE GPA: </strong> {{ $exr->ave/25 }}</td>
        <!-- <td colspan="10"><strong>FINAL AVERAGE: </strong> {{ $exr->ave }}</td> -->
        <!-- <td colspan="10"><strong>CUMULATIVE AVERAGE: </strong> {{ $exr->class_ave }} -->
    </td>
    </tr>
    </tbody>
</table>
