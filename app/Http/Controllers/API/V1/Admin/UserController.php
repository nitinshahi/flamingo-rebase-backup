<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\AdminTypeEnum;
use App\Http\Controllers\ResponseController;
use App\Http\Requests\API\Actions\BulkDelete;
use App\Http\Requests\API\Actions\BulkPermaDelete;
use App\Http\Requests\API\Actions\BulkRestore;
use App\Http\Requests\API\Admin\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class UserController extends ResponseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getLoggedInUser(Request $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        return $this->jsonResponse(data: $data, message: 'user data');
    }

    public function index(Request $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        $data['list'] = UserResource::collection(User::withTrashed()->get());
        return $this->jsonResponse(data: $data, message: 'list fetched');
    }

    protected function store(UserStoreRequest $request)
    {
        $user = auth()->guard('api')->user();
        if ($user->user_type->getPrecedence() > 1) {
            return $this->jsonResponse(data: $request->toArray(), message: 'not allowed', code: 403);
        }
        if (!AdminTypeEnum::tryFrom($request->user_type) instanceof AdminTypeEnum) {
            throw ValidationException::withMessages(['user_type' => 'Invalid User Type']);
        }
        $newUser = User::create($request->toArray());
        $data['user'] = new UserResource($user);
        return $this->jsonResponse(data: $data, message: 'user added succesfully');
    }

    protected function update(UserUpdateRequest $request, $id)
    {
        $user = auth()->guard('api')->user();
        $updateUser = User::findOrFail($id);
        $data['user'] = new UserResource($user);
        if (!($user->user_type->getPrecedence() == 1 || $user->id != $id)) {
            return $this->jsonResponse(data: $data, message: 'not allowed', code: 403);
        }
        if ($user->user_type->getPrecedence() == 1) {
            $updateUser->update($request->toArray());
        } else {
            $updateUser->update($request->only(['name', 'email', 'password']));
        }
        $data['user'] = new UserResource(User::find($id));
        // $updateUser->update();
        return $this->jsonResponse(data: $data, message: 'user updated successfully');
    }

    protected function delete(Request $reuqest, $id)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        $deleteUser = User::find($id);
        if (!$deleteUser) {
            return $this->jsonResponse(data: $data, message: 'user not found', code: 422);
        }
        $deleteUser->delete();
        $data['list'] = UserResource::collection(User::all());
        return $this->jsonResponse(data: $data, message: 'user deleted');
    }

    protected function bulkDelete(BulkDelete $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        if (count($request->bulk) > User::count()) {
            return $this->jsonResponse(data: $data, message: 'bulk array not valid', code: 422);
        }
        $softDelete = User::whereIn('id', $request->bulk)->where('id', '!=', $user->id)->get();
        if ($softDelete->count() < 1) {
            return $this->jsonResponse(data: $data, message: 'bulk array not valid', code: 422);
        }
        foreach ($softDelete as $delUser) {
            $delUser->tokens->each(fn ($token) => $token->delete());
            $delUser->delete();
        }
        $data['list'] = UserResource::collection(User::withTrashed()->get());
        return $this->jsonResponse(data: $data, message: 'users deleted successfully');
    }

    protected function bulkRestore(BulkRestore $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        $restoreUsers = User::onlyTrashed()->whereIn('id', $request->bulk)->get();
        $restoreUsers->each(fn ($user) => $user->restore());
        $data['list'] = UserResource::collection(User::withTrashed()->get());
        return $this->jsonResponse(data: $data, message: 'users restored succesfully');
    }

    protected function bulkPermaDelete(BulkPermaDelete $request)
    {
        $user = auth()->guard('api')->user();
        $data['user'] = new UserResource($user);
        $permaUsers = User::onlyTrashed()->whereIn('id', $request->bulk)->get();
        if ($permaUsers->count() < 1) {
            return $this->jsonResponse(data: $data, message: 'bulk array not valid', code: 422);
        }
        $permaUsers->each(fn ($user) => $user->forceDelete());
        $data['list'] = UserResource::collection(User::withTrashed()->get());
        return $this->jsonResponse(data: $data, message: 'users permanently deleted successfully');
    }
}
