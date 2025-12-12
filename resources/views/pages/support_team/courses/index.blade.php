@extends('layouts.master')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Courses List</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="col-md-12">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Click Me to See Courses By Department</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach($departments  as $c)
                            <a href="#c{{ $c->id }}" class="dropdown-item" data-toggle="tab">{{ $c->name }}</a>
                        @endforeach
                    </div>
                </li>
            </ul>
            <div class="tab-content">
                @foreach($departments as $c)
                <div class="tab-pane fade" id="c{{ $c->id }}">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Teacher</th>
                            <th>Semester</th>
                            <th>Session</th>
                            <th>Room</th>
                            <th>Day</th>
                            <th>Cohort</th>
                            <th>Time From</th>
                            <th>Time To</th>
                            <th>Capacity</th>
                            <th>Academic Year</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $s)
                                @if(in_array($c->id, explode(',', $s->department_id)))
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $s->subject->name ?? '' }}</td>
                                    <td>{{ $s->teacher->name ?? '' }}</td>
                                    <td>@if($s->term_id == 1)
                                        {{ 'First Semester' }}
                                        @else
                                        {{ 'Second Semester' }}
                                    @endif
                                    </td>
                                    <td>{{ $s->session ?? 'TBA' }}</td>
                                    <td>{{ $s->room ?? 'TBA' }}</td>
                                    <td>{{ $s->day ?? 'Unknown' }}</td>
                                    <td>{{ $s->section->name ?? '' }}</td>
                                    <td>{{ Carbon\Carbon::parse($s->time_from)->format('g:i A') ?? 'unknown' }}</td>
                                    <td>{{ Carbon\Carbon::parse($s->time_to)->format('g:i A') ?? 'unknown' }}</td>
                                    <td>{{ $s->capacity }}/{{ $s->total }}</td>
                                    <td>{{ $s->year}}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    {{-- Edit --}}
                                                    @if(Qs::userIsTeamSA())
                                                        <a href="{{ route('courses.edit', $s->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    {{-- Delete --}}
                                                    @if(Qs::userIsSuperAdmin())
                                                        <a id="{{ $s->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                        <form method="post" id="item-delete-{{ $s->id }}" action="{{ route('courses.destroy', $s->id) }}" class="hidden">@csrf @method('delete')</form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
