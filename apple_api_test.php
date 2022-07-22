<?php

include('./vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// https://developer.apple.com/documentation/appstoreconnectapi/generating_tokens_for_api_requests
// https://github.com/firebase/php-jwt

// key, key_id and apple_issuer_id from https://appstoreconnect.apple.com/access/api

$apple_key_id = ''; 

// don't hard code this in production, obviously..

$apple_key = <<<EOD
-----BEGIN PRIVATE KEY-----

-----END PRIVATE KEY-----
EOD;

$apple_issuer_id = '';

// create jwt payload array

$payload_array = [
    'iss' => $apple_issuer_id,
    'aud' => 'appstoreconnect-v1',
    'iat' => time(),
    'exp' => (time()+(60*5))
];

// create jwt payload header
// note - no "alg" in the below, per https://developer.apple.com/forums/thread/117754

$payload_header = [
  "kid" => "'.$apple_key_id.'",
  "typ" => "JWT"
];

// pass the payload header as an option, per https://github.com/firebase/php-jwt/pull/53/files
$apple_jwt = JWT::encode($payload_array, $apple_key, 'ES256', $apple_key_id,$payload_header);

// test it
exec("curl -v -H 'Authorization: Bearer {$apple_jwt}' \"https://api.appstoreconnect.apple.com/v1/apps\"");

?>
