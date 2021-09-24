<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealerController extends Controller
{
    //

    public function index(Request $request){
        if(isset($request['dealer']) && isset($request['saldo']) && isset($request['row']) && isset($request['username'])){
            $data['data_dealer'] = array(
                'username' => $request['username'].'@altcel.com',
                'password' => '123456789'
            );

            return view('dealers.register', $data);
        }else{
            return "No tiene permiso...";
        }
    }
}
