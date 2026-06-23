<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['posts', 'comments'])
            ->latest()
            ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "User '{$userName}' has been deleted successfully!");
    }

    public function toggleRole(User $user)
    {
        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role!');
        }

        // Toggle role
        $user->role = $user->role === 'admin' ? 'subscriber' : 'admin';
        $user->save();

        return back()->with('success', "User role updated to {$user->role}!");
    }
}