<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


use App\Http\Requests\Api\Auth\AuthApiRequest;

class AuthApiController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {}
    public function auth(AuthApiRequest $request) {

        $user = $this->userRepository->findByEmail($request->email);

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

         $token = $user->createToken($request->device_name)->plainTextToken;

         return response()->json([ 'token' => $token ]);
    }
}
