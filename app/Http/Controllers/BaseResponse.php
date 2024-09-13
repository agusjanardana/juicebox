<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class BaseResponse
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

     public static function rollback($e, $message ="Something went wrong! Process not completed"){
        Log::info($e);
        DB::rollBack();

         $response=[
            'meta' => [
                'success' => false,
                'code' => 500,
                'message' => $message
            ],
            'data'    => null
        ];

        return response()->json($response, 500);
    }

    public static function throw($e, $message ="Something went wrong! Process not completed"){
        Log::info($e);
        throw new HttpResponseException(response()->json(["message"=> $e->getMessage()], 500));
    }

    public static function sendSuccessResponse($result , $message ,$code=200){
        $response=[
            'meta' => [
                'success' => true,
                'code' => $code,
                'message' => $message
            ],
            'data'    => $result
        ];

        DB::commit();
        return response()->json($response, $code);
    }

      public static function sendError($result , $message ,$code=200){
        $response=[
            'meta' => [
                'success' => false,
                'code' => $code,
                'message' => $message
            ],
            'data'    => $result
        ];

        return response()->json($response, $code);
    }
}