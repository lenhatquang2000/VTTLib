<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Get latest users for dashboard
        $users = \App\Models\User::with('roles')->latest()->take(10)->get();
        return view('admin.dashboard', compact('users'));
    }
}
