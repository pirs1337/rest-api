<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccess($arr = [], $code = 200){
        $data = [
            'status' => true
        ];

        if (!empty($arr)) {
            foreach ($arr as $key => $element) {
                $data[$key] = $element;
            }
        }

        return response()->json($data, $code);
    }

    public function sendError($arr, $code = 422){
        $data = [
            'error' => [
                'status' => false
            ]
            
        ];

        if (!empty($arr)) {
            foreach ($arr as $key => $element) {
                $data['error'][$key] = $element;
            }
        }

        return response()->json($data, $code);
    }

    public function sendUnauthorized(){
        $data = [
            'error' => [
                'status' => false,
                'msg' => 'Unauthorized'
            ]
        ];

        return response()->json($data, 401);
    }

    public function sendAccessDenied(){
        $data = [
            'error' => [
                'status' => false,
                'msg' => 'Access denied'
            ]
        ];

        return response()->json($data, 403);
    }

    public static function formateDateToDmY($date){
       return date('d.m.Y', strtotime($date));
    }
}
