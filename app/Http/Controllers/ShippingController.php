<?php

namespace App\Http\Controllers;

use DB;
use Http;
use App\Shipping;
use App\User;
// use App\GuzzleHttp;
use App\Conekta\ConektaPayment;
use Illuminate\Http\Request;
use App\Mail\NotifyShipping;
use Illuminate\Support\Facades\Mail;

class ShippingController extends Controller
{
    protected $ConektaPayment;

    function __construct(){
        $this->ConektaPayment = new ConektaPayment();
    }

    public function index()
    {
        $pendings = Shipping::all()->where('status','pendiente');
        $data['pendings'] = [];

        foreach ($pendings as $pending) {
            $createdData = User::where('id',$pending->created_by)->first();
            $soldData = User::where('id',$pending->sold_by)->first();
            $toData = User::where('id',$pending->to_id)->first();

            array_push($data['pendings'],array(
                'id' => $pending->id,
                'cp' => $pending->cp,
                'estado' => $pending->estado,
                'municipio' => $pending->municipio,
                'canal' => $pending->canal,
                'to' => $toData->name.' '.$toData->lastname,
                'created_by' => $createdData->name.' '.$createdData->lastname,
                'sold_by' => $soldData->name.' '.$soldData->lastname,
                'created_at' => $pending->created_at,
                'phone_contact' => $pending->phone_contact
            ));
        }

        return view('shippings.index',$data);
    }

    public function create(Request $request)
    {
        $data['flagMessage'] = 0;
        if(isset($request['phone'])){
            $data['message'] = 'HEY! Tu envío debe ser para '.$request['name'].' con teléfono '.$request['phone'];
            $data['flagMessage'] = 1;
        }
        $data['clients'] = DB::table('users')
                              ->leftJoin('clients','clients.user_id','=','users.id')
                              ->select('users.*','clients.rfc','clients.date_born','clients.address','clients.ine_code','clients.cellphone')
                              ->orderBy('users.name','asc')
                              ->get();

        $data['employes'] = User::all()->where('role_id','!=',3);
        return view('shippings.create',$data);
    }

    public function store(Request $request)
    {
        $no_interior = $request->no_interior;
        $referencias = $request->referencias;
        $recibe = $request->recibe;
        $phone_alternative = $request->phone_alternative;
        $comments = $request->comments;

        $to_id = $request->to_id;
        $sold_by = $request->sold_by;
        $created_by = $request->created_by;
        $phone = $request->phone_contact;
        $zona = $request->zona;
        $device_price = $request->device_price;
        $shipping_price = 0;

        if($zona == 'Local'){
            $shipping_price = 50;
        }else if($zona == 'Fuera'){
            $shipping_price = 150;
        }

        $status = $request->flag_link == 1 ? 'por pagar' : 'pendiente';
        $rate_id = $request->flag_link == 1 ? $request->rate_id : null;

        $clientData = User::where('id',$to_id)->first();
        $anothercompany_id = $clientData->anothercompany_id;
        $nameLink = $clientData->name;
        $lastnameLink = $clientData->lastname;
        $emailLink = $clientData->email;
        $clientData = $clientData->name.' '.$clientData->lastname;
        $userData = User::where('id',$sold_by)->first();
        $userData = $userData->name.' '.$userData->lastname;
        $createdData = User::where('id',$created_by)->first();
        $createdEmail = $createdData->email;
        

        if($no_interior == null || $no_interior == ''){
            $request['no_interior'] = 'N/A';
        }

        if($referencias == null || $referencias == ''){
            $request['referencias'] = 'N/A';
        }

        if($recibe == null || $recibe == ''){
            $request['recibe'] = 'N/A';
        }

        if($phone_alternative == null || $phone_alternative == ''){
            $request['phone_alternative'] = 'N/A';
        }

        if($comments == null || $comments == ''){
            $request['comments'] = 'N/A';
        }
        // $request = request()->except('_token');
        // Shipping::insert($request);
        $shipping = new Shipping;
        // $shipping = $request;
        $shipping->cp = $request->cp;
        $shipping->colonia = $request->colonia;
        $shipping->tipo_asentamiento = $request->tipo_asentamiento;
        $shipping->municipio = $request->municipio;
        $shipping->estado = $request->estado;
        $shipping->ciudad = $request->ciudad;
        $shipping->no_exterior = $request->no_exterior;
        $shipping->phone_contact = $request->phone_contact;
        $shipping->canal = $request->canal;
        $shipping->status = $status;
        $shipping->created_by = $request->created_by;
        $shipping->sold_by = $request->sold_by;
        $shipping->to_id = $request->to_id;
        $shipping->zona = $request->zona;
        $shipping->rate_id = $rate_id;
        $shipping->rate_price = $request->rate_price;
        $shipping->device_price = $request->device_price;
        $shipping->shipping_price = $shipping_price;
        $shipping->no_interior = $request['no_interior'];
        $shipping->referencias = $request['referencias'];
        $shipping->recibe = $request['recibe'];
        $shipping->phone_alternative = $request['phone_alternative'];
        $shipping->comments = $request['comments'];
        $shipping->save();

        $id = $shipping->id;
        if($anothercompany_id != null){
            DB::table('anothercompanies')->where('id',$anothercompany_id)->update(['status'=>'completado']);
        }

        if($request->flag_link == 1){
            $data = [
                "pay_id" => $id,
                "client_id" => $request->to_id,
                "type" => 7,
                "offer_id" => 0,
                "number_id" => 0,
                "rate_id" => 0,
                "user_id" => $request->created_by,
                "pack_id" => 0,
                "channel_system" => 'crm_clientes',
                "method" => null,
                "concepto" => 'Contratación de Servicio Conecta Altcel',
                "amount" => $shipping_price+$request->rate_price+$device_price,
                "name" => $nameLink,
                "lastname" => $lastnameLink,
                "email" => $emailLink,
                "cel_destiny_reference" => $request->phone_contact
            ];
            $x = $this->ConektaPayment->createPaymentLinkAllMethods($data);
            $link = $x->url;
            return back()->with('success','Envío guardado con éxito, lo único que falta es completar el pago, envía al cliente el siguiente link <a href="'.$link.'" target="_blank">'.$link.'</a>.');
        }else{
            $data = [
                "clientData" => $clientData,
                "userData" => $userData,
                "phone" => $phone,
                "shippingID" => $id,
            ];
            Mail::to('keila_vazquez@altcel.com')->send(new NotifyShipping($data));
            Mail::to('jalexis_santana@altcel.com')->send(new NotifyShipping($data));
            Mail::to('charlesrootsman97@gmail.com')->send(new NotifyShipping($data));
            Mail::to($createdEmail)->send(new NotifyShipping($data));
            return back()->with('success','Envío guardado con éxito.');
        }
        
        
    }

    public function show(Shipping $shipping)
    {
        //
    }

    public function edit(Shipping $shipping)
    {
        //
    }

    public function update(Request $request, Shipping $shipping)
    {
        //
    }

    public function destroy(Shipping $shipping)
    {
        if($shipping->status == 'pendiente'){
            Shipping::destroy($shipping->id);
            return 1;
        }else{
            return 2;
        }
        
    }

    public function consultCP(Request $request){
        $cp = $request->cp;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('https://api.copomex.com/query/info_cp/'.$cp.'?token=0fdba07b-c507-4a6c-9de6-fc533f413577');

        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json'
        // ])->get('https://api.copomex.com/query/info_cp/'.$cp.'?token=pruebas');

        return $response;
    }
}
