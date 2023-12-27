<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function TestRoute(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(['message' => 'Test successfully tested. Everything is ok.']);
        } catch(Exception $ex) {
            return response()->json(['status' => 'error', 'message' => 'Test unsuccessful.', 'errors' => $ex], 304);
        }
    }
}
