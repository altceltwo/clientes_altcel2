<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\User;
use App\Contract;

class PDFController extends Controller
{
    public function PDF(Request $request){
        $data['name'] = $request->name;
        $data['lastnameP'] = $request->lastnameP;
        $data['lastnameM'] = $request->lastnameM;
        $data['email'] = $request->email;
        $data['rfc'] = $request->rfc;
        $data['cellphone'] = $request->cellphone;
        $data['typePhone'] = $request->typePhone;
        $data['client_id'] = $request->client_id;
        $data['street'] = $request->street;
        $data['exterior'] = $request->exterior;
        $data['interior'] = $request->interior;
        $data['colonia'] = $request->colonia;
        $data['municipal'] = $request->municipal;
        $data['state'] = $request->state;
        $data['postal_code'] = $request->postal_code;
        $data['marca'] = $request->marca;
        $data['modelo'] = $request->modelo;
        $data['serialNumber'] = $request->serialNumber;
        $data['deviceQuantity'] = $request->deviceQuantity;
        $data['devicePrice'] = $request->devicePrice;
        $data['ratePrice'] = $request->ratePrice;
        $data['product'] = $request->product;
        $data['msisdn'] = $request->msisdn;
        $data['icc'] = $request->icc;
        $data['typePayment'] = $request->typePayment;
        $data['bank'] = $request->bank;
        $data['cardNumber'] = $request->cardNumber;
        $data['cvv'] = $request->cvv;
        $data['monthExpiration'] = $request->monthExpiration;
        $data['yearExpiration'] = $request->yearExpiration;
        $data['invoiceBool'] = $request->invoiceBool;
        $data['rightsMinBool'] = $request->rightsMinBool;
        $data['contractAdhesionBool'] = $request->contractAdhesionBool;
        $data['useInfoFirst'] = $request->useInfoFirst;
        $data['useInfoSecond'] = $request->useInfoSecond;
        $data['who_did_id'] = $request->who_did_id;

        $data['signature'] = "signature".$data['client_id'].".png";
        Contract::insert($data);

        // return $request;
        $pdf = PDF::loadView('layouts.prueba',$data);
        return $pdf->download('contrato_'.$data['name'].'_'.$data['lastnameP'].'_'.$data['lastnameM'].'-'.'_'.$data['client_id'].'.pdf');
        
        // return $pdf->stream('prueba.pdf');
    }
}
