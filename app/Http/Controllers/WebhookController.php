<?php

namespace App\Http\Controllers;


use DB;
use Http;
use DateTime;
use App\Reference;
use App\Recharge;
use App\Ethernetpay;
use App\Pay;
use App\Pack;
use App\Rate;
use App\Offer;
use App\Instalation;
use App\Activation;
use App\Number;
use App\Shipping;
use App\User;
use App\Client;
use Illuminate\Http\Request;
use App\Mail\NotifyShipping;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public function notificationOpenPay(Request $request) {
        $type = $request->type;
        $event_date = $request->event_date;
        $event_date = substr($event_date,0,19);
        $event_date = str_replace("T", "", $event_date);
        $event_date = str_replace("-", "", $event_date);
        $event_date = str_replace(":", "", $event_date);
        $transaction = $request->transaction;
        $reference_id = $transaction['id'];
        $status = $transaction['status'];
        $authorization = $transaction['authorization'];

        if($type == 'charge.succeeded'){
            $fee = $transaction['fee'];
            $fee_amount = $fee['amount'];
            $fee_tax = $fee['tax'];
            $fee_currency = $fee['currency'];

            $x = Reference::where('reference_id',$reference_id)->first();
            $referencestype_id = $x->referencestype_id;
            
            if($referencestype_id){
                Ethernetpay::where('reference_id',$reference_id)->update([
                    'status' => 'completado'
                ]);
                $y = Ethernetpay::where('reference_id',$reference_id)->first();

                $instalation_id = $y->instalation_id;
                $fecha_pay = $y->date_pay;
                $fecha_limit = $y->date_pay_limit;
                // Obtenemos el siguiente mes
                $fecha_pay_new = strtotime('+3 days', strtotime($fecha_pay));
                $fecha_pay_new = date('Y-m-d',$fecha_pay_new);
                // Obtenemos su último día
                $fecha_pay_new = new DateTime($fecha_pay_new);
                $fecha_pay_new->modify('last day of this month');
                $fecha_pay_new = $fecha_pay_new->format('Y-m-d');
                // Obtenemos nueva fecha límite de pago
                $fecha_limit_new = strtotime('+5 days', strtotime($fecha_pay_new));
                $fecha_limit_new = date('Y-m-d',$fecha_limit_new);
                
                Ethernetpay::insert([
                    'date_pay' => $fecha_pay_new,
                    'date_pay_limit' => $fecha_limit_new,
                    'instalation_id' => $instalation_id,
                    'status' => 'pendiente'
                ]);
            }

            Reference::where('reference_id',$reference_id)->update([
                'event_date_complete' => $event_date,
                'status' => $status,
                'authorizacion' => $authorization,
                'fee_amount' => $fee_amount,
                'fee_tax' => $fee_tax,
                'fee_currency' => $fee_currency
            ]); 
        }
        return response()->json(['http_code'=>'200']);
    }

    public function openpayPays(Request $request){
        if(isset($request['start']) && isset($request['end'])){
            if($request['start'] != null && $request['end'] != null){
                $year =  substr($request['start'],6,4);
                $month = substr($request['start'],0,2);
                $day = substr($request['start'],3,2);
                $init_date = $year.'-'.$month.'-'.$day;

                $year =  substr($request['end'],6,4);
                $month = substr($request['end'],0,2);
                $day = substr($request['end'],3,2);
                $final_date = $year.'-'.$month.'-'.$day;

                // return $date_init.' y '.$date_final;
            }else{
                
                $today = date('D');
        
                if($today == 'Fri'){
                    $init_date = date('Y-m-d');
                    $final_date = date("Y-m-d", strtotime('next Thursday', time()));
                }else if($today == 'Thu'){
                    $init_date = date("Y-m-d", strtotime('last Friday', time()));
                    $final_date = date('Y-m-d');
                }else{
                    $init_date = date("Y-m-d", strtotime('last Friday', time()));
                    $final_date = date("Y-m-d", strtotime('next Thursday', time()));
                }
            }
        }else{
            $today = date('D');
        
            if($today == 'Fri'){
                $init_date = date('Y-m-d');
                $final_date = date("Y-m-d", strtotime('next Thursday', time()));
            }else if($today == 'Thu'){
                $init_date = date("Y-m-d", strtotime('last Friday', time()));
                $final_date = date('Y-m-d');
            }else{
                $init_date = date("Y-m-d", strtotime('last Friday', time()));
                $final_date = date("Y-m-d", strtotime('next Thursday', time()));
            }
        }
        

        $init_date = $init_date.' 00:00:00';
        $final_date = $final_date.' 23:59:59';
        $data['date_init'] = $init_date;
        $data['date_final'] = $final_date;
        
        // $data['referencesPendings'] = WebhookController::pendingPaysAltan($init_date,$final_date);
        $data['paysCompleted'] = WebhookController::completedPaysAltan($init_date,$final_date);
        $data['paysReferencedCompleted'] = WebhookController::completedPaysReferencedAltan($init_date,$final_date);
        // return $data['paysCompleted'];
        // $data['conectaReferencesPendings'] = WebhookController::pendingPaysConecta($init_date,$final_date);
        $data['paysConectaCompleted'] = WebhookController::completedPaysConecta($init_date,$final_date);
        $data['paysReferencedConectaCompleted'] = WebhookController::completedPaysReferencedConecta($init_date,$final_date);
        
        return view('webhooks.openpay',$data);
    }

    public function pendingPaysAltan($init_date,$final_date){
        $data = DB::table('references')
                   ->join('referencestypes','referencestypes.id','=','references.referencestype_id')
                   ->join('channels','channels.id','=','references.channel_id')
                   ->join('rates','rates.id','=','references.rate_id')
                   ->join('numbers','numbers.id','=','references.number_id')
                   ->join('users','users.id','=','references.client_id')
                   ->where('references.status','in_progress')
                   ->whereBetween('references.creation_date',[$init_date,$final_date])
                   ->select('references.reference','references.status','references.creation_date','references.description','references.amount','references.user_id',
                        'channels.name AS channel_name','rates.name AS rate_name',
                        'numbers.MSISDN AS DN','numbers.producto AS number_product',
                        'referencestypes.name AS type_name',
                        'users.name AS user_name')
                        ->get();
        return $data;
    }

    public function completedPaysAltan($init_date,$final_date){
        $data = DB::table('pays')
                   ->join('activations','activations.id','=','pays.activation_id')
                   ->join('numbers','numbers.id','=','activations.numbers_id')
                   ->join('rates','rates.id','=','activations.rate_id')
                   ->whereBetween('pays.updated_at',[$init_date,$final_date])
                   ->where('pays.status','completado')
                   ->where('type_pay','=','deposito')
                   ->orWhere('type_pay','=','transferencia')
                   ->orWhere('type_pay','=','efectivo')
                   ->select('pays.*','numbers.producto AS number_product','rates.price AS amount_waited')
                   ->get();
        return $data;
    }

    public function completedPaysReferencedAltan($init_date,$final_date){
        $data = DB::table('pays')
                   ->join('activations','activations.id','=','pays.activation_id')
                   ->join('numbers','numbers.id','=','activations.numbers_id')
                   ->join('references','references.reference_id','=','pays.reference_id')
                   ->join('channels','channels.id','=','references.channel_id')
                   ->where('pays.status','completado')
                   ->whereBetween('references.event_date_complete',[$init_date,$final_date])
                   ->where('pays.type_pay','referencia')
                   ->select('pays.*','numbers.producto AS number_product','references.amount AS reference_amount',
                   'references.fee_amount AS reference_fee_amount','references.event_date_complete AS reference_date_complete',
                   'channels.name AS channel_name')
                   ->get();
        return $data;
    }

    public function pendingPaysConecta($init_date,$final_date){
        $data = DB::table('references')
                   ->join('referencestypes','referencestypes.id','=','references.referencestype_id')
                   ->join('channels','channels.id','=','references.channel_id')
                   ->join('packs','packs.id','=','references.pack_id')
                   ->join('users','users.id','=','references.client_id')
                   ->where('references.status','in_progress')
                   ->whereBetween('references.creation_date',[$init_date,$final_date])
                   ->select('references.reference','references.status','references.creation_date','references.description','references.amount','references.user_id',
                        'channels.name AS channel_name','packs.name AS pack_name','packs.service_name AS pack_service',
                        'referencestypes.name AS type_name',
                        'users.name AS user_name')
                        ->get();
        return $data;
    }

    public function completedPaysConecta($init_date,$final_date){
        $data = DB::table('ethernetpays')
                   ->join('instalations','instalations.id','=','ethernetpays.instalation_id')
                   ->join('packs','packs.id','=','instalations.pack_id')
                   ->whereBetween('ethernetpays.updated_at',[$init_date,$final_date])
                   ->where('ethernetpays.status','completado')
                   ->where('type_pay','=','deposito')
                   ->orWhere('type_pay','=','transferencia')
                   ->orWhere('type_pay','=','efectivo')
                   ->select('ethernetpays.*','packs.price AS amount_waited','packs.service_name AS service_name')
                   ->get();
        return $data;
    }

    public function completedPaysReferencedConecta($init_date,$final_date){
        $data = DB::table('ethernetpays')
                   ->join('instalations','instalations.id','=','ethernetpays.instalation_id')
                   ->join('references','references.reference_id','=','ethernetpays.reference_id')
                   ->join('channels','channels.id','=','references.channel_id')
                   ->join('packs','packs.id','=','instalations.pack_id')
                   ->where('references.status','completed')
                   ->whereBetween('references.event_date_complete',[$init_date,$final_date])
                   ->select('ethernetpays.*','references.amount AS amount_waited','references.fee_amount AS reference_fee_amount',
                   'references.event_date_complete AS reference_date_complete','channels.name AS channel_name',
                   'packs.service_name AS service_name')
                   ->get();
        return $data;
    }

    public function notificationWHk(Request $request) {
        
        $fee_amount = round($request['fee_amount'],2);
        $fee_amount = number_format($fee_amount,2);

        $fee_tax = round($request['fee_tax'],4);
        $fee_tax = number_format($fee_tax,4);

        $fee_currency = $request['fee_currency'];

        $event_date = $request['event_date_complete'];
        $reference_id = $request['reference_id'];
        $status = $request['status'];
        $authorization = $request['authorization'];

        $validate = Reference::where('reference_id',$reference_id)->exists();
        if(!$validate){
            return response()->json(['http_code'=>'404']);
        }
        $x = Reference::where('reference_id',$reference_id)->first();
            $referencestype_id = $x->referencestype_id;
            $amount_reference = $x->amount;
            
            if($referencestype_id == 2){
                $pack_id = $x->pack_id;
                $pack_data = Pack::where('id',$pack_id)->first();
                $pack_price = $pack_data->price;

                $payment_data = Ethernetpay::where('reference_id',$reference_id)->first();
                $payment_amountReceived = $payment_data->amount_received;
                $payment_amountReceived = $payment_amountReceived == null ? 0 : $payment_amountReceived;
                $payment_type = $payment_data->type_pay;
                $payment_type = $payment_type == null ? 'referencia' : $payment_type.'/referencia';
                
                $monto_recibido = $payment_amountReceived+$amount_reference;


                Ethernetpay::where('reference_id',$reference_id)->update([
                    'status' => 'completado',
                    'type_pay' => $payment_type,
                    'amount_received' => $monto_recibido
                ]);
                
            }else if($referencestype_id == 6){
                $pays_id = explode(",", $request['pay_id']);
                $amounts = explode(",", $request['amounts']);

                for ($i=0; $i < sizeof($pays_id); $i++) { 
                    Ethernetpay::where('id',$pays_id[$i])->where('reference_id',$reference_id)->update([
                        'status' => 'completado',
                        'type_pay' => 'referencia',
                        'amount_received' => $amounts[$i]/100
                    ]);
                }

            }else if($referencestype_id == 1){
                // Obtención de datos almacenados en la referencia de pago
                $number_id = $x->number_id;
                $rate_idFromPayment = $x->rate_id;
                $offer_idFromPayment = $x->offer_id;

                $dataNumber = Number::where('id',$number_id)->first();
                $msisdn = $dataNumber->MSISDN;
                $producto = $dataNumber->producto;
                $producto = trim($producto);
                // Obtención de datos del número respecto a los datos de su activación
                $dataActivation = Activation::where('numbers_id',$number_id)->first();
                $rate_idFromActivation = $dataActivation->rate_id;
                $offer_idFromActivation = $dataActivation->offer_id;

                $rate_id = $x->rate_id;
                $rate_data = Rate::where('id',$rate_id)->first();
                $rate_price = $rate_data->price;

                $payment_data = Pay::where('reference_id',$reference_id)->first();
                $payment_amountReceived = $payment_data->amount_received;
                $payment_amountReceived = $payment_amountReceived == null ? 0 : $payment_amountReceived;
                $payment_type = $payment_data->type_pay;
                $payment_type = $payment_type == null ? 'referencia' : $payment_type.'/referencia';
                
                $monto_recibido = $payment_amountReceived+$amount_reference;
                
                // Verificación del plan y oferta solicitados en la referencia de pago
                if($rate_idFromPayment == $rate_idFromActivation && $offer_idFromPayment == $offer_idFromActivation){
                    // Deberá verificar el estado del UF, en caso de no estar en baja temporar o barring, sacar de ese status y ponerlo activo
                
                    $statusAltan = WebhookController::consultUFRuntime($msisdn,$producto);
                    if($statusAltan == 1){
                        // return response()->json(['message'=>'Todo ha salido good.','http_code'=>1]);
                    }else{
                        return response()->json(['message'=>'Hubo un error, no se hará cambio alguno.','http_code'=>0]);
                    }
                }else{
                    if($rate_idFromPayment != $rate_idFromActivation){
                        $dataOffer =  Offer::where('id',$offer_idFromPayment)->first();
                        $offerID = $dataOffer->offerID;
                        $scheduleDate = '';
                        $address = null;

                        if($producto == 'HBB'){
                            $lat = $dataActivation->lat_hbb;
                            $lng = $dataActivation->lng_hbb;
                            $address = $lat.','.$lng;
                        }else{
                            $address = null;
                        }

                        if($offer_idFromPayment == $offer_idFromActivation){
                            $type = 'internalChange';
                            $pay_id = $payment_data->id;

                            $response = Http::withHeaders([
                                'Content-Type' => 'application/json'
                            ])->post('http://10.44.0.70/change-product',[
                                'msisdn' => $msisdn,
                                'offerID' => $offerID,
                                'offer_id' => $offer_idFromPayment,
                                'rate_id' => $rate_idFromPayment,
                                'scheduleDate' => $scheduleDate,
                                'address' => $address,
                                'type' => $type,
                                'comment' => null,
                                'reason' => 'mensualidad',
                                'status' => 'completado',
                                'pay_id' => $pay_id,
                                'reference_id' => null

                            ]);
                            // return $response;
                        }else{
                            $statusAltan = WebhookController::consultUFRuntime($msisdn,$producto);
                            if($statusAltan == 1){
                                
                                $type = 'internalExternalChange';
                                $pay_id = $payment_data->id;

                                $response = Http::withHeaders([
                                    'Content-Type' => 'application/json'
                                ])->post('http://10.44.0.70/change-product',[
                                    'msisdn' => $msisdn,
                                    'offerID' => $offerID,
                                    'offer_id' => $offer_idFromPayment,
                                    'rate_id' => $rate_idFromPayment,
                                    'scheduleDate' => $scheduleDate,
                                    'address' => $address,
                                    'type' => $type,
                                    'comment' => null,
                                    'reason' => 'mensualidad',
                                    'status' => 'completado',
                                    'pay_id' => $pay_id,
                                    'reference_id' => null
                                ]);
                                // return $response;
                            }else{
                                return response()->json(['message'=>'Hubo un error, no se hará cambio alguno','http_code'=>0]);
                            }
                        }
                    }
                }

                Pay::where('reference_id',$reference_id)->update([
                    'status' => 'completado',
                    'type_pay' => $payment_type,
                    'amount_received' => $monto_recibido
                ]);
                
            }else if($referencestype_id == 4){
                // Ya se obtienen datos al caer el pago
                $offer_id = $x->offer_id;
                $rate_id = $x->rate_id;
                $amount = $x->amount;
                $number_id = $x->number_id;
                $dataNumber = Number::where('id',$number_id)->first();
                $dataOffer = Offer::where('id',$offer_id)->first();
                $dataRate = Rate::where('id',$rate_id)->first();
                // Falta hacer actualización de pago y cambio de producto con actualización en la DB
                $msisdn = $dataNumber->MSISDN;
                $producto = $dataNumber->producto;
                $producto = trim($producto);
                $offerID = $dataOffer->offerID;
                $offer_id = $dataOffer->id;
                $rate_id = $dataRate->id;
                $scheduleDate = '';
                $address = null;
                $type = 'internalExternalChange';

                $dataActivation = Activation::where('numbers_id',$number_id)->first();

                if($producto == 'HBB'){
                    $lat = $dataActivation->lat_hbb;
                    $lng = $dataActivation->lng_hbb;
                    $address = $lat.','.$lng;
                }else{
                    $address = null;
                }

                $statusAltan = WebhookController::consultUFRuntime($msisdn,$producto);
                if($statusAltan == 1){
                    
                    $type = 'internalExternalChange';

                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json'
                    ])->post('http://10.44.0.70/change-product',[
                        'msisdn' => $msisdn,
                        'offerID' => $offerID,
                        'offer_id' => $offer_id,
                        'rate_id' => $rate_id,
                        'scheduleDate' => $scheduleDate,
                        'address' => $address,
                        'type' => $type,
                        'comment' => null,
                        'reason' => 'referenciado',
                        'status' => 'completado',
                        'pay_id' => null,
                        'reference_id' => $reference_id,
                        'amount' => $amount
                    ]);
                    // return $response;
                }else{
                    return response()->json(['message'=>'Hubo un error, no se hará cambio alguno','http_code'=>0]);
                }

                // return $response;
            }else if($referencestype_id == 5){
                $rate_id = $x->rate_id;
                $rate_data = Rate::where('id',$rate_id)->first();
                $number_data = Number::where('id',$x->number_id)->first();
                $offer_data = Offer::where('id',$x->offer_id)->first();
                $producto = $number_data->producto;
                $MSISDN = $number_data->MSISDN;
                
                $producto = trim($producto);
                
                if($producto == 'MIFI' || $producto == 'HBB'){
                    $offerID = $offer_data->offerID;
                }else if($producto == 'MOV'){
                    $offerID = $offer_data->offerID_second;
                }

                $data = array('msisdn'=>$MSISDN,'offer'=>$offerID);

                $purchaseProduct = app('App\Http\Controllers\AltanController')->productPurchase($data);
                
                // return $purchaseProduct;
            }else if($referencestype_id == 7){
                $shipping_id = $request['pay_id'];
                $shippingData = Shipping::where('id',$shipping_id)->first();
                $to_id = $shippingData->to_id;
                $sold_by = $shippingData->sold_by;

                $clientData = User::where('id',$to_id)->first();
                $clientData = $clientData->name.' '.$clientData->lastname;
                $userData = User::where('id',$sold_by)->first();
                $userData = $userData->name.' '.$userData->lastname;
                $clientDataPhone = Client::where('user_id',$to_id)->first();
                $phone = $clientDataPhone->cellphone;

                $data = [
                    "clientData" => $clientData,
                    "userData" => $userData,
                    "phone" => $phone,
                    "shippingID" => $shipping_id,
                ];

                // $data = [
                //     "clientData" => 'CHARLES PRUEBA',
                //     "userData" => 'DESARROLLO PRUEBA',
                //     "phone" => '9612538386',
                //     "shippingID" => 6,
                // ];
                DB::table('shippings')->where('id',$shipping_id)->update(['status'=>'pendiente','reference_id'=>$reference_id]);
                Mail::to('keila_vazquez@altcel.com')->send(new NotifyShipping($data));
                Mail::to('jalexis_santana@altcel.com')->send(new NotifyShipping($data));
                Mail::to('charlesrootsman97@gmail.com')->send(new NotifyShipping($data));
            }

            Reference::where('reference_id',$reference_id)->update([
                'event_date_complete' => $event_date,
                'status' => $status,
                'authorizacion' => $authorization,
                'fee_amount' => $fee_amount,
                'fee_tax' => $fee_tax,
                'fee_currency' => $fee_currency
            ]); 
            return response()->json(['http_code'=>'200']);
    }

    public function consultUFRuntime($msisdn,$producto){
        $consultUF = app('App\Http\Controllers\AltanController')->consultUF($msisdn);
        $responseSubscriber = $consultUF['responseSubscriber'];
        $status = $responseSubscriber['status']['subStatus'];
        $bool = 0;
        
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

        return $bool;

    }

    public function saveManualPay(Request $request){
        $service = $request['service'];
        $payID = $request['payID'];
        $monto = $request['monto'];
        $typePay = $request['typePay'];
        $folioPay = $request['folioPay'];

        if($service == 'Telmex' || $service == 'Conecta'){
            $x = Ethernetpay::where('id',$payID)->update([
                'status' => 'completado',
                'amount_received' => $monto,
                'type_pay' => $typePay,
                'folio_pay' => $folioPay
            ]);

            $y = Ethernetpay::where('id',$payID)->first();

            $instalation_id = $y->instalation_id;
            $fecha_pay = $y->date_pay;
            $fecha_limit = $y->date_pay_limit;
            // Obtenemos el siguiente mes
            $fecha_pay_new = strtotime('+3 days', strtotime($fecha_pay));
            $fecha_pay_new = date('Y-m-d',$fecha_pay_new);
            // Obtenemos su último día
            $fecha_pay_new = new DateTime($fecha_pay_new);
            $fecha_pay_new->modify('last day of this month');
            $fecha_pay_new = $fecha_pay_new->format('Y-m-d');
            // Obtenemos nueva fecha límite de pago
            $fecha_limit_new = strtotime('+5 days', strtotime($fecha_pay_new));
            $fecha_limit_new = date('Y-m-d',$fecha_limit_new);

            $instalation_data = Instalation::where('id',$instalation_id)->first();
            $pack_id = $instalation_data->pack_id;
            $pack_data = Pack::where('id',$pack_id)->first();
            $pack_price = $pack_data->price;
            
            $x = Ethernetpay::insert([
                    'date_pay' => $fecha_pay_new,
                    'date_pay_limit' => $fecha_limit_new,
                    'instalation_id' => $instalation_id,
                    'status' => 'pendiente',
                    'amount' => $pack_price
                ]);
        }else if($service == 'MIFI' || $service == 'HBB'){
            $x = Pay::where('id',$payID)->update([
                'status' => 'completado',
                'amount_received' => $monto,
                'type_pay' => $typePay,
                'folio_pay' => $folioPay
            ]);

            $y = Pay::where('id',$payID)->first();

            $activation_id = $y->activation_id;
            $fecha_pay = $y->date_pay;
            $fecha_limit = $y->date_pay_limit;
            // Obtenemos el siguiente mes
            $fecha_pay_new = strtotime('+3 days', strtotime($fecha_pay));
            $fecha_pay_new = date('Y-m-d',$fecha_pay_new);
            // Obtenemos su último día
            $fecha_pay_new = new DateTime($fecha_pay_new);
            $fecha_pay_new->modify('last day of this month');
            $fecha_pay_new = $fecha_pay_new->format('Y-m-d');
            // Obtenemos nueva fecha límite de pago
            $fecha_limit_new = strtotime('+5 days', strtotime($fecha_pay_new));
            $fecha_limit_new = date('Y-m-d',$fecha_limit_new);

            $activation_data = Activation::where('id',$activation_id)->first();
            $rate_id = $activation_data->rate_id;
            $rate_data = Rate::where('id',$rate_id)->first();
            $rate_price = $rate_data->price;
            
            $x = Pay::insert([
                    'date_pay' => $fecha_pay_new,
                    'date_pay_limit' => $fecha_limit_new,
                    'activation_id' => $activation_id,
                    'status' => 'pendiente',
                    'amount' => $rate_price
                ]);
        }

        if($x){
            return 1;
        }else{
            return 0;
        }
    }

    public function orderCreate(Request $request){
        // return $request;
        $client_id = $request['client_id'];
        $dataUser = User::find($client_id);
        $pack_id = $request['pack_id'];
        $offer_id = $request['offer_id'];
        $number_id = $request['number_id'];
        $rate_id = $request['rate_id'];
        $channel_system = $request['channel_system'];

        $request['pack_id'] = $pack_id == 0 ? null : $pack_id;
        $request['offer_id'] = $offer_id == 0 ? null : $offer_id;
        $request['number_id'] = $number_id == 0 ? null : $number_id;
        $request['rate_id'] = $rate_id == 0 ? null : $rate_id;
        $request['channel_system'] = $channel_system == 0 ? null : $channel_system;

        $x = Reference::insert([
            'reference_id' => $request['reference_id'],
            'reference' => $request['reference'],
            'description' => $request['description'],
            'amount' => $request['amount']/100,
            'currency' => $request['currency'],
            'client_id' => $request['client_id'],
            'offer_id' => $request['offer_id'],
            'rate_id' => $request['rate_id'],
            'pack_id' => $request['pack_id'],
            'user_id' => $request['user_id'],
            'number_id' => $request['number_id'],
            'email' => $request['email'],
            'name' => $dataUser->name,
            'lastname' => $dataUser->lastname,
            'creation_date' => date('Y-m-d H:i:s'),
            'payment_method' => 'card',
            'transaction_type' => 'charge',
            'referencestype_id' => $request['referencestype_id'],
            'status' => 'pending_payment',
            'channel_id' => 3,
            'channel_system' => $channel_system
        ]);

        if($request['referencestype_id'] == 1){
            Pay::where('id',$request['pay_id'])->update(['reference_id'=>$request['reference_id']]);
        }else if($request['referencestype_id'] == 2){
            Ethernetpay::where('id',$request['pay_id'])->update(['reference_id'=>$request['reference_id']]);
        }else if($request['referencestype_id'] == 6){
            $pays_id = explode(",", $request['pay_id']);
            for ($i=0; $i < sizeof($pays_id); $i++) { 
                Ethernetpay::where('id',$pays_id[$i])->update(['reference_id'=>$request['reference_id']]);
            }
        }
        
        if($x){
            return response()->json(['message'=>'ÉXITO'],200);
        }
    }
}
