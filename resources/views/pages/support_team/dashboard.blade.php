@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @if(Qs::userIsTeamSA())
       <div class="row">
        <!-- Stats Cards (Existing) -->
        @foreach ($classtype as $class)
        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card card-body p-4 shadow-sm border-0 h-100">
                <div class="media align-items-center">
                    <div class="mr-4">
                        <div class="bg-indigo-100 text-indigo-600 rounded-circle p-3 d-inline-block">
                            <i class="icon-users4 icon-2x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h3 class="font-weight-bold mb-2 text-dark">
                         {{ $student->filter(function ($item) use ($class) {
                                return optional($item->department)->id == $class->id || $item->department_id == $class->id;
                            })->count() }}
                        </h3>
                        <span class="text-muted font-size-sm">{{ $class->name }} Students</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Quick Summary Stats -->
         <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card card-body p-4 shadow-sm border-0 h-100">
                <div class="media align-items-center">
                    <div class="mr-4">
                        <div class="bg-danger-100 text-danger-600 rounded-circle p-3 d-inline-block">
                            <i class="icon-users4 icon-2x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h3 class="font-weight-bold mb-2 text-dark">{{ $users->where('user_type', 'student')->count() }}</h3>
                        <span class="text-muted font-size-sm">Total Students</span>
                    </div>
                </div>
            </div>
        </div>
       </div>

       <!-- Middle Section: Chart and Quick Links -->
       <div class="row mt-4">
           <!-- GPA/Student Trend Chart -->
           <div class="col-lg-8">
               <div class="card shadow-sm border-0">
                   <div class="card-header bg-transparent header-elements-inline">
                       <h6 class="card-title font-weight-bold text-dark">Student Enrollment Trends</h6>
                       <div class="header-elements">
                           <span class="badge badge-light text-muted">2024-2025</span>
                       </div>
                   </div>
                   <div class="card-body">
                       <div class="chart-container">
                           <canvas id="enrollmentChart"></canvas>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Quick Links -->
           <div class="col-lg-4">
               <div class="card shadow-sm border-0 h-100">
                   <div class="card-header bg-transparent">
                       <h6 class="card-title font-weight-bold text-dark">Quick Actions</h6>
                   </div>
                   <div class="card-body">
                       <div class="row text-center">
                           <div class="col-6 mb-3">
                               <a href="{{ route('students.create') }}" class="btn btn-outline-primary btn-block p-3">
                                   <i class="icon-user-plus icon-2x mb-2"></i><br>
                                   Admit
                               </a>
                           </div>
                           <div class="col-6 mb-3">
                               <a href="{{ route('payments.create') }}" class="btn btn-outline-success btn-block p-3">
                                   <i class="icon-coins icon-2x mb-2"></i><br>
                                   Pay
                               </a>
                           </div>
                            <div class="col-6 mb-3">
                               <a href="{{ route('marks.index') }}" class="btn btn-outline-warning btn-block p-3">
                                   <i class="icon-pencil5 icon-2x mb-2"></i><br>
                                   Grades
                               </a>
                           </div>
                           <div class="col-6 mb-3">
                               <a href="{{ route('library.index') }}" class="btn btn-outline-info btn-block p-3">
                                   <i class="icon-books icon-2x mb-2"></i><br>
                                   Library
                               </a>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
    @endif

    {{--Events Calendar--}}
    <div class="card mt-4">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">School Events Calendar</h5>
         {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body" style="width: 100%;">
            @include('fullcalender')
        </div>
    </div>

    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('enrollmentChart');
            if(ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'New Students',
                            data: [12, 19, 3, 5, 2, 3, 20, 45, 10, 15, 8, 12],
                            borderColor: '#4F46E5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    color: '#f3f4f6'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endsection
