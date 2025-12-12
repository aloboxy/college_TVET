@extends('layouts.master')
@section('page_title', 'Edit Cohort of '.$s->my_class->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Cohort of {{ $s->my_class->name }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" method="post" action="{{ route('sections.update', $s->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Cohort Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $s->name }}" required type="text" class="form-control" placeholder="Name of Class">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class </label>
                            <div class="col-lg-9">
                                <input class="form-control" id="my_class_id" disabled="disabled" type="text" value="{{ $s->my_class->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Cohort WhatsApp link</label>
                            <div class="col-lg-9">
                                <input name="whatsapplink" value="{{ $s->whatsapplink }}" type="text" class="form-control" placeholder="https://google.com">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class </label>
                            <div class="col-lg-9">
                                <input class="form-control" id="my_class_id" disabled="disabled" type="text" value="{{ $s->my_class->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="active" class="col-lg-3 col-form-label font-weight-semibold">Active Status</label>
                            <div class="form-group">
                                <select class="select form-control" id="active" name="active" required data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    <option {{ ($s->active  == '1' ? 'selected' : '') }} value="1">Active</option>
                                    <option {{ ($s->active  == '0' ? 'selected' : '') }} value="0">InActive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="planning" class="col-lg-3 col-form-label font-weight-semibold">Planning</label>
                            <div class="form-group">
                                <select class="select form-control" id="planning" name="planning" required data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    <option {{ ($s->planning  == '1' ? 'selected' : '') }} value="1">Avaliable</option>
                                    <option {{ ($s->planning  == '0' ? 'selected' : '') }} value="0">Not Avaliable</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Coordinator</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Teacher" class="form-control select-search" name="teacher_id" id="teacher_id">
                                    <option value=""></option>
                                    @foreach($teachers as $t)
                                        <option {{ $s->teacher_id == $t->id ? 'selected' : '' }} value="{{ Qs::hash($t->id) }}">{{ $t->name }}</option>
                                    @endforeach
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
    {{--Section Edit Ends--}}
@endsection
