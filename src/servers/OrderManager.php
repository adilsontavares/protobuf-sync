<?php
require_once __DIR__ . '/Server';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Catalog/Catalog_Item.php';

// Catalog Manager -> HOST: 127.0.0.1 | PORT: 7834
// Order Manager -> HOST: 127.0.0.1 | PORT: 7835
// Frontend -> HOST: 127.0.0.1 | PORT: 7836

class OrderManager extends Server
{
    
    function __construct($host, $port){
        parent::__construct($host, $port);
        parent::$database = parent::$client->Catalog;
        parent::$collection = parent::$database->Books;
    }

    //Aqui roda o socket
    //ele manda solicitacao pro Catalog Manager quando for realizar uma compra
    function run(){
        ob_implicit_flush();
        set_time_limit(0);

        $sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Socket create error\n");

        socket_bind($sock, parent::$hostname, parent::$portno) or die("Socket bind error\n");
        socket_listen($sock, 3) or die("Could not set up socket listener\n");

        while(1){
            echo "socket connection started\n";

            $accept = socket_accept($sock) or die("Could not accept incoming connection\n");
            
            $catalog_server = fsockopen(parent::$hostname, "7834", $errno, $errstr);
            if(!$catalog_server){
                echo "$errstr ($errno)";
            }
            
            //Recebe uma mensagem aqui
            while($recv = socket_read($accept, 24000)){
                
                echo 'Client Said: ' . $recv;
                $msg = 'Server Said: ' . $recv . "\r\n";
                
                //Escrevendo para outro Server:
                fwrite($catalog_server, $msg) or die("Could not write to server Catalog Manager");

                //Ler do servidor:
                //$str = fread($fp, 100000);
	        }

            socket_close($accept);
            
            echo "socket connection done\n";
        }
        fclose($catalog_server);
        socket_close($sock);
    }
    
    
    function buy($id)
    {

    }
}
?>