<?php

namespace App\Enums;

enum CouponTypeEnum: int
{
    case VALUE = 1;
    case PERCENTAGE = 2;

    public function toString(): string
    {
        return self::list()[$this->value];
    }

    public static function list(): array
    {
        return __('coupon.types');
    }
}
