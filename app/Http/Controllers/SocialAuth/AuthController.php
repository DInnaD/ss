<?php

namespace App\Http\Controllers\SocialAuth;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\SocialLoginUserRequest;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);

    }
//fb default
    public function socialLogin(SocialLoginUserRequest $request, User $users)
    {
        try {
            if ($user = User::where('social_id', $request->social_id)->first()) {
                $token = JWTAuth::fromUser($user);
            } else {
                //$newUser = true;
                $user = $users->createUser($request->all());
                $token = JWTAuth::fromUser($user);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(['type'=> 'login','success' => true, 'token' => $token, 'data' =>$user]);
    }
    

    // public function socialLoginGoogle(SocialLoginUserRequest $request, User $users)
    // {
    //     try {
    //         if ($user = User::where('social_google_id', $request->social_id)->first()) {
    //             $token = JWTAuth::fromUser($user);
    //         } else {
    //             //$newUser = true;
    //             $user = $users->createUser($request->all());
    //             $token = JWTAuth::fromUser($user);
    //         }
    //     } catch (JWTException $e) {
    //         return response()->json(['error' => 'could_not_create_token'], 500);
    //     }

    //     return response()->json(['type'=> 'login','success' => true, 'token' => $token, 'data' =>$user]);
    // }

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
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
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
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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

