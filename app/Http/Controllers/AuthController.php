<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Resources\User\UserSafeResource;
use App\Models\Role;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $roleId = Role::firstOrCreate(['code' => 'user'])->id;

        $user = User::create([
            ...$request->validated(),
            'role_id' => $roleId
        ]);
        $token = $user->generateToken();
        return response([
            'token' => $token,
            'userdata' => UserSafeResource::make($user),
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = request(['phone', 'password']);
        if (!Auth::attempt($credentials))
            throw new ApiException(401, 'Invalid credentials');

        $user = Auth::user();

        $token = $user->generateToken();
        return response([
            'token' => $token,
            'userdata' => UserSafeResource::make($user),
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        Token::where('value', $token)->delete();
        Cache::delete("user:token=$token");

        return response(null, 204);
    }
}
