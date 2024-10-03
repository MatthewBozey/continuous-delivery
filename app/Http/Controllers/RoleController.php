<?php

namespace App\Http\Controllers;

use App\Events\Role\RoleCreatedEvent;
use App\Events\Role\RoleDeletedEvent;
use App\Events\Role\RoleUpdatedEvent;
use App\Events\User\UserUpdatePermissionEvent;
use App\Http\Requests\Role\RoleCreateRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role list', ['only' => ['index']]);
        $this->middleware('permission:role show', ['only' => ['show']]);
        $this->middleware('permission:role create', ['only' => ['store']]);
        $this->middleware('permission:role edit', ['only' => ['update']]);
        $this->middleware('permission:role delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => Role::all()]);
    }

    public function store(RoleCreateRequest $request): JsonResponse
    {
        $data = Role::create(['name' => $request->get('name'), 'title' => $request->get('title')]);
        $data->syncPermissions($request->get('permissions'));
        broadcast(new RoleCreatedEvent($data));

        return response()->json(compact('data'));
    }

    /**
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $data = Role::findById($id);
        $data['permissions'] = $data->permissions()->pluck('name');

        return response()->json(compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Role  $role
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $data = Role::findById($id);
        if (! $request->user()->hasRole('super-admin') && $request->get('name') === 'super-admin') {
            throw new HttpResponseException(response()->json(['message' => 'Нельзя редактировать роль Главного Администратора'], 400));
        }
        //        $data->update($request->only());
        $data->syncPermissions($request->get('permissions'));

        //        broadcast(new RoleUpdatedEvent($data))->toOthers();
        /*User::role($data->name)->get()->each(static function ($user) use ($data) {
            broadcast(new UserUpdatePermissionEvent($user->user_id, $data->permissions()->pluck("name")));
        });*/
        return response()->json(compact('data'));
    }

    /**
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $data = Role::findById($id);
        $data->delete();
        broadcast(new RoleDeletedEvent($data));

        return response()->json(compact('data'));
    }
}
