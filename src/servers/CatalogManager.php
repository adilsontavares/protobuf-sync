<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Messages/Books.php';
require_once __DIR__ . '/../Messages/RequestByQuery.php';
require_once __DIR__ . '/../Messages/UpdateCatalogItemCount.php';
require_once __DIR__ . '/../Messages/UpdateBookPrice.php';
require_once __DIR__ . '/../Bridge/FromMongo.php';

class CatalogManager extends Server
{
    static $SEARCH = 1;
    static $FIND = 2;
    static $UPDATE_PRICE = 3;
    static $UPDATE_COUNT = 4;

    function __construct() 
    {
        parent::__construct('catalog', [
            CatalogManager::$SEARCH => "search",
            CatalogManager::$FIND => "find",
            CatalogManager::$UPDATE_PRICE => "updatePrice",
            CatalogManager::$UPDATE_COUNT => "updateCount"
        ]);
    }

    function search($data)
    {
        $request = new Messages\RequestByQuery();
        $request->mergeFromString($data);

        printf("Search for '%s'.\n", $request->getQuery());

        $result = $this->db->catalog->find(['book.name' => new MongoDB\BSON\Regex($request->getQuery(), "i")]);
        $books = many($result, "to_catalog");

        $hue = new Messages\Book();
        $hue->setName('HUE HUE HUE');
        $hue->setPrice(69.69);

        // $response = new Messages\Books();
        // $response->setBooks([$hue]);

        return $hue;
    }

    function find($data)
    {
        $request = new Messages\RequestById();
        $request->mergeFromString($data);

        printf("Find catalog with id %d.\n", $request->getId());

        $result = $this->db->catalog->findOne(['_id' => $request->getId()]);
        return to_catalog($result);
    }

    function updatePrice($data)
    {
        $request = new Messages\UpdateBookPrice();
        $request->mergeFromString($data);

        $id = $request->getId();
        $price = $request->getPrice();

        printf("Update catalog price to %f.\n", $price);

        $this->db->catalog->updateOne(['_id' => $id],
        [
            '$set' => [
                'book.price' => $price
            ]
        ]);

        return $this->find($id);
    }

    function updateCount($data)
    {
        $request = new Messages\UpdateCatalogItemCount();
        $request->mergeFromString($data);

        $id = $request->getId();
        $count = $request->getCount();

        printf("Update catalog count to %d.\n", $count);

        $this->db->catalog->updateOne(['_id' => $id],
        [
            '$set' => [
                'count' => $count
            ]
        ]);

        return $this->find($id);
    }
}
?>