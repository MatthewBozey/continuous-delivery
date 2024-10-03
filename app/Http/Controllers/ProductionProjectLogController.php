<?php

namespace App\Http\Controllers;

use App\Events\UpdatePackageCreateEvent;
use App\Http\Requests\UpdatePackage\UpdatePackageCreateRequest;
use App\Http\Requests\UpdatePackage\UpdatePackageUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\ProductionProjectLog;
use App\Models\UpdatePackage;
use Illuminate\Http\Request;

class ProductionProjectLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:update-package list', ['only' => ['index']]);
        $this->middleware('permission:update-package show', ['only' => ['show']]);
        $this->middleware('permission:update-package create', ['only' => ['store']]);
        $this->middleware('permission:update-package edit', ['only' => ['update']]);
        $this->middleware('permission:update-package delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): ApiSuccessResponse
    {
        $data = ProductionProjectLog::with(
            [
                'server_status',
                'production_project_log_server',
                'production_project_log_server.production_project_log_server_error',
                'production_project_log_server.production_project_log_script',
                'production_project_log_server.production_project_log_script.script',
                'production_project_log_server.production_project_log_script.production_project_log_script_error',
                'production_project_log_server.production_project_log_script.server_status',
                'production_project_log_server.server',
                'production_project_log_server.server_status',
            ])
            ->orderBy('project_log_id', 'desc')->get();

        return new ApiSuccessResponse($data);
    }

    public function store(UpdatePackageCreateRequest $request): ApiSuccessResponse
    {
        $data = new UpdatePackage($request->all());
        $data->author_name = $request->user()->username;
        $data->save();
        broadcast(new UpdatePackageCreateEvent(UpdatePackage::find($data->update_package_id)))->toOthers();

        return new ApiSuccessResponse($data);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        $data = ProductionProjectLog::with(
            [
                'server_status',
                'production_project_log_server',
                'production_project_log_server.production_project_log_server_error',
                'production_project_log_server.production_project_log_script',
                'production_project_log_server.production_project_log_script.script',
                'production_project_log_server.production_project_log_script.production_project_log_script_error',
                'production_project_log_server.production_project_log_script.server_status',
                'production_project_log_server.server',
                'production_project_log_server.server_status',
            ])->find($id);

        return new ApiSuccessResponse($data);
    }

    public function update(UpdatePackageUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $data = ProductionProjectLog::findOrFail($id);
        $data->update($request->validated());

        return new ApiSuccessResponse($data);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $data = ProductionProjectLog::findOrFail($id);
        $data->delete();

        return new ApiSuccessResponse($data);
    }
}
