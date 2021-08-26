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
}
