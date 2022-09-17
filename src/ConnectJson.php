<?php

namespace SwissFreeCommerce\Connect;

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\GetRequest;
use hollodotme\FastCGI\Requests\PostRequest;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;

class ConnectJson
{
    private $script_file_name;
    private $hostname;
    private $port;

    public function __construct(string $script_file_name, string $hostname, int $port)
    {
        $this->script_file_name = $script_file_name;
        $this->hostname = $hostname;
        $this->port = $port;
    }

    public function get(string $url, array $params)
    {
        // set params
        $content = http_build_query($params);

        // set connection
        $client = new Client();
        $connection = new NetworkSocket($this->hostname, $this->port);
        $request = new GetRequest($this->script_file_name, $content);

        // set http2 protocol
        $request->setServerProtocol('HTTP/2.0');

        // set accept json
        $request->setCustomVar('HTTP_ACCEPT', 'application/json');

        // set request uri
        $request->setRequestUri($url);

        // set query string params
        $request->setCustomVar('QUERY_STRING', $content);

        $response = $client->sendRequest($connection, $request);

        return $response->getBody();
    }
}
