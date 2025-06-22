<?php
namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Events\BookUpdateEvent;
use OpenApi\Attributes as OAT;
use Illuminate\Http\Request;
use App\Events\SaleEvent;
use App\Models\Author;
use App\Models\Buyer;
use App\Models\Book;
use App\Models\Sale;


class BookController extends Controller{

    #[OAT\Get(
        path: '/books',
        summary: 'Получение списка не проданных книг',
        description: 'Получение списка не проданных книг',
        tags: ['books'],
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
                            new OAT\Property(property: 'title', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'description', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'cover_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                            new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                            new OAT\Property(property: 'quantity', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'authors', type: 'array', items: new OAT\Items(
                                properties: [
                                    new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                                    new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                                    new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                                    new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                                    new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                                ]
                            )),
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

        $books = Book::with('authors')->where('quantity', '>', 0);

        if($request->photo == 1){
            /* Хоть у одного автора есть фото */
            $books = Book::whereHas('authors_with_photo')->with('authors')
            ->where('quantity', '>', 0);
        }
        elseif(isset($request->sale_sort)){
            /* Сортировка по количеству продаж */
            $books = Book::withCount('sales')->with('authors')
            ->where('quantity', '>', 0)->orderBy('sales_count', $request->sale_sort);
        }
        elseif(isset($request->popular)){
            /* Вывод только популярных книг у которых автор с рейтингом больше 75 либо количество продаж больше 3 */
            $books = Book::whereHas('authors_popular')->orWhereHas('book_popular_today')->with('authors')
            ->where('quantity', '>', 0);
        }
        else{
            $books = Book::with('authors')->where('quantity', '>', 0);
        }

        if(isset($request->search)) {
            $books = self::searchBooks($books, $request);
        }

        if(isset($request->sort_item) AND isset($request->sort_type) AND !isset($request->sale_sort)){
            $books = $books->orderBy($request->sort_item, $request->sort_type);
        }

        return self::response($books->paginate(perPage: env('PER_PAGE'), page: $request->page));
    }

    #[OAT\Get(
        path: '/books/{id}',
        summary: 'Получение одной книги',
        description: 'Получение одной книги',
        tags: ['books'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'object',
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'title', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'description', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'cover_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                            new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                            new OAT\Property(property: 'quantity', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'authors', type: 'array', items: new OAT\Items(
                                properties: [
                                    new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                                    new OAT\Property(property: 'first_name', type: 'string', format: 'first name', example: 'Oleg'),
                                    new OAT\Property(property: 'last_name', type: 'string', format: 'last name', example: 'Oleg'),
                                    new OAT\Property(property: 'rank', type: 'float', format: 'float', example: '1.24'),
                                    new OAT\Property(property: 'avatar_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                                ]
                            )),
                        ]
                    )
                ])
            ),
        ],
        parameters: [
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор книги', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ],
    )]
    public function show(string $id){
        return self::response(Book::with('authors')->find($id));
    }
    
    #[OAT\Put(
        path: '/books/{id}',
        summary: 'Изменение книги',
        description: 'Изменение книги',
        tags: ['books'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'title', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'description', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'cover_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                            new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                            new OAT\Property(property: 'quantity', type: 'int', format: 'int', example: '1'),
                        ]
                    ))
                ])
            ),
        ],
        parameters: [
            new OAT\RequestBody(
                required: true,
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'title', type: 'string', format: 'string', example: 'text'),
                    new OAT\Property(property: 'description', type: 'string', format: 'string', example: 'text'),
                    new OAT\Property(property: 'cover_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                    new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                    new OAT\Property(property: 'quantity', type: 'int', format: 'int', example: '1'),
                ]),
            ),
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор книги', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ]
    )]
    public function update(Request $request, string $id){
        $book = Book::find($id);

        $book->fill($request->only(['title', 'description', 'cover_url', 'price', 'quantity']));
        $book->save();
        if(!isset($request->isTest)) event(new BookUpdateEvent($book));

        return self::response($book->toArray());
    }

    #[OAT\Post(
        path: '/books',
        summary: 'Создание книги',
        description: 'Создание книги',
        tags: ['books'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(property: 'status', type: 'string', format: 'string', example: 'Successfully'),
                    new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(
                        properties: [
                            new OAT\Property(property: 'id', type: 'int', format: 'int', example: '1'),
                            new OAT\Property(property: 'title', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'description', type: 'string', format: 'string', example: 'text'),
                            new OAT\Property(property: 'cover_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                            new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                            new OAT\Property(property: 'quantity', type: 'int', format: 'int', example: '1'),
                        ]
                    ))
                ])
            ),
        ],
        parameters: [new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'title', type: 'string', format: 'string', example: 'text'),
                new OAT\Property(property: 'description', type: 'string', format: 'string', example: 'text'),
                new OAT\Property(property: 'cover_url', type: 'string', format: 'url', example: 'http://rodriguez.com/aut-similique-laudantium-qui-assumenda-ducimus-quisquam'),
                new OAT\Property(property: 'price', type: 'float', format: 'float', example: '1.2'),
                new OAT\Property(property: 'quantity', type: 'int', format: 'int', example: '1'),
            ])
        )]
    )]
    public function store(Request $request){
        throw_if(!isset($request->authors), new \Exception('Authors is missing'), 400);
        $book = collect();
        
        DB::transaction(function() use (&$book, $request) {
            $author = Author::whereIn('id', $request->authors)->get();
            throw_if($author->count() != count($request->authors), new \Exception('One ore more authors not found'), 400);
            
            
            $book = new Book;

            $book->fill($request->only(['title', 'description', 'cover_url', 'price', 'quantity']));
            $book->save();
            
            $book->authors()->attach($request->authors);
        });

        return self::response($book->toArray());
    }
    
    #[OAT\Delete(
        path: '/books/{id}',
        summary: 'Удаление книги',
        description: 'Удаление книги',
        tags: ['books'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
            ),
        ],
        parameters: [
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор книги', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ],
    )]
    public function destroy(Book $book){ 
        $book::destroy($book->id);

        return self::response([]);
    }

    #[OAT\Post(
        path: '/books/{id}/buy',
        summary: 'Покупка книги',
        description: 'Покупка книги',
        tags: ['books'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Успешно',
            ),
        ],
        parameters: [
            new OAT\Parameter(name: 'id', parameter: 'id', description: 'Идентификатор книги', in: 'query', required: true, deprecated: false, allowEmptyValue: true),
        ],
    )]
    public function buy(Book $book, Request $request){
        DB::transaction(function() use ($book, $request) {
            $book->decrement('quantity'); 
            $user = $request->user();

            Buyer::insertOrIgnore([
                $user->only(['id', 'email', 'name'])
            ]);

            Sale::insert([
                'book_id'   => $book->id,
                'client_id' => $user->id,
                'price'     => $book->price
            ]);

            if(!isset($request->isTest)) event(new SaleEvent($book));
        });

        return self::response([]);
    }

    /**
     * Поиск книги у которой есть подстрока request->search
     * 
     * @param Book $books
     * @param Request $request
     * @return Builder
     */
    private static function searchBooks($books, $request)
    {
        return $books->where(function (Builder $query) use ($request) {
            $columns = Schema::getColumnListing('books');

            foreach ($columns as $column) {
                $query->orWhereLike($column, "%$request->search%");
            }
        });
    }
}