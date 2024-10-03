<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionSection\PermissionSectionCreateRequest;
use App\Http\Requests\PermissionSection\PermissionSectionUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\PermissionSection;
use Illuminate\Http\Request;

class PermissionSectionController extends Controller
{
    public function index(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(PermissionSection::orderBy('title')->get());
    }

    /**
     * @throws \Throwable
     */
    public function store(PermissionSectionCreateRequest $request): ApiSuccessResponse
    {
        $permissionSection = new PermissionSection($request->validated());
        $permissionSection->saveOrFail();

        return new ApiSuccessResponse($permissionSection);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(PermissionSection::find($id));
    }

    public function update(PermissionSectionUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $permissionSection = PermissionSection::findOrFail($id);
        $permissionSection->update($request->validated());

        return new ApiSuccessResponse($permissionSection);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $permissionSection = PermissionSection::findOrFail($id);

        return new ApiSuccessResponse($permissionSection->delete());
    }
}
