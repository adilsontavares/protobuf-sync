<?php
class Election extends Thread {

    protected $config;
    protected $success = false;

    public function __construct($config) {
        $this->config = $config;
    }

    public function run() {

        printf("Send ARE_YOU_ALIVE to $this->config->name.\n");

        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO,["sec" => 10, "usec" => 0]);

        $conn = socket_connect($sock, $this->config->host, $this->config->port);

        $request = new Messages\Request();
        $request->setType(RequestType::ARE_YOU_ALIVE);
        $payload = $request->serializeToString();

        $data = pack("NA*", strlen($payload), $payload);
        socket_send($sock, $data, strlen($payload) + 4, 0);

        $this->success = socket_recv($client, $request, 4, MSG_WAITALL);
        $header = unpack("Nlength", $request);
        $length = $header['length'];
        
        $ret = socket_recv($client, $payload, $length, MSG_WAITALL);

        printf("RESPONSE FROM $this->config->name:\n");
        var_dump($ret);
    }
}
?>