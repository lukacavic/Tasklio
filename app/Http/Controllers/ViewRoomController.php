<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Jitsi;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ViewRoomController extends Controller
{
    private Jitsi $jitsi;

    function __construct(Jitsi $jitsi)
    {
        $this->jitsi = $jitsi;
    }

    public function __invoke(Request $request, $room = null)
    {
        return $this->jitsi->viewRoom($room, $request->user());
    }


}
