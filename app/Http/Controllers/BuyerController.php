<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OAT;
use Illuminate\Http\Request;
use App\Models\Buyer;


class BuyerController extends Controller{


    #[OAT\Get(
        path: '/buyers',
        summary: 'Получение покупателей',
        description: 'Получение покупателей',
        tags: ['buyers'],
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
                            new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
                            new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail'),
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
        $buyer = Buyer::select()->paginate(perPage: env('PER_PAGE'), page: $request->page);

        return self::response($buyer);
    }

    #[OAT\Get(
        path: '/buyers/{id}',
        summary: 'Получение одного покупателя',
        description: 'Получение одного покупателя',
        tags: ['buyers'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'object',
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
                            new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail'),
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
        return self::response(Buyer::find($id));
    }

    #[OAT\Put(
        path: '/buyers/{id}',
        summary: 'Изменение данных покупателя',
        description: 'Изменение данных покупателя',
        tags: ['buyers'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
                            new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail'),
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
        $buyer = Buyer::find($id);

        $buyer->fill($request->only(['name', 'email']));
        $buyer->save();

        return self::response($buyer->toArray());
    }

    #[OAT\Delete(
        path: '/buyers/{id}',
        summary: 'Удаление покупателя',
        description: 'Удаление покупателя',
        tags: ['buyers'],
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
    public function destroy(Buyer $buyer){ 
        $buyer::destroy($buyer->id);

        return self::response([]);
    }
}