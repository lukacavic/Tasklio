<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class Jitsi
{
    private string $secret;
    private string $domain;
    private string $appId;

    public function __construct()
    {
        $this->secret = config('services.jitsi.secret');
        $this->appId = config('services.jitsi.app_id');
        $this->domain = config('services.jitsi.domain');
    }

    public function viewRoom($room = null, $user = null, $moderator = false): View|Factory|Application
    {
        if (is_null($room)) {
            $room = Str::random();
        }

        $jwt = null;
        if (!is_null($user)) {
            $jwt = $this->generateJwt($user, $room, $moderator);
        }

        return view('jitsi', compact('room', 'jwt'));
    }

    public function generateJwt(User $user, $room = '*', $moderator = false): string
    {
        $user = collect([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->getFilamentAvatarUrl(),
            'moderator' => $moderator,
        ]);

        $payload = [
            'iss' => $this->appId,
            'aud' => $this->appId,
            'sub' => $this->domain,
            'exp' => now()->addMinutes(5)->timestamp,
            'room' => $room,
            'user' => $user->filter()->all(),
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }
}
