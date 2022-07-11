<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/notifications-webhook',
        '/createJWT',
        '/createJWTTen',
        '/get-offers-rates-surplus',
        '/generateReferenceAPI',
        '/saveIMG',
        '/get-offers-rates-surplus-public',
        '/generateReferenceAPIPublic',
        '/send-card-payment-public',
        '/get-data-monthly-public',
        '/purchase-pos',
        '/purchase-pos-test',
        '/change-product-pos',
        '/change-product-pos-test',
        '/save-manual-pay-pos',
        '/webhook-altan-redes',
        '/order-create',
        '/get-data-monthly-oreda-public',
        '/multipayment-oreda',
        '/appUser'
    ];
}
