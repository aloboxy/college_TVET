@extends('layouts.master')
@php
    $title = 'Student Fees Report | Semester: ' . $semester . ' | Year: ' . $year;

    if (is_null($class_id)) {
        $title .= ' | Department: ' . ($dept ?? 'N/A');
    } else {
        $title .= ' | Class: ' . ($class_name ?? 'N/A') . ' | Cohort: ' . ($section_name ?? 'N/A');
    }
@endphp

@section('page_title', $title)
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Students List ({{ ucfirst($filterStatus ?? 'all') }})</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        {{-- Filter Form --}}
        <form method="post" action="{{ route('accounting.student_fees') }}">
            @csrf
            @include('pages.support_team.marks.selector_accounting')
        </form>

        {{-- Students Table --}}
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="all-students">
              <caption>
                @if($class_id == NULL)
                    {{ $dept }}
                @else
                    {{ 'Class: '. $class_name.  'Cohort:'. $section_name }}
                @endif
                </caption>
                <table class="table datatable-button-html5-columns table-bordered table-striped">
              
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Expected (USD)</th>
                            <th>Expected (LRD)</th>
                            <th>Paid (USD)</th>
                            <th>Paid (LRD)</th>
                            <th>Balance (USD)</th>
                            <th>Balance (LRD)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s['name'] }}</td>
                                <td>${{ number_format($s['expected_usd'], 2) }}</td>
                                <td>LD{{ number_format($s['expected_lrd'], 2) }}</td>
                                <td>${{ number_format($s['paid_usd'], 2) }}</td>
                                <td>LD{{ number_format($s['paid_lrd'], 2) }}</td>
                                <td>${{ number_format($s['balance_usd'], 2) }}</td>
                                <td>LD{{ number_format($s['balance_lrd'], 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $s['status'] === 'clear' ? 'success' : 'danger' }}">
                                        {{ ucfirst($s['status']) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">No data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
