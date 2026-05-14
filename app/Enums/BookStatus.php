<?php

declare(strict_types=1);

namespace App\Enums;

enum BookStatus: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
}
