<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoiceController extends Controller
{
    protected $africasTalking;

    public function __construct()
    {
        $username = env('maikolmutiso');
        $apiKey = env('299a0d3c2b773ee78639a5a9ec1f544b9fb0f297fb661984ddb73b747aac5a8a');
        $this->africasTalking = new AfricasTalking($username, $apiKey);
    }

    public function makeCall(Request $request)
    {
        $request->validate([
            'from' => 'required',
            'to' => 'required'
        ]);

        $voice = $this->africasTalking->voice();
        $callFrom = $request->input('from');
        $callTo = $request->input('to');

        try {
            $response = $voice->call([
                'from' => $callFrom,
                'to' => $callTo
            ]);

            Log::info("Call initiated successfully - From: $callFrom, To: $callTo");

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
