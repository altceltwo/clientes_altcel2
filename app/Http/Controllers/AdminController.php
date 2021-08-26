<?php

namespace App\Http\Controllers;
use App\Pack;
use App\Radiobase;
use App\Rate;
use App\Politic;
use App\Packspolitic;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $data['packs'] = Pack::all();
        $data['radiobases'] = Radiobase::all();
        $data['politics'] = DB::table('packspolitics')
                               ->join('packs','packs.id','=','packspolitics.pack_id')
                               ->select('packspolitics.*','packs.name AS pack_name','packs.service_name AS service_name')
                               ->get();
        return view('ethernet.administration',$data);
    }

    public function createPack(Request $request) {
        Pack::insert([
            'name' => $request->post('name'),
            'description' => $request->post('description'),
            'service_name' => $request->post('service_name'),
            'recurrency' => $request->post('recurrency'),
            'price' => $request->post('price'),
            'price_s_iva' => $request->post('price_s_iva'),
            'price_install' => $request->post('price_install')
        ]);
    }

    public function createRadiobase(Request $request) {
        Radiobase::insert([
            'name' => $request->post('name'),
            'address' => $request->post('address'),
            'ip_address' => $request->post('ip_address'),
            'lat' => $request->post('lat'),
            'lng' => $request->post('lng')
        ]);
    }

    public function createPoliticRate() {
        $data['politics'] = DB::table('politics')
                               ->join('rates','rates.id','=','politics.rate_id')
                               ->join('offers','offers.id','=','rates.alta_offer_id')
                               ->select('politics.id AS politic_id','politics.description AS description','politics.porcent AS porcent', 'rates.name AS rate_name','offers.product AS offer_product')
                               ->get();
        $data['rates'] = DB::table('rates')
                            ->join('offers','offers.id','=','rates.alta_offer_id')
                            ->select('rates.name AS name','rates.id AS rate_id','offers.product AS product')
                            ->get();
        return view('rates.politics',$data);
    }

    public function insertPoliticRate(Request $request) {
        $politicFlag = $request->post('politicFlag');

        if($politicFlag == 0){
            Politic::insert([
                'description' => $request->post('description'),
                'porcent' => $request->post('porcentaje'),
                'rate_id' => $request->post('rate_id')
            ]);
        }else if($politicFlag == 1){
            Packspolitic::insert([
                'description' => $request->post('description'),
                'porcent' => $request->post('porcentaje'),
                'pack_id' => $request->post('pack_id')
            ]);
        }
    }

    public function destroy($politic_id){
        Politic::where('id', $politic_id)->delete();
        return back();
    }

    public function getPolitic($politic_id){
        $response = Politic::find($politic_id);;
        return $response;
    }

    public function updatePolitic(Request $request,Politic $politic){
        $id = $politic->id;
        $request = request()->except('_method','_token');
        $x = Politic::where('id',$id)->update($request);

        if($x){
            $message = 'Cambios guardados.';
            return back()->with('message',$message);
        }else{
            $message = 'Parece que ha ocurrido un error, intente de nuevo.';
            return back()->with('error',$message);
        }
    }

    public function changeStatusPacksRates(Request $request){
        $status = $request['status'];
        $id = $request['id'];
        $type = $request['type'];

        if($type == 'ethernet'){
            if($status == 'activo'){
                Pack::where('id',$id)->update(['status' => 'inactivo']);
            }else if($status == 'inactivo'){
                Pack::where('id',$id)->update(['status' => 'activo']);
            }
        }else if($type == 'altan'){
            if($status == 'activo'){
                Rate::where('id',$id)->update(['status' => 'inactivo']);
            }else if($status == 'inactivo'){
                Rate::where('id',$id)->update(['status' => 'activo']);
            }
        }
    }
    
}
