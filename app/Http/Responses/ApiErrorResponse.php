<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Throwable;

class ApiErrorResponse extends \Symfony\Component\HttpFoundation\Response implements Responsable
{
    private $message;

    private $exception;

    private $errorList;

    private $code;

    public \Symfony\Component\HttpFoundation\ResponseHeaderBag $headers;

    public function __construct(
        string $message,
        array $errorList = [],
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?Throwable $exception = null,
        #[\SensitiveParameter] $headers = []
    ) {
        $this->headers = new ResponseHeaderBag($headers);
        $this->code = $code;
        $this->exception = $exception;
        $this->message = $message;
        $this->errorList = $errorList;
    }

    public function toResponse($request)
    {
        $response = ['message' => $this->message, 'error_list' => $this->errorList];

        if ($this->exception !== null && config('app.debug')) {
            $response['debug'] = [
                'message' => $this->exception->getMessage(),
                'file' => $this->exception->getFile(),
                'line' => $this->exception->getLine(),
                'trace' => $this->exception->getTraceAsString(),
            ];
        }

        return response()->json($response, $this->code, (array)$this->headers->all());
    }
}
