<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\RealtimeEvent;
use Illuminate\Support\Facades\Log;

class EventCheck extends Controller
{
    public function fireTest()
    {
        try {
            broadcast(new RealtimeEvent("Hello World from controller"));
            Log::info('RealtimeEvent broadcast initiated');
            return response()->json([
                'status' => 'success',
                'message' => 'Event fired successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast event: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to broadcast event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkStatus()
    {
        $reverbConfig = config('reverb');
        $broadcastConfig = config('broadcasting');

        return response()->json([
            'reverb_driver' => $broadcastConfig['default'],
            'reverb_host' => env('REVERB_HOST'),
            'reverb_port' => env('REVERB_PORT'),
            'reverb_app_key' => env('REVERB_APP_KEY') ? 'Set' : 'Not set',
            'reverb_app_secret' => env('REVERB_APP_SECRET') ? 'Set' : 'Not set',
            'reverb_app_id' => env('REVERB_APP_ID') ? 'Set' : 'Not set',
            'queue_connection' => config('queue.default'),
        ]);
    }
}
