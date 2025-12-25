<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['museums', 'museums as deleted_museums_count' => function ($query) {
            $query->onlyTrashed();
        }])->paginate(10);

        return view('users.index', compact('users'));
    }

    public function show($identifier)
    {
        if (is_numeric($identifier)) {
            $user = User::findOrFail($identifier);
        } else {
            $user = User::whereRaw('LOWER(name) = LOWER(?)', [$identifier])->firstOrFail();
        }
        
        $museums = $user->museums()
            ->whereNull('deleted_at')
            ->with('popovers')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('users.show', compact('user', 'museums'));
    }
}