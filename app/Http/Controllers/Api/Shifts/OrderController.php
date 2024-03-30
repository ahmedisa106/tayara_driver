<?php

namespace App\Http\Controllers\Api\Shifts;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\orders\OrderResource;
use App\Http\Resources\orders\ShowResource;
use App\Models\Order;
use App\Traits\response;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use response;

    public function index(Request $request)
    {
        $orders = auth()->user()->orders()
            ->with('products:id')
            ->when($request->type != '', function ($query) use ($request) {
                if ($request->type == 'current') {
                    $query->where(function ($query) {
                        $query->whereNot('status', OrderStatus::Cancelled->value)
                            ->whereNot('status', OrderStatus::Complete->value);
                    });
                } elseif ($request->type == "list") {
                    $query->where(function ($query) {
                        $query->where('status', OrderStatus::Cancelled->value)
                            ->orWhere('status', OrderStatus::Complete->value);
                    });
                }
            })
            ->select([
                'id',
                'subtotal',
                'order_code',
                'note',
                'status',
                'created_at',
                'provider_id',
                'driver_attached_order',
                'driver_attached_order_from_provider',
            ])
            ->withCount('products as products_count')
            ->latest()
            ->paginate();

        return $this->success($orders, OrderResource::class);
    }

    public function show(Order $order)
    {
        $order->load(
            [
                'products:id,name,image',
                'customer:id,name,phone,second_phone',
                'address:id,address,bookmark,lat,long,city_id' => [
                    'city:id,name'
                ],
                'branch:id,provider_id,name,address,lat,long' => [
                    'provider:id,name,image'
                ]
            ]
        )
            ->loadCount('products as products_count');

        return $this->final_response(data: new ShowResource($order));
    }

    public function attach(Order $order)
    {
        $order->update(['driver_attached_order' => true]);

        if ($order->provider_id == null) {
            $order->update(['status' => OrderStatus::In_progress->value]);
        }

        return $this->final_response(message: "تم إستلام الطلب بنجاح",);
    }

    public function attachFromProvider(Order $order)
    {
        $order->update(['driver_attached_order_from_provider' => true, 'status' => OrderStatus::In_progress]);

        return $this->final_response(message: "تم إستلام الطلب من الفرع بنجاح");
    }


    public function cancel(Order $order)
    {
        $order->update(['status' => OrderStatus::Cancelled->value]);

        return $this->final_response(message: "تم إلغاء الطلب بنجاح",);
    }

    public function complete(Order $order)
    {
        $order->update(['status' => OrderStatus::Complete->value]);

        return $this->final_response(message: "تم إكمال الطلب بنجاح",);
    }
}
