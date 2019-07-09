<?php
return [
    // Yahoo
    'YAHOO_CLIENT_ID' => env('YAHOO_CLIENT_ID'),
    'YAHOO_CLIENT_SECRET' => env('YAHOO_CLIENT_SECRET'),
    'YAHOO_URI_CALLBACK' => env('YAHOO_URI_CALLBACK'),
    'YAHOO_API_GET_TOKEN' => 'https://api.login.yahoo.com/oauth2/get_token',
    'YAHOO_API_REQUEST_AUTH' => 'https://api.login.yahoo.com/oauth2/request_auth?response_type=code&client_id='.env('YAHOO_CLIENT_ID').'&redirect_uri='.env('YAHOO_URI_CALLBACK'),

    // Zalo
    'ZALO_CLIENT_ID' => env('ZALO_CLIENT_ID'),
    'ZALO_CLIENT_SECRET' => env('ZALO_CLIENT_SECRET'),
    'ZALO_URI_CALLBACK' => env('ZALO_URI_CALLBACK'),
    'ZALO_API_REQUEST_AUTH' => 'https://oauth.zaloapp.com/v3/auth?app_id='.env('ZALO_CLIENT_ID').'&redirect_uri='.env('ZALO_URI_CALLBACK').'&state=',
];