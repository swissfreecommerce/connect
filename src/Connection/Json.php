<?php

namespace SwissFreeCommerce\Connect\Connection;

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\GetRequest;
use hollodotme\FastCGI\Requests\PostRequest;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;
use hollodotme\FastCGI\RequestContents\UrlEncodedFormData;
use SwissFreeCommerce\Connect\Response;
use hollodotme\FastCGI\Interfaces\ProvidesResponseData;

class Json
{
    private string $script_file_name;
    private string $hostname;
    private int $port;
    private object $client;
    private object $connection;

    /**
     * Construct Json
     *
     * @param string $script_file_name
     * @param string $hostname
     * @param int $port
     *
     * @return void
     */
    public function __construct(string $script_file_name, string $hostname, int $port)
    {
        $this->script_file_name = $script_file_name;
        $this->hostname = $hostname;
        $this->port = $port;

        $this->client = new Client;
        $this->connection = new NetworkSocket($hostname, $port);
    }

    /**
     * Get Method
     *
     * @param string $url
     * @param array $query_params
     * @param array $variables
     *
     * @return Response
     */
    public function get(string $url, array $query_params = [], array $variables = []): Response
    {
        // set params
        $content = http_build_query($query_params);

        // set request
        $request = new GetRequest($this->script_file_name, $content);

        // set http2 protocol
        $request->setServerProtocol('HTTP/2.0');

        // set accept json
        $request->setCustomVar('HTTP_ACCEPT', 'application/json');

        foreach ($variables as $key => $value) {
            $request->setCustomVar($key, $value);
        }

        // set request uri
        $request->setRequestUri($url);

        // set query string params
        $request->setCustomVar('QUERY_STRING', $content);

        $response = $this->client->sendRequest($this->connection, $request);

        return $this->response($response);
    }

    /**
     * Post Method
     *
     * @param string $url
     * @param array $params
     * @param array $query_params
     * @param array $variables
     *
     * @return Response
     */
    public function post(string $url, array $params = [], array $query_params = [], array $variables = [])
    {
        // set params
        $url_encode_form_data = new UrlEncodedFormData($params);

        $request = PostRequest::newWithRequestContent($this->script_file_name, $url_encode_form_data);

        // set http2 protocol
        $request->setServerProtocol('HTTP/2.0');

        // set accept json
        $request->setCustomVar('HTTP_ACCEPT', 'application/json');

        foreach ($variables as $key => $value) {
            $request->setCustomVar($key, $value);
        }

        // set request uri
        $request->setRequestUri($url);

        // set query string params
        if (!empty($query_params)) {
            $query_params = http_build_query($query_params);

            $request->setCustomVar('QUERY_STRING', $query_params);
        }

        $response = $this->client->sendRequest($this->connection, $request);

        return $this->response($response);
    }

    /**
     * Responce information
     *
     * @param ProvidesResponseData $response
     *
     * @return Response
     */
    public function response(ProvidesResponseData $response): Response
    {
        $status = 200;

        $get_headers = $response->getHeaders();
        if (isset($get_headers['Status'][0])) {
            $status_parts = explode(' ', $get_headers['Status'][0]);
            $status = (int)$status_parts[0];
        }

        return (new Response($response->getBody(), $status));
    }
}
