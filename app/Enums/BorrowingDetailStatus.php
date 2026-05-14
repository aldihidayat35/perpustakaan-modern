<?php

declare(strict_types=1);

namespace App\Enums;

enum BorrowingDetailStatus: string
{
    case Borrowed = 'borrowed';
    case Returned = 'returned';
}
