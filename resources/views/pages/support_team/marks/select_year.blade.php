@extends('layouts.master')
@section('page_title', 'Select Exam Year')
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-alarm mr-2"></i> Select Exam Year</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form method="post" action="{{ route('marks.year_select', $student_id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="year" class="font-weight-bold col-form-label-lg">Select Exam Year:</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($years as $y)
                                        <tr>
                                            <td>{{ $y->year }}</td>
                                            <td>
                                                <button type="submit" name="year" value="{{ $y->year }}" class="btn btn-primary btn-sm">View</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
