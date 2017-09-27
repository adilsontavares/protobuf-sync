<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Config/Servers.php';

class Server
{
    public $collection;
    public $database;
    public $client;
    public $config;

    function __construct($name, $actions)
    {
        $this->name = $name;
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $this->client->book_store;
        $this->actions = $actions;
        $this->config = (object)ServerConfig::${$name};
    }

    function run() 
    {
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('Could not create socket.');
        $conn = socket_bind($sock, $this->config->host, $this->config->port) or die('Could not bind socket.');
        socket_listen($sock) or die('Could not start listening to clients.');

        printf("Server listening to client at port %d.\n", $this->config->port);

        while (true)
        {
            $client = socket_accept($sock);
            $this->receiveRequest($client, $id, $request);

            $result = $this->process($id, $request);
            $this->sendResponse($client, $result);
        }
    }

    function receiveRequest($client, &$id, &$payload)
    {
        socket_recv($client, $request, 8, MSG_WAITALL);
        $header = unpack("Lid/Llength", $request);

        $id = $header['id'];
        $length = $header['length'];
        
        printf("Receiving message for id $id with $length bytes.\n");

        socket_recv($client, $payload, $length, MSG_WAITALL);
    }

    function sendResponse($sock, $data)
    {
        $payload = $data->serializeToString();

        $data = pack("LA*", strlen($payload), $payload);
        socket_send($sock, $data, strlen($payload) + 4, 0);
    }

    function receiveResponse($client)
    {
        socket_recv($client, $request, 4, MSG_WAITALL);

        $header = unpack("Llength", $request);
        $length = $header['length'];

        socket_recv($client, $payload, $length, MSG_WAITALL);

        return $payload;
    }

    function sendRequest($sock, $id, $data)
    {
        $payload = $data->serializeToString();

        $data = pack("LLA*", $id, strlen($payload), $payload);
        socket_send($sock, $data, strlen($payload) + 8, 0);
    }

    function sendRequestTo($server, $id, $data)
    {
        $config = (object)ServerConfig::${$server};
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $conn = socket_connect($sock, $config->host, $config->port);

        $this->sendRequest($sock, $id, $data);

        return $sock;
    }

    function process($id, $data)
    {
        return $this->{$this->actions[$id]}($data);
    }

    function request($target, $messageId, $data)
    {
        $config = (object)ServerConfig::${$target};
        $serverClass = $config->className;
        $id = $serverClass::${$messageId};

        $sock = $this->sendRequestTo($target, $id, $data);
        return $this->receiveResponse($sock);
    }
}
?>