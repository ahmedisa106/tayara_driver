<?php

namespace App\Http\Controllers\Api\Shifts;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use App\Traits\response;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    use response;

    /**
     * Display all shifts with orders in desc
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $shifts = Shift::whereBelongsTo(auth()->user())
            ->select('id', 'start_at', 'end_at', 'driver_salary', 'custody')
            ->whereNotNull('end_at')
            ->withSum(['orders' => function (Builder $builder) {
                $builder->where('status', OrderStatus::Complete);
            }], 'delivery_fee')
            ->withCount(['orders' => function (Builder $builder) {
                $builder->where('status', OrderStatus::Complete);
            }])
            ->when(\request('date'), function ($q) {
                $q->whereDate('start_at', '>=', request('date'));
            })
            ->latest()
            ->paginate(request()->limit);

        return $this->success($shifts, ShiftResource::class);
    }

    /**
     * Display Shift
     *
     * @return JsonResponse
     */
    public function show(Shift $shift): JsonResponse
    {
        $shift->load(['orders' => fn($q) => $q->where('status', OrderStatus::Complete)
            ->withCount('products as products_count')])
            ->loadSum(['orders' => fn($q) => $q->where('status', OrderStatus::Complete)], 'delivery_fee')
            ->loadCount(['orders' => fn($q) => $q->where('status', OrderStatus::Complete)]);

        return $this->final_response(data: new ShiftResource($shift));
    }

    /**
     * Display Current Shift
     *
     * @return JsonResponse
     */
    public function current(): JsonResponse
    {
        $shift = auth()->user()->currentShift()
            ->withSum(['orders' => function ($q) {
                $q->where('status', OrderStatus::Complete);
            }], 'driver_ratio')
            ->withCount(['orders as orders_count' => fn($q) => $q->where('status', OrderStatus::Complete)]);

        if ($shift->doesntExist()) {
            return $this->final_response(success: false, message: "لا يوجد اي ورديات متااحة الأن", code: 404);
        }

        return $this->final_response(data: new ShiftResource($shift->first()));
    }

    /**
     * Start new Shift
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        abort_unless(
            !auth('sanctum')->user()->currentShift()?->first(),
            400,
            'لا يمكنمك بدأ وردية عمل جديدة حتي تنهي أخر وردية');

        auth('sanctum')->user()->update([
            'is_in_shift' => 1,
        ]);

        $shift = auth('sanctum')->user()->shifts()->create(['start_at' => now()]);

        return $this->final_response(message: "تم بدأ الوردية بنجاح", data: new ShiftResource($shift));
    }

    /**
     * End driver's latest shift
     *
     * @return JsonResponse
     */
    public function endShift(): JsonResponse
    {
        $shift = auth()->user()->currentShift()?->first();

        abort_unless(!$shift?->end_at != null, 400, 'تم الإنتهاء من الوردية من قبل');

        if ($shift) {
            if ($shift->orders()->where('status', '!=', OrderStatus::Complete)->where('status', '!=', OrderStatus::Cancelled)->count() > 0) {
                return $this->final_response(success: false, message: "لا يمكنك إنهاء الوردية وهناك طلبات قيد التنفيذ", code: 400);
            }
            
            $shift->update(['end_at' => now()]);
        }

        auth('sanctum')->user()->update(['is_in_shift' => false,]);

        return $this->final_response(message: "تم إنهاء الوردية بنجاح");
    }

    /**
     * Display the latest shift with orders details
     *
     * @return JsonResponse
     */
    public function latest(): JsonResponse
    {
        $shift = auth()->user()->shifts()
            ->whereNotNull('end_at')
            ->latest()
            ->first();

        if (!$shift) {
            return $this->final_response(success: false, message: "لا يوجد اي ورديات متااحة الأن", code: 404);
        }

        $shift->load(['orders' => fn($q) => $q->where('status', OrderStatus::Complete)
            ->withCount('products as products_count')])
            ->loadSum(['orders' => fn($q) => $q->where('status', OrderStatus::Complete)], 'driver_ratio')
            ->loadCount(['orders' => fn($q) => $q->where('status', OrderStatus::Complete)]);

        return $this->final_response(data: new ShiftResource($shift));
    }
}
