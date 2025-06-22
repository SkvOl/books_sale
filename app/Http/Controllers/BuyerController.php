<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Buyer;


class BuyerController extends Controller{

    public function index(Request $request){
        $buyer = Buyer::select()->paginate(perPage: env('PER_PAGE'), page: $request->page);

        return self::response($buyer);
    }

    public function show(string $id){
        return self::response(Buyer::find($id));
    }

    
    public function update(Request $request, string $id){
        $buyer = Buyer::find($id);

        $buyer->fill($request->only(['name', 'email']));
        $buyer->save();

        return self::response($buyer->toArray());
    }

    
    public function destroy(Buyer $buyer){ 
        $buyer::destroy($buyer->id);

        return self::response([]);
    }
}