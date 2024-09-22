<?php

namespace App\Http\Controllers;

use App\Models\ProfilePhoto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use App\Models\User;
use App\Models\Profile;


class ProfileController extends Controller
{
    public function index() : Response
    {
        $user = auth()->user();
        $profile = $user->getProfile();

        return Inertia::render('profile/index', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        $profile = $user->profile()->with('photos')->firstOrFail();

        return Inertia::render('profile/show', [
            'profile' => $profile,
            'profiles_user' => $user,
        ]);
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->getProfile();

        return Inertia::render('profile/edit', [
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'bio' => 'nullable|string|max:500',
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = auth()->user()->getProfile();
        $profile->update($data);
        if ($request->has('delete_photos')) {
            $deletePhotos = json_decode($request->delete_photos, true);
            foreach ($deletePhotos as $photoId) {
                $photo = ProfilePhoto::find($photoId);
                if ($photo) {
                    \Storage::disk('public')->delete($photo->photo_path);
                    $photo->delete();
                }
            }
        }
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('profile_photos', 'public');

                ProfilePhoto::create([
                    'profile_id' => $profile->id,
                    'photo_path' => $path,
                ]);
            }
        }
        return redirect()->intended(route('profile.index'))->with('success', 'Профиль успешно обновлён');
    }

}
