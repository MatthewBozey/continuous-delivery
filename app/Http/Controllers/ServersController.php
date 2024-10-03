<?php

namespace App\Http\Controllers;

use App\Filters\ServerFilter;
use App\Http\Requests\Server\ServerCreateRequest;
use App\Http\Requests\Server\ServerUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Server;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServersController extends Controller
{
    const DONE_SERVER_STATUS = 4;

    public function __construct()
    {
        $this->middleware('permission:server list', ['only' => ['index']]);
        $this->middleware('permission:server show', ['only' => ['show']]);
        $this->middleware('permission:server create', ['only' => ['store']]);
        $this->middleware('permission:server edit', ['only' => ['update']]);
        $this->middleware('permission:server delete', ['only' => ['destroy']]);
        $this->middleware('permission:server trash-list', ['only' => ['trash']]);
        $this->middleware('permission:server trash-restore', ['only' => ['restore']]);
        $this->middleware('permission:server trash-force-delete', ['only' => ['forceDelete']]);
    }

    public function index(ServerFilter $filter, Request $request): ApiSuccessResponse
    {
        $servers = Server::orderBy('server_id')->filter($filter)->get();
        if ($request->user()->can('server full-list')) {
            $servers->makeVisible([
                'database_user', 'port', 'ip_address', 'database_password', 'database_name',
            ]);
        }

        return new ApiSuccessResponse($servers);
    }

    /**
     * @throws \Throwable
     */
    public function store(ServerCreateRequest $request): ApiSuccessResponse
    {
        $server = Server::create($request->validated());
        $server->saveOrFail();

        return new ApiSuccessResponse($server);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        $server = Server::findOrFail($id);
        $server->makeVisibleIf($request->user()->can('server full-list'), [
            'database_user', 'port', 'ip_address', 'database_password', 'database_name',
        ]);

        return new ApiSuccessResponse($server);
    }

    public function update(ServerUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $server = Server::findOrFail($id);
        $server->fill($request->validated());
        if ($server->isDirty()) {
            $server->update();

            return new ApiSuccessResponse($server->makeVisibleIf($request->user()->can('server full-list'), [
                'database_user', 'port', 'ip_address', 'database_password', 'database_name',
            ]));
        } else {
            return new ApiSuccessResponse($server, Response::HTTP_NOT_MODIFIED);
        }

    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $server = Server::findOrFail($id);

        return new ApiSuccessResponse($server->delete());
    }

    public function trash(ServerFilter $filter, Request $request): ApiSuccessResponse
    {
        $servers = Server::onlyTrashed()->orderBy('server_id')->get();
        if ($request->user()->can('server full-list')) {
            $servers->makeVisible([
                'database_user', 'port', 'ip_address', 'database_password', 'database_name',
            ]);
        }

        return new ApiSuccessResponse($servers);
    }

    public function restore(Request $request, int $id): ApiSuccessResponse
    {
        $server = Server::onlyTrashed()->findOrFail($id);
        $server->restore();

        return new ApiSuccessResponse($server);
    }

    public function forceDelete(Request $request, int $id): ApiSuccessResponse
    {
        $server = Server::onlyTrashed()->findOrFail($id);
        $server->forceDelete();

        return new ApiSuccessResponse([], Response::HTTP_OK);
    }

    public function getSelectedPackagesServers(Request $request): ApiSuccessResponse
    {
        $overwritingServers = $request->get('overwriting_servers', false);
        $selectedPackages = collect($request->get('packages', []));
        $selectedPackages = $selectedPackages->filter(static fn ($item) => ! $item['has_errors']);
        $selectedPackages->transform(function ($pac) use ($overwritingServers) {
            $projectId = $pac['project_id'];
            $scripts = \DB::table('update.update_script', 'us')
                ->leftJoin('update.script_type as st', 'st.script_type_id', '=', 'us.script_type_id')
                ->where('us.update_package_id', $pac['update_package_id'])
                ->select([
                    'us.*',
                    'st.script_type_title',
                ])
                ->orderBy('us.script_order')
                ->get();
            $pac['update_scripts'] = $scripts;
            $servers = \DB::table('production.server', 's')
                ->join('dbo.project_server as ps', 'ps.server', '=', 's.server_id', '')
                ->where('ps.project', $projectId)
                ->select('s.server_id', 's.server_name')
                ->get()
                ->map(fn ($item) => [
                    'server_id' => (int) $item->server_id,
                    'server_name' => $item->server_name,
                ]);

            $doneServers = \DB::table('production.project_log_script', 'sc')
                ->where('sc.update_package', $pac['update_package_id'])
                ->where('pls.server_status_id', self::DONE_SERVER_STATUS)
                ->leftJoin('production.project_log_server as pls', 'pls.project_log_server_id', '=', 'sc.project_log_server')
                ->select('pls.server_id', 'sc.project_log_server', 'pls.server_status_id')
                ->get()
                ->map(fn ($item) => [
                    'server_id' => (int) $item->server_id,
                    'project_log_server' => (int) $item->project_log_server,
                    'server_status_id' => (int) $item->server_status_id,
                ])
                ->unique('project_log_server')
                ->values();

            $servers = $servers->map(function ($server) use ($doneServers, $overwritingServers) {
                $server['disabled'] = in_array($server['server_id'], $doneServers->pluck('server_id')
                    ->toArray()) && ! $overwritingServers;

                return $server;
            });

            $filter = $servers->filter(static fn ($server) => ! in_array($server['server_id'], $doneServers->pluck('server_id')
                ->toArray()))->values();
            $pac['servers'] = $servers;

            $pac['selected_servers'] = $filter->pluck('server_id')->toArray();

            return $pac;
        });

        $selectedPackages = $selectedPackages->filter(static fn ($item) => count($item['servers']) > 0);

        return new ApiSuccessResponse($selectedPackages);
    }
}
