<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->all();
        $books = Book::search($input)->orderBy('id', 'desc')->paginate(10);
        $publications = Book::select('publication')->groupBy('publication')->pluck('publication');
        $authors = Book::select('author')->groupBy('author')->pluck('author');

        return view(
            'book.index',
            [
                'books' => $books,
                // selectboxの値
                'publications' => $publications,
                'authors' => $authors,

                // 検索する値
                'name' => $input['name'] ?? '',
                'publication' => $input['publication'] ?? '',
                'author' => $input['author'] ?? '',
                'status' => $input['status'] ?? '',
                'note' => $input['note'] ?? '',
            ]
        );
    }

    public function detail($id)
    {

        $book = Book::findOrFail($id);

        return view('book.detail', ['book' => $book]);
    }

    public function edit($id)
    {

        $book = Book::findOrFail($id);

        return view('book.edit', ['book' => $book]);
    }

    public function update(BookRequest $request)
    {
        try {
            //NOTE:コントローラーに対してバリデーションを当てる方法
            // $validated = Validator::make($request->all(), [
            //     'name' => 'required|max:255',
            //     'status' => ['required', Rule::in(BOOK::BOOK_STATUS_ARRAY)],
            // ]);

            // if ($validated->fails()) {
            //     return redirect()->route('book.edit', ['id' => $request->input('id')])->withErrors($validated)->withInput();
            // }

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

    public function new()
    {
        return view('book.new');
    }

    public function create(BookRequest $request)
    {
        try {
            Book::create($request->all());
            return redirect('book')->with('status', '本を作成しました。');
        } catch (\Exception $ex) {
            logger($ex->getMessage());
            return redirect('book')->withErrors($ex->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            Book::findOrFail($id)->delete();
            return redirect('book')->with('status', '本を削除しました。');
        } catch (\Exception $ex) {
            logger($ex->getMessage());
            abort(404);
        }
    }
}
