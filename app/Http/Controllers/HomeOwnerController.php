<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeOwnerController extends Controller
{
    /**
     * Define the homeowners view
     * Containing dummy data
     */
    public function list(Request $request)
    {
        // Start with the initial query to retrieve all home owners
        $query = HomeOwner::with(['blockLots', 'blockLots.block', 'blockLots.lot', 'vehicles'])->orderBy('created_at', 'DESC');

        // Check if URL has "?search=keyword"
        if ($search = $request->input('search')) {
            $likeSearch = '%' . $search . '%';
            $query->where(function($query) use ($likeSearch) {
                $query->where(function($query) use ($likeSearch) {
                    $query->where(DB::raw("CONCAT(last_name, ', ', first_name, COALESCE(', ', middle_name, ''))"), 'LIKE', $likeSearch)
                        ->orWhere(function ($query) use ($likeSearch) {
                            $query->whereNull('middle_name')
                                ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $likeSearch);
                        });
                })
                ->orWhere(DB::raw("CONCAT(first_name, COALESCE(' ', middle_name, ''), ' ', last_name)"), 'LIKE', $likeSearch);
            });
        }

        // Get the results after applying the search conditions
        $homeOwners = $query->get();

        return view('admin.Homeowner.list', compact('homeOwners', 'search'));
    }

    /**
     * Define the homeowners new page
     */
    public function create(Request $request) {
        return view('admin.Homeowner.create-page');
    }
}
