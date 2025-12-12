<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Sheet</title>
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/my_print.css') }}" />
<style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .center {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background: #f0f0f0;
        }
        th, td {
            padding: 5px;
            text-align: center;
        }
        .summary p {
            font-size: 16px;
        }
    </style>
</head>
    <body>
        <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
           <table width="100%" style="border: none;">
            <tr>
                <td style="img-align: left;border:none; "><img src="{{ $s['logo'] }}" style="max-height : 100px;opacity: 5; margin-left: auto;margin-right: auto; left: 0; right: 0;"></td>
                <td style="text-align: center; border:none;">
                    <strong><span style="color: #1b0c80; font-size: 25px;margin: 10px 10px 0px 10px;">{{ strtoupper(Qs::getSetting('system_name')) }}</span></strong><br/>
                   {{-- <strong><span style="color: #1b0c80; font-size: 20px;">Paynesville, Liberia</span></strong><br/>--}}
                    <strong><span
                                style="color: #000; font-size: 15px;margin: 10px 10px 0px 10px;"><i>{{ ucwords($s['address']) }}</i></span></strong><br/>
                    {{-- @if($studentclass->my_class_id == NULL)
                    <strong><span style="color: #000; font-size: 15px;margin: 10px 10px 0px 10px;"> GRADE SHEET {{ '('.strtoupper($studclas->major).')' }}
                    @else
                    <strong><span style="color: #000; font-size: 15px;margin: 10px 10px 0px 10px;"> GRADE SHEET {{ '('.strtoupper($class_type->name).')' }}
                    </span></strong>
                    @endif --}}
                </td>

            </tr>
        </table>

        <div style="position: relative;  text-align: center; ">
            <img src="{{ $s['logo'] }}"
                 style="max-width: 600px; max-height:600px; margin-top: 60px; position:absolute ; opacity: 0.2; margin-left: auto;margin-right: auto; left: 0; right: 0;" />
        </div>


        <div>
            <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>NAME:</strong> {{ strtoupper($sr->user->name) }}</p>
            <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>ADM NO:</strong> {{ $sr->adm_no }}</p>
        @if($studentclass->my_class_id == null)
            <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>DEPARTMENT:</strong> {{ strtoupper($studclas->real_department->name) }}</p>

            <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>MAJOR:</strong> {{ strtoupper($studclas->major) }}</p>

        @else
        <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>DEPARTMENT:</strong> {{ strtoupper($class_type->name) }}</strong></p>
                @if($class_type->code == 'CS' || $class_type->code == 'PCP' || $class_type->code == 'LS' || $class_type->code == 'NA')

                @endif
        @endif
        <p style="font-size: 20px; text-align: center;margin: 10px 10px 0px 10px;"><strong>{{ strtoupper('grade ledger') }}</strong> </p>
            {{-- <td><strong>AGE:</strong> {{ $sr->age ?: ($sr->user->dob ? date_diff(date_create($sr->user->dob), date_create('now'))->y : '-') }}</td> --}}
        </div>



    @foreach($exams as $ex)
        @foreach($exam_records->where('exam_id', $ex->id) as $exr)
            <h3 style="text-align: center;">{{ $ex->exam_name }} - {{ $ex->year }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>COURSE</th>
                        <th>COURSE DESCRIPTION</th>
                        <th>CREDIT/HOURS</th>
                        <th>GRADE</th>
                        <th>POINT</th>
                    </tr>
                </thead>
                @if($ex->published == 1)
                <tbody>
                    @foreach($marks->where('exam_id', $ex->id) as $mk)
                    @if(Qs::userIsStudent())
                        @if(Qs::student_fees($student_id, $ex->year, $ex->id)['status'] == 1 || Qs::student_fees($student_id, $ex->year, $ex->id)['status'] == -1)
                        <tr>
                            <th  style="color: red; text-align:center">
                               {{ 'Please Visit the Business Office'}}
                            </th>
                        </tr>
            
                        @else
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mk->course->subject->name }}</td>
                            <td>{{ $mk->course->subject->slug }}</td>
                            <td>{{ $mk->course->subject->credit }}</td>
                            <td>{{ $mk->grade->name ?? '-' }}</td>
                            <td>{{ number_format($mk->grade_get ?? 0, 1) }}</td>
                        </tr>
                        @endif
                    @else
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mk->course->subject->name }}</td>
                            <td>{{ $mk->course->subject->slug }}</td>
                            <td>{{ $mk->course->subject->credit }}</td>
                            <td>{{ $mk->grade->name ?? '-' }}</td>
                            <td>{{ number_format($mk->grade_get ?? 0, 1) }}</td>
                        </tr>
                    @endif
                    @endforeach
                </tbody>
                @else
                <tbody>
                    <tr>
                        <th  style="color: red; text-align:center" colspan="6">
                           {{ 'Grades Pending Approval'}}
                        </th>
                    </tr>
                </tbody>
                @endif
            </table>

        <table>
            <thead>
                <tr>
                    <th style="border:none;"></th>
                    <th style="border:none;"></th>
                @if($ex->published == 1)
                    <th colspan="4" style="font-size: 20px; border:none;">
                        <strong>TOTAL CREDITS:</strong>
                        {{ number_format($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit) ?? 0) }}
                    </th>
                </tr>
                <tr>
                    <th colspan="5"></th>
                    <th style=" font-size: 20px"><strong>TOTAL POINTS: </strong> {{ number_format($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get) ?? 0, 2)}}</th>
                </tr>
                <tr>
                    <th colspan="6" style="color: rgb(19, 19, 21); font-size: 20px"><strong>SEMESTER GRADE POINT AVERAGE:</strong> {{number_format($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->grade_get) / ($marks->where('exam_id', $ex->id)->sum(fn($m) => $m->course->subject->credit)),3 ) ?? 0}}</th>
                </tr>
                @else
                <tr>
                    <th  style="color: red; text-align:center" colspan="6">
                        {{ 'Grades Pending Approval'}}
                    </th>
                </tr>
                @endif
            </thead>

        </table>
        @endforeach
    @endforeach

   <table>
    @php
        $total_point = \App\Models\Mark::join('exams', 'exams.id', '=', 'marks.exam_id')
            ->where('marks.student_id', $student_id)
            ->where('exams.published', 1)
            ->selectRaw('SUM(marks.grade_get) as total_point')
            ->first();

        $total_point_value = $total_point->total_point ?? 0;
        $cumulative_gpa = $credit_sum > 0 ? ($total_point_value / $credit_sum) : 0;
    @endphp
    <thead>
        <tr>
            <th style="font-size: 20px">
                <strong>TOTAL CREDITS DONE:</strong> {{ number_format($credit_sum ?? 0) }}
            </th>
        </tr>
        <tr>
            <th style="font-size: 20px">
                <strong>TOTAL POINTS:</strong> {{ number_format($total_point_value, 2) }}
            </th>
        </tr>
        <tr>
            <th style="font-size: 20px">
                <strong>CUMULATIVE GPA:</strong> {{ number_format($cumulative_gpa, 4) }}
            </th>
        </tr>
    </thead>
</table>


            <footer>
                <p><strong>AUTHORIZED SIGNATURE:</strong> ____________________________</p> <br>
                <p class="powered">• Powered By SouMed Tech</p>
            </footer>
        </div>
           <script>
    window.print();
</script>
    </body>

    </html>
