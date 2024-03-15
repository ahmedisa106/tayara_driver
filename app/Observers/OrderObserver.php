<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Admin;
use App\Models\Order;
use App\Notifications\NewOrder;
use App\Services\Fcm\Fcm;
use App\Services\System\SendNotificationToSystem;
use Illuminate\Support\Facades\Notification;

class OrderObserver
{
    protected string $defaultImagePath = "defaults/new_message_2.png";


    public function updating(Order $order){
        $icon = @$order->provider
        ? config('tayara.local') . '/storage/uploads/' . $order->provider->image
        : config('tayara.local').'/'. $this->defaultImagePath;

        // when order completed
        if (
            $order->getOriginal('status')->value != $order->status->value
            &&
            $order->status->value == OrderStatus::Complete->value
        ) {
            $adminNotification = [
                'title' => "رسالة من " . auth()->user()->name,
                'order_code' => $order->order_code,
                'body' => "تم الإنتهاء من الطلب رقم " . $order->order_code,
                'icon' => $icon,
                'order_id' => $order->id,
                'created_at' => $order->created_at->isoFormat('dddd  hh:mm a'),
                'url' => config('tayara.local') . '/dashboard/orders/' . $order->order_code
            ];

            Notification::send(Admin::all(), new NewOrder($adminNotification));

            $driver_ratio = auth()->user()->contract_ratio;

            $driver_salary = ($driver_ratio / 100) * $order->delivery_fee;

            $provider_ratio = $order->provider?->contract_ratio;

            $provider_salary = $order->subtotal -  (($provider_ratio / 100) * $order->subtotal);


            $order->driver_ratio  =$driver_salary ;
            $order->provider_ratio = $provider_salary;
            $order->net_price  =($order->delivery_fee - $driver_salary) + ($provider_ratio ? ($order->subtotal - $provider_salary) : 0);


            SendNotificationToSystem::send($adminNotification);
        }

        // when order cancelled
        if (
            $order->getOriginal('status')->value != $order->status->value
            &&
            $order->status->value == OrderStatus::Cancelled->value
        )
        {
            $adminNotification =  [
                'title' => "رسالة من " . auth()->user()->name,
                'body' => "تم إلغاء الطلب رقم " . $order->order_code,
                'icon' => $icon,
                'order_code' => $order->order_code,
                'order_id' => $order->id,
                'created_at' => $order->created_at->isoFormat('dddd  hh:mm a'),
                'url' => config('tayara.local') . '/dashboard/orders/' . $order->order_code
            ];

            Notification::send(Admin::all(), new NewOrder($adminNotification));

            $order->driver_ratio  =0 ;
            $order->provider_ratio =0;
            $order->net_price  =0;

            SendNotificationToSystem::send($adminNotification);
        }

    }

    public function updated(Order $order): void
    {
        $icon = @$order->provider
            ? config('tayara.local') . '/storage/uploads/' . $order->provider->image
            : config('tayara.local').'/'.$this->defaultImagePath;

        // when order Attached
        if (
            $order->getOriginal('driver_attached_order') != $order->driver_attached_order
            &&
            $order->driver_attached_order == 1
        ) {
            $adminNotification = [
                'title' => "رسالة من " . auth()->user()->name,
                'order_code' => $order->order_code,
                'body' => "الطيار قام بإستلام  الطلب رقم " . $order->order_code,
                'icon' => $icon,
                'order_id' => $order->id,
                'created_at' => $order->created_at->isoFormat('dddd  hh:mm a'),
                'url' => config('tayara.local') . '/dashboard/orders/' . $order->order_code
            ];

            Notification::send(Admin::all(), new NewOrder($adminNotification));

            SendNotificationToSystem::send($adminNotification);
        }

        // when order Attached from Provider
        if (
            $order->getOriginal('driver_attached_order_from_provider') != $order->driver_attached_order_from_provider
            &&
            $order->driver_attached_order_from_provider == 1
        ) {

            $adminNotification = [
                'title' => "رسالة من " . auth()->user()->name,
                'order_code' => $order->order_code,
                'body' => "جاري توصيل الطلب رقم " . $order->order_code,
                'icon' => $icon,
                'order_id' => $order->id,
                'created_at' => $order->created_at->isoFormat('dddd  hh:mm a'),
                'url' => config('tayara.local') . '/dashboard/orders/' . $order->order_code
            ];

            Notification::send(Admin::all(), new NewOrder($adminNotification));

            $customer_notification = [
                'title' => $order->provider?->name ?? env('APP_NAME'),
                'body' => "جاري توصيل طلبكم",
                'icon' => $icon,
                'created_at' => $order->created_at->isoFormat('dddd  hh:mm a'),
                'order_id' => $order->id
            ];

            Notification::send($order->customer, new NewOrder($customer_notification));

            SendNotificationToSystem::send($adminNotification);

            Fcm::sendToTokens(
                tokens: [$order->customer->notifiable->token],
                title: $order->provider?->name ?? env('APP_NAME'),
                message: $customer_notification['body']
            );
        }
    }
}
