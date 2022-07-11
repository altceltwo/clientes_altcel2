<?php

namespace App\Http\Controllers;

use DB;
use App\Portability;
use App\Rate;
use App\Client;
use App\User;
use Illuminate\Http\Request;
use App\Mail\NotifyPortability;
use Illuminate\Support\Facades\Mail;

class PortabilityController extends Controller
{
    public function index()
    {
        $pendings = Portability::all()->where('status','pendiente');
        $activateds = Portability::all()->where('status','activado');
        $completeds = Portability::all()->where('status','completado');

        $arrayPending = [];
        $arrayActivated = [];
        $arrayCompleted = [];

        foreach ($pendings as $pending) {
            $who_did_it = User::where('id',$pending->who_did_it)->first();
            $who_attended = User::where('id',$pending->who_attended)->first();
            $client = User::where('id','=',$pending->client_id)->first();
            $rate = Rate::where('id','=',$pending->rate_id)->first();
            array_push($arrayPending,array(
                'id' => $pending->id,
                'msisdnPorted' => $pending->msisdnPorted,
                'icc' => $pending->icc,
                'msisdnTransitory' => $pending->msisdnTransitory,
                'date' => $pending->date,
                'approvedDateABD' => $pending->approvedDateABD,
                'nip' => $pending->nip,
                'client' => $client->name.' '.$client->lastname,
                'who_did_it' => $who_did_it->name.' '.$who_did_it->lastname,
                'who_attended' => $who_attended = $who_attended == null ? 'N/A' : $who_attended->name.' '.$who_attended->lastname,
                'rate' => $rate->name.' - $'.number_format($rate->price,2),
                'comments' => $pending->comments
            ));
        }

        foreach ($activateds as $activated) {
            $who_did_it = User::where('id',$activated->who_did_it)->first();
            $who_attended = User::where('id',$activated->who_attended)->first();
            $client = User::where('id','=',$activated->client_id)->first();
            $rate = Rate::where('id','=',$activated->rate_id)->first();
            array_push($arrayActivated,array(
                'id' => $activated->id,
                'msisdnPorted' => $activated->msisdnPorted,
                'icc' => $activated->icc,
                'msisdnTransitory' => $activated->msisdnTransitory,
                'date' => $activated->date,
                'approvedDateABD' => $activated->approvedDateABD,
                'nip' => $activated->nip,
                'client' => $client->name.' '.$client->lastname,
                'who_did_it' => $who_did_it->name.' '.$who_did_it->lastname,
                'who_attended' => $who_attended = $who_attended == null ? 'N/A' : $who_attended->name.' '.$who_attended->lastname,
                'rate' => $rate->name.' - $'.number_format($rate->price,2),
                'comments' => $activated->comments
            ));
        }

        foreach ($completeds as $completed) {
            $who_did_it = User::where('id',$completed->who_did_it)->first();
            $who_attended = User::where('id',$completed->who_attended)->first();
            $client = User::where('id',$completed->client_id)->first();
            $rate = Rate::where('id','=',$completed->rate_id)->first();
            array_push($arrayCompleted,array(
                'msisdnPorted' => $completed->msisdnPorted,
                'icc' => $completed->icc,
                'msisdnTransitory' => $completed->msisdnTransitory,
                'date' => $completed->date,
                'approvedDateABD' => $completed->approvedDateABD,
                'nip' => $completed->nip,
                'client' => $client->name.' '.$client->lastname,
                'who_did_it' => $who_did_it->name.' '.$who_did_it->lastname,
                'who_attended' => $who_attended = $who_attended == null ? 'N/A' : $who_attended->name.' '.$who_attended->lastname,
                'rate' => $rate->name.' - $'.number_format($rate->price,2),
                'comments' => $completed->comments
            ));
        }

        $data['pendings'] = $arrayPending;
        $data['activateds'] = $arrayActivated;
        $data['completeds'] = $arrayCompleted;
        return view('portabilities.index',$data);
    }

    public function create()
    {
        $data['clients'] = DB::table('users')
                              ->leftJoin('clients','clients.user_id','=','users.id')
                              ->select('users.*','clients.rfc','clients.date_born','clients.address','clients.ine_code','clients.cellphone')
                              ->orderBy('users.name','asc')
                              ->get();
                              
        $data['rates'] = DB::table('rates')
                            ->leftJoin('offers','rates.alta_offer_id','=','offers.id')
                            ->where('offers.product','LIKE','%MOV%')
                            ->where('rates.status','activo')
                            ->where('rates.type','publico')
                            ->select('rates.*')
                            ->get();
        return view('portabilities.create',$data);
    }

    public function store(Request $request)
    {
        $request['rida'] = 319;
        $request['rcr'] = 175;
        $rate_id = $request['rate_id'];
        $client_id = $request['client_id'];
        // GET DATA RATE
        $rate = Rate::where('id',$rate_id)->first();
        $rate_name = $rate->name.' - $'.number_format($rate->price,2);
        // GET DATA CLIENT
        $client = User::where('id',$client_id)->first();
        $clientData = $client->name.' '.$client->lastname;
        $clientAddress = Client::where('user_id',$client_id)->first();
        $address = $clientAddress->address;

        $data = [
            'numberPorted' => $request['msisdnPorted'],
            'dida' => $request['dida'],
            'dcr' => $request['dcr'],
            'icc' => $request['icc'],
            'numberTransitory' => $request['msisdnTransitory'],
            'imsi' => $request['imsi'],
            'dateActivate' => $request['date'],
            'datePort' => $request['approvedDateABD'],
            'nip' => $request['nip'],
            'rate' => $rate_name,
            'comments' => $request['comments'],
            'clientData' => $clientData,
            'address' => $address,
            'dateSend' => date('Y-m-d H:i:s')
        ];
        $request = request()->except('_token');
        // $portability = app('App\Http\Controllers\AltanController')->importPortability($msisdnTransitory,$msisdnPorted,$imsi,$approvedDateABD,$dida,$dcr);

        Portability::insert($request);

        Mail::to('joel_maza@altcel.com')->send(new NotifyPortability($data));
        Mail::to('laura_coutino@altcel.com')->send(new NotifyPortability($data));
        Mail::to('carlos_vazquez@altcel.com')->send(new NotifyPortability($data));
        // Mail::to('charlesrootsman97@gmail.com')->send(new NotifyPortability($data));
        Mail::to('carlos_ruiz@altcel.com')->send(new NotifyPortability($data));
        return back()->with('success','Se ha enviado la portabilidad.');
    }

    public function show(Portability $portability)
    {
        //
    }

    public function edit(Portability $portability)
    {
        //
    }

    public function update(Request $request, Portability $portability)
    {
        //
    }

    public function destroy(Portability $portability)
    {
        //
    }
}
