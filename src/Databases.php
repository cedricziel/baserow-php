<?php

namespace CedricZiel\BaserowPhp;

use Psr\Http\Client\ClientInterface;

class Databases
{
    public function __construct(
        private ?ClientInterface $client = null,
        private ?string $token = null,
        private ?string $jwt = null,
        private ?string $baseUrl = "https://baserow.io/api/",
    ){
    }

    public function list()
    {
        if (is_null($this->jwt)) {
            throw new \LogicException('JWT must be provided');
        }

        $request = $this->client
            ->createRequest('GET', sprintf('%s%s', rtrim($this->baseUrl, '/'), '/api/database/'))
            ->withHeader('Authorization', sprintf('JWT %s', $this->jwt));

        $res = $this->client->sendRequest($request);

        return json_decode($res->getBody()->getContents(), true);
    }
}
