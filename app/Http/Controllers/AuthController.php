<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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

