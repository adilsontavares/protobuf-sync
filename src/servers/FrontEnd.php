<?php
require_once __DIR__ . '/Server.php';
require_once __DIR__ . '/CatalogManager.php';
require_once __DIR__ . '/OrderManager.php';
require_once __DIR__ . '/../Messages/RequestByQuery.php';
require_once __DIR__ . '/../Messages/RequestById.php';

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
            echo "-> Option: ";

            fscanf($this->stdin, "%d", $option);

            switch ($option)
            {
                case 1: $this->menuSearch(); break;
                case 2: $this->menuDetails(); break;
                case 3: $this->menuBuy(); break;
            }
            
            printf("\n");
            printf("Pressione ENTER para continuar.\n");
            fscanf($this->stdin, "%s");
        }

        fclose($this->stdin);
    }
    
    function menuSearch()
    {
        printf("-> Query: ");
        fscanf($this->stdin, "%s", $query);

        $result = $this->search($query);
        print_r($result);
    }

    function menuDetails()
    {
        printf("-> Id: ");
        fscanf($this->stdin, "%d", $id);

        $result = $this->details($id);
        print_r($result);
    }

    function menuBuy()
    {
        printf("-> Id: ");
        fscanf($this->stdin, "%d", $id);

        $result = $this->buy($id);
        print_r($result);
    }
}
?>