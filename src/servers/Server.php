<?php
require_once './Server';
require 'vendor/autoload.php';

class Server
{
    private $collection;
    private $client;

    function __construct(){
        this.$client = new MongoDB\Client("mongodb://localhost:27017");
    }

    function run(){}
}
?>