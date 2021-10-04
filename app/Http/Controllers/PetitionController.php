<?php

namespace App\Http\Controllers;

use App\Petition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Http;
use App\User;
use App\Client;
use App\Rate;
use App\GuzzleHttp;

class PetitionController extends Controller
{

    public function index()
    {
        $current_id = auth()->user()->id;
        $petitions = DB::table('petitions')
                         ->join('users','users.id','=','petitions.client_id')
                         ->where('status','solicitud')
                         ->orWhere('status','activado')
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
        return view('petitions.index',$data);
    }

    public function create()
    {
        $data['clients'] = DB::table('users')
                              ->join('clients','clients.user_id','=','users.id')
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
                         ->where('petitions.status','recibido')
                         ->where('petitions.sender',$current_id)
                         ->select('petitions.id AS id','users.name AS client_name','users.lastname AS client_lastname','petitions.date_sent AS date_sent',
                         'petitions.date_activated AS date_activated','petitions.who_attended AS who_activated','petitions.who_received AS who_received',
                         'petitions.date_received AS date_received','petitions.product AS product','petitions.status AS status','petitions.comment AS comment',
                         'petitions.collected_device AS collected_device','petitions.collected_rate AS collected_rate','rates.name AS rate_name')
                         ->get();

            
        $data['petitions'] = [];
        foreach ($petitions as $petition) {

            $whoActivatedData = User::where('id',$petition->who_activated)->first();
            $who_activated = $whoActivatedData->name.' '.$whoActivatedData->lastname;

            $whoReceivedData = User::where('id',$petition->who_received)->first();
            $who_received = $whoReceivedData->name.' '.$whoReceivedData->lastname;
            
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

        return view('petitions.completed',$data);
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
        $date_sent = date('Y-m-d H:i:s');

        $request = request()->except('_token','client_id','name','lastname','email','product','user','comment','rate_activation','rate_secondary');

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

                Client::insert($request);
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
            'rate_secondary' => $rate_secondary
        ]);

        $response = Http::withHeaders([
            'Conten-Type'=>'application/json'
        ])->get('http://crm.altcel/petitions-notifications',[
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
        //
    }
}
