<?php

namespace App\TranslationClient;

class LectoAIClient extends TranslationClient
{
	const ENDPOINT = "https://api.lecto.ai/v1/translate/text";
	const KEY = "21MJCX3-J8Z47CY-HWTPF9K-BGM1QZQ";
	
	public function translate(string $lang, string $text) 
	{
		$response = $this->client->request(
            'POST',
            self::ENDPOINT,
            [
            	'headers' => [
        			'X-API-Key' => self::KEY,
        			'Content-Type' => 'application/json',
        			'Accept' => 'application/json',
    			],
    			'json' => [
    				'texts' => [$text],
    				'to' => [$lang],
    				//'from' => 'es'
    			],
    		]
        );

        if ($response->getStatusCode() == 200) {
        	//sucess
        	$content = $response->toArray();
            return [
        		"lang_from" => $content['from'],
        		"from" => $content['translations'][0]['translated'],
        	];
        } else {
        	//failure
        	error_log("LectoAIClient: ".$response->getStatusCode().$lang." ".$text);
        	return false;
        }
	}
}