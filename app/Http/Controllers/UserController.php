<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\RecordHistoryLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $sessionUser = $request->session()->get('user');
        $user = User::findOrFail(data_get($sessionUser, 'id_user'));

        return view('profile', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $sessionUser = $request->session()->get('user');
        $user = User::findOrFail(data_get($sessionUser, 'id_user'));
        $beforeState = $this->userState($user);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->getKey(), $user->getKeyName())
                    ->whereNull('deleted_at'),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['updated_at'] = now();
        $data['updated_by'] = $this->currentUserId($request);

        $user->update($data);

        RecordHistoryLogger::log('users', $user->getKey(), 'update', $beforeState, $this->userState($user->fresh()), $this->currentUserId($request));

        $request->session()->put('user', [
            'id_user' => $user->id_user,
            'username' => $user->username,
            'email' => $user->email,
            'role' => (int) $user->role,
        ]);

        return redirect()
            ->route('profile')
            ->with('status', 'Profile updated.');
    }

    public function index(Request $request)
    {
        $search = $request->input('q');
        $tab = $request->input('tab', 'now');

        abort_unless(in_array($tab, ['now', 'trash', 'history'], true), 404);

        $roles = DB::table('role')
            ->select('id_role', 'role')
            ->orderBy('role')
            ->get();

        return view('users', [
            'search' => $search,
            'tab' => $tab,
            'users' => $tab === 'history'
                ? null
                : $this->userQuery($search, $tab === 'trash')
                    ->orderBy('users.username')
                    ->select('users.*', 'role.role as role_name')
                    ->paginate(5)
                    ->withQueryString(),
            'histories' => $tab === 'history'
                ? $this->historyQuery('users')
                    ->where('action', 'update')
                    ->when($search, function ($query, $search) {
                        $query->where(function ($query) use ($search) {
                            $query->where('record_id', 'like', "%{$search}%")
                                ->orWhere('before_state', 'like', "%{$search}%")
                                ->orWhere('after_state', 'like', "%{$search}%");
                        });
                    })
                    ->paginate(5)
                    ->withQueryString()
                : null,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $actorId = $this->currentUserId($request);
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['nullable', 'integer', 'exists:role,id_role'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['created_at'] = now();
        $data['created_by'] = $actorId;

        $user = User::create($data);

        RecordHistoryLogger::log('users', $user->getKey(), 'create', null, $this->userState($user), $actorId);

        return redirect()
            ->route('users')
            ->with('status', 'User created.');
    }

    public function update(Request $request, $id)
    {
        $actorId = $this->currentUserId($request);
        $user = User::findOrFail($id);
        $beforeState = $this->userState($user);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->getKey(), $user->getKeyName())
                    ->whereNull('deleted_at'),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['nullable', 'integer', 'exists:role,id_role'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['updated_at'] = now();
        $data['updated_by'] = $actorId;

        $user->update($data);

        RecordHistoryLogger::log('users', $user->getKey(), 'update', $beforeState, $this->userState($user->fresh()), $actorId);

        return redirect()
            ->route('users')
            ->with('status', 'User updated.');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->userState($user);

        $user->deleted_by = $actorId;
        $user->save();
        $user->delete();

        RecordHistoryLogger::log('users', $user->getKey(), 'delete', $beforeState, $this->userState($user), $actorId);

        return redirect()
            ->route('users')
            ->with('status', 'User moved to trash.');
    }

    public function restore(Request $request, $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->userState($user);

        $user->restore();
        $user->forceFill(['deleted_by' => null])->save();

        RecordHistoryLogger::log('users', $user->getKey(), 'restore', $beforeState, $this->userState($user->fresh()), $actorId);

        return redirect()
            ->route('users', ['tab' => 'trash'])
            ->with('status', 'User restored.');
    }

    public function forceDelete(Request $request, $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);

        RecordHistoryLogger::log('users', $user->getKey(), 'permanent_delete', $this->userState($user), null, $actorId);
        $user->forceDelete();

        return redirect()
            ->route('users', ['tab' => 'trash'])
            ->with('status', 'User deleted permanently.');
    }

    public function revertHistory(Request $request, $historyId)
    {
        $history = $this->historyQuery('users')
            ->where('action', 'update')
            ->findOrFail($historyId);

        $user = User::findOrFail($history->record_id);
        $targetState = $history->before_state ?? [];

        $payload = [
            'username' => $targetState['username'] ?? $user->username,
            'email' => $targetState['email'] ?? $user->email,
            'role' => $targetState['role'] ?? $user->role,
            'updated_at' => now(),
            'updated_by' => $this->currentUserId($request),
        ];

        if (! empty($targetState['password'])) {
            $payload['password'] = $targetState['password'];
        }

        $user->update($payload);

        $history->delete();

        return redirect()
            ->route('users', ['tab' => 'history'])
            ->with('status', 'User reverted.');
    }

    private function userQuery(?string $search, bool $trash = false)
    {
        return User::query()
            ->when($trash, fn ($query) => $query->onlyTrashed())
            ->leftJoin('role', 'users.role', '=', 'role.id_role')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('users.username', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('role.role', 'like', "%{$search}%");
                });
            });
    }

    private function userState(User $user): array
    {
        return $this->modelState($user);
    }
}
