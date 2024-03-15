<?php

namespace App\Services\System;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class SendNotificationToSystem
{
    public static function send(?array $data = null)
    {
        $data = $data + ['url' => config('tayara.local') . '/dashboard/orders/' . $data['order_code']];

        Http::post(config('tayara.local') . '/sendNotification', $data);
    }
}
