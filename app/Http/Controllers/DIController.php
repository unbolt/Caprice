<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\DIUpdateRequest;

use App\Events\DIUpdatedEvent;

class DIController extends Controller
{
    /**
     * Save item data to the server
     *
     * @param Request $request
     * @return void
     */
    public function DIMessageReceived(DIUpdateRequest $request)
    {



        event(new DIUpdatedEvent($request->input('message')));

        return response()->json('Updated');
    }

}
