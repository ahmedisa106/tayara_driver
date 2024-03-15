<?php

namespace App\Enums;

enum StatusEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public function toString()
    {
        return self::list()[$this->value];
    }

    public static function list(): array
    {
        return __('coupon.status');
    }
}
