@extends('layouts.master')
@section('page_title', 'Create Permissions')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Create New Permission</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Permission Description</label>
                <input type="text" name="description" id="description" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category">Permission Category</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="user">User</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="course">Course</option>
                    <option value="exam">Exam</option>
                    <option value="mark">Mark</option>
                    <option value="attendance">Attendance</option>
                    <option value="fee">Fee</option>
                    <option value="library">Library</option>
                    <option value="transport">Transport</option>
                    <option value="department">Department</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Permission</button>
        </form>
    </div>
</div>
@endsection