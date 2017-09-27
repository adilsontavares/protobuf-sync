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

/**
* Catalog Manager Server's class.
*/
class CatalogManager extends Server
{
    /**
    * Search a Book Action ID.
    */
    static $SEARCH = 1;
    /**
    * Find a Book Action ID.
    */
    static $FIND = 2;
    /**
    * Update Book's Price Action ID.
    */
    static $UPDATE_PRICE = 3;
    /**
    * Update Book's Count Action ID.
    */
    static $UPDATE_COUNT = 4;
    static $DEBUG = 5;

    /**
    * CatalogManager Server's constructor.
    */
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
    /**
    * Search a book by name.
    * @param object $data, protobuf object that contains the book name. 
    */
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
    /**
    * Search a book by ID.
    * @param object $data, protobuf object that contains the book id. 
    */
    function find($data)
    {
        $request = new Messages\RequestById();
        $request->mergeFromString($data);

        printf("Find catalog with id %d.\n", $request->getId());

        $result = $this->db->catalog->findOne(['_id' => $request->getId()]);        
        return to_catalog($result);
    }
    /**
    * Update a book's price.
    * @param object $data, protobuf object that contains the book's id and new price. 
    */
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
    /**
    * Update a book's count.
    * @param object $data, protobuf object that contains the book's id and new count. 
    */
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