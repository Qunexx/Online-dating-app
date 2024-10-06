<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\ProfilePhoto;
use App\Models\Question;
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

    public function updateUser(array $request, User $user) : bool
    {

        $result = $user->update($request);
        if($result){
            return true;
        } else {
            return false;
        }
    }
    public function getAllQuestions(): Collection
    {
        $questions = Question::get();
        return $questions;

    }

    public function processQuestion(int $id): bool
    {
        $question = Question::findOrFail($id);

        $question->is_processed = !$question->is_processed;
        $question->save();
        if($question){
            return true;
        }
        else {
            return false;
        }
    }
}





