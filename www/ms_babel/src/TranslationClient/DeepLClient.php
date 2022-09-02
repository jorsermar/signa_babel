<?php

namespace App\TranslationClient;

class DeepLClient extends TranslationClient
{
	const ENDPOINT = "https://api-free.deepl.com/v2/translate";
	const KEY = "842419a2-a0e9-f1ae-1870-5892754d2784:fx";
	
	public function translate(string $lang, string $text) 
	{
		$response = $this->client->request(
            'POST',
            self::ENDPOINT,
            [
            	'headers' => [
        			'Authorization' => 'DeepL-Auth-Key '.self::KEY,
    			],
    			'body' => [
    				'text' => $text,
    				'target_lang' => $lang,
    			],
    		]
        );
        if ($response->getStatusCode() == 200) {
        	//sucess
        	$content = $response->toArray();
            return [
        		"lang_from" => $content['translations'][0]['detected_source_language'],
        		"text" => $content['translations'][0]['text'],
        	];
        } else {
        	//failure
        	error_log("DeepLClient: ".$response->getStatusCode()." ".$lang." ".$text);
        	return false;
        }
	}
}