<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller{

    #[OAT\Post(
        path: '/user/signup',
        summary: 'Регистрагия пользователя',
        description: 'Регистрагия пользователя',
        tags: ['user'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'token', type: 'string', format: 'JWT', example: 'Fk32aopIeVO5KKgVu27FYwQMwE3'),
                            new OAT\Property(property: 'user', type: 'object',
                                properties: [
                                    new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                                    new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
                                    new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail.ru'),
                                    new OAT\Property(property: 'email_verified_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                                    new OAT\Property(property: 'created_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                                    new OAT\Property(property: 'updated_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                                ]
                            )
                        ]
                    ))
                ])
            ),
        ],
        parameters: [new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
                new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail.ru'),
                new OAT\Property(property: 'password', type: 'string', format: 'password', example: '23455'),
            ])
        )]
    )]
    public function signup(Request $request)
    {
        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return self::response([
            'token' => $user->createToken($user->name)->plainTextToken,
            'user' => $user
        ]);;
    }

    #[OAT\Post(
        path: '/user/signin',
        summary: 'Получение токена пользователя',
        description: 'Получение токена пользователя',
        tags: ['user'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'token', type: 'string', format: 'JWT', example: 'Fk32aopIeVO5KKgVu27FYwQMwE3'),
                            new OAT\Property(property: 'user', type: 'object',
                                properties: [
                                    new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                                    new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
                                    new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail.ru'),
                                    new OAT\Property(property: 'email_verified_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                                    new OAT\Property(property: 'created_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                                    new OAT\Property(property: 'updated_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                                ]
                            )
                        ]
                    ))
                ])
            ),
            new OAT\Response(
                response: 401,
                description: 'Ошибка'
            )
        ],
        parameters: [new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail.ru'),
                new OAT\Property(property: 'password', type: 'string', format: 'password', example: '23455'),
            ])
        )]
    )]
    public function signin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 

            return self::response([
                'token' => $user->createToken($user->name)->plainTextToken,
                'user' => $user
            ]);
        } 
        else{ 
            return self::response('Invalid username or password', 401);
        } 
    }

    #[OAT\Post(
        path: '/user',
        summary: 'Поиск пользователя по имени',
        description: 'Поиск пользователя по имени',
        tags: ['user'],
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
                            new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail.ru'),
                            new OAT\Property(property: 'email_verified_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                            new OAT\Property(property: 'created_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                            new OAT\Property(property: 'updated_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                        ]
                    ))
                ])
            ),
            new OAT\Response(
                response: 401,
                description: 'Ошибка'
            )
        ],
        parameters: [new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'name', type: 'string', format: 'name', example: 'Oleg'),
            ])
        )]
    )]
    public function user(Request $request)
    {
        $name = urldecode($request->name);
        $user_id = $request->user()->id;
        
        return self::response(User::where('name', 'LIKE', '%'.$name.'%')->where('id', '!=', $user_id)->
        paginate(perPage: env('PER_PAGE'), page: $request->page));
    }

    #[OAT\Post(
        path: '/user/current',
        summary: 'Получение данных текущего пользователя',
        description: 'Получение данных текущего пользователя',
        tags: ['user'],
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
                            new OAT\Property(property: 'email', type: 'string', format: 'email', example: 'Oleg@mail.ru'),
                            new OAT\Property(property: 'email_verified_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                            new OAT\Property(property: 'created_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                            new OAT\Property(property: 'updated_at', type: 'string', format: 'TIMESTAMP', example: '2025-06-21T12:56:45.000000Z'),
                        ]
                    )
                ])
            ),
        ]
    )]
    public function current_user(Request $request)
    {
        return self::response($request->user());
    }

    #[OAT\Post(
        path: '/user/logout',
        summary: 'Сброс аутентификации',
        description: 'Сброс аутентификации',
        tags: ['user'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
            ),
        ]
    )]
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
    
        return self::response(statusCode: 200);
    }
}