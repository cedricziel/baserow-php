<?php

namespace CedricZiel\BaserowPhp\Test;

use CedricZiel\BaserowPhp\Baserow;
use CedricZiel\BaserowPhp\Tables;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\Response\JsonMockResponse;
use function PHPUnit\Framework\assertInstanceOf;

class BaserowTest extends TestCase
{
    public function getAuthenticatedClient(array $responses): Psr18Client
    {
        $responses = array_merge(
            [
                new JsonMockResponse(['token' => 'token']),
            ],
            $responses
        );
        $client = new MockHttpClient($responses);

        return new Psr18Client($client);
    }
    #[test]
    public function can_create()
    {
        $client = new Psr18Client();
        $baserow = new Baserow($client);

        $this->assertInstanceOf(Baserow::class, $baserow);
    }

    #[test]
    public function can_authenticate()
    {
        $responses = [];

        $psr18Client = $this->getAuthenticatedClient($responses);
        $baserow = new Baserow($psr18Client);

        assertInstanceOf(Baserow::class, $baserow->authenticate('username', 'password'));
    }

    #[test]
    public function can_retrieve_tables()
    {
        $responses = [];

        $psr18Client = $this->getAuthenticatedClient($responses);
        $baserow = new Baserow($psr18Client);
        $baserow->authenticate('username', 'password');

        $tables = $baserow->tables();

        $this->assertInstanceOf(Tables::class, $tables);
    }
}
