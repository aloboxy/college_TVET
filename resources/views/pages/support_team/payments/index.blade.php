@extends('layouts.master')
@section('page_title', 'Manage Payments')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-cash2 mr-2"></i> Select Academic Period</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('payments.showpay') }}">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="year" class="col-form-label font-weight-bold">Select Year <span class="text-danger">*</span></label>
                            <select data-placeholder="Select Year" required id="year" name="year" class="form-control select">
                                @foreach($years as $yr)
                                    <option {{ ($selected && $year == $yr->year) ? 'selected' : '' }} value="{{ $yr->year }}">{{ $yr->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="term_id" class="col-form-label font-weight-semibold">Semester <span class="text-danger">*</span></label>
                            <select required id="term_id" name="term_id" data-placeholder="Select Semester" class="form-control select">
                                <option value=""></option>
                                <option {{ (old('term_id') == 1 || ($selected && $semester->id == 1)) ? 'selected' : '' }} value="1">First Semester</option>
                                <option {{ (old('term_id') == 2 || ($selected && $semester->id == 2)) ? 'selected' : '' }} value="2">Second Semester</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selected)
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Manage Payments for {{ $year }} - {{ $semester->name }}</h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="nav-item"><a href="#all-payments" class="nav-link active" data-toggle="tab">All Payments</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">By Class</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach($my_classes as $mc)
                                <a href="#pc-{{ $mc->id }}" class="dropdown-item" data-toggle="tab">{{ $mc->name }}</a>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">By Department</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach($departments as $dc)
                                <a href="#dc-{{ $dc->id }}" class="dropdown-item" data-toggle="tab">{{ $dc->name }}</a>
                            @endforeach
                        </div>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- All Payments Tab -->
                    <div class="tab-pane fade show active" id="all-payments">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Ref No</th>
                                    <th>Class(es)</th>
                                    <th>Department(s)</th>
                                    <th>Method</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->title }}</td>
                                        <td>{{ number_format($p->amount, 2) }}</td>
                                        <td>{{ $p->ref_no }}</td>
                                        <td>
                                            @php
                                                $classNames = [];
                                                if ($p->my_class_id) {
                                                    $classIds = explode(',', $p->my_class_id);
                                                    foreach ($classIds as $classId) {
                                                        $class = \App\Models\MyClass::find($classId);
                                                        if ($class) $classNames[] = $class->name;
                                                    }
                                                }
                                            @endphp
                                            {{ implode(', ', $classNames) }}
                                        </td>
                                        <td>
                                            @php
                                                $deptNames = [];
                                                if ($p->department_id) {
                                                    $deptIds = explode(',', $p->department_id);
                                                    foreach ($deptIds as $deptId) {
                                                        $dept = \App\Models\ClassType::find($deptId);
                                                        if ($dept) $deptNames[] = $dept->name;
                                                    }
                                                }
                                            @endphp
                                            {{ implode(', ', $deptNames) }}
                                        </td>
                                        <td>{{ ucwords($p->method) }}</td>
                                        <td>{{  $p->description }}</td>
                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <a href="{{ route('payments.edit', $p->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                        <a id="{{ $p->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                        <form method="post" id="item-delete-{{ $p->id }}" action="{{ route('payments.destroy', $p->id) }}" class="hidden">@csrf @method('delete')</form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Class-wise Payment Tabs -->
                    @foreach($my_classes as $mc)
                        <div class="tab-pane fade" id="pc-{{ $mc->id }}">
                            <div class="alert alert-info mb-4">
                                Showing payments for <strong>{{ $mc->name }}</strong> class
                            </div>
                            <table class="table datatable-button-html5-columns">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Ref No</th>
                                        <th>Method</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $p)
                                        @if($p->my_class_id && in_array($mc->id, explode(',', $p->my_class_id)))
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $p->title }}</td>
                                                <td>{{ number_format($p->amount, 2) }}</td>
                                                <td>{{ $p->ref_no }}</td>
                                                <td>{{ ucwords($p->method) }}</td>
                                                <td>{{  $p->description }}</td>
                                                <td class="text-center">
                                                    <div class="list-icons">
                                                        <div class="dropdown">
                                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                                <i class="icon-menu9"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-left">
                                                                <a href="{{ route('payments.edit', $p->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                                <a id="{{ $p->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                                <form method="post" id="item-delete-{{ $p->id }}" action="{{ route('payments.destroy', $p->id) }}" class="hidden">@csrf @method('delete')</form>
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

                    <!-- Department-wise Payment Tabs -->
                    @foreach($departments as $dc)
                        <div class="tab-pane fade" id="dc-{{ $dc->id }}">
                            <div class="alert alert-info mb-4">
                                Showing payments for <strong>{{ $dc->name }}</strong> department
                            </div>
                            <table class="table datatable-button-html5-columns">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Ref No</th>
                                        <th>Method</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $p)
                                        @if($p->department_id && in_array($dc->id, explode(',', $p->department_id)))
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $p->title }}</td>
                                                <td>{{ number_format($p->amount, 2) }}</td>
                                                <td>{{ $p->ref_no }}</td>
                                                <td>{{ ucwords($p->method) }}</td>
                                                <td>{{  $p->description }}</td>
                                                <td class="text-center">
                                                    <div class="list-icons">
                                                        <div class="dropdown">
                                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                                <i class="icon-menu9"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-left">
                                                                <a href="{{ route('payments.edit', $p->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                                <a id="{{ $p->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                                <form method="post" id="item-delete-{{ $p->id }}" action="{{ route('payments.destroy', $p->id) }}" class="hidden">@csrf @method('delete')</form>
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
    @endif

@endsection
