@extends('layouts.master')
@section('page_title', 'Grade Change Requests')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Grade Change Requests</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(Qs::userIsTeacher())
                <div class="text-right mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newRequestModal">
                        <i class="icon-plus3 mr-2"></i> New Grade Change Request
                    </button>
                </div>
            @endif

            <table class="table datatable-button-html5-basic">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Subject</th>
                    <th>Exam</th>
                    <th>Requester</th>
                    <th>Status</th>
                    <th>Dept. Head</th>
                    <th>College Head</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $req->student->name ?? '-' }}</td>
                        <td>{{ $req->subject->name ?? '-' }}</td>
                        <td>{{ $req->exam->name ?? '-' }}</td>
                        <td>{{ $req->requester->name ?? '-' }}</td>
                        <td>
                            @if($req->status == 'approved') <span class="badge badge-success">Approved</span>
                            @elseif($req->status == 'rejected') <span class="badge badge-danger">Rejected</span>
                            @else <span class="badge badge-warning">{{ $req->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if($req->dept_head_status == 'approved') <span class="badge badge-success">Approved</span>
                            @elseif($req->dept_head_status == 'rejected') <span class="badge badge-danger">Rejected</span>
                            @else <span class="badge badge-secondary">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($req->college_head_status == 'approved') <span class="badge badge-success">Approved</span>
                            @elseif($req->college_head_status == 'rejected') <span class="badge badge-danger">Rejected</span>
                            @else <span class="badge badge-secondary">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right">
                                    {{-- Actions for Dept Head --}}
                                    @if(Auth::user()->can('mark.approve_dept') && $req->status == 'pending_dept')
                                        <form method="POST" action="{{ route('grade_requests.approve_dept', $req->id) }}" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="dropdown-item"><i class="icon-check"></i> Approve (Dept)</button>
                                        </form>
                                        <form method="POST" action="{{ route('grade_requests.reject', $req->id) }}" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="dropdown-item"><i class="icon-cross"></i> Reject</button>
                                        </form>
                                    @endif

                                    {{-- Actions for College Head --}}
                                    @if(Auth::user()->can('mark.approve_college') && $req->status == 'pending_college')
                                        <form method="POST" action="{{ route('grade_requests.approve_college', $req->id) }}" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="dropdown-item"><i class="icon-check"></i> Approve (College)</button>
                                        </form>
                                        <form method="POST" action="{{ route('grade_requests.reject', $req->id) }}" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="dropdown-item"><i class="icon-cross"></i> Reject</button>
                                        </form>
                                    @endif

                                    {{-- Revert Action for College Head (Approved Requests) --}}
                                    @if(Auth::user()->can('mark.approve_college') && $req->status == 'approved')
                                        <form method="POST" action="{{ route('grade_requests.revert', $req->id) }}" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="dropdown-item"><i class="icon-undo"></i> Revert</button>
                                        </form>
                                    @endif
                                    
                                     {{-- View Details (Could be a modal showing the specific scores) --}}
                                     <a href="#" class="dropdown-item" data-toggle="modal" data-target="#viewRequestModal-{{ $req->id }}"><i class="icon-eye"></i> View Details</a>

                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Detail Modal --}}
                    <div class="modal fade" id="viewRequestModal-{{ $req->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Request Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Proposed Marks:</strong></p>
                                    <ul>
                                        @foreach($req->data as $key => $val)
                                            <li>{{ strtoupper($key) }}: {{ $val }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- New Request Modal --}}
    @if(Qs::userIsTeacher())
    <div class="modal fade" id="newRequestModal" tabindex="-1" role="dialog" aria-labelledby="newRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('grade_requests.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="newRequestModalLabel">New Grade Change Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Exam:</label>
                                    <select required name="exam_id" class="form-control select-search">
                                        <option value="">Select Exam</option>
                                        @foreach(\App\Models\Exam::all() as $e)
                                            <option value="{{ $e->id }}">{{ $e->name }} - {{ $e->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Class:</label>
                                    <select required name="my_class_id" class="form-control select-search">
                                        <option value="">Select Class</option>
                                        @foreach(\App\Models\MyClass::all() as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Section:</label>
                                    <select required name="section_id" class="form-control select-search">
                                        <option value="">Select Section</option>
                                          @foreach(\App\Models\Section::all() as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Subject:</label>
                                    <select required name="subject_id" class="form-control select-search">
                                        <option value="">Select Subject</option>
                                        @foreach(\App\Models\Subject::orderBy('name')->get() as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Student (Start typing to search):</label>
                                     <select required name="student_id" class="form-control select-search" data-placeholder="Select Student">
                                        {{-- In a real app this should be ajax loaded for performance, but using typical seeding data for now --}}
                                         @foreach(\App\Models\User::where('user_type', 'student')->limit(500)->get() as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6>New Marks</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>T1:</label>
                                    <input type="number" name="t1" class="form-control" placeholder="10">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>T2:</label>
                                    <input type="number" name="t2" class="form-control" placeholder="10">
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label>T3:</label>
                                    <input type="number" name="t3" class="form-control" placeholder="10">
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label>T4:</label>
                                    <input type="number" name="t4" class="form-control" placeholder="10">
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label>TCA:</label>
                                    <input type="number" name="tca" class="form-control" placeholder="40">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Exam:</label>
                                    <input type="number" name="exm" class="form-control" placeholder="60">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

@endsection
