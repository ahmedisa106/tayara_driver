<?php

namespace App\Enums;

enum OptionsEnum: int
{
    case REQUIRED = 1;
    case OPTIONAL = 2;

    public function toString()
    {
        return self::list()[$this->value];
    }

    public static function list(): array
    {
        return __('options');
    }
}
