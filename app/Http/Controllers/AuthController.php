<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
 
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function getTokenStatus(): JsonResponse
    {
        $payload = auth()->getPayload();
        $tokenStatus = collect([
            'expired_at' => $payload->get('exp'),
            'not_before_at' => $payload->get('nbf'),
            'issued_at' => $payload->get('iat'),
        ])
        ->map(fn($value) => Carbon::createFromTimestamp($value)->toIso8601ZuluString());

        return response()->json($tokenStatus);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'user' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ]);
    }

    protected function createNewToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
