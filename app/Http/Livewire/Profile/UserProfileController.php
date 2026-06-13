<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show', [
            'request' => $request,
            'user' => $request->user(),
        ])->layout('layouts.app-pep-auth');
    }
}
