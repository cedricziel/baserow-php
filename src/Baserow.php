<?php

namespace CedricZiel\BaserowPhp;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;

class Baserow
{
    public function __construct(
        private ?ClientInterface $client = null,
        private ?string $token = null,
        private ?string $jwt = null,
        private ?string $baseUrl = "https://baserow.io/api/",
    ){
    }

    /**
     * Logs in to the Baserow API for the given username and password.
     *
     * This sets the JWT token for the client.
     */
    public function login(string $username, string $password): static
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

        return $this->setJWT($data['token']);
    }

    /**
     * Returns a list of databases.
     */
    public function databases(): Databases
    {
        return new Databases($this->client, $this->token, $this->jwt, $this->baseUrl);
    }

    public function tables(): Tables
    {
        return new Tables($this->client, $this->token, $this->jwt, $this->baseUrl);
    }

    public function files(): Files
    {
        return new Files($this->client, $this->token, $this->jwt, $this->baseUrl);
    }

    private function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function setJWT(string $token): static
    {
        $this->jwt = $token;

        return $this;
    }
}
