<?php

namespace App\Enums;

enum PaymentMethodEnum: int
{
    case CASH = 1;

    /**
     * @return mixed
     */
    public function toString(): mixed
    {
        return self::list()[$this->value];
    }

    /**
     * @return array
     */
    public static function list(): array
    {
        return __('payment_methods');
    }
}
