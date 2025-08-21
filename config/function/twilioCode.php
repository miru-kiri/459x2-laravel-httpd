<?php

use Twilio\Rest\Client;

function sendCode($phoneNumber)
{
    try {
        $twilioId = env('TWILIO_SID');
        $twilioToken = env('TWILIO_TOKEN');
        $twilioFrom = env('TWILIO_FROM');
    
        $twilio = new Client($twilioId, $twilioToken);
    
        $code = generateRandomCode();
    
        $res = [
            'result' => true,
            'code' => $code,
        ];
        $twilio->messages
            ->create($phoneNumber, [
                "body" => "認証コード：". " ". $code,
                "from" => $twilioFrom
            ]);
    } catch (\Exception $ex) {
		\Log::debug($ex);
        $res = [
            'result' => false,
            'code' => $code,
        ];
    }
    return $res;
}
/**
 * コード生成
 *
 * @param int $length
 * @return string
 */
function generateRandomCode($length = 6)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomCode = '';

    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomCode;
}
