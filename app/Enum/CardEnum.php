<?php

namespace App\Enum;

use App\Enum\EnumTrait;

enum CardEnum: string
{
    use EnumTrait;

    case STATUS_ACTIVE = 'active';
    case STATUS_INACTIVE = 'inactive';
}
