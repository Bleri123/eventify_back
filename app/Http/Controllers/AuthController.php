<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:50',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'gender' => 'required|in:male,female',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password_hash' => Hash::make(request('password')),
            'phone_number' => request('phone_number'),
            'city' => request('city'),
            'address' => request('address'),
            'gender' => request('gender'),
            'role' => 'user',
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully. You can now log in with your email and password.',
            'user' => $user
        ], 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Send password reset link.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = request('email');
        $token = Str::random(64);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Get frontend URL from env or use default
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
        $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($email);

        // Send email
        try {
            $mailDriver = config('mail.default');
            \Log::info('Sending password reset email', [
                'email' => $email,
                'mail_driver' => $mailDriver,
                'reset_url' => $resetUrl
            ]);

            if ($mailDriver === 'log') {
                // If using log driver, just log it
                \Log::info('Password Reset Email (Log Driver)', [
                    'to' => $email,
                    'reset_url' => $resetUrl,
                    'subject' => 'Reset Your Password - Eventify Cinema'
                ]);
            } else {
                // Send actual email
                Mail::send([], [], function ($message) use ($email, $resetUrl) {
                    $message->to($email)
                        ->subject('Reset Your Password - Eventify Cinema')
                        ->html('
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                        margin: 0;
                                        padding: 0;
                                        background-color: #f4f4f4;
                                    }
                                    .container {
                                        max-width: 600px;
                                        margin: 40px auto;
                                        background-color: #ffffff;
                                        border-radius: 8px;
                                        overflow: hidden;
                                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                    }
                                    .header {
                                        background-color: #1a1a1a;
                                        color: #ffffff;
                                        padding: 30px 20px;
                                        text-align: center;
                                    }
                                    .header h1 {
                                        margin: 0;
                                        font-size: 28px;
                                        font-weight: bold;
                                    }
                                    .content {
                                        background-color: #ffffff;
                                        padding: 40px 30px;
                                        text-align: center;
                                    }
                                    .content h2 {
                                        color: #333;
                                        font-size: 24px;
                                        margin: 0 0 20px 0;
                                        font-weight: 600;
                                    }
                                    .content p {
                                        color: #666;
                                        font-size: 16px;
                                        margin: 15px 0;
                                        line-height: 1.6;
                                    }
                                    .button-container {
                                        margin: 30px 0;
                                    }
                                    .button {
                                        display: inline-block;
                                        padding: 16px 40px;
                                        background-color: #DC3545;
                                        color: #ffffff !important;
                                        text-decoration: none !important;
                                        border-radius: 6px;
                                        font-size: 16px;
                                        font-weight: 600;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                        box-shadow: 0 4px 6px rgba(29, 81, 226, 0.3);
                                        transition: background-color 0.3s;
                                    }
                                    .button:hover {
                                        background-color: #0d2acc;
                                        color: #ffffff !important;
                                    }
                                    a.button {
                                        color: #ffffff !important;
                                    }
                                    .expiry-notice {
                                        background-color: #fff3cd;
                                        border-left: 4px solid #ffc107;
                                        padding: 15px;
                                        margin: 25px 0;
                                        border-radius: 4px;
                                        text-align: left;
                                    }
                                    .expiry-notice p {
                                        margin: 5px 0;
                                        color: #856404;
                                        font-size: 14px;
                                    }
                                    .security-notice {
                                        margin-top: 30px;
                                        padding-top: 20px;
                                        border-top: 1px solid #e0e0e0;
                                    }
                                    .security-notice p {
                                        color: #999;
                                        font-size: 13px;
                                        margin: 5px 0;
                                    }
                                    .footer {
                                        text-align: center;
                                        padding: 25px 20px;
                                        background-color: #f9f9f9;
                                        color: #666;
                                        font-size: 12px;
                                        border-top: 1px solid #e0e0e0;
                                    }
                                    .footer p {
                                        margin: 5px 0;
                                        color: #999;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <div class="header">
                                        <h1>Eventify Cinema</h1>
                                    </div>
                                    <div class="content">
                                        <h2>Reset Your Password</h2>
                                        <p>We received a request to reset your password for your Eventify Cinema account.</p>
                                        <p>To complete the password reset process, please click the button below. This will take you to a secure page where you can create a new password.</p>

                                        <div class="button-container">
                                            <a href="' . $resetUrl . '" class="button" style="color: #ffffff !important; text-decoration: none !important;">Reset Password</a>
                                        </div>

                                        <div class="expiry-notice">
                                            <p><strong>⏰ Important:</strong> This password reset link will expire in 60 minutes for security reasons.</p>
                                            <p>If you did not request this password reset, you can safely ignore this email and your password will remain unchanged.</p>
                                        </div>

                                        <div class="security-notice">
                                            <p><strong>Security Tip:</strong> For your protection, never share this link with anyone. Eventify Cinema will never ask for your password via email.</p>
                                        </div>
                                    </div>
                                    <div class="footer">
                                        <p>&copy; ' . date('Y') . ' Eventify Cinema. All rights reserved.</p>
                                        <p>This is an automated email, please do not reply.</p>
                                    </div>
                                </div>
                            </body>
                            </html>
                        ');
                });
            }

            $response = [
                'success' => true,
                'message' => 'Password reset link has been sent to your email address.'
            ];

            // If using log driver, include the link in response for testing
            if ($mailDriver === 'log') {
                $response['reset_link'] = $resetUrl;
                $response['message'] = 'Password reset link generated. Check logs or use the link below.';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $email
            ]);

            return response()->json([
                'error' => 'Failed to send email: ' . $e->getMessage() . '. Please check your email configuration in .env file.'
            ], 500);
        }
    }

    /**
     * Reset password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = request('email');
        $token = request('token');
        $password = request('password');

        // Check if token exists and is valid
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'error' => 'Invalid or expired reset token.'
            ], 400);
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'error' => 'Reset token has expired. Please request a new one.'
            ], 400);
        }

        // Verify token
        if (!Hash::check($token, $passwordReset->token)) {
            return response()->json([
                'error' => 'Invalid reset token.'
            ], 400);
        }

        // Update password
        $user = User::where('email', $email)->first();
        $user->password_hash = Hash::make($password);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully. You can now login with your new password.'
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}