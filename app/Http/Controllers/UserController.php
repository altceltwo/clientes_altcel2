<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function changeRolUser(Request $request) {
        $user = $request->post('user');
        $rol = $request->post('rol');
        $x = User::where('id','=',$user)->update(['role_id'=>$rol]);
        return $x;
    }

    public function myProfile(){
        return view('myProfile');
    }

    public function updateMyProfile(Request $request){
        $id = $request->get('id');
        $password = $request->get('password');
        if($password == "null"){
            $request = request()->except('id','password');
            User::where('id',$id)->update($request);
        }else{
            $request = request()->except('id');
            $password = Hash::make($password);
            $request['password'] = $password;
            User::where('id',$id)->update($request);
        }
        return $request;
    }
}
