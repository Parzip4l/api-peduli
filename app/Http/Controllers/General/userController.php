<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class userController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');  // Get the search term from the request

        $user = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(10);

        if ($request->ajax()) {
            return view('general.user.list', compact('user'))->render();  // Return the table rows without reloading the page
        }

        return view('general.user.list', compact('user'));
    }

    public function create()
    {
        $role = Role::all();
        return view('general.user.create',compact('role')); 
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Jika validasi gagal, kembalikan response error
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Membuat user baru dengan remember_token
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role_id' => $request->input('role_id'),
                'password' => Hash::make($request->input('password')),
                'remember_token' => Str::random(60), // Menghasilkan token acak untuk 'remember_token'
            ]);

            // Mengembalikan response sukses
            return redirect()->back()->with('success', 'User created successfully!');
            
        } catch (\Exception $e) {
            // Menangani error jika terjadi masalah saat membuat user
            return redirect()->back()->with('success', 'Failed to create user:' . $e->getMessage());
        }
    }
}
