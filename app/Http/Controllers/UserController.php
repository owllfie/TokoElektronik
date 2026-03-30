<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $roles = DB::table('role')
            ->select('id_role', 'role')
            ->orderBy('role')
            ->get();

        $users = User::query()
            ->leftJoin('role', 'users.role', '=', 'role.id_role')
            ->when($search, function ($query, $search) {
                $query->where('users.username', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('role.role', 'like', "%{$search}%");
            })
            ->orderBy('users.username')
            ->select('users.*', 'role.role as role_name')
            ->paginate(5)
            ->withQueryString();

        return view('users', [
            'users' => $users,
            'search' => $search,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['nullable', 'integer', 'exists:role,id_role'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('users')
            ->with('status', 'User created.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->getKey() . ',' . $user->getKeyName()],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['nullable', 'integer', 'exists:role,id_role'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users')
            ->with('status', 'User updated.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()
            ->route('users')
            ->with('status', 'User deleted.');
    }
}
