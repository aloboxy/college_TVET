@extends('layouts.master')
@section('page_title', 'Edit Department - '.$c->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Department</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('departments.update', $c->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">

                            <label class="col-lg-3 col-form-label font-weight-semibold">College Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select College" class="form-control select-search" name="college_id" id="college_id">
                                    <option value=""></option>
                                    @foreach($college as $cl)
                                        <option {{ $c->college_id == $cl->id ? 'selected' : '' }} value="{{ $cl->id}}">{{ $cl->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Department Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $c->name }}" required type="text" class="form-control" placeholder="Name of Class">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Program<span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select  data-placeholder="Select Programs" class="form-control select-search" name="program" id="program">
                                    <option {{$c->program == 'BSc' ? 'selected' : '' }} value="BSc">BSc</option>
                                    <option  {{$c->program == 'MSc' ? 'selected' : '' }} value="MSc">MSc</option>
                                    <option  {{$c->program == 'Diploma' ? 'selected' : '' }} value="Diploma">Diploma</option>
                                    <option  {{$c->program == 'Certificate' ? 'selected' : '' }}  value="certificate">Certificate</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Total Credit</label>
                            <div class="col-lg-9">
                                <input name="total_credit" id="total_credit" value="{{ $c->total_credit }}" type="text" class="form-control" placeholder="Total Credit">
                            </div>
                        </div>


                      <div class="form-group row">
                            <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Department Head</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Teacher" class="form-control select-search" name="teacher_id" id="teacher_id">
                                    <option value=""></option>
                                    @foreach($teachers as $t)
                                        <option {{ $c->teacher_id == $t->id ? 'selected' : '' }} value="{{ Qs::hash($t->id) }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="class" class="col-lg-3 col-form-label font-weight-semibold">
                                Class Base
                            </label>
                            <div class="col-lg-9">
                            <select data-placeholder="please select yes/no" class="form-control select-search" name="class_base" id="class_base">
                                <option  value=""></option>
                                <option {{$c->class_base == 1 ? 'selected' : '' }} value="1"> Yes</option>
                                <option {{$c->class_base == 0 ? 'selected' : '' }} value="0"> No</option>
                            </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="code" class="col-lg-3 col-form-label font-weight-semibold">Department Code</label>
                            <div class="col-lg-9">
                                <input class="form-control" disabled="disabled" value="{{ $c->code }}" title="Class Type" type="text">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class Edit Ends--}}

@endsection
