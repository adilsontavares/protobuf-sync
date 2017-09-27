<?php
require_once __DIR__ . '/GPBMetadata/Messages.php';
require_once __DIR__ . '/Config/Servers.php';
require_once __DIR__ . '/Servers/CatalogManager.php';
require_once __DIR__ . '/Servers/OrderManager.php';
require_once __DIR__ . '/Servers/FrontEnd.php';

if ($argc <= 1) 
{
    printf("You must expose the server you want to start.\n");
    die("Ex: php Run.php <front, catalog, order>.\n");
}

$serverType = $argv[1];
$config = (object)ServerConfig::${$serverType};
$server = new $config->className();
$server->run();
?>