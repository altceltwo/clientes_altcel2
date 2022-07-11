<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
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
        $data['invoiceBool'] = $request->invoiceBool;
        $data['rightsMinBool'] = $request->rightsMinBool;
        $data['contractAdhesionBool'] = $request->contractAdhesionBool;
        $data['useInfoFirst'] = $request->useInfoFirst;
        $data['useInfoSecond'] = $request->useInfoSecond;
        $data['who_did_id'] = $request->who_did_id;
        $firma = $request->firma;
       

        $validateExists = Contract::where('msisdn',$data['msisdn'])->exists();

        if($firma == null || $firma == 'null'){
            $data['signature'] = null;
        }else{
            if($validateExists){
               $contractExist =  Contract::where('msisdn',$data['msisdn'])->first();
               $data['signature'] = $contractExist->signature;
            }else{
                $data['signature'] = "signature".$data['client_id'].".png";
            }
        }
        // return $data['signature'];

        $dataActivation = DB::table('numbers')
                             ->join('activations','activations.numbers_id','=','numbers.id')
                             ->where('numbers.MSISDN',$data['msisdn'])
                             ->select('activations.id AS activation_id','activations.date_activation AS date_activation')
                             ->get();

        $data['activation_id'] = $dataActivation[0]->activation_id;

        
        if(!$validateExists){
            Contract::insert($data);
        }
        $data['date_activation'] = $dataActivation[0]->date_activation;

        if($data['product'] == 'MIFI'){
            $data['description'] = 'Plan 5GB MIFI';
            $data['folioIFT'] = '397885';
            $data['priceMonthly'] = '100';
        }else if($data['product'] == 'HBB'){
            $data['description'] = 'Plan 50GB 5MBPS HBB';
            $data['folioIFT'] = '405974';
            $data['priceMonthly'] = '189';
        }

        // return $request;
        $pdf = PDF::loadView('layouts.prueba',$data);
        return $pdf->download('contrato_'.$data['name'].'_'.$data['lastnameP'].'_'.$data['lastnameM'].'-'.'_'.$data['client_id'].'.pdf');
        
        // return $pdf->stream('prueba.pdf');
    }
}
