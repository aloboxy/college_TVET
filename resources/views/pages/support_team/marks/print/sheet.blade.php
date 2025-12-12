{{--<!--NAME , CLASS AND OTHER INFO -->--}}
<div>
        <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>NAME:</strong> {{ strtoupper($sr->user->name) }}</p>
        <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>ADM NO:</strong> {{ $sr->adm_no }}</p>
        @if($studentclass->my_class_id == null)
        <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>DEPARTMENT:</strong> {{ strtoupper($studclas->real_department->name) }}</p>

        <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>MAJOR:</strong> {{ strtoupper($studclas->major) }}</p>

    @else
    <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>DEPARTMENT:</strong> {{ strtoupper($class_type->name) }}</p>
    @if($class_type->code == 'CS' || $class_type->code == 'PCP' || $class_type->code == 'LS' || $class_type->code == 'NA')
    <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>LEVEL:</strong> {{ strtoupper($level) }}</p>
    @endif
        {{-- <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>COHORT:</strong> {{ strtoupper($my_class->section->name) }}</p> --}}
    @endif
        <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong >GRADE SHEET FOR:</strong> {!! strtoupper(Mk::getSuffix($ex->term)) !!} SEMESTER </p>
        <p style="font-size: 20px; text-align: center; margin: 10px 10px 0px 10px;"><strong >ACADEMIC YEAR:</strong> {{ $ex->year }}</p>
        {{-- <td><strong>AGE:</strong> {{ $sr->age ?: ($sr->user->dob ? date_diff(date_create($sr->user->dob), date_create('now'))->y : '-') }}</td> --}}
    </div>


{{--Exam Table--}}
<table style="width:100%; border-collapse:collapse; border: 1px solid #000; margin: 10px auto;" border="1">
    <thead>
    <tr>
        <th rowspan="2" style="font-size: 20px">S/N</th>
        <th rowspan="2" style="font-size: 20px">COURSE</th>
        <th rowspan="2"  style="font-size: 20px">COURSE DESCRIPTION</th>
        <th rowspan="2" style="font-size: 20px">CREDIT/HOURS</th>
        <th rowspan="2" style="font-size: 20px">GRADE</th>
        <th rowspan="2" style="font-size: 20px">POINT</th>

    </tr>
    <tr>
<!--         <th>CA(20)</th>
        <th>Mid-Term(20)</th>
        <th>Final(40)</th> -->
    </tr>
    </thead>
    <tbody>
        <tr>
    @foreach($marks->where('exam_id', $ex->id) as $mk)
            <td width="1500px" style="font-size: 22px">{{ $loop->iteration }}</td>
            <td width="5000px" style="font-weight: bold; font-size: 22px;">{{ $mk->course->subject->name }}</td>
            <td width="5000px" style="font-weight: bold; font-size: 15px;">{{ $mk->course->subject->slug }}</td>
            <td width="5000px" style="font-weight: bold; font-size: 15px;">{{ $mk->course->subject->credit }}</td>
            <td width="2000px" style="font-size: 22px">{{ $mk->grade ? $mk->grade->name : '-' }}</td>
            <td width="2000px" style="font-size: 22px" >{{ number_format($mk->grade_get,1) ?: '0' ?: '-'}}</td>
        </tr>
    @endforeach
    <tr>

        <td  colspan="3"></td>
        <td style="background-color: rgb(25, 128, 231); font-size: 25px"><strong>TOTAL CREDITS: </strong> {{ number_format($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit), 2) }}</td>
    </tr>
    <tr>
        <td  colspan="5"></td>
        <td  style="background-color: rgb(25, 128, 231); font-size: 25px"><strong>TOTAL POINTS: </strong> {{ number_format($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get), 2) }}</td>
    </tr>
    <tr>
        <td colspan="10" style="color: rgb(19, 19, 21); font-size: 25px"><strong>SEMESTER GRADE POINT AVERAGE:</strong> {{ number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3)}} </td>
    </tr>
    <br>
    <tr>
@if(Qs::department_code($student_id) == 'LS' || Qs::department_code($student_id) == 'NA')
 <td colspan="10" style="background-color: rgb(103, 127, 247)"><strong>AVERAGE PERFORMANCE: </strong>
        @if($remark =='Resit')
                <strong style="background-color: rgb(228, 17, 17); font-size: 25px;">
                {{ 'Resit' }}
               </strong>
            @else
            <strong>
                @if((number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3)) >= 3)
                {{"Excellent"}}
                @elseif((number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3))  > 2.0)
                {{ "Good" }}
                @elseif((number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3))  == 2.0)
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
                @if(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3) >= 3)
                <strong style="font-size: 25px;">
                {{"Honor Roll"}}
                </strong>
                @elseif(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3) >= 2.0)
                <strong style="font-size: 25px;">
                {{ "Good" }}
                </strong>
                @elseif(number_format(($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get)) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3) >= 2.0)
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

