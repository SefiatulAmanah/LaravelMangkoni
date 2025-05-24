<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login.index');
    }
    
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors(['email' => 'Email or password incorrect']);
    }

    // login user, misalnya
    auth()->login($user);

     return redirect('/')->with('login_success', true)->with('user_name', $user->name);

}


    public function showRegister()
    {
        return view('register.index');
    }

    public function register(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:6'],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],  // kirim plain, mutator akan hash
    ]);

    return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
}

    public function logout(Request $request)
    {
          Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('logout_success', 'Sampai jumpa kembali!');
    }
}