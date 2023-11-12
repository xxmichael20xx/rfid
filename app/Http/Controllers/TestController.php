<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function template()
    {
        $today = Carbon::today()->format('Y-m-d');

        $activitiesToday = Activity::whereDate('start_date', $today)
            ->orWhereDate('end_date', $today)
            ->orWhere(function($query) use($today) {
                $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
            })->get();

        $upcomingActivities = Activity::whereDate('start_date', '>', $today)->get();

        return response()->json([
            'status' => true,
            'today' => $activitiesToday,
            'upcomming' => $upcomingActivities
        ]);
    }
}
