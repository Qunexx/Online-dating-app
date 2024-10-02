<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMail;

class MailService
{
    public function sendResetPasswordEmail($user, $token)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        $this->sendEmail($user->email, 'Сброс пароля', 'forgot-password', ['url' => $resetUrl]);
    }

    public function sendCustomEmail($to, $subject, $view, $data = [])
    {
        $this->sendEmail($to, $subject, 'email', $data);
    }

    protected function sendEmail($to, $subject, $view, $data = [])
    {
        Mail::to($to)->send(new CustomMail($subject, $view, $data));
    }
}
