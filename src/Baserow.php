<?php

namespace CedricZiel\BaserowPhp;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;

class Baserow
{
    public function __construct(
        private ?ClientInterface $client = null,
        private ?string $token = null,
        private ?string $baseUrl = "https://baserow.io/api/",
    ){
    }

    public function authenticate(string $username, string $password): static
    {
        $psr17Factory = new Psr17Factory();
        $request = $psr17Factory
            ->createRequest('POST', sprintf('%s%s', rtrim($this->baseUrl, '/'), '/api/auth/token/'))
            ->withHeader('Content-Type', 'application/json')
            ->withBody($psr17Factory->createStream(json_encode([
                'username' => $username,
                'password' => $password,
            ])));

        $res = $this->client->sendRequest($request);
        $data = json_decode($res->getBody()->getContents(), true);

        return $this->setToken($data['token']);
    }

    public function tables(): Tables
    {
        return new Tables($this->client, $this->token);
    }

    private function setToken(mixed $token): static
    {
        $this->token = $token;

        return $this;
    }
}
