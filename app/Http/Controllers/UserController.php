<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
      $users_query = User::query();
      $search_param = $request->query('search');
      if (!empty($search_param)) {
          $users_query = User::search($search_param);
      }
        $UsersFromDB = $users_query->paginate(20);

       return view('admin.users.index' , ['users' => $UsersFromDB]);
    }

    public function create()
    {
        return view('admin.users.create');
    }


    public function store(Request $request)
    {
        request()->validate(
            [
                'fname'=>['required','min:3'],
                'lname'=>['required','min:3'],
                'email'=>['required','email'],
                'mobile'=>['required','min:9','numeric'],
                'role'=>['required'],
                'gender'=>['required'],
                'password'=>['required','min:5'],
                'image'=>['nullable','image','mimes:jpeg,png,jpg'],
            ]
        );
        if ($request->hasFile('image')) {
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $fileName=time().'.'.$extension;
            $path='uploads/user/';
            $file->move($path, $fileName);
        }

        $fname = request()->fname;
        $lname = request()->lname;
        $email = request()->email;
        $mobile = request()->mobile;
        $role = request()->role;
        $gender = request()->gender;
        $password = request()->password;

        User::create([
            'fname'=>$fname,
            'lname'=>$lname,
            'email'=>$email,
            'password'=>hash::make($password),
            'mobile'=>$mobile,
            'role'=>$role,
            'gender'=>$gender,
            'image'=>'uploads/user/'.$fileName
        ]);
        session()->flash('success', 'User Created Successfully!');
        return to_route('user.index');
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
        return to_route('user.show', $user->id);
    }


    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success', 'User Deleted Successfully!');
        return to_route('user.index');
    }

    public function adminProfile()
    {
        return view("admin.users.profile");
    }
}
