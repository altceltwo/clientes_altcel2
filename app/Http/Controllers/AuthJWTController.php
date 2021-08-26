<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthJWTController extends Controller
{
    public function createJWT(Request $request){
        $credentials = $request->only("email", "password");
        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $myTTL = 5; //minutes
            JWTAuth::factory()->setTTL($myTTL);
            $token = JWTAuth::attempt($credentials);

            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }

    public function createJWTTen(Request $request){
        $credentials = $request->only("email", "password");
        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $myTTL = 10; //minutes
            JWTAuth::factory()->setTTL($myTTL);
            $token = JWTAuth::attempt($credentials);

            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'invalid_credentials']);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token']);
        }
        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser(){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }
}
