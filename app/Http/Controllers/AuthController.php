<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Remove the constructor with middleware
    // This is causing the error
    
    public function showLogin()
    {
        return view('auth.login', [
            'meta_title' => 'Login - Larkon',
            'meta_desc' => 'A Management system that helps businesses keep track of their products...',
            'meta_image' => url('images/favicon.ico'),
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Welcome back! You have successfully logged in.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function showRegister()
    {
        return view('auth.register', [
            'meta_title' => 'Register - Larkon',
            'meta_desc' => 'A Management system that helps businesses keep track of their products...',
            'meta_image' => url('images/favicon.ico'),
        ]);
    }

    public function register(Request $request)
    {
        // Log the registration attempt
        Log::info('Registration attempt', ['request' => $request->all()]);
        
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|min:2',
                'last_name' => 'required|string|min:2',
                'email' => 'required|string|email|unique:users',
                'phone' => 'required|string|min:10|max:12',
                'business_name' => 'required|string|min:3',
                'business_type' => 'required|string',
                'business_address' => 'required|string|min:10',
                'username' => 'required|string|min:4|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'terms_accepted' => 'required',
            ], [
                'terms_accepted.required' => 'You must accept the Terms of Service and Privacy Policy',
            ]);

            if ($validator->fails()) {
                Log::warning('Registration validation failed', ['errors' => $validator->errors()]);
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'business_address' => $request->business_address,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);
            
            // Instead of logging in the user, redirect to login page with success message
            return redirect()->route('login')->with('success', 'Account created successfully! Please login to continue.');
        } catch (\Exception $e) {
            Log::error('Registration error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'An error occurred during registration. Please try again.')
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }
}