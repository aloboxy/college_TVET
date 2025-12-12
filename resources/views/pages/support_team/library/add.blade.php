@extends('layouts.master')
@section('page_title', 'Add Book to Library')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Add New Book</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('library.store') }}" enctype="multipart/form-data" class="ajax-store" data-reload=" {{ route('library.index') }} ">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Book Name: <span class="text-danger">*</span></label>
                        <input value="{{ old('name') }}" required type="text" name="name" class="form-control" placeholder="Book Title">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Author: <span class="text-danger">*</span></label>
                        <input value="{{ old('author') }}" required type="text" name="author" class="form-control" placeholder="Author Name">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Book Type: <span class="text-danger">*</span></label>
                        <select required class="select form-control" name="book_type">
                            <option value="physical">Physical Book</option>
                            <option value="digital">Digital (E-Book)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total Copies (Physical): <span class="text-danger">*</span></label>
                        <input value="{{ old('total_copies', 1) }}" required type="number" name="total_copies" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                     <div class="form-group">
                        <label>Location (Shelf/Row):</label>
                        <input value="{{ old('location') }}" type="text" name="location" class="form-control" placeholder="e.g. Row 5, Shelf A">
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-group">
                        <label>Category/Tag:</label>
                         <input value="{{ old('description') }}" type="text" name="description" class="form-control" placeholder="Science, History...">
                    </div>
                </div>
            </div>

            <div class="row">
                 <div class="col-md-6">
                    <div class="form-group">
                        <label>Cover Image:</label>
                        <input type="file" name="cover_image" class="form-control-file">
                        <span class="form-text text-muted">Acepted Images: jpeg, png. Max size 2MB</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Digital File (PDF/Epub):</label>
                         <input type="file" name="url" class="form-control-file">
                         <span class="form-text text-muted">Accepted Files: pdf, epub. Max size 10MB</span>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
            </div>
        </form>
    </div>
</div>
@endsection
