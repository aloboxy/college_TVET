@extends('layouts.master')
@section('page_title', 'Major/Minor')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Major</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-departments" class="nav-link active" data-toggle="tab">Major</a></li>
                <li class="nav-item"><a href="#new-department" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Major</a></li>
            </ul>

            <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-departments">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>College</th>
                                <th>Department</th>
                                <th>Major</th>
                                <th>Minor</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($majors as $c)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $c->department->college->name ?? 'NA' }}</td>
                                    <td>{{ $c->department->name ?? 'NA' }}</td>
                                    <td>{{ $c->major ?? 'NA' }}</td>
                                    <td>{{ $c->major->minor->name ?? 'NA' }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                    {{--Edit--}}
                                                    <a href="{{ route('major.edit', $c->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                        @if(Qs::userIsSuperAdmin())
                                                    {{--Delete--}}
                                                    <a id="{{ $c->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $c->id }}" action="{{ route('major.destroy', $c->id) }}" class="hidden">@csrf @method('delete')</form>
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
                            <form class="ajax-store" method="post" action="{{ route('major.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Department<span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="department_id" id="department_id" required class="form-control select">
                                            <option value="">Select Department</option>
                                            @foreach($department as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Major<span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="major" id="major" value="{{ old('major') }}" required type="text" class="form-control" placeholder="Major">
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
