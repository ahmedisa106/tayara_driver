<?php

use App\Enums\OrderDriverStatus;
use App\Enums\OrderProviderStatus;
use App\Enums\OrderStatus;

return [
    'order' => [
        OrderStatus::Pending->value => "معلق",
        OrderStatus::In_progress->value => "جاري التوصيل",
        OrderStatus::Complete->value => "مكتمل",
        OrderStatus::Cancelled->value => "ملغي",
    ],
    'order_driver' => [
        OrderDriverStatus::PENDING->value => "معلق",
        OrderDriverStatus::IN_DELIVERY->value => "جاري التوصيل",
        OrderDriverStatus::COMPLETED->value => "مكتمل",
        OrderDriverStatus::CANCELLED->value => "ملغي",
    ],
    'order_provider' => [
        OrderProviderStatus::PENDING->value => "معلق",
        OrderProviderStatus::IN_DELIVERY->value => "جاري التحضير",
        OrderProviderStatus::COMPLETED->value => "مكتمل",
        OrderProviderStatus::CANCELLED->value => "ملغي",
    ],
];
