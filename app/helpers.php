<?php

use Illuminate\Support\Facades\Crypt;

if (! function_exists('encrypt_response')) {
    function encrypt_response($data, $status = 200) {
        return response(Crypt::encryptString(json_encode($data)), $status)->header('Content-Type', 'application/json');
    }
}

if (! function_exists('decrypt_request')) {
    function decrypt_request($encryptedContent) {
        try {
            $decrypted = Crypt::decryptString($encryptedContent);
            return json_decode($decrypted, true);
        } catch (\Exception $e) {
            return null;
        }
    }
}