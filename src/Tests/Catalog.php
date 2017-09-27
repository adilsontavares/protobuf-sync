<?php

    require __DIR__ . '/test.php';
    require __DIR__ . '/../servers/CatalogManager.php';

    $server = new CatalogManager('localhost', 8080);

    test('Buscando por "fund"', $server->search('fund'));
    test('Buscando por ""', $server->search(''));
    
    test('Buscando pelo id 1', $server->find(1));
    test('Buscando pelo id 3', $server->find(3));

    test('Ajustando preço do livro 2 para R$10,50', $server->updatePrice(2, 10.5));
    test('Ajustando preço do livro 2 para R$3,20', $server->updatePrice(2, 3.2));

    test('Ajustando quantidade do livro 4 para 36', $server->updateCount(4, 36));
    test('Ajustando quantidade do livro 4 para 12', $server->updateCount(4, 12));
?>