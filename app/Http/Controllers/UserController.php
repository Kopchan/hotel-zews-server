<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserEditSelfRequest;
use App\Http\Resources\UserAllResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSafeResource;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function showSelf() {
        $user = User::find(request()->user()->id);
        return response(UserSafeResource::make($user));
    }
    public function editSelf(UserEditSelfRequest $request) {
        $user = User::find($request->user()->id);
        $user->update($request->validated());
        return response(null, 204);
    }
    public function showAll() {
        return response([
            'users' => UserResource::collection(User::all())
        ]);
    }
    public function show(int $id) {
        $user = User::find($id);

        if (!$user)
            throw new ApiException(404, 'User not found');

        return response(UserAllResource::make($user));
    }
    public function create(UserCreateRequest $request) {
        if ($request->role)
            $role = Role::where('code', $request->role)->first();
        else
            $role = Role::firstOrCreate(['code' => 'user']);

        $user = User::create([
            ...$request->validated(),
            'role_id' => $role->id,
        ]);
        return response(UserResource::make($user), 201);
    }
    public function edit(UserEditRequest $request, int $id) {
        $user = User::find($id);

        if (!$user)
            throw new ApiException(404, 'User not found');

        if ($request->role) {
            $role = Role::where('code', $request->role)->first();
            $user->update(['role_id' => $role->id]);
        }

        $user->update($request->validated());
        return response(null, 204);
    }
    public function delete(int $id) {
        $user = User::find($id);

        if (!$user)
            throw new ApiException(404, 'User not found');

        $user->delete();
        return response(null, 204);
    }
}
