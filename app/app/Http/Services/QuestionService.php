<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\ProfilePhoto;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class QuestionService
{
    public function sendQuestion(array $validatedRequest) : array
    {
        $result = Question::create([
            'name' => $validatedRequest['name'],
            'email' =>  $validatedRequest['email'],
            'message' =>  $validatedRequest['message'],
            'is_processed' =>  0,
            ]);

        if($result){
            return [
                'success' => 'Ваш вопрос или предложение успешно отправлено'
            ];
        } else{
            return [
                'error' => 'Что-то пошло не так, попробуйте ещё раз'
            ];
        }

    }

}
