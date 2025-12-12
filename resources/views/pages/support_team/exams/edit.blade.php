@extends('layouts.master')
@section('page_title', 'Edit Exam - '.$ex->name. ' ('.$ex->year.')')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Exam</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form method="post" action="{{ route('exams.update', $ex->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $ex->name }}" required type="text" class="form-control" placeholder="Name of Exam">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="term" class="col-lg-3 col-form-label font-weight-semibold">Semester</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Semester" class="form-control select-search" name="term" id="term">
                                    <option {{ $ex->term == 1 ? 'selected' : '' }} value="1">First Semester</option>
                                    <option {{ $ex->term == 2 ? 'selected' : '' }} value="2">Second Semester</option>
                                </select>
                            </div>
                        </div>


                         <div class="form-group row">
                            <label for="active" class="col-lg-3 col-form-label font-weight-semibold">Active</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select" class="form-control select-search" name="active" id="active">
                                    <option {{ $ex->active == 1 ? 'selected' : '' }} value="1">Yes</option>
                                    <option {{ $ex->active == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="active" class="col-lg-3 col-form-label font-weight-semibold">Grades Published</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select" class="form-control select-search" name="published" id="published">
                                    <option {{ $ex->published == 1 ? 'selected' : '' }} value="1">Yes</option>
                                    <option {{ $ex->published == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
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
