<?php

namespace App\Http\Controllers;

use App\Events\UpdatePackageCreateEvent;
use App\Events\UpdatePackageDeleteEvent;
use App\Filters\UpdatePackageFilter;
use App\Http\Requests\UpdatePackage\UpdatePackageCreateRequest;
use App\Http\Requests\UpdatePackage\UpdatePackageUpdateRequest;
use App\Http\Requests\UpdatePackage\UpdateServerRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Jobs\UpdateServerRefactor;
use App\Models\ProductionProjectLog;
use App\Models\UpdateErrorLog;
use App\Models\UpdatePackage;
use App\Models\UpdateScript;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdatePackageController extends Controller
{
    const ProjectConfigUpdateId = 70;

    const FailureStatusId = 3;

    const SuccessStatusId = 4;

    const ProcessStatusId = 2;

    public function __construct()
    {
        $this->middleware('permission:update-package list', ['only' => ['index']]);
        $this->middleware('permission:update-package show', ['only' => ['show']]);
        $this->middleware('permission:update-package create', ['only' => ['store']]);
        $this->middleware('permission:update-package edit', ['only' => ['update']]);
        $this->middleware('permission:update-package delete', ['only' => ['destroy']]);
    }

    public function index(UpdatePackageFilter $filter, Request $request): JsonResponse
    {
        $data = UpdatePackage::filter($filter)
            ->orderBy('update_package_id', 'desc')
            ->when($request->user()->canNot('update-package view-all'), static function ($query) use ($request) {
                $query->where('author_name', $request->user()->username);
            })
            ->get();

        if ($request->has('hide_is_done') && $request->get('hide_is_done') == 'true') {
            $data = $data->filter(static function ($item) {
                return $item->percent_servers['value'] < 100;
            })->values();
        }

        return response()->json(compact('data'));
    }

    public function store(UpdatePackageCreateRequest $request): JsonResponse
    {
        $data = new UpdatePackage($request->all());
        $data->author_name = $request->user()->username;
        $data->save();
        broadcast(new UpdatePackageCreateEvent(UpdatePackage::find($data->update_package_id)))->toOthers();

        return response()->json(compact('data'));
    }

    /**
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $data = UpdatePackage::find($id);
        broadcast(new UpdatePackageCreateEvent(UpdatePackage::find($id)))->toOthers();

        return response()->json(compact('data'));
    }

    /**
     * @return JsonResponse
     */
    public function update(UpdatePackageUpdateRequest $request, int $id)
    {
        $data = UpdatePackage::findOrFail($id);
        $data->update($request->all());
        broadcast(new UpdatePackageCreateEvent(UpdatePackage::find($data->update_package_id)))->toOthers();

        return response()->json(compact('data'));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $request->user()->can('delete_update_package');
        $data = UpdatePackage::findOrFail($id);
        broadcast(new UpdatePackageDeleteEvent(UpdatePackage::find($data->update_package_id)))->toOthers();
        $data->delete();

        return response()->json(compact('data'));
    }

    /**
     * @throws \Throwable
     */
    public function updateServers(UpdateServerRequest $request): ApiSuccessResponse
    {

        $packages = collect($request->get('update_package', []));
        if ($packages->isEmpty()) {
            throw new \Exception('Необходимо указать конфигурационные пакеты');
        }

        $packages = $packages->filter(static fn ($item) => count($item['selected_servers']) !== 0);

        $productionProjectLog = new ProductionProjectLog([
            'project_config_id' => self::ProjectConfigUpdateId,
            'date_start' => Carbon::now(),
            'project_log_status_id' => self::ProcessStatusId,
            'version' => $request->get('version'),
            'author' => auth()->user()->username,
        ]);
        $productionProjectLog->save();

        $this->dispatch(new UpdateServerRefactor($packages, $productionProjectLog));

        $data = ProductionProjectLog::where('project_log_id', $productionProjectLog->project_log_id)->with([
            'production_project_log_server',
            'production_project_log_server.server',
            'production_project_log_server.server_status',
            'production_project_log_server.production_project_log_server_error',
            'server_status',
        ])->first();

        return new ApiSuccessResponse($data);
    }

    public function getUpdatePackageErrors(Request $request): ApiSuccessResponse
    {
        $updateScripts = UpdateScript::where('update_package_id', $request->get('update_package_id'))
            ->pluck('update_script_id')
            ->toArray();
        $updateErrorLog = UpdateErrorLog::whereIn('update_script_id', $updateScripts)
            ->orderBy('update_error_log_date')
            ->get();

        return new ApiSuccessResponse(($updateErrorLog));

    }

    public function updateProjectLogInfo(Request $request, int $id): ApiSuccessResponse
    {
        $data = ProductionProjectLog::where('project_log_id', $id)->with([
            'production_project_log_server',
            'production_project_log_server.server',
            'production_project_log_server.server_status',
            'production_project_log_server.production_project_log_server_error',
            'production_project_log_server.production_project_log_script',
            'server_status',
        ])->first();

        return new ApiSuccessResponse($data);
    }

    public function getUserList(Request $request): ApiSuccessResponse
    {
        $data = User::select(['username'])->orderBy('username')->get();

        return new ApiSuccessResponse($data);
    }
}
