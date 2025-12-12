@extends('layouts.master')
@section('page_title', 'Edit '.$major->major.' Major for- '.$major->department->name. ' Department')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Major/Minor</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('major.update', $major->id) }}">
                        @csrf @method('PUT')
                      <div class="form-group row">
                            <label for="dean" class="col-lg-3 col-form-label font-weight-semibold">Department</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Select Department" class="form-control select-search" name="department_id" id="department_id">
                                    <option value=""></option>
                                    @foreach($department as $t)
                                        <option {{ $major->department_id == $t->id ? 'selected' : '' }} value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Major <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="major" value="{{ $major->major }}" required type="text" class="form-control" placeholder="Major">
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
