<?php

namespace App\Helpers;

use Kavenegar\KavenegarApi;

class SMSSender
{
    public function sendVerificationCode($receptor, $code, $template = "auth")
    {
        try{
            $verifyLookup = new KavenegarApi(config('kavenegar.apikey'));
            if ($template == "loanres") {
                $result = $verifyLookup->VerifyLookup($receptor, ".", null, null, $template, $type = null, $code);
            } else {
                $result = $verifyLookup->VerifyLookup($receptor, $code, null, null, $template, $type = null);
            }
            if($result){
                return collect([
                    'status' => true,
                    'result' => $result[0]
                ]);
            }
        }
        catch(\Kavenegar\Exceptions\ApiException | \Kavenegar\Exceptions\HttpException $e){
            return collect([
                'status' => false,
                'result' => $e->errorMessage()
            ]);
        }
    }
}
