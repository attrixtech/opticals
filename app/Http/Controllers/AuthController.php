<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(['email' => 'Your account is inactive. Please contact an administrator.'])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();
            session(['active_role' => $user->role]);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'You have been signed out.');
    }

    public function switchRole(Request $request, string $role)
    {
        $user = User::where('role', $role)->first();
        if (!$user) {
            $user = User::first();
        }

        if ($user) {
            Auth::login($user);
            session(['active_role' => $user->role]);
            return redirect()->route('dashboard')
                ->with('success', 'Switched to ' . strtoupper(str_replace('_', ' ', $user->role)) . ' view: ' . $user->name);
        }

        return redirect()->back()->with('error', 'Role user not found.');
    }

    public function loginAs(Request $request, int $userId)
    {
        $user = User::findOrFail($userId);
        Auth::login($user);
        session(['active_role' => $user->role]);
        return redirect()->route('dashboard')
            ->with('success', 'Logged in as ' . $user->name . ' (' . ucfirst($user->role) . ')');
    }
}
