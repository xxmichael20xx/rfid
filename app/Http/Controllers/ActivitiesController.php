<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    /**
     * Define the homeowners view
     * Containing dummy data
     */
    public function list(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');

        // get all home owners data from database
        $activities = Activity::where('end_date', '>=', $today)
            ->orderBy('created_at', 'DESC')
            ->get();

        // check if URL has "?search=keyword"
        if ($search = data_get($_GET, 'search')) {
            $likeSearch = '%'.$search.'%';
            $activities = Activity::where('title', 'LIKE', $likeSearch)
                ->orWhere('location', 'LIKE', $likeSearch)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        // check if URL has "?filter=today"
        if ($filter = request()->get('filter')) {
            if ($filter == 'today') {
                $activities = Activity::whereDate('start_date', $today)
                    ->orWhereDate('end_date', $today)
                    ->orWhere(function($query) use($today) {
                        $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
                    })->get();
            } elseif ($filter == 'archived') {
                $activities = Activity::where('end_date', '<', $today)->get();
            }
        }

        return view('admin.Activity.list', compact('activities', 'search', 'filter'));
    }

    /**
     * Callback for API all
     */
    public function all()
    {
        $activities = Activity::orderBy('end_date', 'DESC')->get();

        return response()->json([
            'status' => true,
            'data' => $activities
        ]);
    }

    /**
     * Fetch grouped Activities
     * In-progress and Future
     */
    public function grouped()
    {
        $today = Carbon::today()->format('Y-m-d');

        $activitiesToday = Activity::whereDate('start_date', $today)
            ->orWhereDate('end_date', $today)
            ->orWhere(function($query) use($today) {
                $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
            })->get();

        $upcomingActivities = Activity::whereDate('start_date', '>', $today)->get();

        // Get the current date and time
        $now = Carbon::now();

        // Calculate the date and time 48 hours ago
        $twoDaysAgo = $now->subHours(48);

        // Fetch activities created in the last 48 hours
        $newActivities = Activity::where('created_at', '>=', $twoDaysAgo)
            ->where('created_at', '<=', $now)
            ->get();

        return response()->json([
            'status' => true,
            'today' => $activitiesToday,
            'upcomming' => $upcomingActivities,
            'new' => $newActivities
        ]);
    }

    /**
     * Callback for the API today
     */
    public function today()
    {
        $today = Carbon::today()->format('Y-m-d');

        $activities = Activity::whereDate('start_date', $today)
            ->orWhereDate('end_date', $today)
            ->orWhere(function($query) use($today) {
                $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
            })->get();

        return response()->json([
            'status' => true,
            'data' => $activities
        ]);
    }

    /**
     * Callback for the API search
     */
    public function search($s)
    {
        $likeSearch = '%'.$s.'%';
        $activities = Activity::where('title', 'LIKE', $likeSearch)
            ->orWhere('location', 'LIKE', $likeSearch)
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $activities
        ]);
    }
}
