@extends('layouts.master')
@section('page_title', 'Edit '.$minor->minor.' for- '.$minor->major->major. ' Major')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Minor</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('minor.update', $minor->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label for="dean" class="col-lg-3 col-form-label font-weight-semibold">College</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Major" class="form-control select-search" name="major_id" id="major_id">
                                    <option value=""></option>
                                    @foreach($major as $t)
                                        <option {{ $minor->major_id == $t->id ? 'selected' : '' }} value="{{ $t->id }}">{{ $t->major }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Minor <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="minor" id="minor" value="{{ $minor->minor }}" required type="text" class="form-control" placeholder="Minor">
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
