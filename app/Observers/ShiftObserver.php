<?php

namespace App\Observers;

use App\Models\Shift;

class ShiftObserver
{
    public function updating(Shift $shift)
    {
        if ($shift->end_at && $shift->getOriginal('end_at') == null) {
            $shift->driver_salary = $shift->orders()->sum('driver_ratio');
        }
    }
}
