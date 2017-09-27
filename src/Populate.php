<?php

    require('../vendor/autoload.php');

    echo "Populating database... ";

    $client = new MongoDB\Client("mongodb://localhost:27017");
    $client->book_store->drop();
    $db = $client->book_store;

    $books = $db->books;
    $catalog = $db->catalog;

    $catalog->insertOne([
        '_id' => 1,
        'book' => [
            'name' => 'Fundação',
            'price' => 18.75
        ],
        'count' => 3
    ]);

    $catalog->insertOne([
        '_id' => 2,
        'book' => [
            'name' => 'Fundação e Império',
            'price' => 24.00
        ],
        'count' => 46
    ]);

    $catalog->insertOne([
        '_id' => 3,
        'book' => [
            'name' => 'Animal Farm',
            'price' => 12.50
        ],
        'count' => 54
    ]);

    $catalog->insertOne([
        '_id' => 4,
        'book' => [
            'name' => 'Battle Royale',
            'price' => 32.00
        ],
        'count' => 78
    ]);

    $catalog->insertOne([
        '_id' => 5,
        'book' => [
            'name' => 'Pequeno Príncipe',
            'price' => 0.99
        ],
        'count' => 48
    ]);

    $catalog->insertOne([
        '_id' => 6,
        'book' => [
            'name' => 'Colorless Tsukuru Tazaki and His Years of Pilgrimage',
            'price' => 15.75
        ],
        'count' => 302
    ]);

    echo "ok\n";
?>