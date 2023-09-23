<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\HomeOwner;
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
        $dummyVisitors = [
            [
                'image' => 'images/avatar_1.png',
                'name' => 'Justin Romero',
                'date' => '06/06/23'
            ],
            [
                'image' => 'images/avatar_2.png',
                'name' => 'Mark James Manzano',
                'date' => '06/04/23'
            ],
            [
                'image' => 'images/avatar_3.png',
                'name' => 'Jethro Maiquz',
                'date' => '06/01/23'
            ]
        ];

        // create a dummy date for activities
        $activities = Activity::latest()->get();
        $today = Carbon::today()->format('Y-m-d');
        $activitiesToday = Activity::whereDate('start_date', $today)
            ->orWhereDate('end_date', $today)
            ->orWhere(function($query) use($today) {
                $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })
            ->get()
            ->count();

        // returns a view an using compact, it will pass in the variable data to the view
        return view('admin.dashboard', compact('totalHomeOwners', 'dummyVisitors', 'activities', 'activitiesToday'));
    }
}
