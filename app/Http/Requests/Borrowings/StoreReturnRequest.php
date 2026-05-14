<?php

declare(strict_types=1);

namespace App\Http\Requests\Borrowings;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'return_date' => ['required', 'date'],
            'detail_ids' => ['nullable', 'array', 'min:1'],
            'detail_ids.*' => ['required', 'integer', 'exists:borrowing_details,id'],
            'condition' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
