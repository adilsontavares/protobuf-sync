<?php
require_once './Server';
require 'vendor/autoload.php';

class Server
{
    private $collection;
    private $dabase;
    private $client;

    private $hostname;
    private $portno;

    function __construct($host, $port){
        this.$hostname = $host;
        this.$portno = $port;
        this.$client = new MongoDB\Client("mongodb://localhost:27017");
    }

    function run(){}
}
?>