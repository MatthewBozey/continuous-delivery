<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'resetPassword', 'refresh']]);
        $this->guard = 'api';
    }

    public function login(): JsonResponse
    {
        $credentials = request(['username', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Неверный логин или пароль'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth($this->guard)->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @throws JWTException
     */
    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::parseToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     *
     * @throws JWTException
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('username', $request->get('username'))->first();
        if (! $user) {
            throw new HttpResponseException(response()->json(['message' => 'Данный пользователь не зарегистрирован в системе'], 422));
        }
        if (! $user->email) {
            throw new HttpResponseException(response()->json(['message' => 'Для данного аккаунта не привязана электронная почта'], 422));
        } else {
            Password::sendResetLink(['email' => $user->email]);
            $message = 'На вашу электронную почту было отправлено письмо для сброса пароля';

            return response()->json(compact('message'));
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'access_token' => $token,
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'roles' => $user->getRoleNames(),
                'token_type' => 'bearer',
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
            ]);
        }
    }

    public function showLogin()
    {
        return view('login');
    }
}
