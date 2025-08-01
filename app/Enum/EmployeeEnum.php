<?php

namespace App\Enum;

use App\Enum\EnumTrait;

enum EmployeeEnum: string
{
    use EnumTrait;

    case STATUS_ACTIVE = 'active';
    case STATUS_INACTIVE = 'inactive';
}
