<?php
require_once __DIR__ . "/Messages/Book.php";
require_once __DIR__ . "/Messages/CatalogItem.php";

function debug_validate($item)
{
    return $item;
}

function debug_many($obj, $name)
{
    $name = "debug_$name";

    printf("%d items.\n", sizeof($obj));

    foreach($obj as $item)
    {
        printf("-\n");
        $name($item);
        printf("\n");
    }
}

function debug_books($books)
{
    debug_many($books->getBooks(), 'book');
}

function debug_catalog_items($items)
{
    debug_many($items->getItems(), 'catalog_item');
}

function debug_book($book)
{
    if (!debug_validate($book))
        return;

    printf("[BOOK]\n");
    printf("- name:  %s\n", $book->getName());
    printf("- price: %d\n", $book->getPrice());
}

function debug_catalog_item($item)
{
    if (!debug_validate($item))
        return;

    printf("[CATALOG ITEM]\n");
    printf("- id:    %d\n", $item->getId());
    printf("- count: %d\n", $item->getCount());

    debug_book($item->getBook());
}

?>