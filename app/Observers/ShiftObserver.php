<?php

namespace App\Observers;

use App\Models\Shift;

class ShiftObserver
{
    public function updating(Shift $shift){
        $shift->update([
            'driver_salary' => $shift->orders()->sum('driver_ratio')
        ]);

    }
}
