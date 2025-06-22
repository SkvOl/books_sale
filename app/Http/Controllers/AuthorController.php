<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OAT;
use Illuminate\Http\Request;
use App\Models\Author;


class AuthorController extends Controller{

    #[OAT\Get(
        path: '/authors',
        summary: 'Получение списка authors',
        description: 'Получение списка authors',
        tags: ['authors'],
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
                            new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                            new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                            new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                            new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
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
        $author = Author::select()->paginate(perPage: env('PER_PAGE'), page: $request->page);

        return self::response($author);
    }

    #[OAT\Get(
        path: '/authors/{id}',
        summary: 'Получение одного автора',
        description: 'Получение одного автора',
        tags: ['authors'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'object',
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                            new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                            new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                            new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
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
        return self::response(Author::find($id));
    }

    #[OAT\Put(
        path: '/authors/{id}',
        summary: 'Изменение данных автора',
        description: 'Изменение данных автора',
        tags: ['authors'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                            new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                            new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                            new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                        ]
                    ))
                ])
            ),
        ],
        parameters: [
            new OAT\RequestBody(
                required: true,
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                    new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                    new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                    new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                ]),
            ),
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор автора', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ]
    )]
    public function update(Request $request, string $id){
        $author = Author::find($id);

        $author->fill($request->only(['first_name', 'last_name', 'rank', 'avatar_url']));
        $author->save();

        return self::response($author->toArray());
    }
    
    #[OAT\Post(
        path: '/authors',
        summary: 'Создание автора',
        description: 'Создание автора',
        tags: ['authors'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                            new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                            new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                            new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                        ]
                    ))
                ])
            ),
        ],
        parameters: [new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
            ])
        )]
    )]
    public function store(Request $request){
        $author = new Author;
        
        $author->fill($request->only(['first_name', 'last_name', 'rank', 'avatar_url']));
        $author->save();

        return self::response($author->toArray());
    }
    
    #[OAT\Delete(
        path: '/authors/{id}',
        summary: 'Удаление автора',
        description: 'Удаление автора',
        tags: ['authors'],
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
    public function destroy(Author $author){ 
        $author::destroy($author->id);

        return self::response([]);
    }
}