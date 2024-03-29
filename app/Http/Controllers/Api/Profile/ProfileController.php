<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Traits\response;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use response;

    public function hasShift()
    {
        return $this->final_response(data: [
            'unread_notifications_count' => auth()->user()->unreadNotifications->count(),
            'has_shift' => (bool)auth()->user()->currentShift()
        ]);
    }
}
