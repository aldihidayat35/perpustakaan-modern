<?php

declare(strict_types=1);

namespace App\Enums;

enum FineStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
}
