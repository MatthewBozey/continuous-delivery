<?php

namespace App\Http\Controllers;

use App\Filters\ProjectFilter;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Project;
use App\Models\ProjectServer;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:project list', ['only' => ['index']]);
        $this->middleware('permission:project show', ['only' => ['show']]);
        $this->middleware('permission:project create', ['only' => ['store']]);
        $this->middleware('permission:project edit', ['only' => ['update']]);
        $this->middleware('permission:project delete', ['only' => ['destroy']]);
    }

    public function index(ProjectFilter $filter, Request $request): ApiSuccessResponse
    {
        $projects = Project::filter($filter)->orderBy('project_id')->with('servers')->get();

        return new ApiSuccessResponse($projects);
    }

    /**
     * @throws \Throwable
     */
    public function store(ProjectCreateRequest $request): ApiSuccessResponse
    {
        $project = Project::create($request->validated());
        $project->saveOrFail();
        $data = $this->deletePreviousServers($project, $request);

        return new ApiSuccessResponse($project);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        $data = Project::where('project_id', $id)->with(['servers'])->first();

        return new ApiSuccessResponse($data);
    }

    public function update(ProjectUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $project = Project::findOrFail($id);
        $project->update($request->validated());

        $data = $this->deletePreviousServers($project, $request);

        return new ApiSuccessResponse($data);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $project = Project::findOrFail($id);

        return new ApiSuccessResponse($project->delete());
    }

    private function deletePreviousServers($project, $request): mixed
    {
        ProjectServer::whereProject($project->project_id)->delete();
        $requiredUpdateServers = $request->get('required_update_server_ids', []);
        foreach ($request->get('server_ids', []) as $item) {

            $projectServer = ProjectServer::create([
                'server' => $item,
                'project' => $project->project_id,
                'required_update' => in_array($item, $requiredUpdateServers),
            ]);
            $projectServer->save();
        }

        return Project::with('servers')->find($project->project_id);
    }
}
