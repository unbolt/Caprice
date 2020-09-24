<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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

        if($message) {
            // Check and update killstreaks/totalkills
            if($message['event'] != 'boss_dead') {
                $message['killstreak'] = Cache::get('killstreak', 0);
                $message['total_kills'] = Cache::get('total_kills', 0);
            } else {
                Cache::put('killstreak', $message['killstreak']);
                Cache::put('total_kills', $message['total_kills']);
            }

            Storage::disk('local')->put('di/location.txt', json_encode($message));

            event(new DIUpdatedEvent('DI Updated', $request->input('message')));

            return response()->json('Updated');
        } else {
            return response()->json('Not a DI message');
        }
    }

    /**
     * 0 - 0a,          ???
     * 1 - 00c4,        ???
     * 2 - 00000004,    ???
     * 3 - 0000002e,    event - soon   0000002e
     *                  event - pre    0000002d
     *                  event - boss   0000002f
     *                  event - dead   0000002c
     *
     * 4 - 00000120,    zone - zi'tah  00000120
     *                  zone - ruaun   00000121
     *                  zone - reisen  00000123
     *
     * 5 - 00000000,    killstreak - only when event = DEAD
     * 6 - 0000000a,    total kills - only when event = DEAD
     * 7 -
     *
     * @param   string  $message
     * @return  array   $decoded_message
     */
    private function decodeMessage($message)
    {
        $message = explode(",", $message);

        if(count($message) !== 8) {
            return false;
        }

        $decoded_message = array();

        $decoded_message['timestamp'] = Carbon::now();

        if($message[3] == '0000002e') {
            $decoded_message['event'] = 'soon';
        } elseif($message[3] == '0000002d') {
            $decoded_message['event'] = 'very_soon';
        } elseif($message[3] == '0000002f') {
            $decoded_message['event'] = 'boss_spawn';
        } elseif($message[3] == '0000002c') {
            $decoded_message['event'] = 'boss_dead';
            $decoded_message['killstreak'] = hexdec($message[5]);
            $decoded_message['total_kills'] = hexdec($message[6]);
        }

        if($message[4] == '00000120') {
            $decoded_message['zone'] = 'Escha Zi\'Tah';
        } elseif($message[4] == '00000121') {
            $decoded_message['zone'] = 'Escha Ru\'Aun';
        } elseif($message[4] == '00000123') {
            $decoded_message['zone'] = 'Reisenjima';
        }

        return $decoded_message;
    }

}
