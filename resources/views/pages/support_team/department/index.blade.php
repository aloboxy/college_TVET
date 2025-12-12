@extends('layouts.master')
@section('page_title', 'Manage Departments')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Departments</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-departments" class="nav-link active" data-toggle="tab">Departments</a></li>
                <li class="nav-item"><a href="#new-department" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Department</a></li>
            </ul>

            <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-departments">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Department Code</th>
                                <th>Program</th>
                                <th>Total Credit</th>
                                <th>Department Head</th>
                                <th>Class Base</th>
                                <th>College</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($names as $c)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $c->name }}</td>
                                    <td>{{ $c->code }}</td>
                                    <td>{{ $c->program }}</td>
                                    <td>{{ $c->total_credit ?? 'NA' }}</td>
                                    <td>{{ $c->teacher->name ?? 'NA' }}</td>
                                    <td>
                                        @if($c->class_base == 1)
                                        {{ 'Yes' }}
                                        @else
                                        {{ 'No' }}
                                        @endif
                                    </td>
                                    <td>{{ $c->college->name ?? 'NA' }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if(Qs::userIsTeamSA())
                                                    {{--Edit--}}
                                                    <a href="{{ route('departments.edit', $c->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                   @endif
                                                        @if(Qs::userIsSuperAdmin())
                                                    {{--Delete--}}
                                                    <a id="{{ $c->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $c->id }}" action="{{ route('departments.destroy', $c->id) }}" class="hidden">@csrf @method('delete')</form>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                <div class="tab-pane fade" id="new-department">

                    <div class="row">
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('departments.store') }}">
                                @csrf

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">College</label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Select Department Head" class="form-control select-search" name="college_id" id="college_id">
                                            <option value="">Select Colleg</option>
                                            @foreach($college as $c)
                                                <option {{ old('college_id') == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Department Name <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" id="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of Department">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Department Code <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="code" id="code" value="{{ old('code') }}" required type="text" class="form-control" placeholder="Department Code">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Program<span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select  data-placeholder="Select Programs" class="form-control select-search" name="program" id="program">
                                            <option {{ old('program') == 'BSc' ? 'selected' : '' }} value="BSc">BSc</option>
                                            <option  {{ old('program') == 'MSc' ? 'selected' : '' }} value="MSc">MSc</option>
                                            <option  {{ old('program') == 'Diploma' ? 'selected' : '' }} value="Diploma">Diploma</option>
                                            <option  {{ old('program') == 'Certificate' ? 'selected' : '' }}  value="certificate">Certificate</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Total Credit</label>
                                    <div class="col-lg-9">
                                        <input name="total_credit" id="total_credit" value="{{ old('total_credit') }}" type="text" class="form-control" placeholder="Total Credit">
                                    </div>
                                </div>

                                <div class="form-group row">
                            <label for="class" class="col-lg-3 col-form-label font-weight-semibold">
                                Class Base
                            </label>
                            <div class="col-lg-9">
                            <select data-placeholder="please select yes/no" class="form-control select-search" name="class_base" id="class_base">
                                <option value=""></option>
                                <option value="1"> Yes</option>
                                <option value="0"> No</option>
                            </select>
                            </div>
                        </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Department Head</label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Select Department Head" class="form-control select-search" name="teacher_id" id="teacher_id">
                                            <option value="">Select Department Head</option>
                                            @foreach($teachers as $c)
                                                <option {{ old('teacher_id') == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Class List Ends--}}

@endsection
