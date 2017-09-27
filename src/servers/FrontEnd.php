<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/CatalogManager.php';
require_once __DIR__ . '/OrderManager.php';
require_once __DIR__ . '/../Messages/CatalogItems.php';
require_once __DIR__ . '/../Messages/RequestByQuery.php';
require_once __DIR__ . '/../Messages/RequestById.php';
require_once __DIR__ . '/../Debug.php';

/**
*Frontend Server
*/
class FrontEnd extends Server
{
    /**
    * 
    * FrontEnd Server's constructor.
    */
    function __construct()
    {
        parent::__construct('front', []);
        $this->stdin = fopen('php://stdin', 'r');
    }
    /**
    * 
    * Search a book by a name.
    * @param string $query, protobuf object that contains the name of the book.
    */
    function search($query)
    {
        $data = new Messages\RequestByQuery();
        $data->setQuery($query);

        return $this->request('catalog', 'SEARCH', $data);
    }
    /**
    * 
    * Search for book's information by an ID.
    * @param string $id, protobuf object that contains the ID of the book.
    */
    function details($id)
    {
        $data = new Messages\RequestById();
        $data->setId($id);

        return $this->request('catalog', 'FIND', $data);
    }
    /**
    * Buy a book.
    * @param string $id, protobuf object that contains the ID of the book.
    */
    function buy($id)
    {
        $data = new Messages\RequestById();
        $data->setId($id);

        return $this->request('order', 'BUY', $data);
    }
    /**
    * This function put the FrontEnd Server on running.
    */
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
    
    /**
    * Interface function.
    */
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

    /**
    * Interface function.
    */
    function menuSearch()
    {
        printf("-> Query: ");
        fscanf($this->stdin, "%s", $query);

        $result = $this->search($query);
        
        $items = new Messages\CatalogItems();
        $items->mergeFromString($result);

        debug_catalog_items($items);
    }
    /**
    * Interface function.
    */
    function menuDetails()
    {
        printf("-> Id: ");
        fscanf($this->stdin, "%d", $id);

        $result = $this->details($id);
        
        $item = new Messages\CatalogItem();
        $item->mergeFromString($result);

        debug_catalog_item($item);
    }
    /**
    * Interface function.
    */
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