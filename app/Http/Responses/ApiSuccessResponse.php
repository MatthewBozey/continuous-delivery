<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiSuccessResponse implements Responsable
{
    private $code;

    private $metadata;

    private $data;

    private array $headers;

    /**
     * @param  mixed  $data
     */
    public function __construct(
        $data,
        int $code = Response::HTTP_OK,
        array $metadata = [],
        $headers = []
    ) {
        $this->headers = $headers;
        $this->data = $data;
        $this->metadata = $metadata;
        $this->code = $code;
    }

    /**
     * @return JsonResponse|Response
     */
    final public function toResponse($request): JsonResponse
    {
        return response()->json(
            [
                'data' => $this->data,
                'metadata' => $this->metadata,
            ],
            $this->code,
            $this->headers
        );
    }
}
