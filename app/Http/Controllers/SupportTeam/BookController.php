<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Book; // Fixed namespace
use Illuminate\Http\Request;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:library.view', ['only' => ['index', 'show']]);
        $this->middleware('can:library.create', ['only' => ['create', 'store']]);
        $this->middleware('can:library.edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:library.delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $d['books'] = Book::orderBy('created_at', 'desc')->get();
        return view('pages.support_team.library.index', $d);
    }

    public function create()
    {
        return view('pages.support_team.library.add');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'author' => 'required|string',
            'book_type' => 'required|string',
            'total_copies' => 'required|integer',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'url' => 'nullable|file|mimes:pdf,epub,doc,docx|max:10000',
        ]);

        $book = new Book($data);

        // Upload Cover
        if($request->hasFile('cover_image')){
            $book->cover_image = $request->file('cover_image')->store('library/covers', 'public');
        }

        // Upload Digital Book
        if($request->hasFile('url')){
            $book->url = $request->file('url')->store('library/books', 'public');
        }

        $book->save();

        return Qs::jsonStoreOk();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
         $d['book'] = Book::find($id);
         return view('pages.support_team.library.edit', $d);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'author' => 'required|string',
            'book_type' => 'required|string',
            'total_copies' => 'required|integer',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
             'cover_image' => 'nullable|image|max:2048',
            'url' => 'nullable|file|mimes:pdf,epub,doc,docx|max:10000',
        ]);

         $book->fill($data);

        if($request->hasFile('cover_image')){
            $book->cover_image = $request->file('cover_image')->store('library/covers', 'public');
        }

        if($request->hasFile('url')){
            $book->url = $request->file('url')->store('library/books', 'public');
        }

        $book->save();
        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        Book::destroy($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }
}
