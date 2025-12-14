<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'body' => 'required|string|max:150',
            // 'tags' => 'nullable|array',
            // 'tags.*' => 'string|min:2'
        ];
    }

    public function message()
    {
        return [
            'title.required' => 'Title is required',
            'title.string' => 'Title must be string',
            'title.max' => 'Title length must not be greater than 50 characters.',
            'body.required' => 'Body is required',
            'body.string' => 'Body must be string',
            'body.max' => 'Body length must not be greater than 150 characters.',
        ];
    }
}
