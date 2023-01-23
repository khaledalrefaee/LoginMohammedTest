<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{

    //الشغلات يلي بدي ياها وين ما بروح

    public function ApiResponse($data= null , $msg= null , $status = null){

        $array =[
            'data'=>$data,
            'msg'=>$msg,
            'status'=>$status
        ];
        return response($array,$status);
    }
}
