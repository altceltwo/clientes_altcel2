<?php

namespace App\Http\Controllers;
use DB;
use Http;
use App\Offer;
use App\Channel;
use App\User;
use App\Reference;
use App\Pay;
use App\Ethernetpay;
use App\Client;
use App\Clientsson;
use App\Rate;
use App\Role;
use App\Activation;
use App\Instalation;
use App\Number;
use App\Device;
use App\Contract;
use App\Company;
use App\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\GuzzleHttp;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ClientController extends Controller
{
    public function recharge() {
        $data['clients'] = DB::table('activations')
                              ->join('numbers','numbers.id','=','activations.numbers_id')
                              ->join('users','users.id','=','activations.client_id')
                              ->join('rates','rates.id','=','activations.rate_id')
                              ->select('users.*','numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name')
                              ->get();

        $role = auth()->user()->role_id;
        $user_id = auth()->user()->id;
        if($role == 8){
            $company_id = auth()->user()->company_id;
            $data['clients'] = DB::table('activations')
                              ->join('numbers','numbers.id','=','activations.numbers_id')
                              ->join('users','users.id','=','activations.client_id')
                              ->join('rates','rates.id','=','activations.rate_id')
                              ->where('users.company_id',$company_id)
                              ->orWhere('activations.who_did_id',$user_id)
                              ->select('users.*','numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name')
                              ->get();
        }

        return view('clients.recharge',$data);
    }

    public function rechargeAPI(){
        $data['clients'] = DB::table('activations')
                              ->join('numbers','numbers.id','=','activations.numbers_id')
                              ->join('users','users.id','=','activations.client_id')
                              ->join('rates','rates.id','=','activations.rate_id')
                              ->select('users.*','numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name')
                              ->get();
                
        return $data;
    }

    public function myRecharges() {
        $user_id = auth()->user()->id;
        $data['recharges'] = DB::table('purchases')
                              ->join('numbers','numbers.id','=','purchases.number_id')
                              ->join('rates','rates.id','=','purchases.rate_id')
                              ->join('offers','offers.id','=','purchases.offer_id')
                              ->where('purchases.who_did_id',$user_id)
                              ->select('numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name','offers.name AS offer_name','purchases.date AS date_purchase','purchases.amount AS amount')
                              ->get();
        return view('clients.myRecharges',$data);
    }

    public function myCharges() {
        $user_id = auth()->user()->id;
        $data['paymentsCompleted'] = DB::table('pays')
                                      ->join('activations','activations.id','=','pays.activation_id')
                                      ->join('rates','rates.id','=','activations.rate_id')
                                      ->join('users','users.id','=','activations.client_id')
                                      ->join('numbers','numbers.id','=','activations.numbers_id')
                                      ->where('pays.status','completado')
                                      ->where('pays.who_did_id',$user_id)
                                      ->select('pays.*','rates.name AS rate_name','users.id AS client_id','users.name AS client_name','users.lastname AS client_lastname','numbers.producto AS number_product','numbers.id AS number_id','numbers.MSISDN AS DN')
                                      ->get();
        return view('clients.myCharges',$data);
    }

    public function myChanges() {
        $user_id = auth()->user()->id;
        $data['changes'] = DB::table('changes')
                              ->join('numbers','numbers.id','=','changes.number_id')
                              ->join('rates','rates.id','=','changes.rate_id')
                              ->join('offers','offers.id','=','changes.offer_id')
                              ->where('changes.who_did_id',$user_id)
                              ->select('numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name','offers.name AS offer_name','changes.date AS date_purchase','changes.amount AS amount')
                              ->get();
        return view('clients.myChanges',$data);
    }

    public function monthlyPayments($msisdn){
        $dataNumber = Number::where('MSISDN',$msisdn)->first();
        $number_id = $dataNumber->id;
        $dataActivation = Activation::where('numbers_id',$number_id)->first();
        $activation_id = $dataActivation->id;
        
        $data['MSISDN'] = $msisdn;
        $data['paymentsPendings'] = DB::table('pays')
                                      ->join('activations','activations.id','=','pays.activation_id')
                                      ->join('rates','rates.id','=','activations.rate_id')
                                      ->join('users','users.id','=','activations.client_id')
                                      ->join('numbers','numbers.id','=','activations.numbers_id')
                                      ->leftJoin('references','references.reference_id','=','pays.reference_id')
                                      ->where('pays.activation_id',$activation_id)
                                      ->where('pays.status','pendiente')
                                      ->select('pays.*','rates.name AS rate_name','users.id AS client_id','users.name AS client_name',
                                      'users.lastname AS client_lastname','numbers.producto AS number_product','numbers.id AS number_id',
                                      'numbers.MSISDN AS DN','references.reference_id AS referenceID',
                                      'numbers.traffic_outbound_incoming AS traffic_ethernet','numbers.traffic_outbound AS traffic_mov',
                                      'numbers.status_altan AS status_sim','numbers.producto AS producto')
                                      ->get();
        return view('clients.monthlyPayments',$data);
    }

    public function monthlyPaymentsPos($msisdn){
        $dataNumber = Number::where('MSISDN',$msisdn)->first();
        $number_id = $dataNumber->id;
        $dataActivation = Activation::where('numbers_id',$number_id)->first();
        $activation_id = $dataActivation->id;
        
        $data['MSISDN'] = $msisdn;
        $data['paymentsPendings'] = DB::table('pays')
                                      ->join('activations','activations.id','=','pays.activation_id')
                                      ->join('rates','rates.id','=','activations.rate_id')
                                      ->join('offers','offers.id','=','activations.offer_id')
                                      ->join('users','users.id','=','activations.client_id')
                                      ->join('numbers','numbers.id','=','activations.numbers_id')
                                      ->leftJoin('references','references.reference_id','=','pays.reference_id')
                                      ->where('pays.activation_id',$activation_id)
                                      ->where('pays.status','pendiente')
                                      ->select('pays.*','rates.name AS rate_name','rates.id AS rate_id','rates.price_subsequent AS rate_price','users.id AS client_id','users.name AS client_name',
                                      'users.lastname AS client_lastname','numbers.producto AS number_product','numbers.id AS number_id',
                                      'numbers.MSISDN AS DN','references.reference_id AS referenceID',
                                      'numbers.traffic_outbound_incoming AS traffic_ethernet','numbers.traffic_outbound AS traffic_mov',
                                      'numbers.status_altan AS status_sim','numbers.producto AS producto',
                                      'offers.id AS offer_id','offers.offerID AS offerID')
                                      ->orderBy('pays.date_pay','desc')
                                      ->limit(1)
                                      ->get();
        return $data;
    }

    public function unbarring(Request $request){
        $payID = $request->get('payID');
        $dataPayment = Pay::where('id',$payID)->first();
        $activation_id = $dataPayment->activation_id;
        $dataActivation = Activation::where('id',$activation_id)->first();
        $number_id = $dataActivation->numbers_id;
        $dataNumber = Number::where('id',$number_id)->first();
    
        $msisdn = $dataNumber->MSISDN;
        $producto = $dataNumber->producto;
        $producto = trim($producto);

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

        if($bool == 1){
            if($producto == 'MIFI' || $producto == ' HBB'){
                Number::where('id',$number_id)->update(['traffic_outbound_incoming'=>'activo']);
            }
        }

        return $bool;

    }

    public function clientsPayAll() {
        $data['clients'] = DB::table('users')
                              ->join('clients','clients.user_id','=','users.id')
                              ->where('users.role_id',3)
                              ->select('users.*','clients.cellphone AS client_phone','clients.rfc AS RFC','clients.address AS client_address')
                              ->get();
        
        $current_role = auth()->user()->role_id;
        $current_id = auth()->user()->id;
        
        return view('clients.showAll',$data);
    }

    public function clientDetails($id){
        // return $_SERVER["HTTP_CLIENT_IP"];
        $clientData = User::where('id',$id)->first();
        $data['mypays'] = DB::table('pays')
                             ->join('activations','activations.id','=','pays.activation_id')
                             ->join('numbers','numbers.id','=','activations.numbers_id')
                             ->join('rates','rates.id','=','activations.rate_id')
                             ->where('activations.client_id',$id)
                             ->where('pays.status','pendiente')
                             ->select('pays.*','numbers.MSISDN AS DN','numbers.producto AS number_product','numbers.id AS number_id','activations.id AS activation_id','rates.name AS rate_name','rates.price AS rate_price')
                             ->get();
        $data['my2pays'] = DB::table('ethernetpays')
                              ->join('instalations','instalations.id','=','ethernetpays.instalation_id')
                              ->join('packs','packs.id','=','instalations.pack_id')
                              ->where('instalations.client_id',$id)
                              ->where('ethernetpays.status','pendiente')
                              ->select('ethernetpays.*','packs.name AS pack_name','packs.price AS pack_price','packs.service_name AS service_name','instalations.id AS instalation_id')
                              ->get();
        
        $data['completemypays'] = DB::table('pays')
                             ->join('activations','activations.id','=','pays.activation_id')
                             ->join('numbers','numbers.id','=','activations.numbers_id')
                             ->join('rates','rates.id','=','activations.rate_id')
                             ->where('activations.client_id',$id)
                             ->where('pays.status','completado')
                             ->select('pays.*','numbers.MSISDN AS DN','numbers.producto AS number_product','rates.name AS rate_name','rates.price AS rate_price')
                             ->get();
        $data['completemy2pays'] = DB::table('ethernetpays')
                              ->join('instalations','instalations.id','=','ethernetpays.instalation_id')
                              ->join('packs','packs.id','=','instalations.pack_id')
                              ->where('instalations.client_id',$id)
                              ->where('ethernetpays.status','completado')
                              ->select('ethernetpays.*','packs.name AS pack_name','packs.price AS pack_price','packs.service_name AS service_name')
                              ->get();

        $data['activations'] = DB::table('activations')
                                  ->join('numbers','numbers.id','=','activations.numbers_id')
                                  ->join('rates','rates.id','=','activations.rate_id')
                                  ->where('activations.client_id',$id)
                                  ->select('activations.*','numbers.MSISDN AS DN','numbers.producto AS service','rates.name AS pack_name')
                                  ->get();
        
        $data['instalations'] = DB::table('instalations')
                                   ->join('packs','packs.id','=','instalations.pack_id')
                                   ->where('instalations.client_id',$id)
                                   ->select('instalations.*','packs.service_name AS service','packs.name AS pack_name')
                                   ->get();
        $data['client_id'] = $id;
        $data['client_name'] = $clientData->name;
        return view('clients.clientDetails',$data);
    }

    public function showReferenceClient(Request $request){
        $reference_id = $request->get('reference_id');
        $response = Reference::where('reference_id',$reference_id)->first();
        return $response;
    }

    public function searchClients(Request $request){
        $term = $request->get('term');
        $querys = DB::table('users')
                        ->join('clients', 'clients.user_id', '=', 'users.id')
                        ->where('role_id',3)
                        ->where('users.name', 'LIKE', '%'. $term. '%')
                        ->orWhere('users.email','LIKE','%'. $term. '%')
                        ->select('clients.*','users.name','users.lastname','users.email','users.id as user_id')
                        ->get();

        return $querys;
    }

    public function searchClientProduct(Request $request) {
        $id = $request->get('id');
        $querys = DB::table('activations')
                     ->join('numbers','numbers.id','=','activations.numbers_id')
                     ->where('activations.client_id','=',$id)
                     ->select('activations.*','numbers.MSISDN', 'numbers.producto', 'numbers.id AS number_id')
                     ->get();
        return $querys;
    }

    public function generateReference($id,$type,$user_id){
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

            $channels =  Channel::all();
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
                    "concepto" => "Pago de Servicio ".$concepto
                ],
                'channels' => $channels,
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
            $channels =  Channel::all();

            $data = array(
                'datos' => [
                    "referencestype" => $referencestype,
                    "pack_id" => $pack_id,
                    "pack_name" => $pack_name,
                    "pack_price" => $pack_price,
                    "concepto" => "Pago de Servicio de Internet."
                ],
                'channels' => $channels,
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

        return view('clients.generatePay')->with($data);
    }

    public function surplusRates($msisdn){
        $data['user_complete'] = auth()->user()->name.' '.auth()->user()->lastname;
        $role = Role::where('id',auth()->user()->role_id)->first();
        $data['role'] = $role->rol;
        $data['user_id'] = auth()->user()->id;

        $credentials['email'] = 'carlos.ruiz@altcel2.com';
        $credentials['password'] = "123456789";
        try {
            $myTTL = 10; //minutes
            JWTAuth::factory()->setTTL($myTTL);
            $token = JWTAuth::attempt($credentials);

            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $data['token'] = $token;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://10.44.0.70/get-offers-rates-surplus',['msisdn'=>$msisdn]);
        
        $data['dataMSISDN'] = $response['dataMSISDN'];
        $data['packsSurplus'] = $response['packsSurplus'];
        $data['channels'] = Channel::all();

        // return $data;
        return view('clients.surplusRates',$data);
    }

    public function surplusRatesDealer($msisdn){

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://10.44.0.70/get-offers-rates-surplus',['msisdn'=>$msisdn]);
        
        $data['dataMSISDN'] = $response['dataMSISDN'];
        $data['packsSurplus'] = $response['packsSurplus'];
        $data['channels'] = Channel::all();

        // return $data;
        return view('clients.surplusRatesDealer',$data);
    }

    public function surplusRatesDealerAPI($msisdn){

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://10.44.0.70/get-offers-rates-surplus',['msisdn'=>$msisdn]);
        
        $data['dataMSISDN'] = $response['dataMSISDN'];
        $data['packsSurplus'] = $response['packsSurplus'];

        return $data;
    }

    public function generateRecharge($id,$type,$user_id,$surplus_id){
        $data['referencestype'] = 4;
        $dataSurplus = DB::table('rates')
                          ->join('offers','offers.id','=','rates.multi_offer_id')
                          ->where('rates.id',$surplus_id)
                          ->select('rates.*','offers.id AS offerExID','offers.name AS offer_name','offers.product AS offer_product')
                          ->get();
        
        $dataActivation = DB::table('activations')
                             ->join('numbers','numbers.id','=','activations.numbers_id')
                             ->where('activations.id',$id)
                             ->select('numbers.MSISDN','numbers.id AS number_id')
                             ->get();

        $dataClient = Client::where('user_id',$user_id)->first();
        
        $data['name'] = auth()->user()->name;
        $data['lastname'] = auth()->user()->lastname;
        $data['email'] = auth()->user()->email;
        $data['cellphone'] =$dataClient->cellphone;
        $data['rate_name'] = $dataSurplus[0]->name;
        $data['rate_id'] = $dataSurplus[0]->id;
        $data['rate_amount'] = $dataSurplus[0]->price;
        $data['offer_id'] = $dataSurplus[0]->offerExID;
        $data['number_product'] = $dataActivation[0]->MSISDN;
        $data['number_id'] = $dataActivation[0]->number_id;
        $data['activation_id'] = $id;
        $data['concept'] = 'Recarga de '.$type;
        $data['service'] = $type;
        $data['channels'] = Channel::all();

        return view('clients.rechargeView',$data);
    }

    public function productDetails($id_dn,$id_pay,$id_act,$service){
        if($service == 'MIFI' || $service == 'HBB' || $service == 'MOV'){
            $dataQuery = DB::table('activations')
                       ->join('numbers','numbers.id','=','activations.numbers_id')
                       ->join('rates','rates.id','=','activations.rate_id')
                       ->where('activations.id',$id_act)
                       ->where('activations.numbers_id',$id_dn)
                       ->select('numbers.MSISDN AS DN','numbers.traffic_outbound AS traffic_outbound',
                       'numbers.traffic_outbound_incoming AS traffic_outbound_incoming',
                       'rates.name AS rate_name','rates.price AS rate_price',
                       'activations.date_activation AS date_activation')
                       ->get();
            
            $data['DN'] = $dataQuery[0]->DN;
            $data['service'] = $service;
            $data['pack_name'] = $dataQuery[0]->rate_name;
            $data['pack_price'] = $dataQuery[0]->rate_price;
            $data['date_activation'] = $dataQuery[0]->date_activation;
            $data['traffic_out'] = $dataQuery[0]->traffic_outbound;
            $data['traffic_out_in'] = $dataQuery[0]->traffic_outbound_incoming;

            $data['mypays'] = DB::table('pays')
                                 ->join('references','references.reference_id','=','pays.reference_id')
                                 ->where('pays.activation_id',$id_act)
                                 ->where('references.status','completed')
                                 ->select('pays.*','references.amount','references.currency','references.reference','references.fee_amount')
                                 ->get();
            $data['mypaysManual'] = DB::table('pays')
                                      ->where('status','completado')
                                      ->where('type_pay','=','deposito')
                                      ->orWhere('type_pay','=','transferencia')
                                      ->orWhere('type_pay','=','efectivo')
                                      ->select('pays.*')
                                      ->get();
                                    //  return $data['mypaysManual'];

        }else if($service == 'Conecta' || $service == 'Telmex'){
            $dataQuery = DB::table('instalations')
                            ->join('packs','packs.id','=','instalations.pack_id')
                            ->where('instalations.id',$id_act)
                            ->select('instalations.*','packs.name AS pack_name','packs.price AS pack_price')
                            ->get();
            
            $data['mypays'] = DB::table('ethernetpays')
                            ->join('references','references.reference_id','=','ethernetpays.reference_id')
                            ->where('ethernetpays.instalation_id',$id_act)
                            ->where('ethernetpays.status','completado')
                            ->where('references.status','completed')
                            ->select('ethernetpays.*','references.amount','references.currency','references.reference','references.fee_amount')
                            ->get();
            
            $data['mypaysManual'] = DB::table('ethernetpays')
                            ->where('status','completado')
                            ->where('type_pay','=','deposito')
                            ->orWhere('type_pay','=','transferencia')
                            ->orWhere('type_pay','=','efectivo')
                            ->select('ethernetpays.*')
                            ->get();
            
            $data['service'] = $service;
            $data['pack_name'] = $dataQuery[0]->pack_name;
            $data['pack_price'] = $dataQuery[0]->pack_price;
            $data['date_activation'] = $dataQuery[0]->date_instalation;
        }
        return view('clients.productDetails',$data);
    }

    public function create(){
        $role_id = auth()->user()->role_id;
        if($role_id == 8){
            $company_id = auth()->user()->company_id;
            $data['clients'] = User::all()->where('company_id',$company_id);
        }else{
            $data['clients'] = User::all();
        }
        
        return view('clients.create',$data);
    }

    public function savePersonaMoral(Request $request){
        $x = Clientsson::insert([
            'name' => $request->get('name'),
            'rfc' => $request->get('rfc'),
            'address' => $request->get('address'),
            'cellphone' => $request->get('cellphone'),
            'user_id' => $request->get('user_id')
        ]);

        if($x){
            return 1;
        }else{
            return 0;
        }
    }

    public function store(Request $request){
        $time = time();
        $h = date("g", $time);
        
        $name = $request->post('name');
        $lastname = $request->post('lastname');
        $email = $request->post('email');

        $rfc = $request->post('rfc');
        $date_born = $request->post('date_born');
        $address = $request->post('address');
        $cellphone = $request->post('celphone');
        $ine_code = $request->post('ine_code');
        $user_id = $request->post('user');
        $interests = $request->post('interests');
        $date_created = date('Y-m-d');

        $moral_person_bool = $request->post('moral_person_bool');

        $name2 = $request->post('name2');
        $rfc2 = $request->post('rfc2');
        $address2 = $request->post('address2');
        $cellphone2 = $request->post('cellphone2');
        
         if($email == null){
             $email = str_replace(' ', '', $name).date("YmdHis", $time);
         }

        $x = User::where('email',$email)->exists();
        if($x){
            $error = '<p>El cliente con el email <strong>'.$email.'</strong> ya existe.<p>';
            return back()->with('error',$error);
        }

        $role_id = auth()->user()->role_id;
        $company_id = null;

        if($role_id == 8){
            $company_id = auth()->user()->company_id;
        }
        

        User::insert([
            'name' => $name,
            'lastname' => $lastname,
            'email' => $email,
            'password' => Hash::make('123456789'),
            'company_id' => $company_id
        ]);

        $client_id = User::where('email',$email)->first();
        $client_id = $client_id->id;

        Client::insert([
            'address' => $address,
            'ine_code' => $ine_code,
            'date_born' => $date_born,
            'rfc' => $rfc,
            'cellphone' => $cellphone,
            'user_id' => $client_id,
            'who_did_id' => $user_id,
            'interests' => $interests,
            'date_created' => $date_created
        ]);

        if($moral_person_bool == 1){
            $exists = Clientsson::where('rfc',$rfc)->exists();
            if(!$exists){
                Clientsson::insert([
                    'name' => $name2,
                    'rfc' => $rfc2,
                    'address' => $address2,
                    'cellphone' => $cellphone2,
                    'user_id' => $client_id
                ]);
            }
        }
        $success = 'Cliente añadido con éxito.';
        return back()->with('success',$success);
    }

    public function show(User $client){
        $client_data = Client::where('user_id',$client->id)->first();
        $client['address'] = $client_data->address;
        $client['rfc'] = $client_data->rfc;
        $client['date_born'] = $client_data->date_born;
        $client['ine_code'] = $client_data->ine_code;
        $client['cellphone'] = $client_data->cellphone;
        return view('clients.show',$client);
    }
    
    public function update(Request $request, User $client){
        $id = $client->id;
        User::where('id',$id)->update([
            'name' => $request['name'],
            'lastname' => $request['lastname'],
            'email' => $request['email']
        ]);

        Client::where('user_id',$id)->update([
            'address' => $request['address'],
            'ine_code' => $request['ine_code'],
            'date_born' => $request['date_born'],
            'rfc' => $request['rfc'],
            'cellphone' => $request['celphone']
        ]);
        return back();
    }

    public function getInfoUF($msisdn){
        $bool = Number::where('MSISDN',$msisdn)->exists();

        if($bool){
            $numberData = Number::where('MSISDN',$msisdn)->first();
            $service = $numberData->producto;
            $number_id = $numberData->id;
            $service = trim($service);

            $consultUF = app('App\Http\Controllers\AltanController')->consultUF($msisdn);
            // return $consultUF;
            $responseSubscriber = $consultUF['responseSubscriber'];
            $information = $responseSubscriber['information'];
            $status = $responseSubscriber['status']['subStatus'];
            $freeUnits = $responseSubscriber['freeUnits'];
            $coordinates = $responseSubscriber['information']['coordinates'];
            $char = explode(',',$coordinates);

            if($service == 'HBB'){
                $lat_hbb = $char[0];
                $lng_hbb = $char[1];
            }else if($service == 'MIFI' || $service == 'MOV'){
                $lat_hbb = null;
                $lng_hbb = null;
            }

            $data = [];
            $data['status'] = $status;
            $data['imei'] = $information['IMEI'];
            $data['icc'] = $information['ICCID'];

            if($status == 'Active'){
                $data['status_color'] = 'success';
            }else if($status == 'Suspend (B2W)' || $status == 'Barring (B1W) (Notified by client)'){
                $data['status_color'] = 'warning';
            }

            $data['service'] = $service;

            if($service == 'MIFI' || $service == 'HBB'){
                $data['FreeUnitsBoolean'] = 0;
                $data['FreeUnits2Boolean'] = 0;
                $data['consultUF']['offerID'] = 0;
                $unidadesLibres = 0;
                $unidadesTotales = 0;
                $detailOfferings;

                for ($i=0; $i < sizeof($freeUnits); $i++) {
                    if($freeUnits[$i]['name'] == 'Free Units' || $freeUnits[$i]['name'] == 'FU_Altan-RN' || $freeUnits[$i]['name'] == 'Free Units 2' || $freeUnits[$i]['name'] == 'FU_Altan-RN_P2'){
                        $totalAmt = $freeUnits[$i]['freeUnit']['totalAmt'];
                        $unusedAmt = $freeUnits[$i]['freeUnit']['unusedAmt'];
                        $unidadesTotales = $unidadesTotales+$totalAmt;
                        $unidadesLibres = $unidadesLibres+$unusedAmt;
                        $percentageFree = ($unidadesLibres/$unidadesTotales)*100;
                        $data['FreeUnits'] = array('totalAmt'=>$unidadesTotales/1024,'unusedAmt'=>$unidadesLibres/1024,'freePercentage'=>$percentageFree);
                        $data['FreeUnitsBoolean'] = 1;

                        if($freeUnits[$i]['name'] == 'Free Units' || $freeUnits[$i]['name'] == 'FU_Altan-RN' || $freeUnits[$i]['name'] == 'Free Units 2' || $freeUnits[$i]['name'] == 'FU_Altan-RN_P2'){
                            $detailOfferings = $freeUnits[$i]['detailOfferings'];

                            $data['effectiveDatePrimary'] = ClientController::formatDateConsultUF($detailOfferings[0]['effectiveDate']);
                            $data['expireDatePrimary'] = ClientController::formatDateConsultUF($detailOfferings[0]['expireDate']);
                            $expire_date = $detailOfferings[0]['expireDate'];
                            $expire_date = substr($expire_date,0,8);
                        }

                        $data['consultUF']['offerID'] = $detailOfferings[0]['offeringId'];
                    }

                    // if($freeUnits[$i]['name'] == 'Free Units 2' || $freeUnits[$i]['name'] == 'FU_Altan-RN_P2'){
                    //     $totalAmt = $freeUnits[$i]['freeUnit']['totalAmt'];
                    //     $unusedAmt = $freeUnits[$i]['freeUnit']['unusedAmt'];
                    //     $percentageFree = ($unusedAmt/$totalAmt)*100;
                    //     $unidades = $unidades+$unusedAmt;
                    //     $data['FreeUnits2'] = array('totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree);
                    //     $data['FreeUnits2Boolean'] = 1;

                    //     $detailOfferings = $freeUnits[$i]['detailOfferings'];

                    //     $data['effectiveDateSurplus'] = ClientController::formatDateConsultUF($detailOfferings[0]['effectiveDate']);
                    //     $data['expireDateSurplus'] = ClientController::formatDateConsultUF($detailOfferings[0]['expireDate']);
                    // }
                }

                $rateData = DB::table('numbers')
                                   ->leftJoin('activations','activations.numbers_id','=','numbers.id')
                                   ->leftJoin('rates','rates.id','=','activations.rate_id')
                                   ->where('numbers.MSISDN',$msisdn)
                                   ->select('rates.name AS rate_name')
                                   ->get();

                if($status == 'Suspend (B2W)'){
                    $data['consultUF']['rate'] = $rateData[0]->rate_name.'/Suspendido por falta de pago';    
                }else if($status == 'Active'){
                    $data['consultUF']['rate'] = $rateData[0]->rate_name;
                }

                if($status == 'Active'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'activo',
                        'traffic_outbound_incoming' => 'activo',
                        'status_altan' => 'activo'
                    ]);
    
                    if($service = 'MIFI'){
                        Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date]);
                    }
    
                    if($service = 'HBB'){
                        Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date,'lat_hbb'=>$lat_hbb,'lng_hbb'=>$lng_hbb]);
                    }
                }else if($status == 'Suspend (B2W)'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'activo',
                        'traffic_outbound_incoming' => 'inactivo',
                        'status_altan' => 'activo'
                    ]);
                    if($service = 'MIFI'){
                        Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date]);
                    }
                    if($service = 'HBB'){
                        Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date,'lat_hbb'=>$lat_hbb,'lng_hbb'=>$lng_hbb]);
                    }
                }else if($status == 'Predeactivate'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'activo',
                        'traffic_outbound_incoming' => 'activo',
                        'status_altan' => 'predeactivate'
                    ]);
                }else if($status == 'Barring (B1W) (Notified by client)'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'inactivo',
                        'traffic_outbound_incoming' => 'activo',
                        'status_altan' => 'activo'
                    ]);
                }

                // if($data['FreeUnits2Boolean'] == 0){
                //     $data['FreeUnits2'] = array('totalAmt'=>0,'unusedAmt'=>0,'freePercentage'=>0);
                //     $data['effectiveDateSurplus'] = 'No se ha generado recarga.';
                //     $data['expireDateSurplus'] = 'No se ha generado recarga.';
                // }
            }else if($service == 'MOV'){
                $data['consultUF']['freeUnits']['extra'] = [];
                $data['consultUF']['freeUnits']['nacionales'] = [];
                $data['consultUF']['freeUnits']['ri'] = [];
                $data['consultUF']['offerID'] = 0;
                for ($i=0; $i < sizeof($freeUnits); $i++) {
                    $totalAmt = $freeUnits[$i]['freeUnit']['totalAmt'];
                    $unusedAmt = $freeUnits[$i]['freeUnit']['unusedAmt'];
                    $percentageFree = ($unusedAmt/$totalAmt)*100;
                    $indexDetailtOfferings = sizeof($freeUnits[$i]['detailOfferings']);
                    $indexDetailtOfferings = $indexDetailtOfferings-1;
                    $effectiveDate = ClientController::formatDateConsultUF($freeUnits[$i]['detailOfferings'][$indexDetailtOfferings]['effectiveDate']);
                    $expireDate = ClientController::formatDateConsultUF($freeUnits[$i]['detailOfferings'][$indexDetailtOfferings]['expireDate']);

                    if($freeUnits[$i]['name'] == 'FreeData_Altan-RN'){
                        $data['consultUF']['offerID'] = $freeUnits[$i]['detailOfferings'][$indexDetailtOfferings]['offeringId'];
                        array_push($data['consultUF']['freeUnits']['nacionales'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Datos Nacionales','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_SMS_Altan-NR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['nacionales'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'SMS Nacionales','description'=>'sms','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Min_Altan-NR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['nacionales'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'Minutos Nacionales','description'=>'min','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Data_Altan-NR-IR_NA'){
                        array_push($data['consultUF']['freeUnits']['ri'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Datos RI','description'=>'GB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_SMS_Altan-NR-IR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['ri'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'SMS RI','description'=>'sms','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Min_Altan-NR-IR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['ri'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'Minutos RI','description'=>'min','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Redirect_Altan-RN'){
                        array_push($data['consultUF']['freeUnits']['extra'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Navegación en Portal Cautivo','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_ThrMBB_Altan-RN_512kbps'){
                        array_push($data['consultUF']['freeUnits']['extra'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Velocidad Reducida','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }
                    // else if($freeUnits[$i]['name'] == 'FU_Data_Altan-RN_RG18'){
                    //     array_push($data['consultUF']['freeUnits']['extra'],array('totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'no sabe','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate));

                    // }
                    // print_r($freeUnits[$i]['name'].'  --  ');
                    // print_r($freeUnits[$i]['freeUnit']['totalAmt'].'  --  ');
                    // print_r($freeUnits[$i]['freeUnit']['unusedAmt'].'<br>');
                    
                }

                if($data['consultUF']['offerID'] == 0){
                    $data['consultUF']['rate'] = 'PLAN NO CONTRATADO';    
                }else{
                    $rateData = Offer::where('offerID_second',$data['consultUF']['offerID'])->first();
                    $data['consultUF']['rate'] = $rateData->name_second;
                }

                if($status == 'Active'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'activo',
                        'traffic_outbound_incoming' => 'activo',
                        'status_altan' => 'activo'
                    ]);
                }else if($status == 'Suspend (B2W)'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'activo',
                        'traffic_outbound_incoming' => 'inactivo',
                        'status_altan' => 'activo'
                    ]);
                }else if($status == 'Predeactivate'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'activo',
                        'traffic_outbound_incoming' => 'activo',
                        'status_altan' => 'predeactivate'
                    ]);
                }else if($status == 'Barring (B1W) (Notified by client)'){
                    Number::where('id',$number_id)->update([
                        'traffic_outbound' => 'inactivo',
                        'traffic_outbound_incoming' => 'activo',
                        'status_altan' => 'activo'
                    ]);
                }
                
            }
            

            return view('clients.consumptions',$data);
        }else{
            return view('home');
        }
    }

    public function getInfoUFPublic($msisdn){
        $bool = Number::where('MSISDN',$msisdn)->where('status_altan','activo')->where('deleted_at','=',null)->exists();

        if($bool){
            $myNumber = Number::where('MSISDN',$msisdn)->where('status_altan','activo')->where('deleted_at','=',null)->first();
            $mynumberProduct = trim($myNumber->producto)
            ;
            $consultUF = app('App\Http\Controllers\AltanController')->consultUF($msisdn);
            // return $consultUF;
            $responseSubscriber = $consultUF['responseSubscriber'];
            $information = $responseSubscriber['information'];
            $status = $responseSubscriber['status']['subStatus'];
            $freeUnits = $responseSubscriber['freeUnits'];

            $data = [];
            $data['exists'] = 1;
            $data['status'] = $status;
            $data['imei'] = $information['IMEI'];
            $data['icc'] = $information['ICCID'];
            $data['data_client'] = DB::table('numbers')
                                      ->join('activations','activations.numbers_id','=','numbers.id')
                                      ->join('users','users.id','=','activations.client_id')
                                      ->join('clients','clients.user_id','=','users.id')
                                      ->where('numbers.MSISDN',$msisdn)
                                      ->select('users.id AS client_id','users.name AS client_name','users.lastname AS client_lastname',
                                      'users.email AS client_email','clients.cellphone AS client_cellphone')
                                      ->get();

            if($status == 'Active'){
                $data['status_color'] = 'success';
            }else if($status == 'Suspend (B2W)' || $status == 'Barring (B1W) (Notified by client)' || $status == 'Barring (B1W) (By NoB28)' || $status == 'Suspend (B2W) (By mobility)'){
                $data['status_color'] = 'warning';
            }

            if($mynumberProduct == 'MIFI' || $mynumberProduct == 'HBB'){
                $data['FreeUnitsBoolean'] = 0;
                $data['FreeUnits2Boolean'] = 0;

                for ($i=0; $i < sizeof($freeUnits); $i++) {
                    if($freeUnits[$i]['name'] == 'Free Units' || $freeUnits[$i]['name'] == 'FU_Altan-RN'){
                        $totalAmt = $freeUnits[$i]['freeUnit']['totalAmt'];
                        $unusedAmt = $freeUnits[$i]['freeUnit']['unusedAmt'];
                        $percentageFree = ($unusedAmt/$totalAmt)*100;
                        $data['FreeUnits'] = array('totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree);
                        $data['FreeUnitsBoolean'] = 1;

                        $detailOfferings = $freeUnits[$i]['detailOfferings'];

                        $data['effectiveDatePrimary'] = ClientController::formatDateConsultUF($detailOfferings[0]['effectiveDate']);
                        $data['expireDatePrimary'] = ClientController::formatDateConsultUF($detailOfferings[0]['expireDate']);
                    }

                    if($freeUnits[$i]['name'] == 'Free Units 2' || $freeUnits[$i]['name'] == 'FU_Altan-RN_P2'){
                        $totalAmt = $freeUnits[$i]['freeUnit']['totalAmt'];
                        $unusedAmt = $freeUnits[$i]['freeUnit']['unusedAmt'];
                        $percentageFree = ($unusedAmt/$totalAmt)*100;
                        $data['FreeUnits2'] = array('totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree);
                        $data['FreeUnits2Boolean'] = 1;

                        $detailOfferings = $freeUnits[$i]['detailOfferings'];

                        $data['effectiveDateSurplus'] = ClientController::formatDateConsultUF($detailOfferings[0]['effectiveDate']);
                        $data['expireDateSurplus'] = ClientController::formatDateConsultUF($detailOfferings[0]['expireDate']);
                    }
                }

                if($data['FreeUnitsBoolean'] == 0){
                    $data['FreeUnits'] = array('totalAmt'=>0,'unusedAmt'=>0,'freePercentage'=>0);
                    $data['effectiveDatePrimary'] = 'No existe Oferta/Plan designado para esta SIM.';
                    $data['expireDatePrimary'] = 'No existe Oferta/Plan designado para esta SIM.';
                }

                if($data['FreeUnits2Boolean'] == 0){
                    $data['FreeUnits2'] = array('totalAmt'=>0,'unusedAmt'=>0,'freePercentage'=>0);
                    $data['effectiveDateSurplus'] = 'No se ha generado recarga.';
                    $data['expireDateSurplus'] = 'No se ha generado recarga.';
                }
            }else if($mynumberProduct == 'MOV'){
                $data['consultUF']['freeUnits']['extra'] = [];
                $data['consultUF']['freeUnits']['nacionales'] = [];
                $data['consultUF']['freeUnits']['ri'] = [];
                $data['consultUF']['offerID'] = 0;
                for ($i=0; $i < sizeof($freeUnits); $i++) {
                    $totalAmt = $freeUnits[$i]['freeUnit']['totalAmt'];
                    $unusedAmt = $freeUnits[$i]['freeUnit']['unusedAmt'];
                    $percentageFree = ($unusedAmt/$totalAmt)*100;
                    $indexDetailtOfferings = sizeof($freeUnits[$i]['detailOfferings']);
                    $indexDetailtOfferings = $indexDetailtOfferings-1;
                    $effectiveDate = ClientController::formatDateConsultUF($freeUnits[$i]['detailOfferings'][$indexDetailtOfferings]['effectiveDate']);
                    $expireDate = ClientController::formatDateConsultUF($freeUnits[$i]['detailOfferings'][$indexDetailtOfferings]['expireDate']);

                    if($freeUnits[$i]['name'] == 'FreeData_Altan-RN'){
                        $data['consultUF']['offerID'] = $freeUnits[$i]['detailOfferings'][$indexDetailtOfferings]['offeringId'];
                        array_push($data['consultUF']['freeUnits']['nacionales'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Datos Nacionales','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_SMS_Altan-NR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['nacionales'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'SMS Nacionales','description'=>'sms','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Min_Altan-NR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['nacionales'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'Minutos Nacionales','description'=>'min','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Data_Altan-NR-IR_NA'){
                        array_push($data['consultUF']['freeUnits']['ri'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Datos RI','description'=>'GB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_SMS_Altan-NR-IR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['ri'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'SMS RI','description'=>'sms','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Min_Altan-NR-IR-LDI_NA'){
                        array_push($data['consultUF']['freeUnits']['ri'],array(
                            'totalAmt'=>$totalAmt,'unusedAmt'=>$unusedAmt,'freePercentage'=>$percentageFree,'name'=>'Minutos RI','description'=>'min','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_Redirect_Altan-RN'){
                        array_push($data['consultUF']['freeUnits']['extra'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Navegación en Portal Cautivo','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }else if($freeUnits[$i]['name'] == 'FU_ThrMBB_Altan-RN_512kbps'){
                        array_push($data['consultUF']['freeUnits']['extra'],array(
                            'totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'Velocidad Reducida','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate
                        ));

                    }
                    // else if($freeUnits[$i]['name'] == 'FU_Data_Altan-RN_RG18'){
                    //     array_push($data['consultUF']['freeUnits']['extra'],array('totalAmt'=>$totalAmt/1024,'unusedAmt'=>$unusedAmt/1024,'freePercentage'=>$percentageFree,'name'=>'no sabe','description'=>'MB','effectiveDate'=>$effectiveDate,'expireDate'=>$expireDate));

                    // }
                    // print_r($freeUnits[$i]['name'].'  --  ');
                    // print_r($freeUnits[$i]['freeUnit']['totalAmt'].'  --  ');
                    // print_r($freeUnits[$i]['freeUnit']['unusedAmt'].'<br>');
                    
                }

                if($data['consultUF']['offerID'] == 0){
                    $data['consultUF']['rate'] = 'PLAN NO CONTRATADO';    
                }else{
                    $rateData = Offer::where('offerID_second',$data['consultUF']['offerID'])->first();
                    $data['consultUF']['rate'] = $rateData->name_second;
                }
            }

            return $data;
        }else{
            $data['exists'] = 0;
            return $data;
        }
    }

    public function formatDateConsultUF($date){
        $year = substr($date,0,4);
        $month = substr($date,4,2);
        $day = substr($date,6,2);
        $hour = substr($date,8,2);
        $minute = substr($date,10,2);
        $second = substr($date,12,2);
        $date = $day.'-'.$month.'-'.$year.' '.$hour.':'.$minute.':'.$second;
        return $date;
    }

    public function myReferences(){
        // $reference['references'] = Reference::where('referencestype_id' == 5)->get();
        
            $current_id = auth()->user()->id;
            $current_role = auth()->user()->role_id;
            $data['references'] = DB::table('references')
                                ->join('channels','channels.id','=','references.channel_id')
                                ->join('rates','rates.id','=','references.rate_id')
                                ->join('numbers','numbers.id','=','references.number_id')
                                ->where('references.referencestype_id', 5)
                                ->where('references.user_id',$current_id)
                                ->select('references.*','channels.name AS channel_name','rates.name AS rate_name','numbers.MSISDN AS number_dn', 'numbers.producto AS number_product')
                                ->get();
            // return $data;
            return view('clients.references', $data);
    } 

    public function changeProduct($msisdn, Request $request) {
        $payment = 0;
        $extraAmount = 0;
        $service = 0;

        if(isset($request['payment'])){
            $payment = $request['payment'];
            $extraAmount = $request['extraAmount'];
            $service = $request['service'];
        }
 
        $data['payment'] = $payment;
        $data['extraAmount'] = $extraAmount;
        $data['service'] = $service;

        $data['dataMSISDN']['MSISDN'] = $msisdn;

        $dataClient =  DB::table('activations')
                          ->join('numbers','numbers.id','=','activations.numbers_id')
                          ->join('users','users.id','=','activations.client_id')
                          ->where('numbers.MSISDN',$msisdn)
                          ->select('users.*')
                          ->get();
        
        $data['dataMSISDN']['name_user'] = $dataClient[0]->name;
        $data['dataMSISDN']['lastname_user'] = $dataClient[0]->lastname;
        $data['dataMSISDN']['email_user'] = $dataClient[0]->email;
        // return $data;
        return view('clients.changeProduct',$data);
    }

    public function getDataMonthly(Request $request){
        $msisdn = $request->post('msisdn');

        $dataMSISDN = DB::table('numbers')
                         ->join('activations','activations.numbers_id','=','numbers.id')
                         ->join('users','users.id','=','activations.client_id')
                         ->join('clients','clients.user_id','=','users.id')
                         ->where('numbers.MSISDN',$msisdn)
                         ->select('numbers.id AS number_id','activations.expire_date AS expire_date','activations.id AS activation_id',
                         'users.name AS name_user','users.lastname AS lastname_user','users.email AS email_user','clients.cellphone AS cellphone_user','users.id AS user_id')
                         ->get();
            
        $data['information'] = array(
            'number_id' => $dataMSISDN[0]->number_id,
            'expire_date' => $dataMSISDN[0]->expire_date,
            'activation_id' => $dataMSISDN[0]->activation_id,
            'name_user' => $dataMSISDN[0]->name_user,
            'lastname_user' => $dataMSISDN[0]->lastname_user,
            'email_user' => $dataMSISDN[0]->email_user,
            'cellphone_user' => $dataMSISDN[0]->cellphone_user,
            'client_id' => $dataMSISDN[0]->user_id
        );
        return $data;
    }

    public function getDataMonthlyOreda(Request $request){
        $dataInstalation = Instalation::where('number',$request->post('msisdn'))->first();
        $id = $dataInstalation->id;
        $pack_id = $dataInstalation->pack_id;
        $client_id = $dataInstalation->client_id;
        $dataClient = User::where('id',$client_id)->first();
        $dataInfoClient = Client::where('user_id',$client_id)->first();
        $dataPack = Pack::where('id',$pack_id)->first();
        $pack_name = $dataPack->name;
        $monthlies = Ethernetpay::all()->where('instalation_id',$id)->where('status','pendiente');
        $amount = 0;
        $monthliesPerPay = 0;
        $monthliesData = [];
        $monthlyJSON = [];
        $pay_id = '';
        
        foreach ($monthlies as $monthly) {
            $amount+=$monthly->amount;
            array_push($monthliesData,array(
                'datePay'=>$monthly->date_pay,
                'datePayLimit'=>$monthly->date_pay_limit,
                'amount' => $monthly->amount
            ));

            array_push($monthlyJSON,array(
                'name' => $pack_name.' ('.$monthly->date_pay.' - '.$monthly->date_pay_limit.')',
                'unit_price' => $monthly->amount,
                'quantity' => 1
            ));

            if($monthliesPerPay == 0){
                $pay_id.=$monthly->id;
            }else if($monthliesPerPay > 0){
                $pay_id.=','.$monthly->id;
            }
            $monthliesPerPay+=1;
        }
        $data = array(
            'client_id' => $client_id,
            'name' => $dataClient->name,
            'lastname' => $dataClient->lastname,
            'email' => $dataClient->email,
            'phone' => $dataInfoClient->cellphone,
            'pack_id' => $pack_id,
            'pack_name' => $pack_name,
            'pay_id' => $pay_id,
            'amount' => $amount,
            'monthliesPendings' => $monthliesPerPay,
            'monthly_items' => $monthliesData,
            'monthlyJSON' => $monthlyJSON,
            'instalation_id' => $id
        );
        return $data;
    }

    public function getMonthlyPayment(Request $request){
        $activation_id = $request->get('activation_id');

        $dataActivation = Activation::where('id',$activation_id)->first();
        $flag_rate = $dataActivation->flag_rate;

        if($flag_rate == 1){
            $data['payment'] = DB::table('pays')
                             ->join('activations','activations.id','=','pays.activation_id')
                             ->join('numbers','numbers.id','=','activations.numbers_id')
                             ->join('rates','rates.id','=','activations.rate_id')
                             ->join('offers','offers.id','=','activations.offer_id')
                             ->where('pays.activation_id',$activation_id)
                             ->where('pays.status','pendiente')
                             ->select('pays.id AS pay_id','pays.activation_id AS activation_id','pays.amount AS amount','pays.status AS status_payment',
                             'activations.offer_id AS offer_id','activations.rate_id AS rate_id','rates.name AS rate_name','activations.flag_rate AS flag_rate',
                             'numbers.producto AS product','offers.offerID AS offerID')
                             ->orderBy('pays.date_pay','desc')
                             ->limit(1)
                             ->get();
        }else if($flag_rate == 0){
            $data['payment'] = DB::table('pays')
                             ->join('activations','activations.id','=','pays.activation_id')
                             ->join('numbers','numbers.id','=','activations.numbers_id')
                             ->join('rates','rates.id','=','activations.rate_subsequent')
                             ->join('offers','offers.id','=','rates.alta_offer_id')
                             ->where('pays.activation_id',$activation_id)
                             ->where('pays.status','pendiente')
                             ->select('pays.id AS pay_id','pays.activation_id AS activation_id','rates.price_subsequent AS amount','pays.status AS status_payment',
                             'rates.alta_offer_id AS offer_id','rates.id AS rate_id','rates.name AS rate_name','activations.flag_rate AS flag_rate',
                             'numbers.producto AS product','offers.offerID AS offerID')
                             ->orderBy('pays.date_pay','desc')
                             ->limit(1)
                             ->get();
            // return 'Aplica tarifa subsecuente';
        }

        $data['originalData'] = DB::table('activations')
                                   ->join('rates','rates.id','=','activations.rate_id')
                                   ->join('offers','offers.id','=','activations.offer_id')
                                   ->where('activations.id',$activation_id)
                                   ->select('activations.rate_id AS rate_id','rates.price_subsequent AS rate_price','offers.offerID AS offerID','activations.offer_id AS offer_id','rates.name AS rate_name',
                                   'activations.lat_hbb','activations.lng_hbb')
                                   ->get();

            
        return $data;
    }

    public function getRateSubsequentPayment(Request $request){
        $activation_id = $request->get('activation_id');

        $dataActivation = Activation::where('id',$activation_id)->first();
        $flag_rate = $dataActivation->flag_rate;
        $response = [];

        if($flag_rate == 0){
            $rate_subsequent_id = $dataActivation->rate_subsequent;
            $dataRate = Rate::where('id',$rate_subsequent_id)->first();
            
            array_push($response,array(
                'offer_id' => $dataRate->alta_offer_id,
                'rate_id' => $dataRate->id,
                'amount' => $dataRate->price_subsequent,
                'rate_name' => $dataRate->name,
                'flag' => $flag_rate
            ));

        }else if($flag_rate == 1){
            array_push($response,array(
                'flag' => $flag_rate
            ));
        }

        $data['payment'] = DB::table('activations')
                             ->join('numbers','numbers.id','=','activations.numbers_id')
                             ->join('rates','rates.id','=','activations.rate_id')
                             ->join('offers','offers.id','=','activations.offer_id')
                             ->join('pays','pays.activation_id','=','activations.id')
                             ->where('activations.id',$activation_id)
                             ->select('activations.offer_id AS offer_id','activations.rate_id AS rate_id','rates.name AS rate_name','activations.flag_rate AS flag_rate',
                             'numbers.producto AS product','offers.offerID AS offerID')
                             ->orderBy('pays.date_pay','desc')
                             ->limit(1)
                             ->get();

        $response['originalData'] = DB::table('activations')
                                   ->join('rates','rates.id','=','activations.rate_id')
                                   ->join('offers','offers.id','=','activations.offer_id')
                                   ->where('activations.id',$activation_id)
                                   ->select('activations.rate_id AS rate_id','rates.price_subsequent AS rate_price','offers.offerID AS offerID','activations.offer_id AS offer_id','rates.name AS rate_name',
                                   'activations.lat_hbb','activations.lng_hbb')
                                   ->get();

        return $response;
    }

    public function verifyExistsMSISDN($msisdn){
        $x = Number::where('msisdn',$msisdn)->exists();
        if($x){
            $x = Number::where('msisdn',$msisdn)->first();
            $producto = trim($x->producto);
            $number_id = $x->id;
            $consultUF = app('App\Http\Controllers\AltanController')->consultUF($msisdn);
            $responseSubscriber = $consultUF['responseSubscriber'];
            $status = $responseSubscriber['status']['subStatus'];
            $coordinates = $responseSubscriber['information']['coordinates'];
            $freeUnits = $responseSubscriber['freeUnits'];
            $char = explode(',',$coordinates);
            if($producto == 'HBB'){
                $lat_hbb = $char[0];
                $lng_hbb = $char[1];
            }else if($producto == 'MIFI' || $producto == 'MOV'){
                $lat_hbb = null;
                $lng_hbb = null;
            }

            $expire_date = null;
            for ($i=0; $i < sizeof($freeUnits); $i++) {
                if($freeUnits[$i]['name'] == 'Free Units' || $freeUnits[$i]['name'] == 'FU_Altan-RN'){
                    $detailOfferings = $freeUnits[$i]['detailOfferings'];
                    $expire_date = $detailOfferings[0]['expireDate'];
                    $expire_date = substr($expire_date,0,8);
                }
            }

            $http_code = 0;

            if($status == 'Active'){
                Number::where('id',$number_id)->update([
                    'traffic_outbound' => 'activo',
                    'traffic_outbound_incoming' => 'activo',
                    'status_altan' => 'activo'
                ]);

                if($producto == 'MIFI'){
                    Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date]);
                    $http_code = 1;
                }

                if($producto == 'MOV'){
                    Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date]);
                    $http_code = 1;
                }

                if($producto == 'HBB'){
                    Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date,'lat_hbb'=>$lat_hbb,'lng_hbb'=>$lng_hbb]);
                    $http_code = 1;
                }
                
            }else if($status == 'Suspend (B2W)'){
                Number::where('id',$number_id)->update([
                    'traffic_outbound' => 'activo',
                    'traffic_outbound_incoming' => 'inactivo',
                    'status_altan' => 'activo'
                ]);

                if($producto == 'MIFI'){
                    Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date]);
                }

                if($producto == 'HBB'){
                    Activation::where('numbers_id',$number_id)->update(['expire_date'=>$expire_date,'lat_hbb'=>$lat_hbb,'lng_hbb'=>$lng_hbb]);
                }
                $http_code = 2;
            }else if($status == 'Predeactivate'){
                Number::where('id',$number_id)->update([
                    'traffic_outbound' => 'activo',
                    'traffic_outbound_incoming' => 'activo',
                    'status_altan' => 'predeactivate'
                ]);
                $http_code = 3;
            }else if($status == 'Barring (B1W) (Notified by client)'){
                Number::where('id',$number_id)->update([
                    'traffic_outbound' => 'inactivo',
                    'traffic_outbound_incoming' => 'activo',
                    'status_altan' => 'activo'
                ]);
                $http_code = 4;
            }

            return response()->json(['http_code'=>$http_code,'message'=>'MSISDN existente.','product'=>$producto]);
        }else{
            $y = Instalation::where('number',$msisdn)->exists();
            if($y){
                $http_code = 5;
                return response()->json(['http_code'=>$http_code,'message'=>'OREDA existente.']);
            }
            return response()->json(['http_code'=>0,'message'=>'El número de SIM '.$msisdn.' es de otra compañía.']);
        }
    }

    public function contractsView(){
        $data['clients'] = DB::table('users')
                              ->join('activations','activations.client_id','=','users.id')
                              ->join('numbers','numbers.id','=','activations.numbers_id')
                              ->leftJoin('contracts','contracts.msisdn','=','numbers.MSISDN')
                              ->select('users.*','numbers.MSISDN','contracts.msisdn AS contract_sim','contracts.id AS contract_id','numbers.producto','numbers.id AS msisdn_id')
                              ->get();
        // return $data;

        return view('clients.contracts',$data);
    }

    public function getDataContract(Request $request){
        $contract_id = $request->get('contract_id');

        if($contract_id == 0){
            $client_id = $request->get('client_id');
            $msisdn_id = $request->get('msisdn_id');

            $data['dataMSISDN'] = Number::where('id',$msisdn_id)->first();
            $data['dataUser'] = User::where('id',$client_id)->first();
            $data['dataClient'] = Client::where('user_id',$client_id)->first();
            $data['dataActivation'] = Activation::where('numbers_id',$msisdn_id)->first();
            $device_id = $data['dataActivation']->devices_id;
            $data['dataDevice'] = Device::where('id',$device_id)->first();
        }else{
            $data['dataContract'] = Contract::where('id',$contract_id)->first();
        }

        return $data;
    }

    public function myMovements(){
        $user_id = auth()->user()->id;
        $data['recharges'] = [];
        $data['changes'] = [];
        $data['paymentsCompleted'] = [];
        $recharges = DB::table('purchases')
                        ->join('numbers','numbers.id','=','purchases.number_id')
                        ->join('activations','activations.numbers_id','=','purchases.number_id')
                        ->join('users','users.id','=','activations.client_id')
                        ->join('rates','rates.id','=','purchases.rate_id')
                        ->join('offers','offers.id','=','purchases.offer_id')
                        ->where('purchases.reason','cobro')
                        ->select('numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name','offers.name AS offer_name','purchases.date AS date_purchase','purchases.amount AS amount',
                        'users.name AS client_name','users.lastname AS client_lastname','purchases.who_did_id AS who_did_id')
                        ->get();
        
        foreach ($recharges as $recharge) {
            $who_did_id = $recharge->who_did_id;
            $dataWho = User::where('id',$who_did_id)->first();
            $company_id = $dataWho->company_id;
            $company = 'N/A';
            if($company_id != null){
                $dataCompany = Company::where('id',$company_id)->first();
                $company = $dataCompany->name;
            }
            
            array_push($data['recharges'],array(
                'number' => $recharge->number,
                'product' => $recharge->product,
                'rate_name' => $recharge->rate_name,
                'offer_name' => $recharge->offer_name,
                'date_purchase' => $recharge->date_purchase,
                'amount' => $recharge->amount,
                'client_name' => $recharge->client_name,
                'client_lastname' => $recharge->client_lastname,
                'who_did_it' => $dataWho->name.' '.$dataWho->lastname,
                'company' => $company
            ));
        }

        $changes = DB::table('changes')
                      ->join('numbers','numbers.id','=','changes.number_id')
                      ->join('activations','activations.numbers_id','=','changes.number_id')
                      ->join('users','users.id','=','activations.client_id')
                      ->join('rates','rates.id','=','changes.rate_id')
                      ->join('offers','offers.id','=','changes.offer_id')
                      ->where('changes.reason','cobro')
                      ->orWhere('changes.reason','mensualidad')
                      ->select('numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name','offers.name AS offer_name','changes.date AS date_purchase','changes.amount AS amount',
                      'users.name AS client_name','users.lastname AS client_lastname','changes.who_did_id AS who_did_id')
                      ->get();

        foreach ($changes as $change) {
            $who_did_id = $change->who_did_id;
            if($who_did_id != null){
                $dataWho = User::where('id',$who_did_id)->first();
                $company_id = $dataWho->company_id;
                $company = 'N/A';
                if($company_id != null){
                    $dataCompany = Company::where('id',$company_id)->first();
                    $company = $dataCompany->name;
                }
                
                array_push($data['changes'],array(
                    'number' => $change->number,
                    'product' => $change->product,
                    'rate_name' => $change->rate_name,
                    'offer_name' => $change->offer_name,
                    'date_purchase' => $change->date_purchase,
                    'amount' => $change->amount,
                    'client_name' => $change->client_name,
                    'client_lastname' => $change->client_lastname,
                    'who_did_it' => $dataWho->name.' '.$dataWho->lastname,
                    'company' => $company
                ));
            }
        }

        $paymentsCompleted = DB::table('pays')
                                ->join('activations','activations.id','=','pays.activation_id')
                                ->join('rates','rates.id','=','activations.rate_id')
                                ->join('users','users.id','=','activations.client_id')
                                ->join('numbers','numbers.id','=','activations.numbers_id')
                                ->where('pays.status','completado')
                                ->select('pays.*','rates.name AS rate_name','users.id AS client_id','users.name AS client_name','users.lastname AS client_lastname','numbers.producto AS number_product','numbers.id AS number_id','numbers.MSISDN AS DN')
                                ->get();
                            
        foreach ($paymentsCompleted as $paymentCompleted) {
            $who_did_id = $paymentCompleted->who_did_id;
            if($who_did_id != null){
                $dataWho = User::where('id',$who_did_id)->first();
                $company_id = $dataWho->company_id;
                $company = 'N/A';
                if($company_id != null){
                    $dataCompany = Company::where('id',$company_id)->first();
                    $company = $dataCompany->name;
                }
                
                array_push($data['paymentsCompleted'],array(
                    'DN' => $paymentCompleted->DN,
                    'number_product' => $paymentCompleted->number_product,
                    'rate_name' => $paymentCompleted->rate_name,
                    'date_pay' => $paymentCompleted->date_pay,
                    'amount_received' => $paymentCompleted->amount_received,
                    'extra' => $paymentCompleted->extra,
                    'client_name' => $paymentCompleted->client_name,
                    'client_lastname' => $paymentCompleted->client_lastname,
                    'who_did_it' => $dataWho->name.' '.$dataWho->lastname,
                    'company' => $company
                ));
            }
        }
                                        
        return view('clients.myMovements',$data);
    }

    public function appUser(Request $request){
        $msisdn = $request->client_msisdn;
        $exists = Number::where('MSISDN',$msisdn)->where('status_altan','activo')->where('deleted_at','=',null)->exists();
        if($exists){
            $dataUser = DB::table('numbers')
                        ->join('activations','activations.numbers_id','=','numbers.id')
                        ->join('rates','rates.id','=','activations.rate_id')
                        ->where('numbers.MSISDN',$msisdn)
                        ->where('numbers.deleted_at',null)
                        ->select('numbers.MSISDN AS telefono','activations.date_activation AS Subscriber_Creation_date','activations.expire_date AS expire_date',
                        'numbers.icc_id AS ICC','rates.name AS plan','numbers.producto AS service','numbers.traffic_outbound AS status_mov','numbers.traffic_outbound_incoming AS status_mh',
                        'numbers.status_altan AS status_altan','numbers.status_imei AS status_imei','activations.id AS activation_id')
                        ->get();
                        
            $status = '';
            $service = $dataUser[0]->service;
            $service = trim($service);
            $activation_id = $dataUser[0]->activation_id;

            if($dataUser[0]->status_altan == 'activo'){
                if($service == 'MIFI' || $service == 'HBB'){
                    $status = $dataUser[0]->status_mh;
                }else if($service == 'MOV'){
                    $status = $dataUser[0]->status_mov;
                }
            }else{
                $status = $dataUser[0]->status_altan;
            }

            $payment = Pay::where('activation_id',$activation_id)->where('status','pendiente')->exists();
            if($payment){
                $payment = Pay::where('activation_id',$activation_id)->where('status','pendiente')->first();
                $dataActivation = Activation::where('id',$activation_id)->first();
                $payment = [
                    'amount' => $payment->amount,
                    'concepto' => 'Pago de Servicio de Internet Conecta',
                    'client_id' => $dataActivation->client_id,
                    'user_id' => $dataActivation->client_id,
                    'pay_id' => $payment->id,
                    'offer_id' => $dataActivation->offer_id,
                    'rate_id' => $dataActivation->rate_id,
                    'number_id' => $dataActivation->numbers_id,
                    'referencestype' => 1,
                    'service' => trim($dataUser[0]->service),
                    'date_payment' => $payment->date_pay,
                    'date_payment_limit' => $payment->date_pay_limit
                ];
            }

            $data = array('dataUser'=>array(
                'telefono' => $dataUser[0]->telefono,
                'Subscriber_Creation_date' => $dataUser[0]->Subscriber_Creation_date,
                'icc' => $dataUser[0]->ICC,
                'plan' => $dataUser[0]->plan,
                'expire_date' => $dataUser[0]->expire_date,
                'service' => trim($dataUser[0]->service),
                'status' => $status,
                'exists' => 1,
                'payment' => $payment
            ));
        }else{
            $data = array('dataUser'=>array(
                'exists' => 0
            ));
        }
        return $data;
    }
}



                      