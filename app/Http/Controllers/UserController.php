<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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


    public function store(Request $request)
    {
        request()->validate(
            [
                'fname' => ['required', 'min:3'],
                'lname' => ['required', 'min:3'],
                'email' => ['required', 'email'],
                'mobile' => ['required', 'min:9', 'numeric'],
                'role' => ['required', Rule::in(['admin'])],
                'gender' => ['required'],
                'password' => ['required', 'min:5'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            ]
        );

        $this->userManagementService->create(
            [
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'password' => $request->password,
                'mobile' => $request->mobile,
                'role' => 'admin',
                'gender' => $request->gender,
            ],
            $request->file('image')
        );

        session()->flash('success', 'User Created Successfully!');
        return back();
    }


    public function show(User $user)
    {
        return view("admin.users.show", ["user" => $user]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'fname' => ['required', 'min:3'],
            'lname' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'min:9', 'numeric'],
            'role' => ['required'],
            'gender' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        $this->userManagementService->update(
            $user,
            [
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'role' => $request->role,
                'gender' => $request->gender,
            ],
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
        return view('admin.users.restore' , ['users' => $users]);
    }

    public function adminProfile()
    {
        return view("admin.users.profile");
    }

    public function EditAdminProfile()
    {
        return view("admin.users.editProfile");
    }
    public function UpdateAdminProfile(Request $request , User $admin)
    {
        $request->validate([
            'fname' => ['required', 'min:3'],
            'lname' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'min:9', 'numeric'],
            'role' => ['required'],
            'gender' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        $this->userManagementService->update(
            $admin,
            [
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'role' => $request->role,
                'gender' => $request->gender,
            ],
            $request->file('image')
        );

        session()->flash('success', 'User updated successfully!');
        return to_route('admin.index');
    }

    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!$this->userManagementService->changePassword($user, $request->current_password, $request->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ])->withInput();
        }

        return to_route('admin.index')->with('password_success', 'Password changed successfully!');
    }


}
