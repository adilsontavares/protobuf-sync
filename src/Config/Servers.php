<?php
class ServerConfig 
{
    static function config($name) {

        foreach (ServerConfig::$servers as $config)
            if ($config["name"] == $name)
                return (object)$config;

        return null;
    }

    static $servers = 
    [
        [
            'name' => 'catalog1',
            'type' => 'catalog',
            'id' => 1,
            'host' => '127.0.0.1',
            'port' => 8821,
            'className' => 'CatalogManager'
        ],
        [
            'name' => 'catalog2',
            'type' => 'catalog',
            'id' => 2,
            'host' => '127.0.0.1',
            'port' => 8822,
            'className' => 'CatalogManager'
        ],
        [
            'name' => 'catalog3',
            'type' => 'catalog',
            'id' => 3,
            'host' => '127.0.0.1',
            'port' => 8823,
            'className' => 'CatalogManager'
        ],
        [
            'name' => 'catalog4',
            'type' => 'catalog',
            'id' => 4,
            'host' => '127.0.0.1',
            'port' => 8824,
            'className' => 'CatalogManager'
        ],
        [
            'name' => 'front',
            'type' => 'front',
            'host' => '127.0.0.1',
            'port' => 8810,
            'className' => 'FrontEnd'
        ],
        [
            'name' => 'order',
            'type' => 'order',
            'host' => '127.0.0.1',
            'port' => 8830,
            'className' => 'OrderManager'
        ]
    ];
}
?>