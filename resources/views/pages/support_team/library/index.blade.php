@extends('layouts.master')
@section('page_title', 'School Library')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Library Books</h5>
        <div class="header-elements">
            <div class="list-icons">
                 @if(Qs::userIsTeamSA())
                <a href="{{ route('library.create') }}" class="btn btn-sm btn-primary text-white">Add Book</a>
                 @endif
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="remove"></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            @foreach($books as $book)
            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0">
                    <div class="card-img-actions m-1">
                        <img class="card-img img-fluid" style="height: 250px; object-fit: cover;" src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('global_assets/images/placeholders/placeholder.jpg') }}" alt="">
                        <div class="card-img-actions-overlay card-img">
                            @if($book->book_type == 'digital' && $book->url)
                            <a href="{{ asset('storage/'.$book->url) }}" target="_blank" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
                                <i class="icon-book-play"></i>
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title font-weight-bold"><a href="#" class="text-dark">{{ $book->name }}</a></h5>
                        <p class="mb-1 text-muted">{{ $book->author }}</p>
                        <span class="badge badge-{{ $book->book_type == 'digital' ? 'primary' : 'success' }} badge-pill">
                            {{ ucfirst($book->book_type) }}
                        </span>

                         @if($book->book_type == 'physical')
                        <div class="mt-2 text-muted font-size-sm">
                            <i class="icon-drawer3 mr-1"></i> {{ $book->location }} <br>
                            Available: {{ $book->total_copies - $book->issued_copies }} / {{ $book->total_copies }}
                        </div>
                        @else
                         <div class="mt-2">
                             <a href="{{ asset('storage/'.$book->url) }}" target="_blank" class="btn btn-sm btn-light btn-block">Read Now</a>
                         </div>
                        @endif
                        
                        @if(Qs::userIsTeamSA())
                        <div class="mt-2 d-flex justify-content-between">
                            <a href="{{ route('library.edit', $book->id) }}" class="btn btn-sm btn-outline-primary"><i class="icon-pencil"></i></a>
                            <form method="post" action="{{ route('library.destroy', $book->id) }}">
                                @csrf @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="icon-trash"></i></button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
