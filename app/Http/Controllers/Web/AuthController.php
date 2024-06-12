<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WEB\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function loginPage()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['mobile' => $request->mobile, 'password' => $request->password]))
            return redirect()->route('dashboard');

        return redirect()->back()->withErrors(['کاربری با مشخصات وارد شده موجود نمی باشد.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('dashboard');
    }
}
