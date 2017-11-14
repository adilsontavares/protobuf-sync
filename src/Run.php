<?php
require_once __DIR__ . '/GPBMetadata/Messages.php';
require_once __DIR__ . '/Config/Servers.php';
require_once __DIR__ . '/Servers/CatalogManager.php';
require_once __DIR__ . '/Servers/OrderManager.php';
require_once __DIR__ . '/Servers/FrontEnd.php';

if ($argc <= 1) 
{
    printf("You must inform the server you want to start.\n");
    die("Ex: php Run.php <front, catalog, order>.\n");
}

$name = $argv[1];
$config = ServerConfig::config($name) or die("Server with name $name was not found.\n");
$server = new $config->className((object)$config);
$server->run();
?>