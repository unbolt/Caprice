<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemTrackerController extends Controller
{

    public function trackItem(Request $request, $item) {
        return view('item_tracker', ['item' => $item]);
    }

}
