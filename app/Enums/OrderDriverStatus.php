<?php

namespace App\Enums;

enum OrderDriverStatus: int
{
    case PENDING = 1;
    case RECEIVED = 2;
    case IN_DELIVERY = 3;
    case COMPLETED = 4;
    case CANCELLED = 5;

    public function toString()
    {
        return self::list()[$this->value];
    }

    public static function list()
    {
        return __('order.order_driver');
    }
}
