<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Openpay;
use DB;
use App\Offer;
use App\Reference;
use App\Number;
use App\Ethernetpay;
use App\Pay;
use Exception;
use OpenpayApiError;
use OpenpayApiAuthError;
use OpenpayApiRequestError;
use OpenpayApiConnectionError;
use OpenpayApiTransactionError;
use Illuminate\Http\JsonResponse;

require_once '../vendor/autoload.php';

class OpenPayController extends Controller
{
    // Generación de referencia de pago
    public function store(Request $request) {
        $name = $request->input('name');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $cel_destiny_reference = $request->input('cel_destiny_reference');
        $amount = $request->input('amount');
        $concepto = $request->input('concepto');
        $type = $request->input('type');
        $channel = $request->input('channel');
        $user = $request->input('user_id');
        $client_id = $request->input('client_id');
        // $pay_id = $request->input('pay_id');
        
        if($type == 1 || $type == 4)
        {
            $number_id = $request->input('number_id');
            $offerID = $request->input('offer_id');
            $rate = $request->input('rate_id');

            $insert = 'offer_id';
            $insert_content = $offerID;
        }else if($type == 2){
            $pack_id = $request->input('pack_id');
        }
        try {
            // create instance OpenPay sandbox
            // $openpay = Openpay::getInstance('mvtmmoafnxul8oizkhju', 'sk_e69bbf5d1e30448688b24670bcef1743');
            // create instance OpenPay production
            $openpay = Openpay::getInstance('m3one5bybxspoqsygqhz', 'sk_1829d6a2ec22413baffb405b1495b51b');
            
            // Openpay::setProductionMode(false);
            Openpay::setProductionMode(true);
            
            // create object customer
            $customer = array(
                'name' => $name,
                'last_name' => $lastname,
                'email' => $email
            );

            // create object charge
            $chargeRequest =  array(
                'method' => 'store',
                'amount' => $amount,
                'currency' => 'MXN',
                'description' => $concepto,
                'customer' => $customer
            );
            $charge = $openpay->charges->create($chargeRequest);
            $responseJson = new \stdClass();
            $reference_id = $charge->id;
            $authorization = $charge->authorization;
            $transaction_type = $charge->transaction_type;
            $status = $charge->status;
            $creation_date = $charge->creation_date;
            $operation_date = $charge->operation_date;
            $description = $charge->description;
            $error_message = $charge->error_message;
            $order_id = $charge->order_id;
            $payment_method = $charge->payment_method->type;
            $reference = $charge->payment_method->reference;
            $amount = $charge->amount;
            $currency = $charge->currency;
            $name = $charge->customer->name;
            $lastname = $charge->customer->last_name;
            $email = $charge->customer->email;

            $creation_date = substr($creation_date,0,19);
            $creation_date = str_replace("T", "", $creation_date);
            $creation_date = str_replace("-", "", $creation_date);
            $creation_date = str_replace(":", "", $creation_date);

            $user_id = auth()->user()->id;

            if($type == 1 || $type == 4){
                $dataReference = [
                    'reference_id' => $reference_id,
                    'reference' => $reference,
                    'authorizacion' => $authorization,
                    'transaction_type' => $transaction_type,
                    'status' => $status,
                    'creation_date' => $creation_date,
                    'description' => $description,
                    'error_message' => $error_message,
                    'order_id' => $order_id,
                    'payment_method' => $payment_method,
                    'amount' => $amount,
                    'currency' => $currency,
                    'name' => $name,
                    'lastname' => $lastname,
                    'email' => $email,
                    'channel_id' => $channel,
                    'referencestype_id' => $type,
                    'number_id' => $number_id,
                    $insert => $insert_content,
                    'rate_id' => $rate,
                    'user_id' => $user = $user == 'null' ? null : $user,
                    'client_id' => $client_id
                ];
                
                Reference::insert($dataReference);
                if($type == 1){
                    $pay_id = $request->input('pay_id');
                    Pay::where('id',$pay_id)->update(['reference_id' => $reference_id]);
                }else if($type == 4){

                }
                
            }else if($type == 2){
                $dataReference = [
                    'reference_id' => $reference_id,
                    'reference' => $reference,
                    'authorizacion' => $authorization,
                    'transaction_type' => $transaction_type,
                    'status' => $status,
                    'creation_date' => $creation_date,
                    'description' => $description,
                    'error_message' => $error_message,
                    'order_id' => $order_id,
                    'payment_method' => $payment_method,
                    'amount' => $amount,
                    'currency' => $currency,
                    'name' => $name,
                    'lastname' => $lastname,
                    'email' => $email,
                    'channel_id' => $channel,
                    'referencestype_id' => $type,
                    'pack_id' => $pack_id,
                    'user_id' => $user = $user == 'null' ? null : $user,
                    'client_id' => $client_id
                ];
                Reference::insert($dataReference);
                Ethernetpay::where('id',$pay_id)->update(['reference_id' => $reference_id]);
            }
            return $dataReference;

        } catch (OpenpayApiTransactionError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiRequestError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiConnectionError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiAuthError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        }
    }

    public function cardPayment($id, $type, $user_id){
        $current_role = auth()->user()->role_id;
        $employe_id = $current_role == 3 ? 'null' : auth()->user()->id;
            $user = DB::table('users')
                   ->join('clients','clients.user_id','=','users.id')
                   ->where('users.id',$user_id)
                   ->select('users.*','clients.cellphone AS client_cellphone')
                   ->get();
        $user_name = $user[0]->name;
        $user_lastname = $user[0]->lastname;
        $user_email = $user[0]->email;
        $user_phone = $user[0]->client_cellphone;
        if($type == 'MIFI' || $type == 'HBB' || $type == 'MOV'){
            $referencestype = 1;
            $pay = DB::table('pays')
                      ->join('activations','activations.id','=','pays.activation_id')
                      ->join('rates','rates.id','=','activations.rate_id')
                      ->join('numbers','numbers.id','=','activations.numbers_id')
                      ->join('offers','offers.id','=','rates.alta_offer_id')
                      ->where('pays.id',$id)
                      ->where('activations.client_id',$user_id)
                      ->select('pays.*','activations.numbers_id AS activation_number_id',
                               'numbers.MSISDN AS DN','rates.name AS rate_name','rates.id AS rate_id',
                               'rates.price AS rate_price','offers.id as offer_id')
                               ->get();
            $number_id = $pay[0]->activation_number_id;
            $DN = $pay[0]->DN;
            $rate_id = $pay[0]->rate_id;
            $rate_name = $pay[0]->rate_name;
            $rate_price = $pay[0]->amount;
            $offer_id = $pay[0]->offer_id;

            if($type == 'MIFI'){
                $concepto = 'MIFI';
            }else if($type == 'HBB'){
                $concepto = 'HBB';
            }else if($type == 'MOV'){
                $concepto = 'de Telefonía Celular (Movilidad)';
            }

            $data = array(
                'datos' => [
                    "referencestype" => $referencestype,
                    "number_id" => $number_id,
                    "DN" => $DN,
                    "rate_id" => $rate_id,
                    "rate_name" => $rate_name,
                    "rate_price" => $rate_price,
                    "offer_id" => $offer_id,
                    "concepto" => "Pago de Servicio ".$concepto,
                    "channel_id" => 3
                ],
                'data_client' => [
                    "client_name" => $user_name,
                    "client_lastname" => $user_lastname,
                    "client_email" => $user_email,
                    "client_phone" => $user_phone,
                    "client_id" => $user_id,
                    "employe_id" => $employe_id,
                    "pay_id" => $id,
                    "service" => "Altan Redes"
                ]
                );
        }else if($type == 'Conecta' || $type == 'Telmex'){
            $referencestype = 2;
            $pay = DB::table('ethernetpays')
                      ->join('instalations','instalations.id','=','ethernetpays.instalation_id')
                      ->join('packs','packs.id','=','instalations.pack_id')
                      ->where('ethernetpays.id',$id)
                      ->where('instalations.client_id',$user_id)
                      ->select('ethernetpays.*','packs.id AS pack_id','packs.name AS pack_name','packs.price AS pack_price')
                      ->get();
        
            $pack_id = $pay[0]->pack_id;
            $pack_name = $pay[0]->pack_name;
            $pack_price = $pay[0]->amount;

            $data = array(
                'datos' => [
                    "referencestype" => $referencestype,
                    "pack_id" => $pack_id,
                    "pack_name" => $pack_name,
                    "pack_price" => $pack_price,
                    "concepto" => "Pago de Servicio de Internet.",
                    "channel_id" => 3
                ],
                'data_client' => [
                    "client_name" => $user_name,
                    "client_lastname" => $user_lastname,
                    "client_email" => $user_email,
                    "client_phone" => $user_phone,
                    "client_id" => $user_id,
                    "employe_id" => $employe_id,
                    "pay_id" => $id,
                    "service" => "Conecta"
                ]
                );
        }
        return view('clients.card_payment',$data);
    }

    public function cardPaymentSend(Request $request){

        $name = $request->post('name');
        $lastname = $request->post('lastname');
        $email = $request->post('email');
        $cellphone = $request->post('cellphone');
        $amount = $request->post('amount');
        $concepto = $request->post('concepto');
        $channel = $request->post('channel_id');
        $referencestype = $request->post('referencestype');
        $client_id = $request->post('client_id');
        $pay_id = $request->post('pay_id');
        $redirect = $request->post('redirect');
        
        // create instance OpenPay sandbox
        // $openpay = Openpay::getInstance('mvtmmoafnxul8oizkhju', 'sk_e69bbf5d1e30448688b24670bcef1743');
        // create instance OpenPay production
        $openpay = Openpay::getInstance('m3one5bybxspoqsygqhz', 'sk_1829d6a2ec22413baffb405b1495b51b');
        
        // Openpay::setProductionMode(false);
        Openpay::setProductionMode(true);

            $customer = array(
                'name' => $name,
                'last_name' => $lastname,
                'phone_number' => $cellphone,
                'email' => $email);

            $chargeRequest = array(
                "method" => "card",
                'amount' => $amount,
                'description' => $concepto,
                'customer' => $customer,
                'send_email' => true,
                'confirm' => false,
                'redirect_url' => $redirection = $redirect == null ? 'http://200.106.172.56/home' : $redirect);
                // return $request;
            $charge = $openpay->charges->create($chargeRequest);

            $reference_id = $charge->id;
            $authorization = $charge->authorization;
            $transaction_type = $charge->transaction_type;
            $status = 'in_progress';
            $creation_date = $charge->creation_date;
            $description = $charge->description;
            $error_message = $charge->error_message;
            $order_id = $charge->order_id;
            $payment_method = $charge->method;
            $amount = $charge->amount;
            $currency = $charge->currency;
            $name = $charge->customer->name;
            $lastname = $charge->customer->last_name;
            $email = $charge->customer->email;
            $url = $charge->payment_method->url;

            $creation_date = substr($creation_date,0,19);
            $creation_date = str_replace("T", "", $creation_date);
            $creation_date = str_replace("-", "", $creation_date);
            $creation_date = str_replace(":", "", $creation_date);

            $user_id = $client_id;

            if($referencestype == 1 || $referencestype == 5){
                $offer_id = $request->post('offer_id');
                $rate_id = $request->post('rate_id');
                $number_id = $request->post('number_id');
                $pack_id = null;
            }else if($referencestype == 2){
                $offer_id = null;
                $rate_id = null;
                $number_id = null;
                $pack_id = $request->post('pack_id');
            }

            $dataReference = [
                'reference_id' => $reference_id,
                'reference' => $reference_id,
                'authorizacion' => $authorization,
                'transaction_type' => $transaction_type,
                'status' => $status,
                'creation_date' => $creation_date,
                'description' => $description,
                'error_message' => $error_message,
                'order_id' => $order_id,
                'payment_method' => $payment_method,
                'amount' => $amount,
                'currency' => $currency,
                'name' => $name,
                'lastname' => $lastname,
                'email' => $email,
                'channel_id' => $channel,
                'referencestype_id' => $referencestype,
                'number_id' => $number_id,
                'offer_id' => $offer_id,
                'rate_id' => $rate_id,
                'user_id' => $client_id,
                'pack_id' => $pack_id,
                'client_id' => $client_id,
                'url_card_payment' => $url
            ];

            Reference::insert($dataReference);

            if($referencestype == 1){
                Pay::where('id',$pay_id)->update(['reference_id' => $reference_id]);
            }else if($referencestype == 2){
                Ethernetpay::where('id',$pay_id)->update(['reference_id' => $reference_id]);
            }

            return response()->json(['method'=>$charge->method, 'url'=>$charge->payment_method->url, 'type'=>$charge->payment_method->type]);
    }
}
