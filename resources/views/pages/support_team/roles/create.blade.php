@extends('layouts.master')
@section('page_title', 'Create Role')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Create New Role</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form class="ajax-store" method="post" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="name" required type="text" class="form-control" placeholder="Name of Role">
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

@endsection
