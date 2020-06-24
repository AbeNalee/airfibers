<?php

namespace App\Traits;

use Carbon\Carbon;

trait ChecksNightTime {

    public function checkTime() {
        $time = Carbon::now();
        $start = Carbon::create($time->year, $time->month, $time->day, 20, 0, 0);
        $end = Carbon::create($time->year, $time->month, $time->day, 23, 59, 0);

        if ($time->between($start, $end)){
            return true;
        }
        return false;
    }

}