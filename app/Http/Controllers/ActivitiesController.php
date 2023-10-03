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
        if ($filter = data_get($_GET, 'filter')) {
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

        return view('admin.activity.list', compact('activities', 'search', 'filter'));
    }
}
