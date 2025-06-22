<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Book;


class SaleController extends Controller{

    public function index(Request $request){
        $sale = Sale::select()->paginate(perPage: env('PER_PAGE'), page: $request->page);

        return self::response($sale);
    }

    public function show(string $id){
        return self::response(Sale::find($id));
    }

    
    public function update(Request $request, string $id){
        $sale = Sale::find($id);

        $sale->fill($request->only(['book_id', 'client_id', 'price']));
        $sale->save();

        return self::response($sale->toArray());
    }

    
    public function destroy(Sale $sale){ 
        $sale::destroy($sale->id);

        return self::response([]);
    }
}