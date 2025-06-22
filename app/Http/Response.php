<?php

namespace App\Http;


use Illuminate\Pagination\LengthAwarePaginator;

trait Response{
    /**
     * Список возвращаемых заголовков
     */
    static private $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => '*',
        'Access-Control-Allow-Credentials' => 'true',
    ];

    static function toPaginator($response, $status)
    {
        return array_map(
            fn ($value) => [
                'status'=>$value['status'],
                'paginator'=>[
                    'current_page'=>$value['current_page'],
                    'first_page_url'=>$value['first_page_url'],
                    'from'=>$value['from'],
                    'last_page'=>$value['last_page'],
                    'last_page_url'=>$value['last_page_url'],
                    'next_page_url'=>$value['next_page_url'],
                    'path'=>$value['path'],
                    'per_page'=>$value['per_page'],
                    'to'=>$value['to'],
                    'total'=>$value['total'],

                ],
                'data'=>$value['data'],
            ], 
            [[ 'status' => $status ] + $response->toArray()]
        )[0];
    }

    /**
     * Функция, формирующая ответ
     * 
     * @param $response ответ
     * @param $statusCode статус код ответа
     */
    static function response($response = null, $statusCode = 200)
    {
        $status = (in_array($statusCode, [200, 201, 304]) ? 'Successfully' : 'Error');

        if(gettype($statusCode) == 'string') $statusCode = 500;
        
        if($response instanceof LengthAwarePaginator){
            return response(self::toPaginator($response, $status))->setStatusCode($statusCode)->withHeaders(self::$headers);
        }
        elseif (is_null($response)) return response()->noContent()->setStatusCode($statusCode); 
        else return response([
            'status' => $status,
            'data' => $response,

        ])->setStatusCode($statusCode)->withHeaders(self::$headers);
    }
}