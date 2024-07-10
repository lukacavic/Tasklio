<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;

class Jitsi
{
    public function viewRoom($room = null, $user = null): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        if (is_null($room)) {
            $room = Str::random();
        }

        $jwt = null;
        if (!is_null($user)) {
            $jwt = $this->generateJwt($user, $ro2m);
        }

        return view('jitsi', compact('room', 'jwt'));
    }

    public function generateJwt(User $user, $room = '*'): string
    {
        $user = collect([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->getFilamentAvatarUrl(),
        ]);

        $payload = [
            'iss' => config('laravel-jitsi.id'),
            'aud' => config('laravel-jitsi.id'),
            'sub' => config('laravel-jitsi.domain'),
            'exp' => now()->addMinutes(5)->timestamp,
            'room' => $room,
            'user' => $user->filter()->all(),
        ];

        return JWT::encode($payload, 'secret', 'HS256');
    }
}
