<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // Check for session expired message
        $message = $request->session('message');
        
        return view('client.home', compact('message'));
    }
}
