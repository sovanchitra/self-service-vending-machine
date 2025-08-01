<?php

namespace App\Enum;

use App\Enum\EnumTrait;

enum SlotEnum: int
{
    use EnumTrait;

    case STATUS_TRUE = 1;
    case STATUS_FALSE = 0;
}
