<?php

namespace App\Http\Controllers;

use App\Http\Services\NotificationService;
use App\Http\Services\PairSearchingService;
use App\Models\Like;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PairSearchingController extends Controller
{
    protected $pairSearchingService;
    protected $notificationService;

    public function __construct(PairSearchingService $pairSearchingService, NotificationService $notificationService)
    {
        $this->pairSearchingService = $pairSearchingService;
        $this->notificationService = $notificationService;
    }

    public function searchPair(): \Inertia\Response
    {
        $data = $this->pairSearchingService->getRandomProfile();

        return Inertia::render('searchPair', [
            'profile' => $data['profile'],
            'profiles_user' => $data['profiles_user']]);

    }

    public function like(Profile $profile): \Inertia\Response
    {
        $result = $this->pairSearchingService->putLike($profile);

        $this->notificationService->createNotification(
            $result['to_user_id'],
            $result['from_user_id'],
            "Вас лайкнул " . $result['from_user_name'] . ". Если у вас взаимная симпатия, то обязательно напишите ему/ей об этом, либо лайкните в ответ."
        );

        return Inertia::render('searchPair', [
            'profile' => $result['profile'],
            'profiles_user' => $result['profiles_user'],
        ]);
    }

    public function dislike(Profile $profile): RedirectResponse
    {
        $this->pairSearchingService->putDisLike($profile);
        return redirect()->route('search-pair');
    }
}
