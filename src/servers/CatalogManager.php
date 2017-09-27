<?php
require_once __DIR__ . '/Server.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../Messages/Choice.php';
require __DIR__ . '/../Bridge/FromMongo.php';

class CatalogManager extends Server
{
    static $SEARCH = 1;
    static $FIND = 2;
    static $UPDATE_PRICE = 3;
    static $UPDATE_COUNT = 4;

    function __construct($host, $port) 
    {
        parent::__construct($host, $port, [
            CatalogManager::$SEARCH => "search",
            CatalogManager::$FIND => "find",
            CatalogManager::$UPDATE_PRICE => "updatePrice",
            CatalogManager::$UPDATE_COUNT => "updateCount"
        ]);
    }

    function search($query)
    {
        $result = $this->db->catalog->find(['book.name' => new MongoDB\BSON\Regex($query, "i")]);
        return many($result, "to_catalog");
    }

    function find($id)
    {
        $result = $this->db->catalog->findOne(['_id' => $id]);
        return to_catalog($result);
    }

    function updatePrice($data)
    {
        $id = $data->id;
        $price = $data->price;

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
        $id = $data->id;
        $count = $data->count;

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