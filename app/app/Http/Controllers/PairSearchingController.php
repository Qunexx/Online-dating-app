<?php
namespace App\Http\Controllers;

    use App\Models\Like;
    use App\Models\Profile;
    use Illuminate\Http\Request;
    use Inertia\Inertia;

class PairSearchingController extends Controller
{
    public function searchPair()
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
            return Inertia::render('searchPair', [
                'profile' => $profile,
                'profiles_user' => $user,
            ]);
        } else {
            session()->forget('last_viewed_profile_id');
            return Inertia::render('searchPair', [
                'profile' => null,
                'profiles_user' => null,
            ]);
        }
    }
    public function like(Profile $profile)
    {
        auth()->user()->likes()->create([
            'profile_id' => $profile->id,
            'liked' => true,
        ]);

        return Inertia::render('searchPair', [
            'profile' => $profile->with('photos'),
            'profiles_user' => $profile->user,
        ]);
    }

    public function dislike(Profile $profile)
    {
        auth()->user()->likes()->create([
            'profile_id' => $profile->id,
            'liked' => false,
            'dislike_expires_at' => now()->addHours(24),
        ]);
        return redirect()->route('search-pair');
    }
}
