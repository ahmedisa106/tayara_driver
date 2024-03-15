<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 1;
    case In_progress = 2;

    case Complete = 3;

    case Cancelled = 4;

    public static function getStatus($status)
    {
        return self::list()[$status];
    }

    public static function list()
    {
        return __('status.order');
    }

    public function toString()
    {
        return self::list()[$this->value];
    }
}
