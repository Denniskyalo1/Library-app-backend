<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException; 

class AuthController extends Controller
{
  public function register(Request $request){
      $validated = $request->validate([
        'name'=>['required','string','max:255'],
        'email'=>['required','email','unique:users','max:255'],
        'password'=>['required','string','min:6'],
      ]);

      $user = User::create([
          'name'=>$validated['name'],
          'email'=>$validated['email'],
          'password'=>Hash::make($validated['password']),   
      ]);

      $token = $user->createToken('AuthToken')->plainTextToken;

      return response()->json([
        'status'=>'200',
        'message' => 'User registered successfully', 
        'user' => $user, 
        'token'=> $token], 200);

      
  }

    public function login(Request $request){
        $validated = $request->validate([
            'email'=>['required','email'],
            'password'=>['required','string','min:6'],
        ]);

        $user = User::where('email',$validated['email'])->first();  

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials, please try again',
            ], 401);
        }
        $user->tokens()->delete();
        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json([
            'status'=>'200',
            'message' => 'Login successfully', 
            'user' => [
                'id'=> $user->id,
                'name'=> $user->name,
                'email'=> $user->email,
            ], 
            'token'=> $token], 200);
    
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken->delete();
        return response(['message'=>'Logged out'], 201);
    }
    
    
}
