<?php

namespace App\Http\Controllers;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\GuzzleHttp;
use App\Number;

class AltanController extends Controller
{
    public function accessTokenRequestPost(){
        // $prelaunch = 'TzBpSndNOWlkc1ZvZDdoVThrOHcyQTJuQXhQTDdORWU6bm1GaHlCWjdYbWhtaTRTUw==';
        $production = 'ZjRWc3RzQXM4V1c0WFkyQVVtbVBSTE1pRDFGZldFQ0k6YkpHakpCcnBkWGZoajczUg==';

        $response = Http::withHeaders([
            'Authorization' => 'Basic '.$production
        ])->post('https://altanredes-prod.apigee.net/v1/oauth/accesstoken?grant-type=client_credentials', [
            'Authorization' => 'Basic '.$production,
        ]);
        return $response->json();
    }

    public function productPurchase($request){
        $msisdn = $request['msisdn'];
        $offer = $request['offer'];
        // return $msisdn;
        // $url_prelaunch = "https://altanredes-prod.apigee.net/cm-sandbox/v1/products/purchase";
        $url_production = "https://altanredes-prod.apigee.net/cm/v1/products/purchase";
        // return response()->json(['gbs'=>$quantity_gb,'$offerID'=>$offerID,'$MSISDN'=>$MSISDN]);
        $accessTokenResponse = AltanController::accessTokenRequestPost();

        if($accessTokenResponse['status'] == 'approved'){
            $accessToken = $accessTokenResponse['accessToken'];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$accessToken
                ])->post($url_production,[
                    "msisdn" => $msisdn,
                    "offerings" => array(
                        $offer
                    ),
                    "startEffectiveDate" => "",
                    "expireEffectiveDate" => "",
                    "scheduleDate" => ""
                ]);

            return $response;
        }
    }

    public function consultUF($MSISDN){
        $accessTokenResponse = AltanController::accessTokenRequestPost();

        if($accessTokenResponse['status'] == 'approved'){
            $accessToken = $accessTokenResponse['accessToken'];
            
            $url_production = 'https://altanredes-prod.apigee.net/cm/v1/subscribers/'.$MSISDN.'/profile';
                    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$accessToken
            ])->get($url_production);

            return $response->json();
        }
    }

    public function currentOperator(Request $request){
        $msisdn = $request->get('msisdn');

        $accessTokenResponse = AltanController::accessTokenRequestPost();

        if($accessTokenResponse['status'] == 'approved'){
            $accessToken = $accessTokenResponse['accessToken'];
            
            $url_production = 'https://altanredes-prod.apigee.net/cm/v1/subscribers/lookupForOperator';
                    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$accessToken
            ])->get($url_production,[
                'msisdn' => $msisdn
            ]);

            return $response->json();
        }
    }

    public function importPortability($msisdnTransitory,$msisdnPorted,$imsi,$approvedDateABD,$dida,$dcr){
        $accessTokenResponse = AltanController::accessTokenRequestPost();

        if($accessTokenResponse['status'] == 'approved'){
            $accessToken = $accessTokenResponse['accessToken'];
            
            $url_production = 'https://altanredes-prod.apigee.net/ac-sandbox/v1/msisdns/port-in-c';
                    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$accessToken
            ])->post($url_production,[
                'msisdnTransitory' => $msisdnTransitory,
                'msisdnPorted' => $msisdnPorted,
                'imsi' => $imsi,
                'approvedDateABD' => $approvedDateABD,
                'dida' => $dida,
                'rida' => 319,
                'dcr' => $dcr,
                'rcr' => 175
            ]);

            return $response->json();
        }
    }

    public function consultIMEI(Request $request){
        $imei = $request->get('imei');

        $accessTokenResponse = AltanController::accessTokenRequestPost();
    
        if(isset($accessTokenResponse['status'])){
            if($accessTokenResponse['status'] == 'approved'){

                $accessToken = $accessTokenResponse['accessToken'];
                $url_production = 'https://altanredes-prod.apigee.net/ac/v1/imeis/'.$imei.'/status';
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$accessToken,
                    'Content-Type' => 'application/json'
                ])->get($url_production);

                return $response;
            }else{
                return "no aprobado";
            }
        }
    }
}
