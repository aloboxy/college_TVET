@extends('layouts.master')
@section('page_title', 'Manage Courses')
@section('content')

<div class="card">
    <style>
        .toggle-container {
            margin: 20px 0;
        }

        .toggle-button {
            position: relative;
            display: inline-block;
            width: 100px;
            height: 34px;
        }

        .toggle-checkbox {
            display: none;
        }

        .toggle-label {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ccc;
            border-radius: 34px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .toggle-inner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            color: white;
            transition: opacity 0.3s;
        }

        .toggle-switch {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 30px;
            height: 30px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .toggle-checkbox:checked+.toggle-label {
            background-color: #4CAF50;
        }

        .toggle-checkbox:checked+.toggle-label .toggle-switch {
            transform: translateX(64px);
        }

        .toggle-checkbox:checked+.toggle-label .toggle-inner::before {
            content: 'Yes';
        }

        .toggle-checkbox:not(:checked)+.toggle-label .toggle-inner::before {
            content: 'No';
        }
    </style>
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Course</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#new-subject" class="nav-link active" data-toggle="tab">Add Course</a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Courses</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($my_classes as $c)
                    <a href="#c{{ $c->id }}" class="dropdown-item" data-toggle="tab">{{ $c->name }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show  active fade" id="new-subject">
                <div class="row">
                    <div class="col-md-6">
                        {{-- ajax-store --}}
                        <form class="ajax-store" method="post" action="{{ route('subjects.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-lg-3 col-form-label font-weight-semibold">Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="name" name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Name of subject">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="slug" class="col-lg-3 col-form-label font-weight-semibold">Description <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="slug" name="slug" value="{{ old('slug') }}" type="text"
                                        class="form-control" placeholder="Eg. B.Eng">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="term_id"
                                    class="col-lg-3 col-form-label font-weight-semibold">Semester</label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Semester" class="form-control select" name="term_id"
                                        id="term_id">
                                        <option {{ (old('term_id')=='Select Semester' ) ? 'selected' : '' }} value="">
                                        </option>
                                        <option {{ (old('term_id')==1) ? 'selected' : '' }} value="1">First Semester
                                        </option>
                                        <option {{ (old('term_id')==2) ? 'selected' : '' }} value="2">Second Semester
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="level"
                                    class="col-lg-3 col-form-label font-weight-semibold">Level</label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Level" class="form-control select" name="level"
                                        id="term_id">
                                        <option {{ (old('level')=='Select Level' ) ? 'selected' : '' }} value="">
                                        </option>
                                        <option {{ (old('level')== "Freshmen") ? 'selected' : '' }} value="Freshmen">Freshmen
                                        </option>
                                        <option {{ (old('level')== "Sophomore") ? 'selected' : '' }} value="Sophomore">Sophomore
                                        </option>
                                        <option {{ (old('level')== "Junior") ? 'selected' : '' }} value="Junior">Junior
                                        </option>
                                        <option {{ (old('level')== "Senior") ? 'selected' : '' }} value="Senior">Senior
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Select
                                    Department <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Class" class="form-control select-search"
                                        name="department_id[]" id="department_id" multiple>
                                        <option value=""></option>
                                        @foreach($my_classes as $c)
                                        <option {{ old('department_id')==$c->id ? 'selected' : '' }} value="{{ $c->id
                                            }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="prerequisite" class="col-lg-3 col-form-label font-weight-semibold">Prerequisite <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Prerequisite" class="form-control select-search"
                                        name="prerequisite" id="prerequisite">
                                        <option value=""></option>
                                    @foreach($subjects as $c)
                                        <option {{ old('prerequisite')==$c->id ? 'selected' : '' }} value="{{ $c->id
                                            }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label for="credit" class="col-lg-3 col-form-label font-weight-semibold">Credit Hour <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="credit" name="credit" value="{{ old('credit') }}" type="text"
                                        class="form-control" placeholder="3">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="toggle-container col-lg-9">
                                    <label for="toggle"
                                        class="col-lg-3 col-form-label font-weight-semibold">Clinical:</label>
                                    <div class="toggle-button">
                                        <input type="checkbox" id="toggle" name="toggle" class="toggle-checkbox" {{
                                            old('clinical')==1 ? 'checked' : '' }}>
                                        <label for="toggle" class="toggle-label">
                                            <span class="toggle-inner"></span>
                                            <span class="toggle-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            {{-- <div class="form-group row">
                                <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Teacher
                                    <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Teacher" class="form-control select-search"
                                        name="teacher_id" id="teacher_id">
                                        <option value=""></option>
                                        @foreach($teachers as $t)
                                        <option {{ old('teacher_id')==Qs::hash($t->id) ? 'selected' : '' }} value="{{
                                            Qs::hash($t->id) }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit form <i
                                        class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach($my_classes as $c)
            <div class="tab-pane fade" id="c{{ $c->id }}">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Semester</th>
                            <th>Level</th>
                            <th>Prerequisite</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $s)
                        @if(in_array($c->id, explode(',', $s->department_id)))
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->name }} </td>
                            <td>{{ $s->slug }} </td>
                            <td>
                                @if($s->term_id ==1 ){{ 'First Semester' }}
                                @elseif($s->term_id == 2)
                                {{'Second Semester'}}
                                @else
                                {{ 'NA' }}
                                @endif
                            </td>
                            <td>{{ $s->level ?? 'NA' }}</td>
                            <td>{{ $s->prerequisite->name ?? 'None' }}</td>

                            <td>{{ $s->department->name ?? 'NA' }}</td>

                            {{-- <td>{{ $s->teacher->name ?? 'unknown' }}</td> --}}
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{--edit--}}
                                            @if(Qs::userIsTeamSA())
                                            <a href="{{ route('subjects.edit', $s->id) }}" class="dropdown-item"><i
                                                    class="icon-pencil"></i> Edit</a>
                                            @endif
                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                            <a id="{{ $s->id }}" onclick="confirmDelete(this.id)" href="#"
                                                class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                            <form method="post" id="item-delete-{{ $s->id }}"
                                                action="{{ route('subjects.destroy', $s->id) }}" class="hidden">@csrf
                                                @method('delete')</form>
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

{{--subject List Ends--}}

@endsection
