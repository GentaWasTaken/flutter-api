<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendSuccess($result, $message, $code)
    {
        $response = [
            'success'   => true,
            'data'      => $result,
            'message'   => $message
        ];
        return response()->json($response, $code);
    }

    public function sendError($result, $message, $code)
    {
        $response = [
            'success'   => false,
            'data'      => $result,
            'message'   => $message
        ];
        return response()->json($response, $code);
    }
}
