<?php

    require __DIR__ . '/../vendor/autoload.php';

    echo "Populating database... ";

    $client = new MongoDB\Client("mongodb://localhost:27017");
    $client->book_store->drop();
    $db = $client->book_store;

    $books = $db->books;
    $catalog = $db->catalog;

    $catalog->insertOne([
        '_id' => 1,
        'price' => 18.75,
        'count' => 3,
        'book' => [
            'name' => 'Fundação'
        ]
    ]);

    $catalog->insertOne([
        '_id' => 2,
        'price' => 24.00,
        'count' => 46,
        'book' => [
            'name' => 'Fundação e Império'
        ]
    ]);

    $catalog->insertOne([
        '_id' => 3,
        'price' => 12.50,
        'count' => 54,
        'book' => [
            'name' => 'Animal Farm'
        ]
    ]);

    $catalog->insertOne([
        '_id' => 4,
        'price' => 32.00,
        'count' => 78,
        'book' => [
            'name' => 'Battle Royale'
        ]
    ]);

    $catalog->insertOne([
        '_id' => 5,
        'price' => 0.99,
        'count' => 48,
        'book' => [
            'name' => 'Pequeno Príncipe'
        ]
    ]);

    $catalog->insertOne([
        '_id' => 6,
        'price' => 15.75,
        'count' => 302,
        'book' => [
            'name' => 'Colorless Tsukuru Tazaki and His Years of Pilgrimage'
        ]
    ]);

    echo "ok\n";
?>