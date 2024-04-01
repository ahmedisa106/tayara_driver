<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLocationRequest;
use App\Traits\response;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use response;

    public function hasShift()
    {
        return $this->final_response(data: [
            'unread_notifications_count' => auth()->user()->unreadNotifications->count(),
            'has_shift' => (bool)auth()->user()->currentShift()->first()
        ]);
    }

    public function updateLocation(UpdateLocationRequest $request)
    {
        auth()->user()->update($request->validated() + ['location_updated_at' => now()]);

        return $this->final_response();
    }
}
