<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Models\User;
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
                success:false,
                message: "خطأ في البيانات برجاء المحاولة مري أخري",
                code: 404
            );
        }

        return $this->final_response(
            message: "تم تسجيل الدخول بنجاح",
            data: [
                'user' => new DriverResource(auth('sanctum')->user()),
                'token' => auth()->user()->createToken($request->phone)->plainTextToken
            ]
        );

    }

    public function logout(User $user)
    {
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
