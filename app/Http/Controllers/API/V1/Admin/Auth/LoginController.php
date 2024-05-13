<?php

namespace App\Http\Controllers\API\V1\Admin\Auth;

use App\Http\Controllers\ResponseController;
use App\Http\Requests\API\Admin\Auth\LoginRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends ResponseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $data['email'] = $request->email;
        if (!$user) {
            return $this->jsonResponse($data, 'mismatched credentials', 422);
        }
        if (Hash::check($request->password, $user->password)) {
            // generate token
            $user->tokens->each(fn ($token) => $token->delete());
            $token = $user->createToken('AdminToken')->accessToken;
            $data['token'] = $token;
            $data['user'] = new UserResource($user);
            return $this->jsonResponse($data, 'logged in successfully', 200);
        } else {
            return $this->jsonResponse($data, 'mismatched credentials', 422);
        }
    }
}
