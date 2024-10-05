<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerUser(array $validated_data)
    {
        $user = User::create([
            'name' => $validated_data['name'],
            'email' => $validated_data['email'],
            'password' => Hash::make($validated_data['password']),
        ]);

        $user->profile()->create([
            'bio' => 'Ещё не заполнено',
            'gender' => 'Ещё не заполнено',
            'birthdate' => now(),
            'location' => 'Ещё не заполнено',
            'interests' => 'Ещё не заполнено',
        ]);

        return true;
    }
}
