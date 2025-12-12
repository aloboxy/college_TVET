@extends('layouts.master')
@section('page_title', 'Manage Roles')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Roles List</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="text-right mb-3">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role <i class="icon-plus-circle2 ml-2"></i></a>
        </div>

        <table class="table datatable-button-html5-columns">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Guard</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->guard_name }}</td>
                    <td>
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right">
                                    {{--Edit--}}
                                    <a href="{{ route('roles.edit', Qs::hash($role->id)) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                    
                                    {{--Delete--}}
                                    <a id="{{ Qs::hash($role->id) }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                    <form method="post" id="item-delete-{{ Qs::hash($role->id) }}" action="{{ route('roles.destroy', Qs::hash($role->id)) }}" class="hidden">@csrf @method('delete')</form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
