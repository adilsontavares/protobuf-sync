<?php
require_once 'Server.php';
require '../../vendor/autoload.php';
require '../GPBMetadata/Messages.php';
require '../Messages/Choice.php';

// Catalog Manager -> HOST: 127.0.0.1 | PORT: 7834
// Order Manager -> HOST: 127.0.0.1 | PORT: 7835
// Frontend -> HOST: 127.0.0.1 | PORT: 7836

class CatalogManager extends Server
{     
    function __construct($host, $port){
        parent::__construct($host, $port);
        $this->database = $this->client->Catalog;
        $this->collection = $this->database->Books;
    }

    //Aqui roda o socket
    function run(){
        ob_implicit_flush();
        set_time_limit(0);

        $sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Socket create error\n");

        socket_bind($sock, $this->hostname, $this->portno) or die("Socket bind error\n");
        socket_listen($sock, 3) or die("Could not set up socket listener\n");

        while(1){
            echo "socket connection started\n";

            $accept = socket_accept($sock) or die("Could not accept incoming connection\n");
            

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
            
            echo 'Masoq: '. $received_message->getIdMessage() . "\n";
            socket_close($accept);
            
            echo "socket connection done\n";
        }
        socket_close($sock);
    }

    //retorna ID e os titulos dos livros (fuzzy match)
    function search($query)
    {
        $result = $this->collection->find(['Book.name' => $query]);
    }

    //procura pelo ID do livro e retorna infos -> nome, preco, quantidade
    function find($id)
    {
        $result = $this->collection->find(['Book.id' => $id]);
    }

    function updatePrice($id, $price)
    {
        
    }

    function updateStock($id, $count)
    {

    }
}

$example = new CatalogManager("127.0.0.1", "9090");
$example->run();

?>