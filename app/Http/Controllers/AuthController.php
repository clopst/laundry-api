<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login success'
            ]);
        }

        return response()->json([
            'message' => 'Invalid username or password'
        ], 401);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout success'
        ]);
    }

    /**
     * Get the logged in user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
        return $request->user();
    }

    /**
     * Update profile for logged in user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'nullable|string',
            'username' => 'nullable|string|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'avatar_path' => 'nullable|image'
        ]);

        $user->saveDataWithAvatar($request->all());

        return $user;
    }

    /**
     * Update profile for logged in user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'string|required|min:8',
            'new_password' => 'string|required|min:8|confirmed'
        ]);

        $isPasswordCorrect = Hash::check($request->current_password, $user->password);

        if ($isPasswordCorrect) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return $user;
        }

        return response()->json([
            'message' => 'Invalid current password'
        ], 403);
    }
}
