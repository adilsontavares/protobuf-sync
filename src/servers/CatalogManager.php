<?php
require_once './Server';
require 'vendor/autoload.php';

class CatalogManager extends Server
{
    private $collection;
    
    function __construct(){
        parent::__construct();
        this.$collection = $client->Catalog->Books;
    }

    //Aqui roda o socket
    function run(){

    }

    function search($query)
    {
        $result = this.$collection->find(['Book.name' => $query]);
    }

    function find($id)
    {

    }

    function updatePrice($id, $price)
    {

    }

    function updateStock($id, $count)
    {

    }
}


?>