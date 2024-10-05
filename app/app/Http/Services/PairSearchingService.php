<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\Profile;
use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class PairSearchingService
{

    public function getRandomProfile(): array
    {
        $lastViewedProfileId = session('last_viewed_profile_id');
        $query = Profile::where('user_id', '!=', auth()->id());
        if ($lastViewedProfileId) {
            $query->where('id', '!=', $lastViewedProfileId);
        }
        $profile = $query->inRandomOrder()
            ->with('photos')
            ->first();

        if ($profile) {
            session(['last_viewed_profile_id' => $profile->id]);
            $user = $profile->user;
            return [
                'profile' => $profile,
                'profiles_user' => $user,
            ];
        } else {
            session()->forget('last_viewed_profile_id');
            return [
                'profile' => null,
                'profiles_user' => null,
            ];
        }
    }

    public function putLike(Profile $profile): array
    {
        $user = auth()->user();
        $user->likes()->create([
            'profile_id' => $profile->id,
            'liked' => true,
        ]);
        $to_user_id = $profile->getUserId();
        $from_user_id = auth()->id();
        $from_user_name = $user->getUserName($from_user_id);
        $profile->load('photos');
        $users_profile = $profile->user;
        return [
            'profile' => $profile,
            'profiles_user' => $users_profile,
            'from_user_name' => $from_user_name,
            'to_user_id' => $to_user_id,
            'from_user_id' => $from_user_id,
        ];
    }

    public function putDisLike(Profile $profile): bool
    {
        $result = auth()->user()->likes()->create([
            'profile_id' => $profile->id,
            'liked' => false,
            'dislike_expires_at' => now()->addHours(24),
        ]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }


}
