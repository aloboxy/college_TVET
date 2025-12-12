<html>
<head>
    <title>Student Gradesheet - {{ $sr->user->name }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/my_print.css') }}" />
</head>
<body>
<div class="container">
    <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
        {{--    Logo N School Details--}}
        <table width="100%">
                <td style="img-align: left; "><img src="{{ $s['logo'] }}" style="max-height : 100px;opacity: 5; margin-left: auto;margin-right: auto; left: 0; right: 0;"></td>
                <td style="text-align: center; ">
                    <strong><span style="color: #1b0c80; font-size: 25px;margin: 10px 10px 0px 10px;">{{ strtoupper(Qs::getSetting('system_name')) }}</span></strong><br/>
                   {{-- <strong><span style="color: #1b0c80; font-size: 20px;">Paynesville, Liberia</span></strong><br/>--}}
                    <strong><span
                                style="color: #000; font-size: 15px;margin: 10px 10px 0px 10px;"><i>{{ ucwords($s['address']) }}</i></span></strong><br/>
                    @if($studentclass->my_class_id == NULL)
                    <strong><span style="color: #000; font-size: 15px;margin: 10px 10px 0px 10px;"> GRADE SHEET {{ '('.strtoupper($studclas->major).')' }}
                    @else
                    <strong><span style="color: #000; font-size: 15px;margin: 10px 10px 0px 10px;"> GRADE SHEET {{ '('.strtoupper($class_type->name).')' }}
                    </span></strong>
                    @endif
                </td>
                {{-- <td style="width: 100px; height: 100px; float: left;">
                    <img src="{{ $sr->user->photo }}"
                         alt="..."  width="100" height="100">
                </td> --}}
            </tr>
        </table>
        <br/>

        {{--Background Logo--}}
        <div style="position: relative;  text-align: center; ">
            <img src="{{ $s['logo'] }}"
                 style="max-width: 300px; max-height:600px; margin-top: 60px; position:absolute ; opacity: 0.2; margin-left: auto;margin-right: auto; left: 0; right: 0;" />
        </div>

        {{--<!-- SHEET BEGINS HERE-->--}}
@include('pages.support_team.marks.print.sheet')

        {{--Key to Grading--}}
        {{--@include('pages.support_team.marks.print.grading')--}}

        <div style="margin-top: 25px; clear: both;"></div>

        {{--    COMMENTS & SIGNATURE    --}}
        <br><br>
        {{-- @include('pages.support_team.marks.print.comments') --}}

    </div>

</div>


<tr>
    <td style="font-size: 20px"><strong>AUTHORIZED SIGNATURE:</strong></td>
    <td style="font-size: 20px">  {{ $exr->t_comment ?: str_repeat('__', 20) }}</td>
</tr>
<ul class="navbar-nav ml-lg-auto">
    <li class="nav-item"></i>Powered By SouMed Tech</li>
    {{-- <li class="nav-item"></i> Contact Us @ 0888776232/0770732334</li> --}}
</ul>
<script>
    window.print();
</script>
</body>

</html>
