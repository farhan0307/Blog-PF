<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    //
    public function register(Request $request){
        //Validation
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
        if(validator()->fails()){
            $response = [
                'success' => false,
                'message' => validator()->errors()
            ];
            return response()->json($response,400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['email'] =$user->email;

        $response = [
            'success' => true,
            'data' => $success,
            'message'=> 'User Register Successfully'
        ];
        return response()->json($response , 200);
    }
}
