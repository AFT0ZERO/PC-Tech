<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('fname', 'like', '%' . $s . '%')
                    ->orWhere('lname', 'like', '%' . $s . '%')
                    ->orWhere('email', 'like', '%' . $s . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $sort = $request->query('sort', 'created_desc');
        match ($sort) {
            'name_asc' => $query->orderBy('fname')->orderBy('lname'),
            'name_desc' => $query->orderBy('fname', 'desc')->orderBy('lname', 'desc'),
            'role_asc' => $query->orderBy('role')->orderBy('fname'),
            'role_desc' => $query->orderBy('role', 'desc')->orderBy('fname'),
            'created_asc' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $users = $query->paginate(15)->withQueryString();

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

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = 'uploads/user/';
            $file->move($path, $fileName);
            $imagePath = $path . $fileName;
        }

        User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'role' => 'admin',
            'gender' => $request->gender,
            'image' => $imagePath,
        ]);
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
        // Validate the input
        $request->validate([
            'fname' => ['required', 'min:3'],
            'lname' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'min:9', 'numeric'],
            'role' => ['required'],
            'gender' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        // If there's a new image uploaded, handle the upload process
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = 'uploads/user/';
            $file->move($path, $fileName);

            // If image uploaded, set the new image path
            $user->image = $path . $fileName;
        }

        // Update the other user fields
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->gender = $request->gender;

        // Save the updated user information
        $user->save();

        session()->flash('success', 'User updated successfully!');
        // Redirect to the user show route
        return to_route('users.show', $user->id);
    }


    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success', 'User Deleted Successfully!');
        return back();
    }
    public function restore( $id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        session()->flash('success', 'User Restore Successfully!');
        return to_route('users.showRestore');
    }

    public function showRestore( )
    {
        $user = User::onlyTrashed()->paginate(15);
        return view('admin.users.restore' , ['users' => $user]);
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

        // If there's a new image uploaded, handle the upload process
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = 'uploads/user/';
            $file->move($path, $fileName);

            // If image uploaded, set the new image path
            $admin->image = $path . $fileName;
        }

        // Update the other user fields
        $admin->fname = $request->fname;
        $admin->lname = $request->lname;
        $admin->email = $request->email;
        $admin->mobile = $request->mobile;
        $admin->role = $request->role;
        $admin->gender = $request->gender;

        // Save the updated user information
        $admin->save();

        session()->flash('success', 'User updated successfully!');
        // Redirect to the user show route
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

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return to_route('admin.index')->with('password_success', 'Password changed successfully!');
    }


}

