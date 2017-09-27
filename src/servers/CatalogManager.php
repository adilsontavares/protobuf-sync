<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Messages/Book.php';
require_once __DIR__ . '/../Messages/Books.php';
require_once __DIR__ . '/../Messages/CatalogItems.php';
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
    static $DEBUG = 5;

    function __construct() 
    {
        parent::__construct('catalog', [
            CatalogManager::$SEARCH => "search",
            CatalogManager::$FIND => "find",
            CatalogManager::$UPDATE_PRICE => "updatePrice",
            CatalogManager::$UPDATE_COUNT => "updateCount",
            CatalogManager::$DEBUG => "debug"
        ]);
    }

    function search($data)
    {
        $request = new Messages\RequestByQuery();
        $request->mergeFromString($data);

        printf("Search for '%s'.\n", $request->getQuery());

        $result = $this->db->catalog->find(['book.name' => new MongoDB\BSON\Regex($request->getQuery(), "i")]);
        $items = many($result, "to_catalog");

        $response = new Messages\CatalogItems();
        $response->setItems($items);

        return $response;
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

        $response = $this->db->catalog->findOne(['_id' => $id]);
        return to_catalog($response);
    }

    function updateCount($data)
    {
        $request = new Messages\UpdateCatalogItemCount();
        $request->mergeFromString($data);

        $id = $request->getId();
        $count = $request->getCount();

        printf("Update catalog count of %d to %d.\n", $id, $count);

        $this->db->catalog->updateOne(['_id' => $id],
        [
            '$set' => [
                'count' => $count
            ]
        ]);
        
        $response = $this->db->catalog->findOne(['_id' => $id]);
        return to_catalog($response);
    }

    function debug($data)
    {
        $request = new Messages\Book();
        $request->mergeFromString($data);
        
        printf("BOOK NAME = %s\n", $request->getName());
        printf("BOOK PRICE   = %d\n", $request->getPrice());

        $books = array();
        for ($i = 0; $i <= 3; ++$i)
        {
            $temp = new Messages\Book();
            $temp->setPrice($i + 0.5);
            $temp->setName("Book $i");

            array_push($books, $temp);
        }

        $response = new Messages\Books();
        $response->setBooks($books);

        return $response;
    }
}
?>