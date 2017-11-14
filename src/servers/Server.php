<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Config/Servers.php';
require_once __DIR__ . '/../Messages/Request.php';
require_once __DIR__ . '/../Messages/IAmAlive.php';
require_once __DIR__ . '/Election.php';


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
    * @param string $config, Server's Configuration
    * @param array $actions, Server's Action. Example: Find a book, Search by ID, Buy a book, etc..
    */
    function __construct($config, $actions)
    {
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $this->client->book_store;
        $this->actions = $actions;
        $this->config = $config;
        $this->coordinator = false;
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
            printf("WAITING FOR REQUESTS...\n");

            $client = socket_accept($sock);

            printf("CLIENT CONNECTED! RECEIVING REQUEST...\n");
            $this->receiveRequest($client, $id, $request);

            if ($id == RequestType::UPDATE_COORDINATOR)
            {
                $data = new Messages\UpdateCoordinator();
                $data->mergeFromString($request);
                
                $this->coordinator = ServerConfig::byId($data->getId());

                printf("Update coordinator: $this->coordinator->id\n");
                return;
            }

            if ($id == RequestType::ARE_YOU_ALIVE)
            {
                $response = new Messages\IAmAlive();
                $response->setType(RequestType::I_AM_ALIVE);
                $response->setId($config->id);
                $this->sendResponse($client, $response);

                return;
            }

            printf("REQUEST RECEIVED!\n");
            printf("PROCESSING REQUEST...!\n");

            $result = $this->process($id, $request);

            printf("SENDING RESPONSE...\n");
            $this->sendResponse($client, $result);
        }
    }

    function election() {

        $id = $this->config->id;

        printf("STARTING ELECTION!\n");

        $elections = [];

        foreach (ServerConfig::$servers as $server)
        {
            if ($server->id > $id)
            {
                $election = new Election($server);
                $election->start();

                array_push($selections, $election);
            }
        }

        $response = false;

        foreach ($elections as $election)
        {
            $election->join();
            $response = $response || $election->success;
        }

        if (!$response)
        {
            $message = new Messages\UpdateCoordinator();
            $message->setType(Messages\RequestType::UPDATE_COORDINATOR);
            $message->setId($this->config->id);

            foreach (ServerConfig::$servers as $server)
            {
                if ($server->id != $this->config->id)
                    sendRequestTo($server->name, $message->getType(), null);
            }
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
        socket_recv($client, $request, 4, MSG_WAITALL);
        $header = unpack("Nlength", $request);
        $length = $header['length'];

        print_r("LENGTH: $length\n");

        socket_recv($client, $payload, $length, MSG_WAITALL);

        $req = new Messages\Request();
        $req->mergeFromString($payload);
        $id = $req->getType();
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

        $data = pack("NA*", strlen($payload), $payload);
        socket_send($sock, $data, strlen($payload) + 4, 0);
    }
    /**
    * 
    * Receive an answer from a client through a socket.
    * @param string $client, socket address to connect.
    */
    function receiveResponse($client)
    {
        printf("WAITING RESPONSE...\n");
        socket_recv($client, $request, 4, MSG_WAITALL);

        printf("RECEIVED RESPONSE!\n");

        $header = unpack("Nlength", $request);
        $length = $header['length'];

        printf("RESPONSE LENGTH: %d.\n", $length);
        printf("READING RESPONSE...\n");

        socket_recv($client, $payload, $length, MSG_WAITALL);

        printf("RESPONSE READ!\n");

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
        $data->setRequestType($id);
        $payload = $data->serializeToString();

        $data = pack("NA*", strlen($payload), $payload);

        socket_send($sock, $data, strlen($payload) + 4, 0);
    }
    /**
    * 
    * Start a connection with some socket and send a request to a server.
    * @param string $server, server's socket address to connect.
    * @param object $id, request's id.
    * @param object $data, protobuf object that will be sended to a socket. 
    */
    function sendRequestTo($name, $id, $data)
    {
        $config = ServerConfig::config($name);
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

        printf("SENDING REQUEST OF TYPE %d.\n", $messageId);
        $sock = $this->sendRequestTo($target, $id, $data);

        printf("WAITING RESPONSE...\n");
        return $this->receiveResponse($sock);
    }
}
?>