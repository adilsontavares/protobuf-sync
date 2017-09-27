<?php
require __DIR__ . '/../../vendor/autoload.php';

class Server
{
    public $collection;
    public $database;
    public $client;

    public $hostname;
    public $portno;

    function __construct($host, $port, $actions)
    {
        $this->hostname = $host;
        $this->portno = $port;
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $this->client->book_store;
        $this->actions = $actions;
    }

    function run() 
    {
    }

    function process($id, $payload)
    {
        return $this->{$this->actions[$id]}($payload);
    }

    function request($target, $messageId, $payload)
    {
        $targetClass = ucfirst($target) . "Manager";

        $server = new $targetClass('127.0.0.1', 0);
        $id = $targetClass::${$messageId};

        return $server->process($id, $payload);
    }
}
?>