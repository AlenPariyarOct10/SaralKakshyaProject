<?php

use App\Models\Batch;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('assignments.students.{batchId}', function ($user, $batchId) {
//    return $user instanceof \App\Models\Student && $user->batch == Batch::find($batchId)->batch;
    return true;
});

Broadcast::channel('realtime-channel', function () {
    return true; // Public channel
});


