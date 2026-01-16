<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Show Registration Form
    public function showRegister()
    {
        return view('auth.register');
    }

    // 2. Handle Registration Logic
    public function register(Request $request)
    {
        // Validate Inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', // 'confirmed' checks password_confirmation field
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'attendee', // Default role
        ]);

        // Auto Login after registration
        Auth::login($user);

        // Redirect to Home
        return redirect()->route('home')->with('success', 'Account created successfully!');
    }

    // 3. Show Login Form
    public function showLogin()
    {
        return view('auth.login');
    }

    // 4. Handle Login Logic
    public function login(Request $request)
    {
        // Validate
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Logged in successfully!');
        }

        // If failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // 5. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out.');
    }
}
