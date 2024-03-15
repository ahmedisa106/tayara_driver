<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftRequest;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use App\Traits\response;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    use response;

    public function index()
    {
        $shifts = auth('sanctum')->user()->shifts()
            ->select('id', 'start_at', 'end_at')
            ->whereNotNull('end_at')
            ->paginate(request()->limit);

        return $this->success(
            $shifts,
            ShiftResource::class
        );
    }

    public function current()
    {
        $shift = auth()->user()->currentShift();

        if (!$shift) {
            return $this->final_response(
                success:false,
                message:"لا يوجد اي ورديات متااحة الأن",
                code:404
            );
        }

        return $this->final_response(
            data: new ShiftResource($shift)
        );
    }

    public function store(Request $request)
    {
        abort_unless(!auth('sanctum')->user()->currentShift(), 400, 'لا يمكنمك بدأ وردية عمل جديدة حتي تنهي أخر وردية');

        $shift = auth('sanctum')->user()->shifts()->create([
            'start_at' => now()
        ]);

        return $this->final_response(
            message: "تم بدأ الوردية بنجاح",
            data: new ShiftResource($shift)
        );
    }

    public function endShift(Shift $shift)
    {

        abort_unless(!$shift->end_at != null, 400, 'تم الإنتهاء من الوردية من قبل');

        $shift->update(['end_at' => now()]);

        return $this->final_response(
            message: "تم إنهاء الوردية بنجاح"
        );
    }
}
