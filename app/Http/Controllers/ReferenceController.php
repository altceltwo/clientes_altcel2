<?php

namespace App\Http\Controllers;
use App\Conekta\ConektaPayment;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    protected $ConektaPayment;

    function __construct(){
        $this->ConektaPayment = new ConektaPayment();
    }

    public function createReference(Request $request){
        // return $request;
        $channel = $request->input('channel');
        if($channel == 1){
            $x = app('App\Http\Controllers\OpenPayController')->store($request);
        }else if($channel == 2){
            $x = $this->ConektaPayment->createOrder($request);
        }else if($channel == 3){
            $x = $this->ConektaPayment->createPaymentLink($request);
        }else if($channel == 4){
            $x = $this->ConektaPayment->createPaymentLinkAllMethods($request);
        }
        return $x;
    }

    public function multipaymentOreda(Request $request){
        $x = $this->ConektaPayment->multiPaymentOreda($request);
        return $x;
    }
}
