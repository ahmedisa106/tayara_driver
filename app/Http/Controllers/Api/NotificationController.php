<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Traits\response;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use response;

    /**
     * @param Request $request
     *
     * @return [type]
     */
    public function index(Request $request)
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('id', 'desc')
            ->paginate($request->input('limit'));

        return $this->success($notifications, NotificationResource::class);
    }


    public function show($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return $this->final_response(success: false, message: "البيانات غير متوفره", code: 400);
        }

        $notification->markAsRead();

        return $this->final_response(data: NotificationResource::make($notification));
    }
}
