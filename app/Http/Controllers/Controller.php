<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function respondSuccess($message)
    {
        return response()->json([
            'status' => 'success',
            'messages' => $message
        ], 200);
    }

    protected function respondInvalidParams($message)
    {
        return response()->json([
            'status' => 'error',
            'messages' => $message
        ], 400);
    }

    protected function respondUnauthorized()
    {
        return response()->json([
            'status' => 'error',
            'messages' => "Unauthorized"
        ], 401);
    }
}
