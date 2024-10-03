<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiSuccessResponse;
use App\Models\ScriptType;
use App\Models\UpdateScript;
use Illuminate\Http\Request;

class ScriptTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ApiSuccessResponse
     */
    public function index()
    {
        $scriptTypes = ScriptType::orderBy('script_type_name')->get();
        /*$script = UpdateScript::find(173217);

        $query = explode("-- Разделитель", $script->script_text);
        foreach ($query as $item) {
            \DB::connection("KRON_STAGE")->getPdo()->prepare(\DB::raw($item))->execute();
        }*/

        //        \DB::connection("KRON_STAGE")->getPdo()->prepare("SET ANSI_NULLS ON SET QUOTED_IDENTIFIER ON")->execute();
        return new ApiSuccessResponse($scriptTypes);
        //        return \DB::connection("KRON_STAGE")->getPdo()->prepare(\DB::raw($script))->execute();
        //        return \DB::connection("KRON_STAGE")->raw();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ScriptType $scriptType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScriptType $scriptType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScriptType $scriptType)
    {
        //
    }
}
