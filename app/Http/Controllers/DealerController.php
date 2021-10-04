<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealerController extends Controller
{
    //

    public function index(Request $request){
        if(isset($request['dealer']) && isset($request['saldo']) && isset($request['row']) && isset($request['username'])){
            $data['user'] = 'dealer@altcel2.com';
            $data['pass'] = 'Dealer$Altcel';
            return view('dealers.login', $data);
        }else{
            return "No tiene permiso...";
        }
    }
}
