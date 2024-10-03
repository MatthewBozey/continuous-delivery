<?php

namespace App\Http\Controllers;

use App\Events\UserDataUpdatedEvent;
use App\Http\Requests\User\ResetUserPasswordRequest;
use App\Http\Requests\User\SaveUserSettingRequest;
use App\Http\Requests\User\UpdatingUserInfoRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserDeleteAvatarRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use App\Notifications\UserPasswordResetNotification;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pusher\ApiErrorException;
use Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users list', ['only' => ['index']]);
        $this->middleware('permission:users show', ['only' => ['show']]);
        $this->middleware('permission:users create', ['only' => ['store']]);
        $this->middleware('permission:users edit', ['only' => ['update']]);
        $this->middleware('permission:users delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): ApiSuccessResponse
    {
        $data = User::orderBy('user_id')->with(['permissions', 'roles'])->get();

        return new ApiSuccessResponse($data, Response::HTTP_OK);
    }

    public function store(UserCreateRequest $request): ApiSuccessResponse
    {
        $user = User::create($request->validated());
        $user->save();

        return new ApiSuccessResponse($user, Response::HTTP_CREATED);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        $data = User::with(['permissions', 'roles'])->find($id);

        return new ApiSuccessResponse($data, Response::HTTP_OK);
    }

    public function update(UserUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $user = User::findOrFail($id);

        tap($user, function (User $u) use ($request) {
            if (! $request->user('web')
                ->hasRole('super-admin') && collect($request->get('roles'))->contains('super-admin')) {
                throw new HttpResponseException(response()->json(['message' => 'Нельзя выдать роль Главного Администратора'],
                    400));
            }

            $u->syncRoles($request->get('roles'));
            $u->syncPermissions($request->get('permissions'));
            $u->fill($request->all());
        });

        $user->save();
        broadcast(new UserDataUpdatedEvent($user));

        return new ApiSuccessResponse($user);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return new ApiSuccessResponse($user, Response::HTTP_NO_CONTENT);
    }

    public function getUserInfo(Request $request): ApiSuccessResponse
    {
        $data = User::find($request->get('user_id'));

        return new ApiSuccessResponse($data);
    }

    public function updateUserInfo(UpdatingUserInfoRequest $request): ApiSuccessResponse
    {
        $data = $request->validated();
        $user = User::findOrFail($data['user_id']);
        if ($request->hasFile('avatar')) {
            $avatarsFolderPath = '/avatars/'.$data['user_id'];

            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            $path = $request->file('avatar')->store($avatarsFolderPath, 'public');
            $data['avatar'] = $path;
            $data['avatar_url'] = Storage::url($path);
        }
        $user->update($data);

        return new ApiSuccessResponse($user);
    }

    /**
     * @throws ApiErrorException
     */
    public function deleteUserAvatar(UserDeleteAvatarRequest $request): ApiSuccessResponse
    {
        $user = User::findOrFail($request->get('user_id'));
        if ($user->user_id !== $request->user()->user_id || $request->user()->can('user delete_avatar_for_administrator')) {
            throw new ApiErrorException('Ошибка: У вас нет прав на выполнение этого действия.');
        }

        if (is_null($user->avatar)) {
            return new ApiSuccessResponse([], ResponseAlias::HTTP_NOT_MODIFIED);
        }

        $user->update(['avatar' => null, 'avatar_url' => null, 'avatar_at' => null]);

        return new ApiSuccessResponse($user, ResponseAlias::HTTP_OK);
    }

    /**
     * @throws JWTException
     */
    public function resetUserPassword(ResetUserPasswordRequest $request)
    {
        $data = $request->validated();

        $user = User::findOrFail($data['user_id']);

        $user->password = $data['newPassword'];
        $token = JWTAuth::refresh(JWTAuth::parseToken());
        $user->notify(new UserPasswordResetNotification());
        //        $user->save();

        return new ApiSuccessResponse(compact('token'), ResponseAlias::HTTP_OK);
    }

    public function saveUserSettings(SaveUserSettingRequest $request)
    {
        $data = $request->validated();
        $user = User::findOrFail($data['user_id']);
        $user->{$data['code']} = $data['value'];
        $user->save();

        return new ApiSuccessResponse($user);
    }
}
