<?php
class ServerConfig 
{
    static $front = 
    [
        'host' => '127.0.0.1',
        'port' => 8810,
        'className' => 'FrontEnd'
    ];

    static $catalog = 
    [
        'host' => '127.0.0.1',
        'port' => 8811,
        'className' => 'CatalogManager'
    ];

    static $order = 
    [
        'host' => '127.0.0.1',
        'port' => 8812,
        'className' => 'OrderManager'
    ];
}
?>