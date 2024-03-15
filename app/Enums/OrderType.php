<?php
namespace App\Enums;
enum OrderType: int
{
    case DELIVERY = 1;
    case COLLECT = 2;

    public function toString()
    {
        return self::list()[$this->value];
    }

    public static function list(): array
    {
        return __('order.types');
    }
}
