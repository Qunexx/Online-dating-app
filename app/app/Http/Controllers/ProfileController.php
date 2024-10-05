<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\ProfilePhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use App\Models\User;
use App\Models\Profile;


class ProfileController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(): \Inertia\Response
    {
        $data = $this->service->getMyProfile();

        return Inertia::render('profile/index', [
            'user' => $data['user'],
            'profile' => $data['profile'],
        ]);
    }

    public function show(int $user_id): \Inertia\Response
    {
        $data = $this->service->getUserProfile($user_id);

        return Inertia::render('profile/show', [
            'profile' => $data['profile'],
            'profiles_user' => $data['user'],
        ]);
    }

    public function edit(): \Inertia\Response
    {
        $data = $this->service->getMyProfile();

        return Inertia::render('profile/edit', [
            'profile' => $data['profile'],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $result = $this->service->updateUserProfile($request->validate([
            'bio' => 'nullable|string|max:500',
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_photos' => 'nullable|json'
        ]));

        if ($result) {
            return redirect()->intended(route('profile.index'))->with('success', 'Профиль успешно обновлён');
        } else {
            return redirect()->intended(route('profile.index'))->with('error', 'Возникла ошибка при обновлении профиля ');
        }
    }

}
