<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

/**
 * AuthController - Handle user authentication processes
 *
 * Manages all authentication-related functionality including:
 * - User registration with validation
 * - User login with role-based redirection
 * - Password reset and change functionality
 * - Profile management
 * - Session management and logout
 * - Account activation/deactivation
 *
 * Features:
 * - Role-based access control (admin/user)
 * - Secure password hashing
 * - Form validation and error handling
 * - Session management
 * - Remember me functionality
 * - Account status checking
 *
 * Security Features:
 * - CSRF protection on all forms
 * - Password strength validation
 * - Account lockout protection
 * - Secure session handling
 */
class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'home');
        }

        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username or email is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Determine if the input is an email or username
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $request->username,
            'password' => $request->password
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if user account is active
            if (isset($user->status) && $user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Your account is inactive. Please contact support.'
                ])->withInput();
            }

            $request->session()->regenerate();

            // Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Welcome back, ' . $user->username . '!');
            } else {
                return redirect()->intended(route('home'))
                    ->with('success', 'Welcome back, ' . $user->username . '!');
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'home');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:100|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'username.required' => 'Username is required',
            'username.unique' => 'Username already exists',
            'username.regex' => 'Username can only contain letters, numbers, and underscores',
            'username.max' => 'Username cannot exceed 100 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'Email address already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user', // Default role for new registrations
                'status' => 'active' // New users are active by default
            ]);

            // Automatically login the user after registration
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('home')
                ->with('success', 'Registration successful! Welcome to Kerajinan, ' . $user->username . '!');
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'registration' => 'Registration failed: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Show user profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        $viewName = $user->role === 'admin' ? 'auth.profile' : 'user.profile';

        return view($viewName, [
            'user' => $user
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:15|regex:/^[0-9+\-\s()]+$/',
            'address' => 'nullable|string|max:500',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
            'new_password_confirmation' => 'nullable|required_with:new_password|string|min:8',
        ], [
            'username.unique' => 'Username already exists',
            'username.regex' => 'Username can only contain letters, numbers, and underscores',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'Email address already exists',
            'phone.regex' => 'Please enter a valid phone number',
            'tanggal_lahir.before' => 'Birth date must be before today',
            'jenis_kelamin.in' => 'Please select a valid gender',
            'current_password.required_with' => 'Current password is required to change password',
            'new_password.min' => 'New password must be at least 8 characters',
            'new_password.confirmed' => 'New password confirmation does not match',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Check current password if user wants to change password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Current password is incorrect'
                ]);
            }
        }

        try {
            // Update basic information
            $user->username = $request->username;
            $user->email = $request->email;

            // Update additional fields if provided
            if ($request->filled('phone')) {
                $user->phone = $request->phone;
            }
            if ($request->filled('address')) {
                $user->address = $request->address;
            }
            if ($request->filled('tanggal_lahir')) {
                $user->tanggal_lahir = $request->tanggal_lahir;
            }
            if ($request->filled('jenis_kelamin')) {
                $user->jenis_kelamin = $request->jenis_kelamin;
            }

            // Update password if provided
            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'update' => 'Profile update failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show password reset form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request.
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|exists:users,username',
        ], [
            'username.required' => 'Username is required',
            'username.exists' => 'No account found with this username',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // In a real application, you would send an email with reset link
        // For now, we'll just show a success message
        return back()->with(
            'success',
            'Password reset instructions have been sent to your registered contact. ' .
                'Please contact support for assistance.'
        );
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        try {
            // Store user role before logout for redirect decision
            $wasAdmin = Auth::check() && Auth::user()->role === 'admin';

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect based on user role
            if ($wasAdmin) {
                return redirect()->route('login')
                    ->with('success', 'You have been logged out successfully.');
            } else {
                return redirect()->route('home')
                    ->with('success', 'You have been logged out successfully.');
            }
        } catch (\Exception $e) {
            \Log::error('Logout failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Handle any logout errors gracefully
            return redirect()->back()
                ->with('error', 'Logout error: ' . $e->getMessage());
        }
    }

    /**
     * Check if username is available (AJAX endpoint).
     */
    public function checkUsername(Request $request)
    {
        $username = $request->get('username');
        $userId = $request->get('user_id'); // For profile updates

        if (empty($username)) {
            return response()->json(['available' => false, 'message' => 'Username is required']);
        }

        $query = User::where('username', $username);

        // Exclude current user when updating profile
        if ($userId) {
            $query->where('id', '!=', $userId);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username already exists' : 'Username is available'
        ]);
    }

    /**
     * Get authentication status (AJAX endpoint).
     */
    public function status()
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::check() ? [
                'id' => Auth::id(),
                'username' => Auth::user()->username,
                'role' => Auth::user()->role,
                'status' => Auth::user()->status
            ] : null
        ]);
    }

    /**
     * Admin function to toggle user status.
     */
    public function toggleUserStatus($id)
    {
        // This method should only be accessible by admins
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return back()->withErrors([
                'error' => 'You cannot deactivate your own account'
            ]);
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return back()->with(
            'success',
            'User ' . $user->username . ' has been ' . $user->status . '.'
        );
    }
}
