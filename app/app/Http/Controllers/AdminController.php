<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index() : \Inertia\Response
    {
        $users = User::all();

        return Inertia::render('admin/index', [
            'users' => $users,
            'success' => session()->get('success'),
        ]);
    }

    public function editUser(int $id) : \Inertia\Response
    {
        $user = User::findOrFail($id);

        return Inertia::render('admin/editUserTab', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request,int $id) : RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.index')->with('success', 'Пользователь успешно обновлен.');
    }

    public function banUser(int $id) : bool
    {
        $user = User::findOrFail($id);

        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned;

        return $status;
    }

}
