<?php
require_once __DIR__ . '/../Messages/Book.php';
require_once __DIR__ . '/../Messages/Catalog.php';

function many($items, $func) {

    $result = array();

    foreach ($items as $item) {
        array_push($result, $func($item));
    }

    return $result;
}

function assign($obj, $mongo, $prop) {

    $setProp = "set" . ucfirst($prop);

    if ($prop === 'id')
        $prop = '_id';

    // printf("%s[%s] = mongo[%s]\n", gettype($obj), $setProp, $prop);

    $obj->{$setProp}($mongo[$prop]);
}

function to_book($data) {
    
    $book = new Messages\Book();

    if (!$data)
    {
        $book->setId(-1);
        return $book;
    }
    
    assign($book, $data, 'name');

    return $book;
}

function to_catalog($data) {

    $item = new Messages\Catalog();

    if (!$data)
    {
        $item->setId(-1);
        return $item;
    }
    
    assign($item, $data, 'id');
    assign($item, $data, 'count');
    assign($item, $data, 'price');

    $item->setBook(to_book($data['book']));

    return $item;
}
?>