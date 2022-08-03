<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Book;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'status' => ['required', Rule::in(BOOK::BOOK_STATUS_ARRAY)],
            'author' => 'max:255',
            'publication' => 'max:255',
            'note' => 'max:1000',
            'read_at' => 'nullable|date'
        ];
    }
}
