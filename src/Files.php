<?php

namespace CedricZiel\BaserowPhp;

use Psr\Http\Client\ClientInterface;

class Files
{
    public function __construct(
        private ?ClientInterface $client = null,
        private ?string $token = null,
        private ?string $jwt = null,
        private ?string $baseUrl = "https://baserow.io/api/",
    ){
    }
}
