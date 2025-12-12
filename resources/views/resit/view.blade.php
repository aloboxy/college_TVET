@extends('layouts.master')
@section('page_title','(Resit)'.' '.$subject.'  '.$exam. ' '.$year )    
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Resit List</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
        <a href="{{ route('resit.selector') }}" class="btn btn-primary">Do New selection</a>
        </div>
        <div class="card">

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-students">
                    <table class="table datatable-button-html5-columns table-striped" >
                        <thead>
                            <tr>
                                <th style="text-center">Resit List</th>
                            </tr>
                            <tr>
                                <th>Year: {{ $year }}</th>
                                <th>Semest: {{ $exam }}</th>
                                <th>Course: {{ $subject }} {{ 'For session' }}@foreach ($session as $s)
                                    {{ $s }}{{ ',' }}
                                @endforeach</th>
                            </tr>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>ADM_No</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ strtoupper($s->user->name) }}</td>
                                <td>{{ $s->student->adm_no ?? '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        </table>
                </div>
    @endsection
