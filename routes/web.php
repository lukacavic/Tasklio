<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jitsi/{room?}', \App\Http\Controllers\ViewRoomController::class)->name('jitsi.view-room');
