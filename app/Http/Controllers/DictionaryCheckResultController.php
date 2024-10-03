<?php

namespace App\Http\Controllers;

use App\Filters\DictionaryCheckResultFilter;
use App\Http\Requests\DictionaryCheckResult\CheckResultRequest;
use App\Http\Requests\StoreDictionaryCheckResultRequest;
use App\Http\Requests\UpdateDictionaryCheckResultRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\DictionaryCheckResult;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Throwable;

class DictionaryCheckResultController extends Controller
{
    const SUCCESS_STATUS_ID = 4;

    public function __construct()
    {
        $this->middleware('permission:dictionary_check_result list', ['only' => ['index']]);
        $this->middleware('permission:dictionary_check_result show', ['only' => ['show']]);
        $this->middleware('permission:dictionary_check_result create', ['only' => ['store']]);
        $this->middleware('permission:dictionary_check_result edit', ['only' => ['update']]);
        $this->middleware('permission:dictionary_check_result delete', ['only' => ['destroy']]);
    }

    public function index(DictionaryCheckResultFilter $filter, Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(DictionaryCheckResult::filter($filter)->orderBy('id')->get());
    }

    /**
     * @throws Throwable
     */
    public function store(StoreDictionaryCheckResultRequest $request): ApiSuccessResponse
    {
        $dictionaryCheckResult = new DictionaryCheckResult($request->validated());
        $dictionaryCheckResult->saveOrFail();

        return new ApiSuccessResponse($dictionaryCheckResult);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(DictionaryCheckResult::find($id));
    }

    public function update(UpdateDictionaryCheckResultRequest $request, int $id): ApiSuccessResponse
    {
        $dictionaryCheckResult = DictionaryCheckResult::findOrFail($id);
        $dictionaryCheckResult->update($request->validated());

        return new ApiSuccessResponse($dictionaryCheckResult);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $dictionaryCheckResult = DictionaryCheckResult::findOrFail($id);

        return new ApiSuccessResponse($dictionaryCheckResult->delete());
    }

    public function checkHandler(CheckResultRequest $request): ApiSuccessResponse|ApiErrorResponse
    {
        if (DB::table('update.dictionary_check_data')
            ->where('update_script', $request->get('update_script'))
            ->doesntExist()) {
            return new ApiErrorResponse('По данному конфигурационному скрипту нет данных о таблице и данных. Попробуйте пересоздать справочник');
        } elseif (DB::table('production.project_log_script')
            ->where('update_script', $request->get('update_script'))
            ->doesntExist()) {
            return new ApiErrorResponse('Данный конфигурационный скрипт еще не был отправлен ни на один продуктивный сервер');
        }

        $data = DB::table('production.project_log_script', 'pls')
            ->where('pls.update_script', $request->get('update_script'))
            ->where('pls.status', self::SUCCESS_STATUS_ID)
            ->leftJoin('production.project_log_server as ps', 'ps.project_log_server_id', '=', 'pls.project_log_server')
            ->leftJoin('production.server as s', 's.server_id', '=', 'ps.server_id')
            ->leftJoin('update.dictionary_check_data as dcd', 'dcd.update_script', '=', 'pls.update_script')
            ->leftJoin('update.update_script as us', 'us.update_script_id', '=', 'pls.update_script')
            ->leftJoin('production.server_status as ss', 'ss.server_status_id', '=', 'pls.status')
            ->select([
                'pls.project_log_server',
                'ss.status_color',
                'ss.status_title',
                's.server_name',
                's.server_id',
                'dcd.primary_key',
                'dcd.data_fields',
                'dcd.sql_query',
                'dcd.schema',
                'dcd.table',
                'dcd.update_script',

            ])
            ->get()
            ->unique('project_log_server')
            ->values();

        foreach ($data as $datum) {
            $query = DB::connection($datum->server_name)->table(DB::raw("($datum->sql_query) as sub"));

            DictionaryCheckResult::updateOrCreate([
                'update_script' => $datum->update_script,
                'author' => auth()->user()->username,
                'server' => $datum->server_id,
            ], [
                'check_result' => $query->exists(),
            ]);
        }

        $result = DB::table('update.dictionary_check_results', 'dcr')
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
            ->where('dcr.update_script', $request->get('update_script'))
            ->where('ps.server_status_id', self::SUCCESS_STATUS_ID)
            ->get()
            ->unique('project_log_server_id')
            ->values();

        $result->map(function ($result) {
            $result->id = (int) $result->id;
            $result->check_result = (bool) $result->check_result;
            $result->created_at = Carbon::parse($result->updated_at)
                ->longRelativeToNowDiffForHumans() ?? Carbon::parse($result->created_at)
                ->longRelativeToNowDiffForHumans();
            $result->project_log_server_created_at = Carbon::parse($result->project_log_server_updated_at)
                ->longRelativeToNowDiffForHumans() ?? Carbon::parse($result->project_log_server_created_at)
                ->longRelativeToNowDiffForHumans();
        });

        return new ApiSuccessResponse($result);

    }
}
