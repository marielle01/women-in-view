<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result = null, $message = null): JsonResponse
    {
        $response = [
            'success' => true,
        ];

        if (!is_null($result)) {
            $response['data'] = $result;
        }
        if (!is_null($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, 200);
    }


    /**
     * return error message
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $message, array $data = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}

