<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function changePassword($request): bool
    {
        $user = auth()->user();
        if (!Hash::check($request['current_password'], $user->password)) {
            return false;
        }
        $user->forceFill([
            'password' => Hash::make($request['new_password'])
        ])->save();

        auth()->logout();
        return true;
    }

    public function getMyProfile(): array
    {
        $user = auth()->user();
        $profile = $user->getProfile();

        return [
            'user' => $user,
            'profile' => $profile,
        ];
    }

    public function getUserProfile(int $user_id): array
    {
        $user = User::findOrFail($user_id);
        $profile = $user->profile()->with('photos')->firstOrFail();
        return [
            'user' => $user,
            'profile' => $profile,
        ];
    }

    public function updateUserProfile($request): bool
    {
        $profile = auth()->user()->getProfile();
        $profile->update($request);
        if (isset($request['delete_photos'])) {
            $deletePhotos = json_decode($request['delete_photos'], true);
            foreach ($deletePhotos as $photoId) {
                $photo = ProfilePhoto::find($photoId);
                if ($photo) {
                    \Storage::disk('public')->delete($photo->photo_path);
                    $photo->delete();
                }
            }
        }
        if (isset($request['photos'])) {
            foreach ($request['photos'] as $file) {
                $path = $file->store('profile_photos', 'public');

                ProfilePhoto::create([
                    'profile_id' => $profile->id,
                    'photo_path' => $path,
                ]);
            }
        }

        return true;
    }

}
