<?php
require_once __DIR__ . '/Server.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../Messages/Choice.php';
require __DIR__ . '/../Bridge/FromMongo.php';

class CatalogManager extends Server
{
    function __construct($host, $port){
        $map = [
            1 => "search",
            2 => "find",
            4 => "updatePrice",
            5 => "updateCount",
            "send" => "sendMessage"
        ];
        parent::__construct($host, $port, $map);
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

    function updatePrice($id, $price)
    {
        $this->db->catalog->updateOne(['_id' => $id],
        [
            '$set' => [
                'book.price' => $price
            ]
        ]);

        return $this->find($id);
    }

    function updateCount($id, $count)
    {
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