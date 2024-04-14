<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Traits\response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use response;

    /**
     * Login Form
     *
     * @param Request $request
     * */
    public function login(Request $request)
    {
        if (!auth()->attempt($request->only(['phone', 'password']))) {
            return $this->final_response(
                success: false,
                message: "خطأ في البيانات برجاء المحاولة مري أخري",
                code: 404
            );
        }

        auth()->user()->notifiable()->updateOrCreate(
            [
                'notifiable_type' => Driver::class,
                'notifiable_id' => auth()->id(),
            ],
            [
                'token' => $request->header('fcm-token')
            ]
        );

        return $this->final_response(
            message: __('messages.success'),
            data: [
                'user' => new DriverResource(auth('sanctum')->user()),
                'token' => auth()->user()->createToken($request->phone)->plainTextToken
            ]
        );
    }

    public function logout(Driver $driver)
    {
        $shift = auth()->user()->currentShift()?->first();
        
        if ($shift) {
            if ($shift->orders()->where('status', '!=', OrderStatus::Complete)->where('status', '!=', OrderStatus::Cancelled)->count() > 0) {
                return $this->final_response(success: false, message: "لا يمكنك إنهاء الوردية وهناك طلبات قيد التنفيذ", code: 400);
            }
        }

        auth('sanctum')->user()->currentAccessToken()->delete();

        return $this->final_response(message: "تم تسجيل الخروج بنجاح");
    }

    public function deleteAccount()
    {
        auth('sanctum')->user()->tokens()->delete();
        auth()->user()->delete();
        return $this->final_response(message: "تم حذف الحساب بنجاح");
    }
}
