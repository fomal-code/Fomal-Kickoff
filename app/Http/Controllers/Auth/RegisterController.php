<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Base validation
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,subscriber',
            'password' => 'required|string|min:8|confirmed',
        ];

        // If role is admin, require admin code
        if ($request->role === 'admin') {
            $rules['admin_code'] = 'required';
        }

        $validated = $request->validate($rules);

        // Verify admin code if role is admin
        if ($validated['role'] === 'admin') {
            $correctCode = env('ADMIN_REGISTRATION_CODE', 'ADMIN2024SECRET');
            
            if ($request->admin_code !== $correctCode) {
                return back()
                    ->withErrors(['admin_code' => 'Invalid admin registration code.'])
                    ->withInput();
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
        }

        return redirect('/')->with('success', 'Welcome! You can now comment on posts.');
    }
}