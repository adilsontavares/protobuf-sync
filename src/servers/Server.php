<?php
require '../../vendor/autoload.php';

class Server
{
    public $collection;
    public $database;
    public $client;

    public $hostname;
    public $portno;

    function __construct($host, $port){
        $this->hostname = $host;
        $this->portno = $port;
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
    }

    function run(){}
}
?>