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
use App\Rate;
use App\Role;
use App\Activation;
use App\Number;
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
                              ->where('rates.type','publico')
                              ->select('users.*','numbers.MSISDN AS number','numbers.producto AS product','rates.name AS rate_name')
                              ->get();
        return view('clients.recharge',$data);
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
            ])->post('http://crm.altcel/activate-deactivate/DN-api',[
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
        ])->get('http://crm.altcel/get-offers-rates-surplus',['msisdn'=>$msisdn]);
        
        $data['dataMSISDN'] = $response['dataMSISDN'];
        $data['packsSurplus'] = $response['packsSurplus'];
        $data['channels'] = Channel::all();

        // return $data;
        return view('clients.surplusRates',$data);
    }

    public function surplusRatesDealer($msisdn){

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('http://crm.altcel/get-offers-rates-surplus',['msisdn'=>$msisdn]);
        
        $data['dataMSISDN'] = $response['dataMSISDN'];
        $data['packsSurplus'] = $response['packsSurplus'];
        $data['channels'] = Channel::all();

        // return $data;
        return view('clients.surplusRatesDealer',$data);
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
        return view('clients.create');
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
        
         if($email == null){
             $email = str_replace(' ', '', $name).date("YmdHis", $time);
         }

        $x = User::where('email',$email)->exists();
        if($x){
            $error = '<p>El usuario con el email <strong>'.$email.'</strong> ya existe.<p>';
            return back()->with('error',$error);
        }
        

        User::insert([
            'name' => $name,
            'lastname' => $lastname,
            'email' => $email,
            'password' => Hash::make('123456789')
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
            $consultUF = app('App\Http\Controllers\AltanController')->consultUF($msisdn);
            // return $consultUF;
            $responseSubscriber = $consultUF['responseSubscriber'];
            $information = $responseSubscriber['information'];
            $status = $responseSubscriber['status']['subStatus'];
            $freeUnits = $responseSubscriber['freeUnits'];

            $data = [];
            $data['status'] = $status;
            $data['imei'] = $information['IMEI'];
            $data['icc'] = $information['ICCID'];

            if($status == 'Active'){
                $data['status_color'] = 'success';
            }else if($status == 'Suspend (B2W)'){
                $data['status_color'] = 'warning';
            }
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

            if($data['FreeUnits2Boolean'] == 0){
                $data['FreeUnits2'] = array('totalAmt'=>0,'unusedAmt'=>0,'freePercentage'=>0);
                $data['effectiveDateSurplus'] = 'No se ha generado recarga.';
                $data['expireDateSurplus'] = 'No se ha generado recarga.';
            }

            return view('clients.consumptions',$data);
        }else{
            return view('home');
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
}
