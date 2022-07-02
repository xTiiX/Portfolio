<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Success response method.
     * 
     * @param Object $data
     * @param String $message
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($data, $message)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message'=> $message
        ];

        return response()->json($response, 200);
    }

    /**
     * Error reponse method.
     * 
     * @param String $error
     * @param String[] $errorMessages
     * @param Integer $code
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error
        ];

        if (!empty($errorMessages))
            $response['data'] = $errorMessages;

        return response()->json($response, $code);
    }
}
