<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use  App\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Show all Users.
     *
     * @return Response
     */
    public function index(){
        $data = User::all();
        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * Show a User.
     *
     * @return Response
     */
    public function singleUser($id){
        try{
            $user = User::findOrFail($id);
            return response()->json(['user' => $user], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'User not found'], 404);
        }
    }

    /**
     * Update a User.
     *
     * @return Response
     */
    public function updateUser(Request $request, $id){
        try{
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();
            return response()->json([
                'User' => $user,
                'Message' => 'User has been created',
                'Edited by' => Auth::user()
            ],200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'User not found'], 404);
        }
    }
}
