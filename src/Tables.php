<?php

namespace CedricZiel\BaserowPhp;

use Psr\Http\Client\ClientInterface;

class Tables
{
    public function __construct(private ?ClientInterface $client = null, private ?string $token = null)
    {
        if (is_null($this->client)) {
            throw new \InvalidArgumentException('Client must be provided');
        }

        if (is_null($this->token)) {
            throw new \InvalidArgumentException('Token must be provided');
        }
    }
}
