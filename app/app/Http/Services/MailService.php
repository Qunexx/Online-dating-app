<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMail;

class MailService
{
    public function sendResetPasswordEmail($user, $token): bool
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        $this->sendEmail($user->email, 'Сброс пароля', 'forgot-password', ['url' => $resetUrl]);
        return true;
    }

    public function sendCustomEmail($to, $subject, $view, $data = []): bool
    {
        $this->sendEmail($to, $subject, 'email', $data);
        return true;
    }

    protected function sendEmail($to, $subject, $view, $data = [])
    {
        Mail::to($to)->send(new CustomMail($subject, $view, $data));
    }
}
