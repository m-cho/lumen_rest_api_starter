<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\User;

class UserController extends Controller
{
    public function me(Request $req) {
        return $req->user();
    }

    public function register(Request $req) {
        try {
            $this->validate($req, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:4'
            ]);
        } catch(ValidationException $err) {
            return response([
                'message' => $err->getMessage(),
                'data' => $err->getResponse()->original
            ], 422);
        }

        $user = new User();
        $user->first_name = $req->input('first_name');
        $user->last_name = $req->input('last_name');
        $user->email = $req->input('email');
        $user->password = User::cryptPassword($req->input('password'));
        $user->save();
        return $user;
    }

    public function login(Request $req) {
        try {
            $this->validate($req, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
        } catch(ValidationException $err) {
            return response([
                'message' => $err->getMessage(),
                'data' => $err->getResponse()->original
            ], 422);
        }

        $user = User::where('email', $req->input('email'))
            ->where('password', User::cryptPassword($req->input('password')))
            ->first();
        if(!$user) return response(['message' => 'User not found'], 401);

        return $user->generateAccessToken($req);
    }
}
