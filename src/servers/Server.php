<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../Messages/Catalog.php';
require __DIR__ . '/../Messages/CatalogItem.php';
require __DIR__ . '/../Messages/Book.php';

class Server
{
    public $collection;
    public $database;
    public $client;

    public $hostname;
    public $portno;

    public $functions_map;

    function __construct($host, $port, $map){
        $this->functions_map = $map;
        $this->hostname = $host;
        $this->portno = $port;
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $this->client->book_store;
    }

    function run() {
        ob_implicit_flush();
        set_time_limit(0);
        $sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Socket create error\n");

        socket_bind($sock, $this->hostname, $this->portno) or die("Socket bind error\n");
        socket_listen($sock, 3) or die("Could not set up socket listener\n");

        while(1){
            $accept = socket_accept($sock) or die("Could not accept incoming connection\n");
            echo "socket connection started\n";

            $length = socket_read($accept, 4);
            $length = unpack("Lheader", $length);
            $length = $length['header'];
            
            $message = socket_read($accept, $length);
            $message = unpack("A*header", $message);
            $message = $message['header']; 
            
            $proto_message = new \Messages\Choice();
            $proto_message->mergeFromString($message);

            $received_message = new \Messages\Choice();
            $received_message->mergeFromString($PROTO['header']);
            
            $id = $received_message->getIdMessage();
            $args = [$received_message->getIdSearch(), $received_message->getIdSearch(), 
            $received_message->getNameSearch(), $received_message->getPlaceHolder()];

            $this->{$this->functions_map[$id]}($args);

            //Devolve a mensagem pra quem solocitou algo
            socket_write($accept, $msg, strlen($msg)) or die("Could not write output\n");

            socket_close($accept);
	
	        echo "socket connection done\n";

        }
        socket_close($sock);

    }

}
?>