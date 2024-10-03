<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageType\PackageTypeCreateRequest;
use App\Http\Requests\PackageType\PackageTypeUpdateRequest;
use App\Http\Requests\PermissionSection\PermissionSectionCreateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\PackageType;
use Illuminate\Http\Request;

class PackageTypeController extends Controller
{
    public function index(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(PackageType::orderBy('package_name')->get());
    }

    /**
     * @param  PermissionSectionCreateRequest  $request
     *
     * @throws \Throwable
     */
    public function store(PackageTypeCreateRequest $request): ApiSuccessResponse
    {
        $packageType = new PackageType($request->validated());
        $packageType->saveOrFail();

        return new ApiSuccessResponse($packageType);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(PackageType::find($id));
    }

    public function update(PackageTypeUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $packageType = PackageType::findOrFail($id);
        $packageType->update($request->validated());

        return new ApiSuccessResponse($packageType);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $packageType = PackageType::findOrFail($id);

        return new ApiSuccessResponse($packageType->delete());
    }
}
