<?php
namespace App\TranslationClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TranslationClientFacade
{
    public static function translate(string $lang, string $text,  HttpClientInterface $client): array|bool
    {
        foreach( get_declared_classes() as $class ){
            if( is_subclass_of($class, TranslationClient::class)) {
                $translationClient = new $class($client);
                $translation = $translationClient->translate($lang, $text);
                if ($translation) return $translation;
            }   
        }
        return false;
    }
}
