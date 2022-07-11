<?php

namespace App\Http\Controllers;

use DB;
use Http;
use App\Activation;
use App\Rate;
use App\User;
use App\Client;
use App\Petition;
use App\GuzzleHttp;
use App\Otherpetition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetitionController extends Controller
{

    public function index()
    {
        $current_id = auth()->user()->id;
        $petitions = DB::table('petitions')
                         ->join('users','users.id','=','petitions.client_id')
                         ->where('status','solicitud')
                         ->select('petitions.id AS id','users.name AS client_name','users.lastname AS client_lastname','petitions.date_sent AS date_sent',
                         'petitions.date_activated AS date_activated','petitions.who_attended AS who_activated','petitions.product AS product','petitions.status AS status',
                         'petitions.comment AS comment', 'petitions.rate_activation','petitions.rate_secondary','petitions.sender AS sender')
                         ->get();

            
        $data['petitions'] = [];
        foreach ($petitions as $petition) {
            $who_activated = '';
            $who_sent = '';
            $who_sentData = '';
            $date_activated = '';
            $colorActivated = '';

            
                if($petition->status == 'activado'){
                    $whoActivatedData = User::where('id',$petition->who_activated)->first();
                    $who_activated = $whoActivatedData->name.' '.$whoActivatedData->lastname;
                    $date_activated = $petition->date_activated;
                    $colorActivated = 'success';
                    $colorStatus = 'success';
                }else{
                    $who_activated = 'Por activar';
                    $date_activated = 'Por activar';
                    $colorActivated = 'warning';
                    $colorStatus = 'warning';
                }
    
                if($petition->rate_activation == null){
                    $rateActivation = 'No se eligió';
                }else{
                    $dataRate = Rate::where('id',$petition->rate_activation)->first();
                    $rateActivation = $dataRate->name;
                }
    
                if($petition->rate_secondary == null){
                    $rateSecondary = 'No se eligió';
                }else{
                    $dataRate = Rate::where('id',$petition->rate_secondary)->first();
                    $rateSecondary = $dataRate->name;
                }

                $who_sentData = User::where('id',$petition->sender)->first();
                
                array_push($data['petitions'],array(
                    'id' => $petition->id,
                    'client_name' => $petition->client_name.' '.$petition->client_lastname,
                    'product' => $petition->product,
                    'status' => $petition->status,
                    'date_sent' => $petition->date_sent,
                    'who_activated' => $who_activated,
                    'date_activated' => $date_activated,
                    'comment' => $petition->comment,
                    'colorStatus' => $colorStatus,
                    'colorActivated' => $colorActivated,
                    'rate_activation' => $rateActivation,
                    'rate_secondary' => $rateSecondary,
                    'sender' => $petition->sender,
                    'who_sent' => $who_sentData->name.' '.$who_sentData->lastname
                ));
            

        }
        return view('petitions.index',$data);
    }

    public function indexDealer(){
        $current_id = auth()->user()->id;
        $petitions = DB::table('petitions')
                         ->join('users','users.id','=','petitions.client_id')
                         ->where('status','solicitud')
                         ->where('sender',$current_id)
                         ->select('petitions.id AS id','users.name AS client_name','users.lastname AS client_lastname','petitions.date_sent AS date_sent',
                         'petitions.date_activated AS date_activated','petitions.who_attended AS who_activated','petitions.product AS product','petitions.status AS status',
                         'petitions.comment AS comment', 'petitions.rate_activation','petitions.rate_secondary')
                         ->get();

            
        $data['petitions'] = [];
        foreach ($petitions as $petition) {
            $who_activated = '';
            $date_activated = '';
            $colorActivated = '';

            if($petition->status == 'activado'){
                $whoActivatedData = User::where('id',$petition->who_activated)->first();
                $who_activated = $whoActivatedData->name.' '.$whoActivatedData->lastname;
                $date_activated = $petition->date_activated;
                $colorActivated = 'success';
                $colorStatus = 'success';
            }else{
                $who_activated = 'Por activar';
                $date_activated = 'Por activar';
                $colorActivated = 'warning';
                $colorStatus = 'warning';
            }

            if($petition->rate_activation == null){
                $rateActivation = 'No se eligió';
            }else{
                $dataRate = Rate::where('id',$petition->rate_activation)->first();
                $rateActivation = $dataRate->name;
            }

            if($petition->rate_secondary == null){
                $rateSecondary = 'No se eligió';
            }else{
                $dataRate = Rate::where('id',$petition->rate_secondary)->first();
                $rateSecondary = $dataRate->name;
            }
            
            array_push($data['petitions'],array(
                'id' => $petition->id,
                'client_name' => $petition->client_name.' '.$petition->client_lastname,
                'product' => $petition->product,
                'status' => $petition->status,
                'date_sent' => $petition->date_sent,
                'who_activated' => $who_activated,
                'date_activated' => $date_activated,
                'comment' => $petition->comment,
                'colorStatus' => $colorStatus,
                'colorActivated' => $colorActivated,
                'rate_activation' => $rateActivation,
                'rate_secondary' => $rateSecondary
            ));
        }

        $data['otherpetitions'] = DB::table('otherpetitions')
                             ->join('numbers','numbers.id','=','otherpetitions.number_id')
                             ->join('activations','activations.numbers_id','=','numbers.id')
                             ->join('users','users.id','=','activations.client_id')
                             ->where('otherpetitions.status','pendiente')
                             ->select('otherpetitions.*','numbers.MSISDN AS msisdn','users.name AS client_name','users.lastname AS client_lastname','users.email AS client_email')
                             ->get();

        return view('petitions.indexDealer',$data);
    }

    public function create()
    {
        $data['clients'] = DB::table('users')
                              ->leftJoin('clients','clients.user_id','=','users.id')
                              ->select('users.*','clients.rfc','clients.date_born','clients.address','clients.ine_code','clients.cellphone')
                              ->get();

        $data['rates'] = Rate::all()->where('status','activo');
        return view('petitions.create',$data);
    }

    public function completedPetitions(){
        $current_id = auth()->user()->id;
        $petitions = DB::table('petitions')
                         ->join('users','users.id','=','petitions.client_id')
                         ->leftJoin('rates','rates.id','=','petitions.rate_activation')
                         ->where('petitions.status','activado')
                         ->select('petitions.id AS id','users.name AS client_name','users.lastname AS client_lastname','petitions.date_sent AS date_sent',
                         'petitions.date_activated AS date_activated','petitions.who_attended AS who_activated','petitions.who_received AS who_received',
                         'petitions.date_received AS date_received','petitions.product AS product','petitions.status AS status','petitions.comment AS comment',
                         'petitions.collected_device AS collected_device','petitions.collected_rate AS collected_rate','rates.name AS rate_name','petitions.lada','petitions.sender')
                         ->get();

            
        $data['petitions'] = [];
        foreach ($petitions as $petition) {

            $whoActivatedData = User::where('id',$petition->who_activated)->first();
            $who_activated = $whoActivatedData->name.' '.$whoActivatedData->lastname;

            $whoSentData = User::where('id',$petition->sender)->first();
            $who_sent = $whoSentData->name.' '.$whoSentData->lastname;
            
            array_push($data['petitions'],array(
                'id' => $petition->id,
                'client_name' => $petition->client_name.' '.$petition->client_lastname,
                'product' => $petition->product,
                'status' => $petition->status,
                'date_sent' => $petition->date_sent,
                'who_activated' => $who_activated,
                'who_sent' => $who_sent,
                'date_activated' => $petition->date_activated,
                // 'who_received' => $who_received,
                // 'date_received' => $petition->date_received,
                'comment' => $petition->comment,
                'collected_rate' => $petition->collected_rate,
                'collected_device' => $petition->collected_device,
                'rate_activation' => $petition->rate_name,
                'lada' => $petition->lada
            ));
            

        }

        return view('petitions.completed',$data);
    }
    
    public function completedPetitionsDealer(){
        $current_id = auth()->user()->id;
        $petitions = DB::table('petitions')
                         ->join('users','users.id','=','petitions.client_id')
                         ->leftJoin('rates','rates.id','=','petitions.rate_activation')
                         ->where('petitions.status','recibido')
                         ->orWhere('petitions.status','activado')
                         ->where('petitions.sender',$current_id)
                         ->select('petitions.id AS id','users.name AS client_name','users.lastname AS client_lastname','petitions.date_sent AS date_sent',
                         'petitions.date_activated AS date_activated','petitions.who_attended AS who_activated','petitions.who_received AS who_received',
                         'petitions.date_received AS date_received','petitions.product AS product','petitions.status AS status','petitions.comment AS comment',
                         'petitions.collected_device AS collected_device','petitions.collected_rate AS collected_rate','rates.name AS rate_name','petitions.sender AS sender')
                         ->get();

            
        $data['petitions'] = [];
        foreach ($petitions as $petition) {
            if($petition->sender == $current_id){

                $whoActivatedData = User::where('id',$petition->who_activated)->first();
                $who_activated = $whoActivatedData->name.' '.$whoActivatedData->lastname;

                if($petition->who_received != null){
                    $whoReceivedData = User::where('id',$petition->who_received)->first();
                    $who_received = $whoReceivedData->name.' '.$whoReceivedData->lastname;
                }else{
                    $who_received = '---';
                }
                
                
                array_push($data['petitions'],array(
                    'id' => $petition->id,
                    'client_name' => $petition->client_name.' '.$petition->client_lastname,
                    'product' => $petition->product,
                    'status' => $petition->status,
                    'date_sent' => $petition->date_sent,
                    'who_activated' => $who_activated,
                    'date_activated' => $petition->date_activated,
                    'who_received' => $who_received,
                    'date_received' => $petition->date_received,
                    'comment' => $petition->comment,
                    'collected_rate' => $petition->collected_rate,
                    'collected_device' => $petition->collected_device,
                    'rate_activation' => $petition->rate_name
                ));
            }

        }

        $data['otherpetitions'] = DB::table('otherpetitions')
                             ->join('numbers','numbers.id','=','otherpetitions.number_id')
                             ->join('activations','activations.numbers_id','=','numbers.id')
                             ->join('users','users.id','=','activations.client_id')
                             ->where('otherpetitions.status','completado')
                             ->select('otherpetitions.*','numbers.MSISDN AS msisdn','users.name AS client_name','users.lastname AS client_lastname','users.email AS client_email')
                             ->get();

        return view('petitions.completedDealer',$data);
    }

    public function store(Request $request)
    {
        $name_remitente = auth()->user()->name;
        $email_remitente = auth()->user()->email;

        $client_id = $request->post('client_id');
        $name = $request->post('name');
        $lastname = $request->post('lastname');
        $email = $request->post('email');
        $product = $request->post('product');
        $sender = $request->post('user');
        $comment = $request->post('comment');
        $rate_activation = $request->post('rate_activation');
        $rate_secondary = $request->post('rate_secondary');
        $payment_way = $request->post('payment_way');
        $plazo = $request->post('plazo');
        $lada = $request->post('lada');
        $date_sent = date('Y-m-d H:i:s');

        // $request = request()->except('_token','client_id','name','lastname','email','product','user','comment','rate_activation','rate_secondary','lada','plazo');

        if($client_id == 0){
            $exists = User::where('email',$email)->exists();

            if(!$exists){
                User::insert([
                    'name' => $name,
                    'lastname' => $lastname,
                    'email' => $email,
                    'password' => $password = Hash::make('123456789'),
                    'role_id' => 3
                ]);

                $clientData = User::where('email',$email)->first();
                $client_id = $clientData->id;
                $request['user_id'] = $client_id;
                $request['who_did_id'] = auth()->user()->id;
                $request = request()->except('_token','client_id','name','lastname','email','product','user','comment','rate_activation','rate_secondary','payment_way','lada','plazo');

                Client::insert($request);
            }else{
                $clientData = User::where('email',$email)->first();
                $client_id = $clientData->id;
                $request['user_id'] = $client_id;
                $request['who_did_id'] = auth()->user()->id;
                $request = request()->except('_token','client_id','name','lastname','email','product','user','comment','rate_activation','rate_secondary','lada','plazo');
            }
        }

        Petition::insert([
            'sender' => $sender,
            'status' => 'solicitud',
            'date_sent' => $date_sent,
            'client_id' => $client_id,
            'product' => $product,
            'comment' => $comment,
            'rate_activation' => $rate_activation,
            'rate_secondary' => $rate_secondary,
            'payment_way' => $payment_way,
            'plazo' => $plazo,
            'lada' => $lada
        ]);

        $response = Http::withHeaders([
            'Conten-Type'=>'application/json'
        ])->get('http://10.44.0.70/petitions-notifications',[
            'name'=>$name,
            'lastname'=>$lastname,
            'correo'=> $email,
            'comment'=>$comment,
            'status'=>'solicitud',
            'remitente'=>$name_remitente,
            'email_remitente'=>$email_remitente,
            'product'=>$product
        ]);

        if($response['http_code'] == 200){
            return back();
        }
    }

    public function storeDealer(Request $request){
        $name_remitente = auth()->user()->name;
        $email_remitente = auth()->user()->email;

        $client_id = $request->post('client_id');
        $product = $request->post('productChoosed');
        $sender = $request->post('user');
        $comment = $request->post('comment');
        $rate_activation = $request->post('rate_activation');
        $rate_secondary = $request->post('rate_activation');
        $number_id = $request->post('number_id');
        $device_id = $request->post('device_id');
        $date_to_activate = $request->post('date_activation');
        $payment_way = 'Efectivo';
        $plazo = 0;
        $date_sent = date('Y-m-d H:i:s');
        $lat_hbb = $request->post('lat');
        $lng_hbb = $request->post('lng');
        $serial_number = $request->post('serial_number');
        $mac_address = $request->post('mac_address');

        if($product != 'HBB'){
            $lat_hbb = null;
            $lng_hbb = null;
        }

        $name = $request->post('client_name');
        $lastname = $request->post('client_lastname');
        $email = $request->post('client_email');

        Petition::insert([
            'sender' => $sender,
            'status' => 'solicitud',
            'date_sent' => $date_sent,
            'client_id' => $client_id,
            'product' => $product,
            'comment' => $comment,
            'rate_activation' => $rate_activation,
            'rate_secondary' => $rate_secondary,
            'payment_way' => $payment_way,
            'plazo' => $plazo,
            'number_id' => $number_id,
            'device_id' => $device_id,
            'date_to_activate' => $date_to_activate,
            'serial_number' => $serial_number,
            'mac_address' => $mac_address,
            'lat_hbb' => $lat_hbb,
            'lng_hbb' => $lng_hbb
        ]);
        
        $response = Http::withHeaders([
            'Conten-Type'=>'application/json'
        ])->get('http://10.44.0.70/petitions-notifications',[
            'name'=>$name,
            'lastname'=>$lastname,
            'correo'=> $email,
            'comment'=>$comment,
            'status'=>'solicitud',
            'remitente'=>$name_remitente,
            'email_remitente'=>$email_remitente,
            'product'=>$product
        ]);

        if($response['http_code'] == 200){
            return back();
        }
    }

    public function getRatesPetition(Request $request){
        $product = $request->get('producto');
        $rates = DB::table('rates')
                    ->join('offers','offers.id','=','rates.alta_offer_id')
                    ->where('offers.product','=',$product)
                    ->where('rates.status','activo')
                    ->where('offers.type','normal')
                    ->select('rates.*','offers.name AS offer_name','offers.id AS offer_id','offers.product AS offer_product', 'offers.offerID')
                    ->get();
        return $rates;
    }

    public function getInventoryPetition(Request $request){
        $user = $request->get('user');
        $producto = $request->get('producto');
        $dataUser = User::find($user);
        $company_id = $dataUser->company_id;
        
        $numbers = DB::table('inventories')
                      ->join('numbers','numbers.id','=','inventories.number_id')
                      ->where('numbers.producto','LIKE','%'.$producto.'%')
                      ->where('numbers.status','available')
                      ->select('numbers.icc_id AS icc','numbers.id AS number_id')
                      ->get();

        $devices = DB::table('inventories')
                      ->join('devices','devices.id','=','inventories.device_id')
                      ->where('devices.status','available')
                      ->select('devices.no_serie_imei AS imei','devices.id AS device_id','devices.description AS description')
                      ->get();

        $response['inventory']['numbers'] = $numbers;
        $response['inventory']['devices'] = $devices;
        return $response;
    }

    public function show(Petition $petition)
    {
        //
    }

    public function edit(Petition $petition)
    {
        //
    }

    public function update(Request $request, Petition $petition)
    {
        //
    }

    public function destroy(Petition $petition)
    {
        $id = $petition->id;
        $x = Petition::destroy($id);

        if($x){
            return 1;
        }else{
            return 0;
        }
    }

    public function dealerActivation(){
        $company_id = auth()->user()->company_id;
        $data['clients'] = DB::table('users')
                              ->join('clients','clients.user_id','=','users.id')
                              ->where('users.company_id',$company_id)
                              ->select('users.*','clients.rfc','clients.date_born','clients.address','clients.ine_code','clients.cellphone')
                              ->get();

        $data['numbers'] = DB::table('inventories')
                      ->join('numbers','numbers.id','=','inventories.number_id')
                      ->where('inventories.company_id',$company_id)
                      ->where('numbers.status','available')
                      ->select('numbers.icc_id AS icc','numbers.id AS number_id','numbers.producto AS product','numbers.MSISDN AS msisdn')
                      ->get();

        $data['devices'] = DB::table('inventories')
                      ->join('devices','devices.id','=','inventories.device_id')
                      ->where('inventories.company_id',$company_id)
                      ->where('devices.status','available')
                      ->select('devices.no_serie_imei AS imei','devices.id AS device_id','devices.description AS description')
                      ->get();
        return view('petitions.dealerActivation',$data);
    }

    public function otherPetition(){
        $data['clients'] = DB::table('numbers')
                                  ->join('activations','activations.numbers_id','=','numbers.id')
                                  ->join('users','users.id','=','activations.client_id')
                                  ->select('numbers.MSISDN AS msisdn','users.name AS name','users.lastname AS lastname','users.email AS email','numbers.id AS number_id')
                                  ->orderBy('msisdn')
                                  ->get();
        return view('petitions.otherPetition',$data);
    }

    public function sendOtherPetition(Request $request){
        $user_id = auth()->user()->id;
        $request['who_did_id'] = $user_id;
        $request['status'] = 'pendiente';

        $name = $request->post('name');
        $lastname = $request->post('lastname');
        $email = $request->post('email');
        $comment = $request->post('comment');
        $subject = $request->post('subject');
        $description = $request->post('description');
        $email_remitente = auth()->user()->email;
        $name_remitente = auth()->user()->name;

        $request = request()->except('_token','name','lastname','email','subject');
        Otherpetition::insert($request);

        $response = Http::withHeaders([
            'Conten-Type'=>'application/json'
        ])->get('http://10.44.0.70/petitions-notifications',[
            'name'=>$name,
            'lastname'=>$lastname,
            'correo'=> $email,
            'comment'=>$comment,
            'status'=>'other',
            'remitente'=>$name_remitente,
            'email_remitente'=>$email_remitente,
            'product'=>'NA',
            'subject'=>$subject,
            'description'=>$description
        ]);
        
        if($response['http_code'] == 200){
            return back();
        }
    }

    public function dealerBarring(){
        return view('petitions.dealerBarring');
    }

    public function getActivation($petition){
        $activation = Activation::where('petition_id',$petition)->first();
        $activation_id = $activation->id;
        return $activation_id;
    }

    public function createDeliveryFormat($activation){
        $deliveryData = DB::table('activations')
                    ->join('numbers','numbers.id','=','activations.numbers_id')
                    ->leftJoin('devices','devices.id','=','activations.devices_id')
                    ->join('petitions','petitions.id','=','activations.petition_id')
                    ->join('rates','rates.id','=','activations.rate_id')
                    ->where('activations.id',$activation)
                    ->select('numbers.icc_id','numbers.MSISDN','devices.no_serie_imei','devices.description AS especifications','activations.serial_number','activations.mac_address',
                    'petitions.who_attended','petitions.sender','activations.client_id','rates.name AS rate_name','petitions.payment_way','petitions.plazo')
                    ->get();

        $data['ICC'] = $deliveryData[0]->icc_id;
        $data['MSISDN'] = $deliveryData[0]->MSISDN;
        $data['rate_name'] = $deliveryData[0]->rate_name;

        $data['IMEI'] = $deliveryData[0]->no_serie_imei;
        $data['device_quantity'] = $data['IMEI'] == null ? 0 : 1;
        $data['IMEI'] = $data['IMEI'] == null ? 'No Asignado' : $deliveryData[0]->no_serie_imei;

        $data['especifications'] = $deliveryData[0]->especifications;
        $data['especifications'] = $data['especifications'] == null ? 'No Asignado' : $deliveryData[0]->especifications;

        $data['serial_number'] = $deliveryData[0]->serial_number;
        $data['serial_number'] = $data['serial_number'] == null ? 'No Asignado' : $deliveryData[0]->serial_number;

        $mac_address = $deliveryData[0]->mac_address;

        if($mac_address == null){
            $mac_address_reverse = strrev($mac_address);
            $afterPrefix = substr($mac_address_reverse,4,1).substr($mac_address_reverse,3,1).substr($mac_address_reverse,1,1).substr($mac_address_reverse,0,1);
            $data['password'] = 'No Asignado';
            $data['red'] = 'No Asignado';
        }else{
            $mac_address_reverse = strrev($mac_address);
            $afterPrefix = substr($mac_address_reverse,4,1).substr($mac_address_reverse,3,1).substr($mac_address_reverse,1,1).substr($mac_address_reverse,0,1);
            $data['password'] = 'Altcel_'.$afterPrefix;
            $data['red'] = 'Altcel'.$afterPrefix;
        }
        

        $who_attended = $deliveryData[0]->who_attended;
        $who_attendedData = User::where('id',$who_attended)->first();
        $data['who_attended_name'] = strtoupper($who_attendedData->name.' '.$who_attendedData->lastname);

        $sender = $deliveryData[0]->sender;
        $senderData = User::where('id',$sender)->first();
        $data['sender_name'] = strtoupper($senderData->name.' '.$senderData->lastname);

        $client = $deliveryData[0]->client_id;
        $clientData = User::where('id',$client)->first();
        $data['client_name'] = strtoupper($clientData->name.' '.$clientData->lastname);

        $data['fecha'] = date('Y-M-d H:i:s');

        return view('petitions.deliveryFormat',$data);
    }
}
