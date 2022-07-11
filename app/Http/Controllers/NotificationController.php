<?php

namespace App\Http\Controllers;
use App\Notification;
use App\Allnotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getData(Request $request){
        
        Allnotification::insert(['payload'=>$request]);

        $eventType = $request['eventType'];
        $event = $request['event'];
        $id = '';
        $effectiveDate = '';
        $detail = '';

        $id = $event['id'];
        $effectiveDate = $event['effectiveDate'];
        $detail = $event['detail'];
        $details = '';
        
        foreach ($detail as $key => $value) {
            if($key == 'SuspendDate' || $key == 'expiryDate' || $key == 'DetectionDate' || $key == 'notificationDate'){
                $value = date("Y-m-d H:i:s", strtotime($value)); 
            }
            $details.='<strong>'.$key.':</strong> '.$value.'<br>';
        }
        $status = $eventType == 'EVENT_UNITS' || $eventType == 'CAMBIOIMEI' ? 'completado' : 'pendiente';
        $seen = $eventType == 'EVENT_UNITS' || $eventType == 'CAMBIOIMEI' ? true : false;

        $data = [
            'identifier' => $id,
            'effectiveDate' => $effectiveDate,
            'eventType' => $eventType,
            'detail' => ''.$details,
            'date_notification' => $now = date('Y-m-d H:i:s'),
            'status' => $status,
            'seen' => $seen
        ];

        Notification::insert($data);
        return response()->json(['status' => 'OK'], 200);
               
    }
}
