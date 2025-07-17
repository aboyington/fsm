<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('auth_token')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'title' => 'Login - FSM Platform'
        ]);
    }

    public function dashboard()
    {
        // This is a placeholder - we'll create a proper dashboard later
        return view('dashboard/index', [
            'title' => 'Dashboard - FSM Platform'
        ]);
    }

    public function setSession()
    {
        $token = $this->request->getPost('token');
        if ($token) {
            session()->set('auth_token', $token);
        }
        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        // Get the current session token
        $token = session()->get('auth_token');
        
        if ($token) {
            // Remove the specific session from user_sessions table
            $userSessionModel = new \App\Models\UserSessionModel();
            $userSessionModel->removeSession($token);
        }
        
        // Clear the session
        session()->destroy();
        return redirect()->to('/login')->with('message', 'You have been logged out successfully.');
    }
}
