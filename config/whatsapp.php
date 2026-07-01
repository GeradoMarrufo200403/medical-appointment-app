<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default WhatsApp Provider
    |--------------------------------------------------------------------------
    |
    | This value controls which of the following providers will be used to
    | send WhatsApp notifications. Supported: "log", "ultramsg", "twilio"
    |
    | Default is "log" to allow testing without executing real requests.
    |
    */

    'provider' => env('WHATSAPP_PROVIDER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | UltraMsg Credentials
    |--------------------------------------------------------------------------
    |
    | If you use UltraMsg, configure your API URL, Instance ID, and Token.
    |
    */

    'ultramsg' => [
        'url' => env('WHATSAPP_API_URL', 'https://api.ultramsg.com'),
        'token' => env('WHATSAPP_TOKEN'),
        'instance_id' => env('WHATSAPP_INSTANCE_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Twilio Credentials
    |--------------------------------------------------------------------------
    |
    | If you use Twilio, configure your Account SID, Auth Token, and Sender number.
    |
    */

    'twilio' => [
        'sid' => env('WHATSAPP_ACCOUNT_SID'),
        'token' => env('WHATSAPP_TOKEN'),
        'from' => env('WHATSAPP_SENDER_NUMBER'), // format: "whatsapp:+14155238886"
    ],

];
