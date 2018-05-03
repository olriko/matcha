<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;


class SearchController extends Controller
{
    public function search(Request $request)
    {
        return response()->json([
            'success' => true,
            'results' => []
        ]);
    }
}