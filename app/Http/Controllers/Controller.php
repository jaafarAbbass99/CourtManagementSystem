<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function sendResponse($data, $message = '', $success = true, $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function sendOkResponse($message = '', $success = true, $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
        ], $statusCode);
    }

    protected function sendError($message, $statusCode = 400): JsonResponse
    {
        return $this->sendResponse(false,$message, false, $statusCode);
    }

    protected function sendErrorWithCause($msg_error,$message, $statusCode = 400): JsonResponse
    {
        return $this->sendResponse($msg_error,$message, false, $statusCode);
    }




}
