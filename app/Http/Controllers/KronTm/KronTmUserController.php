<?php

namespace App\Http\Controllers\KronTm;

use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KronTmUserController extends KronTmController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function get(Request $request): ApiSuccessResponse
    {
        try {
            $url = url()->query($this->deployControlUrl.'/api/users', $request->all());
            $data = Http::withBasicAuth($this->user, $this->password)->get($url);
        } catch (ConnectionException $e) {
            throw new \Exception('Не удалось подключиться к KRON-TM Deploy Control');
        }

        return new ApiSuccessResponse($data->json());

    }
}
