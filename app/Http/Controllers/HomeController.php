<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $current_id = auth()->user()->id;
        $current_role = auth()->user()->role_id;
        $data['references'] = DB::table('references')
                                ->join('referencestypes','referencestypes.id','=','references.referencestype_id')
                                ->join('channels','channels.id','=','references.channel_id')
                                ->join('rates','rates.id','=','references.rate_id')
                                ->join('numbers','numbers.id','=','references.number_id')
                                ->where('referencestypes.name','Recarga')
                                ->where('references.user_id',$current_id)
                                ->select('references.*','referencestypes.name AS referencestype_name','channels.name AS channel_name','rates.name AS rate_name','numbers.MSISDN AS number_dn')
                                ->get();

        $data['mypays'] = DB::table('pays')
                             ->join('activations','activations.id','=','pays.activation_id')
                             ->join('numbers','numbers.id','=','activations.numbers_id')
                             ->join('rates','rates.id','=','activations.rate_id')
                             ->where('activations.client_id',$current_id)
                             ->where('pays.status','pendiente')
                             ->select('pays.*','numbers.MSISDN AS DN','numbers.producto AS number_product','rates.name AS rate_name','rates.price AS rate_price')
                             ->get();
        $data['my2pays'] = DB::table('ethernetpays')
                              ->join('instalations','instalations.id','=','ethernetpays.instalation_id')
                              ->join('packs','packs.id','=','instalations.pack_id')
                              ->where('instalations.client_id',$current_id)
                              ->where('ethernetpays.status','pendiente')
                              ->select('ethernetpays.*','packs.name AS pack_name','packs.price AS pack_price','packs.service_name AS service_name')
                              ->get();

        $data['activations'] = DB::table('activations')
                                  ->join('numbers','numbers.id','=','activations.numbers_id')
                                  ->join('rates','rates.id','=','activations.rate_id')
                                  ->where('activations.client_id',$current_id)
                                  ->select('activations.*','numbers.MSISDN AS DN','numbers.producto AS service','rates.name AS pack_name')
                                  ->get();
        
        $data['instalations'] = DB::table('instalations')
                                   ->join('packs','packs.id','=','instalations.pack_id')
                                   ->where('instalations.client_id',$current_id)
                                   ->select('instalations.*','packs.service_name AS service','packs.name AS pack_name')
                                   ->get();
        
        // $data['TELMEXinstalations'] = DB::table('instalations')
        //                            ->join('packs','packs.id','=','instalations.pack_id')
        //                            ->where('instalations.client_id',$current_id)
        //                            ->select('instalations.*','packs.service_name AS service','packs.name AS pack_name')
        //                            ->get();

        if($current_role == 3){
            $data['products'] = sizeof($data['instalations']) + sizeof($data['activations']);
            $data['pendingPays'] = sizeof($data['mypays']) + sizeof($data['my2pays']);
            $data['consumer'] = sizeof($data['activations']);
        }

        if($current_role == 7){

            $data['myClients'] = DB::table('users')
                                    ->join('clients','clients.user_id','=','users.id')
                                    ->where('clients.who_did_id',$current_id)
                                    ->select('users.*','clients.*')
                                    ->get();
        }
        // return sizeof($data['instalations']);
        return view('home',$data);
    }
}
