<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/CatalogManager.php';
require_once __DIR__ . '/OrderManager.php';

class FrontEnd extends Server
{
    function __construct($host, $port)
    {
        parent::__construct($host, $port, []);
    }

    function search($query)
    {
        return $this->request('catalog', 'SEARCH', $query);
    }

    function details($id)
    {
        return $this->request('catalog', 'FIND', $id);
    }

    function buy($id)
    {
        return $this->request('order', 'BUY', $id);
    }
}
?>