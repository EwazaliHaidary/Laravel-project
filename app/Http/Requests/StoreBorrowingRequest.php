<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                "user_id" => "required| exists:users,id",
                "book_id" => "required| exists:books,id",
                "borrowed_at" => "required| date",
                "due_at" => "required| date| after:borrowed_at",
                "returned_at" => "nullable| date| after:borrowed_at",
        ];
    }
    public function messages(): array
{
    return [
        'user_id.required' => 'Please select a user.',
        'user_id.exists' => 'The selected user does not exist.',

        'book_id.required' => 'Please select a book.',
        'book_id.exists' => 'The selected book does not exist.',

        'borrowed_at.required' => 'The borrowed date is required.',
        'borrowed_at.date' => 'The borrowed date must be a valid date.',

        'due_at.required' => 'The due date is required.',
        'due_at.date' => 'The due date must be a valid date.',
        'due_at.after' => 'The due date must be after the borrowed date.',

        'returned_at.date' => 'The returned date must be a valid date.',
        'returned_at.after' => 'The returned date must be after the borrowed date.',
    ];
}
}
