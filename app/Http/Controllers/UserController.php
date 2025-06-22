<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller{

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

    public function user(Request $request)
    {
        $name = urldecode($request->name);
        $user_id = $request->user()->id;
        
        return self::response(User::where('name', 'LIKE', '%'.$name.'%')->where('id', '!=', $user_id)->
        paginate(perPage: env('PER_PAGE'), page: $request->page));
    }

    public function current_user(Request $request)
    {
        return self::response($request->user());
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
    
        return self::response(statusCode: 200);
    }
}