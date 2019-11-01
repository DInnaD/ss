<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserResource;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use JWTAuth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/task';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:12',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'role' => 'user',
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'father_name' => $request->get('by_father_name'),
            'code_name' => $request->get('code_name'),
        ]);




        $token = JWTAuth::fromUser($user);

        return response()->json(['success' => true, 'data' => new UserResource($user),'token' => $token]);
    }
}