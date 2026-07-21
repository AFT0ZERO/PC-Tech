<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(private UserManagementService $userManagementService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $role = $request->role;
        $sort = $request->query('sort', 'created_desc');
        $users = $this->userManagementService->list($search, $role, $sort);

        return view('admin.users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->userManagementService->create(
            $request->safe()->only(['fname', 'lname', 'email', 'password', 'mobile', 'gender']) + ['role' => 'admin'],
            $request->file('image')
        );

        session()->flash('success', 'User Created Successfully!');

        return back();
    }

    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userManagementService->update(
            $user,
            $request->safe()->only(['fname', 'lname', 'email', 'mobile', 'role', 'gender']),
            $request->file('image')
        );

        session()->flash('success', 'User updated successfully!');

        return to_route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        $this->userManagementService->delete($user);
        session()->flash('success', 'User Deleted Successfully!');

        return back();
    }

    public function restore($id)
    {
        $this->userManagementService->restore($id);
        session()->flash('success', 'User Restore Successfully!');

        return to_route('users.showRestore');
    }

    public function showRestore()
    {
        $users = $this->userManagementService->listTrashed();

        return view('admin.users.restore', ['users' => $users]);
    }

    public function adminProfile()
    {
        return view('admin.users.profile');
    }

    public function EditAdminProfile()
    {
        return view('admin.users.editProfile');
    }

    public function UpdateAdminProfile(UpdateUserRequest $request, User $admin)
    {
        $this->userManagementService->update(
            $admin,
            $request->safe()->only(['fname', 'lname', 'email', 'mobile', 'role', 'gender']),
            $request->file('image')
        );

        session()->flash('success', 'User updated successfully!');

        return to_route('admin.index');
    }

    public function updateAdminPassword(UpdatePasswordRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $this->userManagementService->changePassword($user, $request->current_password, $request->password);

        return to_route('admin.index')->with('password_success', 'Password changed successfully!');
    }
}
