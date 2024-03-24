<?php

namespace App\Http\Controllers\Api\Shifts;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use App\Traits\response;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    use response;

    public function index()
    {
        $shifts = Shift::whereBelongsTo(auth()->user())
            ->select('id', 'start_at', 'end_at')
            ->whereNotNull('end_at')
            ->withSum(['orders' => function (Builder $builder) {
                $builder->where('status', OrderStatus::Complete);
            }], 'driver_ratio')
            ->withCount(['orders' => function (Builder $builder) {
                $builder->where('status', OrderStatus::Complete);
            }])
            ->paginate(request()->limit);

        return $this->success(
            $shifts,
            ShiftResource::class
        );
    }

    public function show()
    {
        dd('show');
    }

    public function current()
    {
        $shift = auth()->user()->currentShift()
            ->withSum(['orders' => function ($q) {
                $q->where('status', OrderStatus::Complete);
            }], 'driver_ratio');

        if ($shift->doesntExist()) {
            return $this->final_response(
                success: false,
                message: "لا يوجد اي ورديات متااحة الأن",
                code: 404
            );
        }

        return $this->final_response(
            data: new ShiftResource($shift->first())
        );
    }

    public function store(Request $request)
    {
        abort_unless(!auth('sanctum')->user()->currentShift()?->first(), 400, 'لا يمكنمك بدأ وردية عمل جديدة حتي تنهي أخر وردية');

        $shift = auth('sanctum')->user()->shifts()->create([
            'start_at' => now()
        ]);

        return $this->final_response(
            message: "تم بدأ الوردية بنجاح",
            data: new ShiftResource($shift)
        );
    }

    public function endShift()
    {
        $shift = auth()->user()->currentShift()?->first();

        abort_unless(!$shift?->end_at != null, 400, 'تم الإنتهاء من الوردية من قبل');

        if ($shift) {
            $shift->update(['end_at' => now()]);
        }

        return $this->final_response(
            message: "تم إنهاء الوردية بنجاح"
        );
    }

    public function latest()
    {
        $shift = auth()->user()->shifts()
            ->whereNotNull('end_at')
            ->latest()
            ->first()?->load('orders')->loadCount('orders');

        return $this->final_response(data: new ShiftResource($shift));

    }
}
