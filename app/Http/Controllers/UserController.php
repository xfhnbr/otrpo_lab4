<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount(['museums' => function($query) {
            $query->whereNull('deleted_at');
        }])
        ->orderBy('name')
        ->paginate(15);
        
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $museums = $user->museums()
            ->whereNull('deleted_at')
            ->with('popovers')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('users.show', compact('user', 'museums'));
    }
}