@extends('layouts.master')
@section('page_title', 'Repeat Students')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Repeat Students </h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <a class="col-md-4" href="{{ route('repeat.index') }}"><button class="btn btn-primary">Select New Department</button></a>
<div id="statusMessage" style="display:none; color:seagreen !important; font-size:16px; text-align:center"></div>

        <div class="card-body">

          <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-grades">
                        <table class="table datatable-button-html5-columns">
                           
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Admission#</th>
                                <th>Department</th>
                                <th>Semester-Level</th>
                                <th>Failed Subjects</th>
                            </tr>
                            </thead>
                            <tbody>
                      @foreach($students as $gr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $gr->user->name ?? 'N/A' }}</td>
                                    <td>{{ $gr->adm_no ?? 'N/A' }}</td>
                                    <td>{{ $gr->real_department->name ?? 'N/A' }}</td>
                                    <td>{{ $gr->level ?? 'N/A' }}</td>
                                    <td>
                                        @if(!empty($gr->failed_subjects) && is_array($gr->failed_subjects))
                                            @foreach($gr->failed_subjects as $subject)
                                                <span class="badge badge-danger">{{ $subject }}</span>
                                            @endforeach
                                            <br>
                                            <small class="text-muted">
                                                Total: {{ $gr->failed_count ?? count($gr->failed_subjects) }}
                                            </small>
                                        @else
                                            <span class="text-success">No Failed Subject</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                

    {{--Class List Ends--}}

@endsection
