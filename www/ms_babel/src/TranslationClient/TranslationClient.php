<?php

namespace App\TranslationClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class TranslationClient
{
    protected $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    abstract public function translate(string $lang, string $text);
}
