<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

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

    public function edit($id)
    {

        $book = Book::find($id);

        return view('book.edit', [ 'book' => $book ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $book = Book::find($request->input('id'));
            $book->name = $request->input('name');
            $book->status = $request->input('status');
            $book->author = $request->input('author');
            $book->publication = $request->input('publication');
            $book->read_at = $request->input('read_at');
            $book->note = $request->input('note');
            $book->save();

            DB::commit();

            return redirect('book')->with('status', '本を更新しました。');
        } catch (\Exception $ex) {
            DB::rollback();
            logger($ex->getMessage());
            return redirect('book')->withErrors($ex->getMessage());
        }
    }
}
