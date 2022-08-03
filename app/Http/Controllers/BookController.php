<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        // $books = Book::all();
        $books = Book::paginate(5);

        return view('book.index', [ 'books' => $books ]);
    }

    public function detail($id)
    {

        $book = Book::find($id);

        return view('book.detail', [ 'book' => $book ]);
    }
}
