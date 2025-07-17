<?php

namespace App\Helpers;
use Sentinel;
use App\Services\FeederDiktiApiService;

use QrCode;
use URL;

class SignatureHelper{
    static function generateDigitalSignature($prefix=''){
        return uniqid($prefix,true);
    }

    public static function gen_krs_signature($var='')
    {
        return self::generateDigitalSignature($var);
    }

    public static function getKRSDigitalSignatureQRCode($signature)
    {
        return base64_encode(QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate(URL::to('/digital-signature')."/krs"."/".$signature)
        );
    }
}