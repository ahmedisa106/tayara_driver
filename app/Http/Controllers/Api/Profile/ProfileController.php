<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function profile(ProfileRequest $request)
    {
        auth()->user()->update($request->validated());

        return $this->final_response();
    }

    public function updateLocation(UpdateLocationRequest $request)
    {
        auth()->user()->update($request->validated() + ['location_updated_at' => now()]);

        return $this->final_response('تم تحديث البيانات بنجاح');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        auth()->user()->update([
            'password' => $request->validated('password')
        ]);

        return $this->final_response(message: "تم تحديث كلمة المرور بنجاح");
    }

    public function deleteAccount()
    {
        auth()->user()->tokens()->delete();
        auth()->user()->delete();

        return $this->final_response();
    }
}
