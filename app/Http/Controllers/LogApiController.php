<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

/**
     * @OA\Get(
     *      path="/api",
     *      operationId="getLog",
     *      tags={"Log"},
     *      summary="Get data log of import data cron",
     *      description="Returns list of all Products",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       security={{ "bearerAuth": {} }}, 
     *     )
     *
     * Returns list of Products
     */
class LogApiController extends Controller
{
    public function index(){
        $log = Log::all();
        return response()->json($log, 200);
    }
}
