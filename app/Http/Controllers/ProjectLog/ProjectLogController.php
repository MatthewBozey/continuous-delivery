<?php

namespace App\Http\Controllers\ProjectLog;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\ProjectLog\ProjectLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ApiSuccessResponse
    {
        $data = ProjectLog::orderBy('project_log_id', 'desc')->get();

        return new ApiSuccessResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectLog $projectLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectLog $projectLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectLog $projectLog)
    {
        //
    }

    public function getProjectLogList(Request $request): ApiSuccessResponse
    {
        $data = \DB::table('dbo.project_log', 'pl')
            ->leftJoin('dbo.project_config as pc', 'pc.project_config_id', '=', 'pl.project_config_id')
            ->leftJoin('dbo.project_log_status as pls', 'pls.project_log_status_id', '=', 'pl.project_log_status_id')
            ->select(['pl.project_log_id', 'pc.project_config_id', 'pc.project_config_title', 'pl.project_log_percent', 'pls.project_log_status_name', 'pl.project_log_status_id', 'pl.project_log_start'])
            ->get()
            ->map(static function ($item) {
                $item->project_log_start = Carbon::parse($item->project_log_start)->format('H:i d-m-Y');
                $item->project_log_status_id = (int) $item->project_log_status_id;

                return $item;
            });

        $columns = $data->unique('project_config_title')->values()->select(['project_config_title', 'project_config_id']);
        $columnIds = $columns->pluck('project_config_id');

        return new ApiSuccessResponse($data, Response::HTTP_OK, compact('columns', 'columnIds'));
    }
}
