@extends('layouts.master')
@section('page_title', 'Tabulation GradeSheet')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Tabulation GradeSheet</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
        <form method="post" action="{{ route('marks.tabulation_select') }}">
                    @csrf
                    <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                                            <select required id="exam_id" name="exam_id" class="form-control select" data-placeholder="Select Exam">
                                                @foreach($exams as $exm)
                                                    <option {{ ($selected && $exam_id == $exm->id) ? 'selected' : '' }} value="{{ $exm->id }}">{{ $exm->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                            <select onchange="getClassSections(this.value)" required id="my_class_id" name="my_class_id" class="form-control select" data-placeholder="Select Class">
                                                <option value=""></option>
                                                @foreach($my_classes as $c)
                                                    <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="section_id" class="col-form-label font-weight-bold">Cohort:</label>
                                <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                    @if($selected)
                                        @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                            <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type" class="col-form-label font-weight-bold">Status:</label>
                                <select required id="type" name="type" data-placeholder="Select Cohort First" class="form-control select">
                                 <option value="">Please select status</option>
                                 <option value="0">Pass</option>
                                 <option value="1">Failed</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2 mt-4">
                            <div class="text-right mt-1">
                                <button type="submit" class="btn btn-primary">View Sheet <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>

                    </div>

                </form>
        </div>
    </div>
    <script>
        var table = $('#try').DataTable();

     var buttons = new $.fn.dataTable.Buttons(table, {
         buttons: [
           'copyHtml5',
           'excelHtml5',
           'csvHtml5',
           'pdfHtml5'
        ]
    }).container().appendTo($('#buttons'));
        </script>

    {{--if Selction Has Been Made --}}

    @if($selected)
        <div class="card">
            <div class="card-header">
                {{-- <h6 class="card-title font-weight-bold">Tabulation Sheet for {{ $my_class->name.'-Cohort-'.$section->name.' - '.$ex->name.' ('.$year.')' }}</h6> --}}
            </div>
            <div class="card-body">


      <div id="buttons">
                <table id="try"  class="table table-striped table-bordered" cellspacing="0" width="100%">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ADMISSION_NUMBER_OF_STUDENTS</th>
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($students as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="text-align: center">{{ $s->user->name }} - {{ $s->adm_no }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
                {{--Print Button--}}
                <div class="text-center mt-4">
                    <a target="_blank" href="{{  route('marks.print_tabulation', [$exam_id, $my_class_id, $section_id,$type]) }}" class="btn btn-danger btn-lg"><i class="icon-printer mr-2"></i> Print Tabulation Sheet</a>
                </div>
            </div>
        </div>
    @endif

@endsection
