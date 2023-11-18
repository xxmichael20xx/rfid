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
        // get all home owners data from database
        $homeOwners = HomeOwner::with(['blockLots', 'blockLots.block', 'blockLots.lot', 'vehicles'])->orderBy('created_at', 'DESC')->get();

        // check if URL has "?search=keyword"
        if ($search = data_get($_GET, 'search')) {
            $likeSearch = '%'.$search.'%';
                $homeOwners = HomeOwner::where(function($query) use ($likeSearch) {
                    $query->where(DB::raw("CONCAT(last_name, ', ', first_name, COALESCE(', ', middle_name, ''))"), 'LIKE', $likeSearch)
                            ->orWhere(DB::raw("CONCAT(first_name, COALESCE(' ', middle_name, ''), ' ', last_name)"), 'LIKE', $likeSearch);
                })
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return view('admin.Homeowner.list', compact('homeOwners', 'search'));
    }

    /**
     * Define the homeowners new page
     */
    public function create(Request $request) {
        return view('admin.Homeowner.create-page');
    }
}
