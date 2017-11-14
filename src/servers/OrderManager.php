<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/../Messages/Catalog.php';
require_once __DIR__ . '/../Messages/RequestById.php';
require_once __DIR__ . '/../Messages/UpdateCatalogCount.php';

/**
* Order Manager Server. 
*/
class OrderManager extends Server
{
    static $BUY = 4;
    /**
    * 
    * OrderManager Server's constructor. 
    */
    function __construct($config)
    {
        parent::__construct($config, [
            OrderManager::$BUY => "buy"
        ]);
    }
    /**
    * 
    * Buy a book.
    * @param string $data, protobuf object.
    */
    function buy($data)
    {
        $request = new Messages\RequestById();
        $request->mergeFromString($data);
        $id = $request->getId();

        printf("Buy book with id %d.\n", $id);
        
        $response = $this->request('catalog', 'FIND', $request);
        $item = new Messages\Catalog();
        $item->mergeFromString($response);

        $count = $item->getCount() - 1;

        $update = new Messages\UpdateCatalogCount();
        $update->setId($id);
        $update->setCount($count);

        $response = $this->request('catalog', 'UPDATE_COUNT', $update);
        $item = new Messages\Catalog();
        $item->mergeFromString($response);

        return $item;
    }
}
?>