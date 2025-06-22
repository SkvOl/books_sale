<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Author;


class AuthorController extends Controller{

    public function index(Request $request){
        $author = Author::select()->paginate(perPage: env('PER_PAGE'), page: $request->page);

        return self::response($author);
    }

    public function show(string $id){
        return self::response(Author::find($id));
    }


    public function update(Request $request, string $id){
        $author = Author::find($id);

        $author->fill($request->only(['first_name', 'last_name', 'rank', 'avatar_url']));
        $author->save();

        return self::response($author->toArray());
    }
    
    public function store(Request $request){
        $author = new Author;
        
        $author->fill($request->only(['first_name', 'last_name', 'rank', 'avatar_url']));
        $author->save();

        return self::response($author->toArray());
    }
    
    
    public function destroy(Author $author){ 
        $author::destroy($author->id);

        return self::response([]);
    }
}