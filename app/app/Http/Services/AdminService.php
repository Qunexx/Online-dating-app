<?php

namespace App\Http\Services;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\assertNotTrue;

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

    public function updateUser(array $request, User $user): bool
    {

        $result = $user->update($request);
        if ($result) {
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
        if ($question) {
            return true;
        } else {
            return false;
        }
    }

    public function getUsersSessions(): ?array
    {
        $sessions = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name', 'users.email')
            ->get();
        if ($sessions->isNotEmpty()) {
            return $sessions->toArray();
        } else {
            return null;
        }
    }

    public function closeUserSession(string $session_id): bool
    {
        $exist = DB::table('sessions')->where('id', '=', $session_id)->exists();
        if ($exist) {
            $deleted = DB::table('sessions')
                ->where('id', '=', $session_id)
                ->delete();
            if ($deleted) {
                return true;
            }
        }
        return false;
    }
}





