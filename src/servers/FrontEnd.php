<?php
require_once __DIR__ . '/Server';

// Catalog Manager -> HOST: 127.0.0.1 | PORT: 7834
// Order Manager -> HOST: 127.0.0.1 | PORT: 7835
// Frontend -> HOST: 127.0.0.1 | PORT: 7836

class FrontEnd extends Server
{
    
    function __construct($host, $port){
        parent::__construct($host, $port);
    }

    //Aqui roda o socket
    //Esse server recebe informacoes do Client e manda pros Outros servidores
    function run() {
        ob_implicit_flush();
        set_time_limit(0);

        $sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Socket create error\n");

        socket_bind($sock, $this->hostname, $this->portno) or die("Socket bind error\n");
        socket_listen($sock, 3) or die("Could not set up socket listener\n");
        
        while(1){
            echo "socket connection started\n";

            $accept = socket_accept($sock) or die("Could not accept incoming connection\n");
            
            $catalog_server = fsockopen($this->hostname, "7834", $errno, $errstr);
            if(!$catalog_server){
                echo "$errstr ($errno)";
            }

            $order_server = fsockopen($this->hostname, "7835", $errno, $errstr);
            if(!$order_server){
                echo "$errstr ($errno)";
            }

            while($recv = socket_read($accept, 24000))
	        {
                echo 'Client Said: ' . $recv;
                $msg = 'Server Said: ' . $recv . "\r\n";
                
                //Escrever pro server:
                fwrite($catalog_server, $msg) or die("Could not write to server Catalog Manager");
                
                //Ler do servidor:
                //$str = fread($fp, 100000);

                //fwrite($order_server, $msg) or die("Could not write to server Catalog Manager");

                //Escrever pro cliente:
                socket_write($accept, $msg, strlen($msg)) or die("Could not write output\n");
	        }

            socket_close($accept);
            
            echo "socket connection done\n";
        }
        fclose($catalog_server);
        fclose($order_server);
        socket_close($sock);
    }
    
    
    function search($query)
    {

    }

    function details($id)
    {

    }

    function buy($id)
    {

    }
}
?>