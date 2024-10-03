<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionSection\PermissionSectionCreateRequest;
use App\Http\Requests\UpdatePackageColor\UpdatePackageColorCreateRequest;
use App\Http\Requests\UpdatePackageColor\UpdatePackageColorUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\UpdatePackageColor;
use Illuminate\Http\Request;

class UpdatePackageColorController extends Controller
{
    public function index(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(UpdatePackageColor::orderBy('id')->get());
    }

    /**
     * @param  PermissionSectionCreateRequest  $request
     *
     * @throws \Throwable
     */
    public function store(UpdatePackageColorCreateRequest $request): ApiSuccessResponse
    {
        $updatePackageColor = new UpdatePackageColor(array_merge($request->validated(), ['author' => auth()->user()->username]));
        $updatePackageColor->saveOrFail();

        return new ApiSuccessResponse($updatePackageColor);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(UpdatePackageColor::find($id));
    }

    public function update(UpdatePackageColorUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $updatePackageColor = UpdatePackageColor::findOrFail($id);
        $updatePackageColor->update($request->validated());

        return new ApiSuccessResponse($updatePackageColor);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $updatePackageColor = UpdatePackageColor::findOrFail($id);

        return new ApiSuccessResponse($updatePackageColor->delete());
    }
}
