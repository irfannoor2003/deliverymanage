<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request->pin);

        if (!$user) {
            return back()->with('error', 'Invalid PIN')->withInput();
        }

        return $this->authService->redirectBasedOnRole($user->role);
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('login');
    }
}
