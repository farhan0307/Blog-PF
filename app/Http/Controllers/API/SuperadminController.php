<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
//use Dotenv\Validator;
use Illuminate\Http\Request;
//use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuperadminController extends Controller
{
    public function register(Request $request){
        //Validation
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password
        '
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
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email,'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['email'] = $user->email;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'Super Admin Successfully Login'
            ];
            return response()->json($response,200); 
        }else{
            $response = [
            'success' => false,
            'message' => 'Wrong Credential'
            ];
        }
        return response()->json($response);
    }
    //
}
