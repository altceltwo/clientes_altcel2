<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyShipping extends Mailable
{
    use Queueable, SerializesModels;
    public $info;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->info = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $clientData = $this->info['clientData'];
        $userData = $this->info['userData'];
        $phone = $this->info['phone'];
        $id = $this->info['shippingID'];

        return $this->subject('SOLICITUD DE ENVÍO ALTCEL2 - CONECTA')
                    ->view('mails.notificationShipping',$this->info)
                    ->with([
                            'subject' => 'SOLICITUD DE ENVÍO',
                            'clientData' =>$clientData,
                            'userData' =>$userData,
                            'phone' =>$phone,
                            'id' => $id
                        ]);
    }
}
