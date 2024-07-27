<?php

use App\Http\Controllers\ViewRoomController;
use App\Mail\JitsiMeetingInvitation;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jitsi/{room?}', ViewRoomController::class)->name('jitsi.view-room');
