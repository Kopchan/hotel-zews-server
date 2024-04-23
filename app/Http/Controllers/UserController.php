<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserEditSelfRequest;
use App\Http\Resources\UserSafeResource;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function showAll() {
        return response([
            'users' => UserSafeResource::collection(User::all())
        ]);
    }
    public function show(int $id) {
        $user = User::find($id);

        if (!$user)
            throw new ApiException(404, 'User not found');

        return response($user);
    }
    public function create(UserCreateRequest $request) {
        $role = Role::where('code', $request->input($request->role, 'user'))->first();
        $user = User::create([
            ...$request->all(),
            'role_id' => $role->id,
        ]);
        return response(null, 204);
    }
    public function edit(UserEditRequest $request, int $id) {
        $user = User::find($id);

        if (!$user)
            throw new ApiException(404, 'User not found');

        $role = Role::where('code', $request->input($request->role, 'user'))->first();
        $user->update([
            ...$request->all(),
            'role_id' => $role->id,
        ]);
        return response(null, 204);
    }
    public function editSelf(UserEditSelfRequest $request) {
        $user = User::find($request->user()->id);

        return response(null, 204);
    }
    public function destroy(int $id) {
        $user = User::find($id);

        if (!$user)
            throw new ApiException(404, 'User not found');

        $user->delete();
        return response(null, 204);
    }
}
