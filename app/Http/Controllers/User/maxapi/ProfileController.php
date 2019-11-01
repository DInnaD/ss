<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  IndexRequest  $request
     * @return JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->validate($request, [
            'name'	=>	'required',
            'email' =>  [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::user()->id),
            ],
            'image'	=>	'nullable|image'
        ]);

        $user = Auth::user();
        $user->edit($request->all());
        $user->generatePassword($request->get('password'));
        $user->uploadAvatar($request->file('avatar'));

        return new UserResource($user);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user->delete();

        return response()->json(['success' => true], 200);
    }
}
