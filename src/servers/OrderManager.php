<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/../Messages/CatalogItem.php';

class OrderManager extends Server
{
    static $BUY = 1;

    function __construct($host, $port)
    {
        parent::__construct($host, $port, [
            OrderManager::$BUY => "buy"
        ]);
    }

    function buy($id)
    {
        $catalog = $this->request('catalog', 'FIND', $id);
        $count = $catalog->getCount() - 1;

        return $this->request('catalog', 'UPDATE_COUNT', (object)[
            'id' => $id,
            'count' => $count
        ]);
    }
}
?>