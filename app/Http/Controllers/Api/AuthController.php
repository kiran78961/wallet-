<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Wallet;
use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Mail\PasswordResetOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{

    // ==========================================
    // SIGNUP
    // ==========================================

    public function signup(Request $request)
    {
        // Validation
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]
        );


        // Validation error
        if ($validateUser->fails()) {

            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()->all()
            ], 422);
        }


        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

            // Every signup user will be normal user
            'role' => 'user',
        ]);


        // Create wallet
        $user->wallet()->create([
            'balance' => 0.00
        ]);


        // Response
        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }



    // ==========================================
    // LOGIN
    // ==========================================

    public function login(Request $request)
    {
        // Validation
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );


        // Validation error
        if ($validateUser->fails()) {

            return response()->json([
                'status' => false,
                'message' => 'Login validation failed',
                'errors' => $validateUser->errors()->all()
            ], 422);
        }


        // Check email and password
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {


            // Get logged-in user
            $authUser = Auth::user();


            // Create Sanctum token
            $token = $authUser
                ->createToken('api token')
                ->plainTextToken;



            // ==========================================
            // ADMIN LOGIN
            // ==========================================

            if ($authUser->role == 'admin') {

                return response()->json([
                    'status' => true,
                    'message' => 'Admin login successfully',
                    'tokens' => $token,
                    'token_type' => 'bearer',
                    'role' => 'admin',
                    'redirect' => '/admin/dashboard',
                    'user' => $authUser
                ], 200);
            }



            // ==========================================
            // USER LOGIN
            // ==========================================

            if ($authUser->role == 'user') {

                return response()->json([
                    'status' => true,
                    'message' => 'User login successfully',
                    'tokens' => $token,
                    'token_type' => 'bearer',
                    'role' => 'user',
                    'redirect' => '/user/dashboard',
                    'user' => $authUser
                ], 200);
            }



            // ==========================================
            // INVALID ROLE
            // ==========================================

            return response()->json([
                'status' => false,
                'message' => 'Invalid user role'
            ], 403);
        }



        // ==========================================
        // WRONG EMAIL / PASSWORD
        // ==========================================

        return response()->json([
            'status' => false,
            'message' => 'Email and password do not match'
        ], 401);
    }



    // ==========================================
    // LOGOUT
    // ==========================================

    public function logout(Request $request)
    {
        // Get logged-in user
        $user = $request->user();


        // Delete current Sanctum token
        $user->currentAccessToken()->delete();


        return response()->json([
            'status' => true,
            'message' => 'You logout successfully'
        ], 200);
    }


    public function forgotPassword (Request $request){

        $validator=Validator::make($request->all(), [
        'email' => 'required|email',
    ]);

    if($validator->fails()){
         return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }
         

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Email not found',
        ], 404);
    }


    // Delete old OTP
    PasswordResetOtp::where('email', $request->email)->delete();

    // Generate 6 digit OTP
    $otp = random_int(100000, 999999);

       // Save OTP
    PasswordResetOtp::create([
        'email' => $request->email,
        'otp' => $otp,
        'expires_at' => now()->addMinutes(20),
    ]);

     Mail::to($request->email)
        ->send(new PasswordResetOtpMail($otp));

    return response()->json([
        'status' => true,
        'message' => 'OTP sent successfully to your email',
    ]);

  }



  public function verifyResetOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'otp' => 'required|digits:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }

    $resetOtp = PasswordResetOtp::where('email', $request->email)
        ->where('otp', $request->otp)
        ->first();

    if (!$resetOtp) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP',
        ], 400);
    }

    if (now()->greaterThan($resetOtp->expires_at)) {

        $resetOtp->delete();

        return response()->json([
            'status' => false,
            'message' => 'OTP has expired',
        ], 400);
    }

    return response()->json([
        'status' => true,
        'message' => 'OTP verified successfully',
    ]);
}



public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'otp' => 'required|digits:6',
        'password' => 'required|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }

    $resetOtp = PasswordResetOtp::where('email', $request->email)
        ->where('otp', $request->otp)
        ->first();

    if (!$resetOtp) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP',
        ], 400);
    }

    if (now()->greaterThan($resetOtp->expires_at)) {

        $resetOtp->delete();

        return response()->json([
            'status' => false,
            'message' => 'OTP has expired',
        ], 400);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found',
        ], 404);
    }

    // Change password
    $user->password = Hash::make($request->password);
    $user->save();

    // Delete used OTP
    $resetOtp->delete();

    // Optional: logout all existing Sanctum tokens
    $user->tokens()->delete();

    return response()->json([
        'status' => true,
        'message' => 'Password reset successfully',
    ]);
}



}