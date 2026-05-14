<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BorrowingDetailStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingDetail extends Model
{
    protected $fillable = ['borrowing_id', 'book_id', 'status'];

    protected $casts = [
        'status' => BorrowingDetailStatus::class,
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
