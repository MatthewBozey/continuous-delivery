<?php

namespace App\Http\Controllers;

use App\Events\UpdateScriptsUpdatedEvent;
use App\Filters\UpdateScriptFilter;
use App\Http\Requests\UpdateScript\UpdateScriptCreateRequest;
use App\Http\Requests\UpdateScript\UpdateScriptReorderRequest;
use App\Http\Requests\UpdateScript\UpdateScriptUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\UpdateScript;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class UpdateScriptController extends Controller
{
    const SUCCESS_STATUS_ID = 4;

    public function __construct()
    {
        $this->middleware('permission:update-script list', ['only' => ['index']]);
        $this->middleware('permission:update-script show', ['only' => ['show']]);
        $this->middleware('permission:update-script create', ['only' => ['store']]);
        $this->middleware('permission:update-script edit', ['only' => ['update']]);
        $this->middleware('permission:update-script delete', ['only' => ['destroy']]);
        $this->middleware('permission:update-script reorder', ['only' => ['reorderScripts']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UpdateScriptFilter $filter, Request $request): ApiSuccessResponse
    {
        $data = UpdateScript::filter($filter)
            ->orderBy('script_order')
            ->get();
        $data->map(function ($item) {
            $dictionaryCheckResult = DB::table('update.dictionary_check_results', 'dcr')
                ->leftJoin('production.project_log_script as pls', 'pls.update_script', '=', 'dcr.update_script')
                ->leftJoin('production.project_log_server as ps', 'ps.project_log_server_id', '=', 'pls.project_log_server')
                ->leftJoin('production.server as s', 's.server_id', '=', 'ps.server_id')
                ->leftJoin('production.server_status as ss', 'ss.server_status_id', '=', 'ps.server_status_id')
                ->select([
                    'dcr.*',
                    's.server_name',
                    'ss.status_color',
                    'ss.status_title',
                    'ps.project_log_server_id',
                    'ps.created_at as project_log_server_created_at',
                    'ps.updated_at as project_log_server_updated_at',
                ])
                ->where('dcr.update_script', $item->update_script_id)
                ->where('ps.server_status_id', self::SUCCESS_STATUS_ID)
                ->get()
                ->unique('project_log_server_id')
                ->values();
            $dictionaryCheckResult->map(function ($result) {
                $result->id = (int) $result->id;
                $result->check_result = (bool) $result->check_result;
                $result->created_at = Carbon::parse($result->updated_at)
                    ->longRelativeToNowDiffForHumans() ?? Carbon::parse($result->created_at)
                    ->longRelativeToNowDiffForHumans();
                $result->project_log_server_created_at = Carbon::parse($result->project_log_server_updated_at)
                    ->longRelativeToNowDiffForHumans() ?? Carbon::parse($result->project_log_server_created_at)
                    ->longRelativeToNowDiffForHumans();
            });
            $item->dictionaryCheckResult = $dictionaryCheckResult;
            $dictionaryCheckData = DB::table('update.dictionary_check_data')
                ->where('update_script', $item->update_script_id)
                ->first();
            if ($dictionaryCheckData) {
                $dictionaryCheckData->sql_query = str_replace('and', "and\n", $dictionaryCheckData->sql_query);
                $dataFields = json_decode(json_decode($dictionaryCheckData->data_fields, true));
                $fields = [];
                foreach ($dataFields as $datum) {
                    foreach ($datum as $key => $value) {
                        $fields[] = [
                            'column' => $key,
                            'value' => $value,
                        ];
                    }
                }
                $dictionaryCheckData->data_fields = $fields;
                $item->dictionaryCheckData = $dictionaryCheckData;
            } else {
                $item->dictionaryCheckData = [];
            }

            return $item;
        });

        return new ApiSuccessResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateScriptCreateRequest $request): ApiSuccessResponse
    {
        $data = UpdateScript::create($request->all());
        $data->script_order = UpdateScript::where('update_package_id', $request->get('update_package_id'))
            ->max('script_order') + 1;
        $data->save();
        broadcast(new UpdateScriptsUpdatedEvent($data->update_package_id))->toOthers();

        return new ApiSuccessResponse($data);
    }

    public function reorderScripts(UpdateScriptReorderRequest $request): ApiSuccessResponse
    {
        $scripts = $request->get('scripts');
        try {
            DB::beginTransaction();
            foreach ($scripts as $index => $scriptId) {
                UpdateScript::find($scriptId)
                    ->update(['script_order' => $index + 1]);
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }
        broadcast(new UpdateScriptsUpdatedEvent(UpdateScript::find($scripts[0])->update_package_id))->toOthers();

        return new ApiSuccessResponse(UpdateScript::orderBy('script_order')
            ->find($scripts));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScriptUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $data = UpdateScript::findOrFail($id);
        $data->update($request->all());
        broadcast(new UpdateScriptsUpdatedEvent($data->update_package_id))->toOthers();

        return new ApiSuccessResponse($data);
    }

    /**
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $data = UpdateScript::find($id);

        return response()->json(compact('data'));
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $data = UpdateScript::findOrFail($id);
        $data->delete();
        broadcast(new UpdateScriptsUpdatedEvent($data->update_package_id))->toOthers();

        return new ApiSuccessResponse([]);
    }
}
