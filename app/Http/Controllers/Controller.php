<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    protected static function getHttpContentParam(Request $request)
    {
        $paramString = $request->getContent();
        $arrayOfParam = [];

        $arrayOfParamString = explode('&', $paramString);
        foreach ($arrayOfParamString as $row) {
            if (strpos($row, '=') === false) {
                continue;
            }
            $explode = explode('=', $row, 2);
            $arrayOfParam[urldecode($explode[0])] = $explode[1];
        }

        return $arrayOfParam;
    }
}
