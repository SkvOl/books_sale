<?php
namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Events\BookUpdateEvent;
use Illuminate\Http\Request;
use App\Events\SaleEvent;
use App\Models\Author;
use App\Models\Buyer;
use App\Models\Book;
use App\Models\Sale;


class BookController extends Controller{

    public function index(Request $request){

        $books = Book::with('authors')->where('quantity', '>', 0);

        if(isset($request->search)) {
            $books = self::searchBooks($books, $request);
        }

        if(isset($request->sort_item) AND isset($request->sort_type)){
            $books = $books->orderBy($request->sort_item, $request->sort_type);
        }

        return self::response($books->paginate(perPage: env('PER_PAGE'), page: $request->page));
    }

    public function show(string $id){
        return self::response(Book::with('authors')->find($id));
    }
    
    public function update(Request $request, string $id){
        $book = Book::find($id);

        $book->fill($request->only(['title', 'description', 'cover_url', 'price', 'quantity']));
        $book->save();

        return self::response($book->toArray());
    }

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
    
    public function destroy(Book $book){ 
        $book::destroy($book->id);

        return self::response([]);
    }

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