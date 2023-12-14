<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\HomeOwner;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Define the dashboard view
     * Containing dummy data
     */
    public function dashboard(Request $request)
    {
        // get total home owners
        $totalHomeOwners = HomeOwner::all()->count();

        // create a dummy data for visitors
        $visitors = Visitor::where('time_in', '!=', null)
            ->orderBy('time_in', 'DESC')
            ->limit(10)
            ->get();

        // create a dummy date for activities
        $activities = Activity::latest()->limit(5)->get();
        $today = Carbon::today()->format('Y-m-d');
        $activitiesToday = Activity::whereDate('start_date', $today)
            ->orWhereDate('end_date', $today)
            ->orWhere(function($query) use($today) {
                $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })
            ->get()
            ->count();

        // get the visitors today
        $visitorsToday = Visitor::whereDate('time_in', now())->count();

        // returns a view an using compact, it will pass in the variable data to the view
        return view('admin.dashboard', compact('totalHomeOwners', 'visitors', 'activities', 'activitiesToday', 'visitorsToday'));
    }
}
