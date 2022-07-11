<?php

namespace App\Http\Controllers;
use Http;
use DB;
use DateTime;
use App\Activation;
use App\Client;
use App\Clientsson;
use App\Number;
use App\Device;
use App\Offer;
use App\User;
use App\Simexternal;
use App\Instalation;
use App\Pack;
use App\Pay;
use App\Ethernetpay;
use App\Radiobase;
use App\Rate;
use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendAccess;
use App\GuzzleHttp;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ActivationController extends Controller
{
   public function index(Request $request){
        $current_id = auth()->user()->id;
        $data['preactivations'] = DB::table('activations')
                             ->join('users','users.id', '=', 'activations.client_id')
                             ->join('numbers','numbers.id', '=', 'activations.numbers_id')
                             ->join('rates', 'rates.id', '=', 'activations.rate_id')
                             ->where('activations.payment_status', 'pendiente')
                             ->where('activations.who_did_id',$current_id)
                             ->select('users.name AS name','users.lastname AS lastname', 'activations.date_activation AS date_activations', 'numbers.MSISDN AS MSISDN', 'numbers.producto AS producto','rates.name AS rate_name', 'activations.id AS id')
                             ->get();
        return view('activations.index', $data);
    }
    

    public function create(){
        $current_id = auth()->user()->id;
        $current_role = auth()->user()->role_id;
        $data['numbers'] = DB::table('assignments')
                              ->join('numbers','numbers.id','=','assignments.number_id')
                              ->where('assignments.type','sim')
                              ->where('assignments.status','available')
                              ->where('assignments.promoter_id',$current_id)
                              ->select('numbers.icc_id AS ICC','numbers.MSISDN AS MSISDN','numbers.producto AS producto','numbers.id AS number_id')
                              ->get();
        
        $data['devices'] = DB::table('assignments')
                              ->join('devices','devices.id','=','assignments.device_id')
                              ->where('assignments.type','device')
                              ->where('assignments.status','available')
                              ->where('assignments.promoter_id',$current_id)
                              ->select('devices.no_serie_imei AS imei','devices.material AS material','devices.id AS device_id','devices.price AS price', 'devices.description AS description')
                              ->get();
                            
        if($current_role == 7){
            $data['clients'] = DB::table('users')
                              ->join('clients','clients.user_id','=','users.id')
                              ->where('clients.who_did_id',$current_id)
                              ->select('users.*','clients.rfc','clients.date_born','clients.address','clients.ine_code','clients.cellphone')
                              ->get();
        }else if($current_role == 4){
            $data['clients'] = DB::table('users')
                              ->join('clients','clients.user_id','=','users.id')
                              ->select('users.*','clients.rfc','clients.date_born','clients.address','clients.ine_code','clients.cellphone')
                              ->get();
        }

        return view('activations.create',$data);
    }

    public function exceptSpecials($string){
        
        $string = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $string
        );
    
        //Reemplazamos la E y e
        $string = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $string );
    
        //Reemplazamos la I y i
        $string = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $string );
    
        //Reemplazamos la O y o
        $string = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $string );
    
        //Reemplazamos la U y u
        $string = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $string );
    
        //Reemplazamos la N, n, C y c
        $string = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç'),
        array('N', 'n', 'C', 'c'),
        $string
        );
        return $string;
    }

    public function sendCredentials($email,$pass,$name,$lastname) {
        $data= [
            "subject" => "Accesos Altcel II",
            "name" => $name,
            "lastname" => $lastname,
            "user" => $email,
            "password" => $pass
        ];
        Mail::to($email)->send(new SendAccess($data));
    }

    public function saveIMG(Request $request){
        $base64 = $request->post('base64');
        // return $request;
        
        $client = $request->post('client');
        $x = explode(',',$base64);
        $length = sizeof($x);
        if($length == 1) {
            return 1;
        }else if($length == 2){
            $data = base64_decode($x[1]);
            $filepath = '../public/storage/uploads/signature'.$client.'.png';
            if(file_put_contents($filepath,$data)){
                return 1;
            }else{
                return 0;
            }
        }
        
    }
   
}
