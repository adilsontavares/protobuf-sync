<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/../Messages/CatalogItem.php';
require_once __DIR__ . '/../Messages/RequestById.php';
require_once __DIR__ . '/../Messages/UpdateCatalogItemCount.php';

class OrderManager extends Server
{
    static $BUY = 1;

    function __construct()
    {
        parent::__construct('order', [
            OrderManager::$BUY => "buy"
        ]);
    }

    function buy($data)
    {
        $request = new Messages\RequestById();
        $request->mergeFromString($data);
        
        $catalog = $this->request('catalog', 'FIND', $request);
        // $count = $catalog->getCount() - 1;

        // $update = new Messages\UpdateCatalogItemCount();
        // $update->setId($id);
        // $update->setCount($count);

        // $response = new Messages\Book();
        // $response->setName('Olaaaa');
        // $response->setPrice(12.5);

        // return $this->request('catalog', 'UPDATE_COUNT', $update);
        return $catalog;
    }
}
?>