<?php

namespace App\Enum;

use App\Enum\EnumTrait;

enum TransactionEnum: string
{
    use EnumTrait;

    case STATUS_PENDING = 'pending';
    case STATUS_COMPLETED = 'completed';
    case STATUS_FAILED = 'failed';
}
