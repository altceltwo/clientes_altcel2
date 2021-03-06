<?php

namespace App\Http\Controllers;
use DB;
use Http;
use Validator;
use App\Number;
use Illuminate\Http\Request;
use App\GuzzleHttp;

class ApiController extends Controller
{
    public function getOffersRatesSurplus(Request $request){
        // return $request;
        $msisdn = $request->get('msisdn');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://10.44.0.70/get-offers-rates-surplus',['msisdn'=>$msisdn]);
        return $response;
    }

    public function getOffersRatesDiff(Request $request){ 
        $msisdn = $request->get('msisdn');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://10.44.0.70/get-offers-rates-diff-api',['msisdn'=>$msisdn]);

        return $response;
    }

    public function getOffersRatesDiffPublic(Request $request){ 
        $msisdn = $request->get('msisdn');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://10.44.0.70/get-offers-rates-diff-api-public',['msisdn'=>$msisdn]);

        return $response;
    }

    public function getOffersRatesActivate(Request $request){
        $icc = $request->get('icc');
        $dataICC = Number::where('icc_id',$icc)->where('deleted_at',null)->first();
        $product = trim($dataICC->producto);

        $rates = DB::table('rates')
                    ->join('offers','offers.id','=','rates.alta_offer_id')
                    ->where('rates.promotion_id','=',null)
                    ->where('offers.product','=',$product)
                    ->where('rates.status','activo')
                    ->where('offers.type','normal')
                    ->select('rates.*','offers.name AS offer_name','offers.id AS offer_id','offers.product AS offer_product', 'offers.offerID')
                    ->get();

        $ratesFinal = [];
        foreach ($rates as $rate) {
            array_push($ratesFinal,array(
                'offerID' => $rate->offerID,
                'id' => $rate->id,
                'price' => $rate->price,
                'name' => $rate->name,
                'recurrency' => $rate->recurrency,
                'offer_product' => $rate->offer_product,
                'promo_bool' => $rate->promo_bool,
                'device_price' => $rate->device_price
            ));
        }

        $response['rates'] = $ratesFinal;

        return $response;

        return $dataICC;
    }

    public function generateReference(Request $request){
        $from = $request->get('from');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://10.44.0.70/create-reference-api',[
            'number_id'=>$request->get('number_id'),
            'name'=>$request->get('name'),
            'lastname'=>$request->get('lastname'),
            'email'=>$request->get('email'),
            'cel_destiny_reference'=>$request->get('cel_destiny_reference'),
            'amount'=>$request->get('amount'),
            'offer_id'=>$request->get('offer_id'),
            'concepto'=>$request->get('concepto'),
            'type'=>$request->get('type'),
            'channel'=>$request->get('channel'),
            'rate_id'=>$request->get('rate_id'),
            'user_id'=>$request->get('user_id'),
            'client_id'=>$request->get('client_id'),
            'pay_id'=>$request->get('pay_id'),
            'quantity'=>$request->get('quantity'),
        ]);
        if($from == 'portal_cautivo'){
            return response()->json($response);
        }else{
            return $response;
        }
    
    }

    public function getRatesAlta(Request $request){
        $producto = $request->post('product');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://10.44.0.70/get-rates-alta-api',[
            'product' => $producto
        ]);
        return $response->json();
    }

    public function preactivationAltan(Request $request){
        // Datos del cliente
        $name = $request->post('name');
        $lastname = $request->post('lastname');
        $rfc = $request->post('rfc');
        $date_born = $request->post('date_born');
        $address = $request->post('address');
        $email = $request->post('email');
        $ine_code = $request->post('ine_code');
        $cellphone = $request->post('cellphone');

        $activate_bool = $request->post('activate_bool');
        $email_not = $request->post('email_not');
        $from = $request->post('from');
        $icc_id = $request->post('icc_id');
        $imei = $request->post('imei');
        $lat_hbb = $request->post('lat_hbb');
        $lng_hbb = $request->post('lng_hbb');
        $msisdn = $request->post('msisdn');
        $offer_id = $request->post('offer_id');
        $price = $request->post('price');
        $price_device = $request->post('price_device');
        $price_rate = $request->post('price_rate');
        $product = $request->post('product');
        $rate_id = $request->post('rate_id');
        $rate_recurrency = $request->post('rate_recurrency');
        $scheduleDate = $request->post('scheduleDate');
        $serial_number = $request->post('serial_number');
        $sim_altcel = $request->post('sim_altcel');
        $statusActivation = $request->post('statusActivation');
        $type_person = $request->post('type_person');
        $who_did_id = $request->post('who_did_id');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://10.44.0.70/activationsGeneralApi',[
            'name' => $name,
            'lastname' => $lastname,
            'rfc' => $rfc,
            'date_born' => $date_born,
            'address' => $address,
            'email' => $email,
            'ine_code' => $ine_code,
            'cellphone' => $cellphone,
            'name_child' => $name,
            'lastname_child' => $lastname,
            'rfc_child' => $rfc,
            'date_born_child' => $date_born,
            'address_child' => $address,
            'email_child' => $email,
            'ine_code_child' => $ine_code,
            'cellphone_child' => $cellphone,
            'activate_bool' => $activate_bool,
            'email_not' => $email_not,
            'from' => $from,
            'icc_id' => $icc_id,
            'imei' => $imei,
            'lat_hbb' => $lat_hbb,
            'lng_hbb' => $lng_hbb,
            'msisdn' => $msisdn,
            'offer_id' => $offer_id,
            'price' => $price,
            'price_device' => $price_device,
            'price_rate' => $price_rate,
            'product' => $product,
            'rate_id' => $rate_id,
            'rate_recurrency' => $rate_recurrency,
            'scheduleDate' => $scheduleDate,
            'serial_number' => $serial_number,
            'sim_altcel' => $sim_altcel,
            'statusActivation' => $statusActivation,
            'type_person' => $type_person,
            'who_did_id' => $who_did_id
        ]);
        return $response;
    }
    public function cancelActivation(Request $request){
        $id = $request->post('id');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->delete('http://10.44.0.70/rollback-preactivate-api/'.$id);
        return $response;
    }
    public function purchaseProduct(Request $request){
        $msisdn = $request->post('msisdn');
        $offer = $request->post('offerID');
        $user_id = $request->post('user_id');
        $price = $request->post('price');
        $offer_id = $request->post('offer_id');
        $rate_id = $request->post('rate_id');
        $comment = $request->post('comment');
        $reason = $request->post('reason');
        $status = $request->post('status');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://10.44.0.70/purchase-api',[
            'msisdn' => $msisdn,
            'offer' => $offer,
            'offer_id' => $offer_id,
            'rate_id' => $rate_id,
            'price' => $price,
            'user_id' => $user_id,
            'comment' => $comment,
            'reason' => $reason,
            'status' => $status
        ]);

        return $response;
    }

    public function purchaseProductPos(Request $request){
        $msisdn = $request->post('msisdn');
        $offer = $request->post('offerID');
        $user_id = $request->post('user_id');
        $price = $request->post('price');
        $offer_id = $request->post('offer_id');
        $rate_id = $request->post('rate_id');
        $comment = $request->post('comment');
        $reason = $request->post('reason');
        $status = $request->post('status');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://10.44.0.70/purchase-api',[
            'msisdn' => $msisdn,
            'offer' => $offer,
            'offer_id' => $offer_id,
            'rate_id' => $rate_id,
            'price' => $price,
            'user_id' => $user_id,
            'comment' => $comment,
            'reason' => $reason,
            'status' => $status
        ]);

        return $response;
    }

    public function purchaseProductPosTest(Request $request){
        $msisdn = $request->post('msisdn');
        $offer = $request->post('offerID');
        $user_id = $request->post('user_id');
        $price = $request->post('price');
        $offer_id = $request->post('offer_id');
        $rate_id = $request->post('rate_id');
        $comment = $request->post('comment');
        $reason = $request->post('reason');
        $status = $request->post('status');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://newcrm.altcel/purchase-api',[
            'msisdn' => $msisdn,
            'offer' => $offer,
            'offer_id' => $offer_id,
            'rate_id' => $rate_id,
            'price' => $price,
            'user_id' => $user_id,
            'comment' => $comment,
            'reason' => $reason,
            'status' => $status
        ]);

        return $response;
    }

    public function saveManualPay(Request $request){
        // return $request['service'];
        $service = $request['service'];
        $payID = $request['payID'];
        $monto = $request['monto'];
        $typePay = $request['typePay'];
        $folioPay = $request['folioPay'];
        $estadoPay = $request['estadoPay'];
        $montoExtra = $request['montoExtra'];
        $user_id = $request['user_id'];
        $status_consigned = $request['status_consigned'];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://10.44.0.70/save-manual-pay',[
            'service' => $service,
            'payID' => $payID,
            'monto' => $monto,
            'typePay' => $typePay,
            'folioPay' => $folioPay,
            'estadoPay' => $estadoPay,
            'montoExtra' => $montoExtra,
            'user_id' => $user_id
        ]);
        return $response;
        
    }

    public function changeProduct(Request $request){
        // return $request;
        $address = $request->post('address');
        $msisdn = $request->post('msisdn');
        $offerID = $request->post('offerID');
        $offer_id = $request->post('offer_id');
        $rate_id = $request->post('rate_id');
        $scheduleDate = $request->post('scheduleDate');
        $type = $request->post('type');
        $producto = $request->post('producto');
        $user_id = $request->post('user_id');
        $amount = $request->post('amount');

        $comment = $request->post('comment');
        $reason = $request->post('reason');
        $statusC = $request->post('status');

        // Datos para guardar pago de mensualidad en caso de que haya alguno
        $service = $request['service'];
        $payID = $request['payID'];
        $monto = $request['amount'];
        $typePay = $request['typePay'];
        $folioPay = $request['folioPay'];
        $estadoPay = $request['estadoPay'];
        $montoExtra = $request['montoExtra'];

        $consultUF = app('App\Http\Controllers\AltanController')->consultUF($msisdn);
        $responseSubscriber = $consultUF['responseSubscriber'];
        $status = $responseSubscriber['status']['subStatus'];
        $bool = 0;

        // return $type;

        if($status == "Suspend (B2W)"){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('http://10.44.0.70/activate-deactivate/DN-api',[
                'msisdn' => $msisdn,
                'type' => 'out_in',
                'status' => 'inactivo'
            ]);
            $bool = $response['bool'];
        }else{
            $bool = 1;
        }

        if($bool == 1){

            $reason = $payID != 0 ? 'mensualidad' : $reason;
            $statusC = $payID != 0 ? 'completado' : $statusC;
            $payment_id = $payID != 0 ? $payID : null;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('http://10.44.0.70/change-product',[
                'address' => $address,
                'msisdn' => $msisdn,
                'offerID' => $offerID,
                'offer_id' => $offer_id,
                'rate_id' => $rate_id,
                'scheduleDate' => $scheduleDate,
                'type' => $type,
                'user_id' => $user_id,
                'amount' => $amount,
                'comment' => $comment,
                'reason' => $reason,
                'status' => $statusC,
                'pay_id' => $payment_id,
                'reference_id' => null
            ]);
            // $response = response()->json($response);
            // return $response;
            if($response['http_code'] == 1){
                if($payID != 0){
                    // return 'se har?? el pago';
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json'
                    ])->get('http://10.44.0.70/save-manual-pay',[
                        'service' => $service,
                        'payID' => $payID,
                        'monto' => $monto,
                        'typePay' => $typePay,
                        'folioPay' => $folioPay,
                        'estadoPay' => $estadoPay,
                        'montoExtra' => $montoExtra,
                        'user_id' => $user_id
                    ]);
                    $x = json_decode($response);
                    // return $n;
                    if($x == 1){
                        return response()->json(["http_code"=>1,"message"=>"Todo se ha ejecutado de manera exitosa.","order"=>$response['order']]); 
                    }else{
                        return response()->json(["http_code"=>3,"message"=>"Todo se ejecutado de manera exitosa, excepto el pago, notifique a Desarrollo.","order"=>$response['order']]); 
                    }
                }
                return response()->json(["http_code"=>1,"message"=>"Todo se ha ejecutado de manera exitosa.","order"=>$response['order']]);
            }else{
                return response()->json(["http_code"=>$response['http_code'],"message"=>$response['message'],"order"=>$response['order']]);
            }
            
        }else{
            return response()->json(["http_code"=>2,"message"=>"Parece que el status de la SIM no permite realizar el cambio de producto.",'order'=>500]);
        }
    }

    public function changeProductTest(Request $request){
        // return $request;
        $address = $request->post('address');
        $msisdn = $request->post('msisdn');
        $offerID = $request->post('offerID');
        $offer_id = $request->post('offer_id');
        $rate_id = $request->post('rate_id');
        $scheduleDate = $request->post('scheduleDate');
        $type = $request->post('type');
        $producto = $request->post('producto');
        $user_id = $request->post('user_id');
        $amount = $request->post('amount');

        $comment = $request->post('comment');
        $reason = $request->post('reason');
        $statusC = $request->post('status');

        // Datos para guardar pago de mensualidad en caso de que haya alguno
        $service = $request['service'];
        $payID = $request['payID'];
        $monto = $request['amount'];
        $typePay = $request['typePay'];
        $folioPay = $request['folioPay'];
        $estadoPay = $request['estadoPay'];
        $montoExtra = $request['montoExtra'];

        $consultUF = app('App\Http\Controllers\AltanController')->consultUF($msisdn);
        $responseSubscriber = $consultUF['responseSubscriber'];
        $status = $responseSubscriber['status']['subStatus'];
        $bool = 0;

        // return $type;

        if($status == "Suspend (B2W)"){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('http://newcrm.altcel/activate-deactivate/DN-api',[
                'msisdn' => $msisdn,
                'type' => 'out_in',
                'status' => 'inactivo'
            ]);
            $bool = $response['bool'];
        }else{
            $bool = 1;
        }

        if($bool == 1){

            $reason = $payID != 0 ? 'mensualidad' : $reason;
            $statusC = $payID != 0 ? 'completado' : $statusC;
            $payment_id = $payID != 0 ? $payID : null;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('http://newcrm.altcel/change-product',[
                'address' => $address,
                'msisdn' => $msisdn,
                'offerID' => $offerID,
                'offer_id' => $offer_id,
                'rate_id' => $rate_id,
                'scheduleDate' => $scheduleDate,
                'type' => $type,
                'user_id' => $user_id,
                'amount' => $amount,
                'comment' => $comment,
                'reason' => $reason,
                'status' => $statusC,
                'pay_id' => $payment_id,
                'reference_id' => null
            ]);
            // $response = response()->json($response);
            // return $response;
            if($response['http_code'] == 1){
                if($payID != 0){
                    // return 'se har?? el pago';
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json'
                    ])->get('http://newcrm.altcel/save-manual-pay',[
                        'service' => $service,
                        'payID' => $payID,
                        'monto' => $monto,
                        'typePay' => $typePay,
                        'folioPay' => $folioPay,
                        'estadoPay' => $estadoPay,
                        'montoExtra' => $montoExtra,
                        'user_id' => $user_id
                    ]);
                    $x = json_decode($response);
                    // return $n;
                    if($x == 1){
                        return response()->json(["http_code"=>1,"message"=>"Todo se ha ejecutado de manera exitosa.","order"=>$response['order']]); 
                    }else{
                        return response()->json(["http_code"=>3,"message"=>"Todo se ejecutado de manera exitosa, excepto el pago, notifique a Desarrollo.","order"=>$response['order']]); 
                    }
                }
                return response()->json(["http_code"=>1,"message"=>"Todo se ha ejecutado de manera exitosa.","order"=>$response['order']]);
            }else{
                return response()->json(["http_code"=>$response['http_code'],"message"=>$response['message'],"order"=>$response['order']]);
            }
            
        }else{
            return response()->json(["http_code"=>2,"message"=>"Parece que el status de la SIM no permite realizar el cambio de producto."]);
        }
    }
}
