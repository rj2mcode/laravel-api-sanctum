<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;


class AuthController extends Controller
{

    /**
     * Register new user
     * @param App\Http\Requests\RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            if ($user) {
                return ResponseHelper::success(message: "User has been registered successfully!", data: $user, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to register user. Please try again later!", statusCode: 400);
        } catch (Exception $e) {
            Log::error('Unable to register user :' . $e->getMessage() . ' - Line No. ' . $e->getLine());
            return ResponseHelper::error(message: "Unable to register user. Please try again later! " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Login User
     * @param App\Http\Requests\LoginRequest $request
     */
    public function Login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return ResponseHelper::error(message: "Unable to Login due to invalid credentials!", statusCode: 400);
            }

            $user = Auth::user();
            $token = $user->createToken('my token')->plainTextToken;



            //$request->user()->createToken($request->token_name)
            //$token = $user()->createToken($request->token_name)->plainTextToken;

            $authUser = [
                'user' => $user,
                'token' => $token
            ];
            return ResponseHelper::success(message: "You are logged in successfully!", data: $authUser, statusCode: 200);
        } catch (Exception $e) {
            Log::error('Unable to Login :' . $e->getMessage() . ' - Line No. ' . $e->getLine());
            return ResponseHelper::error(message: "Unable to Login. Please try again later! " . $e->getMessage(), statusCode: 500);
        }
    }


    /**
     * Fetch User
     * @param NA
     * @return JsonResponse
     */
    public function userProfile()
    {
        try {
            $user = Auth::user();
            if ($user) {
                return ResponseHelper::success(message: "User Profile fetched successfully!", data: $user, statusCode: 200);
            }
            return ResponseHelper::error(message: "Unable to fetch user profile due to invalid token!", statusCode: 400);
        } catch (Exception $e) {
            Log::error('Unable to Login :' . $e->getMessage() . ' - Line No. ' . $e->getLine());
            return ResponseHelper::error(message: "Unable to fetch user profile. Please try again later! " . $e->getMessage(), statusCode: 500);
        }
    }


    /**
     * Login User
     * @param NA
     * @return JsonResponse
     */
    public function userLogout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(message: "User logged out successfully!", statusCode: 200);
            }
            return ResponseHelper::error(message: "Unable to log out user due to invalid token!", statusCode: 400);
        } catch (Exception $e) {
            Log::error('Unable to Login :' . $e->getMessage() . ' - Line No. ' . $e->getLine());
            return ResponseHelper::error(message: "Unable to log out user. Please try again later! " . $e->getMessage(), statusCode: 500);
        }
    }
}
