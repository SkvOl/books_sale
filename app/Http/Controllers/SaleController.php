<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OAT;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Book;


class SaleController extends Controller{

    #[OAT\Get(
        path: '/sales',
        summary: 'Получение продаж',
        description: 'Получение продаж',
        tags: ['sales'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'paginator', ref: '#/components/schemas/Paginator'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'book_id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'client_id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                            new OAT\Property(property: 'created_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-22T09:02:11.000000Z'),
                        ]
                    ))
                ])
            ),
        ],
        parameters: [
            new OAT\Parameter(name: 'page', parameter: 'page', description: 'Текущая страница', in: 'query', required: false, deprecated: false, allowEmptyValue: true),
        ],
    )]
    public function index(Request $request){
        $sale = Sale::select()->paginate(perPage: env('PER_PAGE'), page: $request->page);

        return self::response($sale);
    }

    #[OAT\Get(
        path: '/sales/{id}',
        summary: 'Получение одной продажи',
        description: 'Получение одной продажи',
        tags: ['sales'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'object',
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'book_id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'client_id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                            new OAT\Property(property: 'created_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-22T09:02:11.000000Z'),
                        ]
                    )
                ])
            ),
        ],
        parameters: [
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор автора', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ],
    )]
    public function show(string $id){
        return self::response(Sale::find($id));
    }

    #[OAT\Put(
        path: '/sales/{id}',
        summary: 'Изменение данных продажи',
        description: 'Изменение данных продажи',
        tags: ['sales'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'book_id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'client_id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                            new OAT\Property(property: 'created_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-22T09:02:11.000000Z'),
                        ]
                    ))
                ])
            ),
        ],
        parameters: [
            new OAT\RequestBody(
                required: true,
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
                    new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail'),
                ]),
            ),
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор автора', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ]
    )]
    public function update(Request $request, string $id){
        $sale = Sale::find($id);

        $sale->fill($request->only(['book_id', 'client_id', 'price']));
        $sale->save();

        return self::response($sale->toArray());
    }

    #[OAT\Delete(
        path: '/sales/{id}',
        summary: 'Удаление продажи',
        description: 'Удаление продажи',
        tags: ['sales'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
            ),
        ],
        parameters: [
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор автора', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ],
    )]
    public function destroy(Sale $sale){ 
        $sale::destroy($sale->id);

        return self::response([]);
    }
}