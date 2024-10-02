<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Services\MailService;
use App\Models\Profile;
use App\Models\User;
use http\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;
use PhpParser\Node\Expr\Cast\Object_;

class RestorePasswordController extends Controller
{
    public function showRestoreForm() : Response
    {
        return Inertia::render('forgotPassword');
    }

    public function showChangePasswordForm(string $token,  string $email) : Response
    {

        return Inertia::render('changePassword',['token' => $token, 'email' => $email]);
    }

    public function sendResetLinkEmail(Request $request, MailService $service): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Пользователь с таким email не найден.']);
        }

        $token = Password::createToken($user);
        $service->sendResetPasswordEmail($user,$token);

        return back()->withErrors(['send' => 'Письмо успешно отправлено']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['success' => true, 'message' => 'Пароль успешно изменен.']);
        } else {
            return response()->json(['success' => false, 'errors' => ['email' => [__($status)]]], 422);
    }
    }
}

