<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Config/Servers.php';


/**
* Server's generic class.
*/
class Server
{
    /**
    * MongoDB collection's ID.
    */
    public $collection;
    /**
    * MongoDB database's ID.
    */
    public $database;
    /**
    * MongoDB's address ID.
    */
    public $client;
    /**
    * Server's configuration set.
    */
    public $config;

    /**
    * Server's constructor. 
    * @param string $name, Server's ID
    * @param array $actions, Server's Action. Example: Find a book, Search by ID, Buy a book, etc..
    */
    function __construct($name, $actions)
    {
        $this->name = $name;
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $this->client->book_store;
        $this->actions = $actions;
        $this->config = (object)ServerConfig::${$name};
    }
    /**
    * 
    * Put the server on running. Listing to a socket.
    */
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
    /**
    * 
    * Receive request from a client through a socket.
    * @param string $client, client to connect through socket.
    * @param object $id, specifying which action the Server will do.
    * @param object $payload, message. 
    */
    function receiveRequest($client, &$id, &$payload)
    {
        socket_recv($client, $request, 8, MSG_WAITALL);
        $header = unpack("Lid/Llength", $request);

        $id = $header['id'];
        $length = $header['length'];
        
        printf("Receiving message for id $id with $length bytes.\n");

        socket_recv($client, $payload, $length, MSG_WAITALL);
    }
    /**
    * 
    * Send a message to a client/server through a socket.
    * @param string $sock, socket address to connect.
    * @param object $data, message to send. Protobuf object. 
    */
    function sendResponse($sock, $data)
    {
        $payload = $data->serializeToString();

        $data = pack("LA*", strlen($payload), $payload);
        socket_send($sock, $data, strlen($payload) + 4, 0);
    }
    /**
    * 
    * Receive an answer from a client through a socket.
    * @param string $client, socket address to connect.
    */
    function receiveResponse($client)
    {
        socket_recv($client, $request, 4, MSG_WAITALL);

        $header = unpack("Llength", $request);
        $length = $header['length'];

        socket_recv($client, $payload, $length, MSG_WAITALL);

        return $payload;
    }
    /**
    * 
    * Send a request to a server through a socket.
    * @param string $sock, socket address to connect.
    * @param object $id, request's id.
    * @param object $data, protobuf object that will be sended to a socket. 
    */
    function sendRequest($sock, $id, $data)
    {
        $payload = $data->serializeToString();

        $data = pack("LLA*", $id, strlen($payload), $payload);
        socket_send($sock, $data, strlen($payload) + 8, 0);
    }
    /**
    * 
    * Start a connection with some socket and send a request to a server.
    * @param string $server, server's socket address to connect.
    * @param object $id, request's id.
    * @param object $data, protobuf object that will be sended to a socket. 
    */
    function sendRequestTo($server, $id, $data)
    {
        $config = (object)ServerConfig::${$server};
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $conn = socket_connect($sock, $config->host, $config->port);

        $this->sendRequest($sock, $id, $data);

        return $sock;
    }
    /**
    * 
    * Process the request using a action's map.
    * @param object $id, request's id.
    * @param object $data, args. 
    */
    function process($id, $data)
    {
        return $this->{$this->actions[$id]}($data);
    }
    /**
    * 
    * Send a request to a server through a socket.
    * @param string $sock, socket address to connect.
    * @param object $id, request's id.
    * @param object $data, protobuf object that will be sended to a socket. 
    */
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