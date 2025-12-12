@extends('layouts.master')
@section('page_title', 'Permissions List')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Permissions List</h5>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        <div class="text-right mb-3">
            <a href="{{ route('permissions.create') }}" class="btn btn-primary">Add Permission <i class="icon-plus-circle2 ml-2"></i></a>
        </div>
   
    <table class="table datatable-button-html5-columns">
        <thead>
            <tr>
                <th>SN</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $permission)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->description }}</td>
                    <td>{{ $permission->category }}</td>
                    <td>
                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a id="{{ Qs::hash($permission->id) }}" onclick="confirmDelete(this.id)" href="#" class="btn btn-danger btn-sm">Delete</a>
                        <form method="post" id="item-delete-{{ Qs::hash($permission->id) }}" action="{{ route('permissions.destroy', Qs::hash($permission->id)) }}" class="hidden">@csrf @method('delete')</form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection