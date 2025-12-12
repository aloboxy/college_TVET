@extends('layouts.master')
@section('page_title', 'Edit Role')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Edit Role - {{ $role->name }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('roles.update', Qs::hash($role->id)) }}">
                    @csrf @method('PUT')
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input name="name" value="{{ $role->name }}" required type="text" class="form-control" placeholder="Name of Role">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label font-weight-semibold">Permissions </label>
                        <div class="col-lg-10">
                            <div class="row">
                                @foreach($permissions as $p)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="permissions[]" value="{{ $p->name }}" class="form-check-input" {{ $role->hasPermissionTo($p->name) ? 'checked' : '' }}>
                                            {{ $p->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
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
