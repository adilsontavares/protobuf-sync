<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/CatalogManager.php';
require_once __DIR__ . '/OrderManager.php';
require_once __DIR__ . '/../Messages/CatalogItems.php';
require_once __DIR__ . '/../Messages/RequestByQuery.php';
require_once __DIR__ . '/../Messages/RequestById.php';
require_once __DIR__ . '/../Debug.php';

class FrontEnd extends Server
{
    function __construct()
    {
        parent::__construct('front', []);
        $this->stdin = fopen('php://stdin', 'r');
    }

    function search($query)
    {
        $data = new Messages\RequestByQuery();
        $data->setQuery($query);

        return $this->request('catalog', 'SEARCH', $data);
    }

    function details($id)
    {
        $data = new Messages\RequestById();
        $data->setId($id);

        return $this->request('catalog', 'FIND', $data);
    }

    function buy($id)
    {
        $data = new Messages\RequestById();
        $data->setId($id);

        return $this->request('order', 'BUY', $data);
    }

    function run() 
    {
        $option = 0;

        while ($option != -1)
        {
            system('clear');

            echo "1. Search.\n";
            echo "2. Details.\n";
            echo "3. Buy.\n";
            echo "4. Debug.\n";
            echo "-> Option: ";

            fscanf($this->stdin, "%d", $option);

            switch ($option)
            {
                case 1: $this->menuSearch(); break;
                case 2: $this->menuDetails(); break;
                case 3: $this->menuBuy(); break;
                case 4: $this->menuDebug(); break;
            }
            
            printf("\n");
            printf("Pressione ENTER para continuar.\n");
            fscanf($this->stdin, "%s");
        }

        fclose($this->stdin);
    }
    
    function menuDebug()
    {
        $request = new Messages\Book();
        $request->setName('Request Book');
        $request->setPrice(666.66);

        $response = $this->request('catalog', 'DEBUG', $request);
        $books = new Messages\Books();
        $books->mergeFromString($response);

        foreach ($books->getBooks() as $book)
        {
            printf("-\n");
            printf("BOOK NAME  = %s\n", $book->getName());
            printf("BOOK PRICE = %d\n", $book->getPrice());
        }
    }

    function menuSearch()
    {
        printf("-> Query: ");
        fscanf($this->stdin, "%s", $query);

        $result = $this->search($query);
        
        $items = new Messages\CatalogItems();
        $items->mergeFromString($result);

        debug_catalog_items($items);
    }

    function menuDetails()
    {
        printf("-> Id: ");
        fscanf($this->stdin, "%d", $id);

        $result = $this->details($id);
        
        $item = new Messages\CatalogItem();
        $item->mergeFromString($result);

        debug_catalog_item($item);
    }

    function menuBuy()
    {
        printf("-> Id: ");
        fscanf($this->stdin, "%d", $id);

        $result = $this->buy($id);

        $item = new Messages\CatalogItem();
        $item->mergeFromString($result);

        debug_catalog_item($item);
    }
}
?>