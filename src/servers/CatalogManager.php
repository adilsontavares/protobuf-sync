<?php
require_once './Server';
require 'vendor/autoload.php';

// Catalog Manager -> HOST: 127.0.0.1 | PORT: 7834
// Order Manager -> HOST: 127.0.0.1 | PORT: 7835
// Frontend -> HOST: 127.0.0.1 | PORT: 7836


class CatalogManager extends Server
{     
    function __construct($host, $port){
        parent::__construct($host, $port);
        this.$database = this.$client->Catalog;
        this.$collection = this.$database->Books;
    }

    //Aqui roda o socket
    function run(){
        ob_implicit_flush();
        set_time_limit(0);

        $sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Socket create error\n");

        socket_bind($sock, this.$hostname, this.$portno) or die("Socket bind error\n");
        socket_listen($sock, 3) or die("Could not set up socket listener\n");

        while(1){
            echo "socket connection started\n";

            $accept = socket_accept($sock) or die("Could not accept incoming connection\n");
            
            while($recv = socket_read($accept, 24000))
	        {
                echo 'Client Said: ' . $recv;
                $msg = 'Server Said: ' . $recv . "\r\n";
                socket_write($accept, $msg, strlen($msg)) or die("Could not write output\n");
	        }

            socket_close($accept);
            
            echo "socket connection done\n";
        }
        socket_close($sock);
    }

    //retorna ID e os titulos dos livros (fuzzy match)
    function search($query)
    {
        $result = this.$collection->find(['Book.name' => $query]);
    }

    //procura pelo ID do livro e retorna infos -> nome, preco, quantidade
    function find($id)
    {
        $result = this.$collection->find(['Book.id' => $id]);
    }

    function updatePrice($id, $price)
    {
        
    }

    function updateStock($id, $count)
    {

    }
}


?>