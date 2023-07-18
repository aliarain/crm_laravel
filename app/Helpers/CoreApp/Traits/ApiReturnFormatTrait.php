<?php

namespace App\Helpers\CoreApp\Traits;

use Illuminate\Http\JsonResponse;

trait ApiReturnFormatTrait
{

    protected function responseWithSuccess($message = '', $data = [], $code = 200)
    {
        if (blank($data)) {
            $data = (object) $data;
        }
        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function responseWithError($message = '', $data = [], $code = 400)
    {

        if (blank($data)) {
            $data = (object) $data;
        }

        return response()->json([
            'result' => false,
            'message' => $message,
            'error' => $data,
        ], $code);

    }

    protected function responseExceptionError($message = '', $data = [], $code = null): JsonResponse
    {
        return response()->json([
            'exception_error' => true,
            'exception_message' => $message,
        ], $code);
    }
}
