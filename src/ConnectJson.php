<?php

namespace SwissFreeCommerce\Connect;

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\GetRequest;
use hollodotme\FastCGI\Requests\PostRequest;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;

class ConnectJson
{
    private string $script_file_name;
    private string $hostname;
    private int $port;
    private object $client;
    private object $connection;

    public function __construct(string $script_file_name, string $hostname, int $port)
    {
        $this->script_file_name = $script_file_name;
        $this->hostname = $hostname;
        $this->port = $port;

        $this->client = new Client;
        $this->connection = new NetworkSocket($hostname, $port);
    }

    public function get(string $url, array $params)
    {
        // set params
        $content = http_build_query($params);

        // set request
        $request = new GetRequest($this->script_file_name, $content);

        // set http2 protocol
        $request->setServerProtocol('HTTP/2.0');

        // set accept json
        $request->setCustomVar('HTTP_ACCEPT', 'application/json');

        // set request uri
        $request->setRequestUri($url);

        // set query string params
        $request->setCustomVar('QUERY_STRING', $content);

        $response = $this->client->sendRequest($this->connection, $request);

        return $response->getBody();
    }
}
