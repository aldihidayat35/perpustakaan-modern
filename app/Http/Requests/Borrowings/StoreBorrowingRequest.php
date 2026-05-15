<?php

declare(strict_types=1);

namespace App\Http\Requests\Borrowings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id' => [
                'required',
                'integer',
                Rule::exists('members', 'id'),
            ],
            'book_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'book_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('books', 'id'),
            ],
            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.required' => 'Pilih anggota terlebih dahulu.',
            'member_id.exists' => 'Anggota tidak ditemukan.',
            'book_ids.required' => 'Pilih setidaknya satu buku.',
            'book_ids.min' => 'Pilih setidaknya satu buku.',
            'book_ids.*.distinct' => 'Tidak boleh memilih buku yang sama lebih dari sekali.',
            'book_ids.*.exists' => 'Buku yang dipilih tidak valid.',
            'due_date.after_or_equal' => 'Tanggal kembali tidak boleh sebelum hari ini.',
        ];
    }
}
