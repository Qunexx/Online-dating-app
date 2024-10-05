<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    public function getAllUsers(): Collection
    {
        $users = User::get();
        return $users;

    }

    public function getUser(int $id): User
    {
        $user = User::findOrFail($id);
        return $user;
    }

    public function banUser(int $id): bool
    {
        $user = User::findOrFail($id);

        $user->is_banned = !$user->is_banned;
        $user->save();

        return $user->is_banned;
    }

    public function updateUser(array $request, User $user)
    {

        $user->update($request);

    }
}





