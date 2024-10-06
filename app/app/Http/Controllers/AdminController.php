<?php

namespace App\Http\Controllers;

use App\Http\Services\AdminService;
use App\Models\User;
use http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(): \Inertia\Response
    {
        $users = $this->adminService->getAllUsers();
        $questions = $this->adminService->getAllQuestions();


        return Inertia::render('admin/index', [
            'users' => $users,
            'questions' => $questions,
            'success' => session()->get('success'),
        ]);
    }

    public function editUser(int $id): \Inertia\Response
    {
        $user = $this->adminService->getUser($id);

        return Inertia::render('admin/editUserTab', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, int $id): RedirectResponse
    {
        $user = $this->adminService->getUser($id);
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $result = $this->adminService->updateUser($validated_data, $user);
        if ($result) {
            return redirect()->route('admin.index')->with('success', 'Пользователь успешно обновлен.');
        } else {
            return redirect()->route('admin.index')->with('error', 'Ошибка при обновлении пользователя.');
        }

    }

    public function banUser(int $id): bool
    {
        $status = $this->adminService->banUser($id);

        return $status;
    }


    public function processQuestion(int $id): bool
    {
        $status = $this->adminService->processQuestion($id);

        return $status;
    }

}
