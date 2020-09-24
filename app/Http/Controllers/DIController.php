<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\DIUpdateRequest;

use App\Events\DIUpdatedEvent;

class DIController extends Controller
{

    public function show() {
        return view('di_location');
    }

    public function DILocation()
    {
        $location = Storage::disk('local')->get('di/location.txt');
        $location = json_decode($location);

        return response()->json($location);
    }

    /**
     * Save item data to the server
     *
     * @param Request $request
     * @return void
     */
    public function DIMessageReceived(DIUpdateRequest $request)
    {

        $message = $this->decodeMessage($request->input('message'));

        Storage::disk('local')->put('di/location.txt', json_encode(['location' => $message, 'debug' => $request->input('message')]));

        event(new DIUpdatedEvent($message, $request->input('message')));

        return response()->json('Updated');
    }

    private function decodeMessage($message)
    {
        $message = explode(",", $message);

        if(!array_key_exists(4, $message)) {
            return false;
        }

        if($message[4] == '00000120') {
            return 'Escha Zi\'Tah';
        } elseif($message[4] == '00000121') {
            return 'Escha Ru\'Aun';
        } elseif($message[4] == '00000123') {
            return 'Reisenjima';
        }
    }

}
