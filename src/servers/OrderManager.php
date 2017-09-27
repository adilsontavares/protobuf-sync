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
        $id = $request->getId();

        printf("Buy book with id %d.\n", $id);
        
        $response = $this->request('catalog', 'FIND', $request);
        $item = new Messages\CatalogItem();
        $item->mergeFromString($response);

        $count = $item->getCount() - 1;

        $update = new Messages\UpdateCatalogItemCount();
        $update->setId($id);
        $update->setCount($count);

        // printf("\n");

        $response = $this->request('catalog', 'UPDATE_COUNT', $update);
        $item = new Messages\CatalogItem();
        // $item->mergeFromString($response);

        return $item;
    }
}
?>