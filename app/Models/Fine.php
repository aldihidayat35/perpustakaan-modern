<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FineStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $fillable = [
        'borrowing_id',
        'member_id',
        'days_late',
        'amount_per_day',
        'total_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount_per_day' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'days_late' => 'integer',
        'paid_at' => 'datetime',
        'status' => FineStatus::class,
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function isPaid(): bool
    {
        return $this->status === FineStatus::Paid;
    }
}
