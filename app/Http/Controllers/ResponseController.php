<?php

namespace App\Http\Controllers;

class ResponseController extends Controller
{
    public function __construct()
    {
    }

    public function jsonResponse(array $data = [], string $message = null, int $code = 200)
    {
        $response = array(
            'success' => in_array($code, ['200', '201', '202']),
            'data' => $data,
            'message' => $message,
            'code' => $code,
        );
        return response()->json($response);
    }
}
